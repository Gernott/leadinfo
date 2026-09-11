<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;

/**
 * Allows the Leadinfo tracking code to load and communicate, for sites using a
 * Content Security Policy. Sites without a CSP are not affected.
 */
return Map::fromEntries([
    Scope::frontend(),
    new MutationCollection(
        // the inline snippet injects a <script> loading https://cdn.leadinfo.net/ping.js
        new Mutation(MutationMode::Extend, Directive::ScriptSrc, new UriValue('cdn.leadinfo.net')),
        // ping.js reports visitor data back to the Leadinfo endpoints
        new Mutation(MutationMode::Extend, Directive::ConnectSrc, new UriValue('*.leadinfo.net')),
    ),
]);
