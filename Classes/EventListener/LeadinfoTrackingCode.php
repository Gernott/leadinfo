<?php

declare(strict_types=1);

namespace LeadinfoExtension\Leadinfo\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * Adds the Leadinfo tracking code to the <head> of every frontend page.
 *
 * The Leadinfo Site ID is read from the site configuration field "leadinfo_id".
 */
final class LeadinfoTrackingCode
{
    /**
     * Matches a Leadinfo Site ID, e.g. "LI-123456789".
     */
    private const ID_PATTERN = '/^LI-[0-9A-Z]+$/';

    private const ASSET_IDENTIFIER = 'leadinfo';

    public function __construct(
        private readonly AssetCollector $assetCollector,
    ) {}

    #[AsEventListener(identifier: 'leadinfo/tracking-code')]
    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        // The event is dispatched once per inline/file and priority/non-priority
        // combination. Only the inline + priority run ends up in the <head>.
        if (!$event->isInline() || !$event->isPriority()) {
            return;
        }
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequestInterface) {
            return;
        }
        // PageRenderer also renders backend pages, so the frontend check is explicit.
        if (!ApplicationType::fromRequest($request)->isFrontend()) {
            return;
        }
        $site = $request->getAttribute('site');
        if (!$site instanceof Site) {
            return;
        }
        $leadinfoId = (string)($site->getConfiguration()['leadinfo_id'] ?? '');
        if (!preg_match(self::ID_PATTERN, $leadinfoId)) {
            return;
        }
        $this->assetCollector->addInlineJavaScript(
            self::ASSET_IDENTIFIER,
            $this->getTrackingCode($leadinfoId),
            [],
            ['priority' => true, 'csp' => true]
        );
    }

    private function getTrackingCode(string $leadinfoId): string
    {
        return '(function(l, e, a, d, i, n, f, o) {
    if (!l[i]) {
        l.GlobalLeadinfoNamespace = l.GlobalLeadinfoNamespace || [];
        l.GlobalLeadinfoNamespace.push(i);
        l[i] = function() {
            (l[i].q = l[i].q || []).push(arguments)
        };
        l[i].t = l[i].t || n;
        l[i].q = l[i].q || [];
        o = e.createElement(a);
        f = e.getElementsByTagName(a)[0];
        o.async = 1;
        o.src = d;
        f.parentNode.insertBefore(o, f);
    }
}(window, document, "script", "https://cdn.leadinfo.net/ping.js", "leadinfo", ' . json_encode($leadinfoId, JSON_THROW_ON_ERROR) . '));';
    }
}
