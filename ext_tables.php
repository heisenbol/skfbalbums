<?php
defined('TYPO3_MODE') || die('Access denied.');

(function () {


        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Skar.Skfbalbums',
                'web', // Make module a submodule of 'web'
                'mod1', // Submodule key
                '', // Position
                [
                    'Token' => 'synclist,sync,checkconnection',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:skfbalbums/Resources/Public/Icons/user_mod_mod1.svg',
                    'labels' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_mod1.xlf',
                ]
            );

        }


        // csh for flexform...
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tt_content.pi_flexform.skfbalbums_fbalbumsdisplay.list', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_flexforms.xlf');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_album', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_album.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_album');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_token', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_token.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_token');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_skfbalbums_domain_model_photo', 'EXT:skfbalbums/Resources/Private/Language/locallang_csh_tx_skfbalbums_domain_model_photo.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_skfbalbums_domain_model_photo');



})();


