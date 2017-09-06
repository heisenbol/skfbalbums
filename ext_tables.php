<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Skar.Skfbalbums',
            'Fbalbumsdisplay',
            'FB Albums List'
        );
/*
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Skar.Skfbalbums',
            'Fbalbumsdisplaysingle',
            'FB Single Album'
        );
*/
        /*** FlexForm ***/
        $extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($extKey);
        $pluginSignature = strtolower($extensionName) . '_fbalbumsdisplay'; 


        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 
            'FILE:EXT:'.$extKey.'/Configuration/FlexForms/FlexForm_fbalbumsdisplay.xml');

        /*
        in controller:
            $this->settings[photolayout]
        in view
            {settings.photolayout}
        */
        /*** FlexForm ***/

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Skar.Skfbalbums',
                'web', // Make module a submodule of 'web'
                'mod1', // Submodule key
                '', // Position
                [
                    'Token' => 'synclist,sync',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:' . $extKey . '/Resources/Public/Icons/user_mod_mod1.svg',
                    'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_mod1.xlf',
                ]
            );

        }




        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'FB Albums');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_album', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_album.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_album');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_token', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_token.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_token');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_photo', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_photo.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_photo');



    },
    $_EXTKEY
);


