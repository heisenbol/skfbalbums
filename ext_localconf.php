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

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					fbalbumsdisplay {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey) . 'Resources/Public/Icons/user_plugin_fbalbumsdisplay.svg
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
    },
    $_EXTKEY
);



$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Skar\Skfbalbums\Task\SyncTokens::class] = array(
        'extension' => $_EXTKEY,
        'title' => 'Sync Facebook Page Albums',
        'description' => 'Syncs Facebook Page Albums and Photos.',
        'additionalFields' => \Skar\Skfbalbums\Task\SyncTokens::class
);