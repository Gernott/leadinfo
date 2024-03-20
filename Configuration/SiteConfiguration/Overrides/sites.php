<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($table) {
        // echo "<pre>";
        // var_dump($GLOBALS['SiteConfiguration'][$table]['columns']);
        $lll = 'LLL:EXT:leadinfo/Resources/Private/Language/Configuration.xlf:';
        $GLOBALS['SiteConfiguration'][$table]['columns']['leadinfo_id'] = [
            'label' => $lll . 'site.configuration.leadinfo_id',
            'config' => [
                'type' => 'input',
                'placeholder' => $lll . 'site.configuration.leadinfo_placeholder',
            ],
        ];
        $GLOBALS['SiteConfiguration'][$table]['types']['0']['showitem'] .= '
            ,--div--;' . $lll . 'site.configuration.tab, leadinfo_id
         ';
    },
    'site'
);