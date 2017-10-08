<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Skar.Skfbalbums',
            'Fbalbumsdisplay',
            [
                'Album' => 'list, show'
            ],
            // non-cacheable actions
            [
                'Album' => ''
            ]
        );


		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
			'mod {
				wizards.newContentElement.wizardItems.plugins {
					elements {
						fbalbumsdisplay {
							icon = EXT:skfbalbums/Resources/Public/Icons/user_plugin_fbalbumsdisplay.svg
							title = LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_fbalbumsdisplay
							description = LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_fbalbumsdisplay.description
							tt_content_defValues {
								CType = list
								list_type = skfbalbums_fbalbumsdisplay
							}
						}
					}
					show = *
				}
		   }'
		);


        if (TYPO3_MODE === 'BE') {
            // Page module hook - show flexform settings in page module
            $extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($extKey);
            $pluginSignature = strtolower($extensionName) . '_fbalbumsdisplay'; 
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$pluginSignature][$_EXTKEY] =
                \Skar\Skfbalbums\Helper\FlexformPreview::class . '->getExtensionSummary';
        }
    },
    $_EXTKEY
);



$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Skar\Skfbalbums\Task\SyncTokens::class] = array(
        'extension' => $_EXTKEY,
        'title' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:scheduler_title',
        'description' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:scheduler_description',
        'additionalFields' => \Skar\Skfbalbums\Task\SyncTokens::class
);