<?php

declare(strict_types=1);

namespace LeadinfoExtension\Leadinfo\Hooks;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class LeadinfoSettings
{
    /** @var PageRenderer */
    protected $pageRenderer;

    public function preProcess(array $params, PageRenderer $pageRenderer)
    {
        try {
            $site = $this->getCurrentSite();
            if ($site && $tsfe = $this->getTypoScriptFrontendController()) {
                $siteConfiguration = $site->getConfiguration();
                if(!preg_match('/^(LI\-)([0-9A-Z]+)$/', $siteConfiguration['leadinfo_id'])){
                    return;
                }
                $leadinfo_id = $siteConfiguration['leadinfo_id'];
                $this->pageRenderer = $pageRenderer;
                if ($siteConfiguration['leadinfo_id']) {
                    $leadLabHeaderData = '<script type="text/javascript"> 
                        (function(l, e, a, d, i, n, f, o) {
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
                        }(window, document, "script", "https://cdn.leadinfo.net/ping.js", "leadinfo", "'.$leadinfo_id.'")); 
                    </script>';
                    $pageRenderer->addHeaderData($leadLabHeaderData);
                }
            }
        } catch (\RuntimeException $e) {
            return "Error: ".$e;
        }
    }


    /**
     * Returns the currently configured site if a site is configured (= resolved) in the current request.
     */
    protected function getCurrentSite(): ?SiteInterface
    {
        if ($GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface
            && $GLOBALS['TYPO3_REQUEST']->getAttribute('site') instanceof Site) {
            return $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
        }
        return null;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
