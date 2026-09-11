<?php

declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(
    function (string $table): void {
        $lll = 'LLL:EXT:leadinfo/Resources/Private/Language/Configuration.xlf:';
        $GLOBALS['SiteConfiguration'][$table]['columns']['leadinfo_id'] = [
            'label' => $lll . 'site.configuration.leadinfo_id',
            'description' => $lll . 'site.configuration.leadinfo_id.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => $lll . 'site.configuration.leadinfo_placeholder',
            ],
        ];
        $GLOBALS['SiteConfiguration'][$table]['types']['0']['showitem'] .= '
            ,--div--;' . $lll . 'site.configuration.tab, leadinfo_id
         ';
    },
    'site'
);
