<?php

namespace Skar\Skfbalbums\Helper;

/**
 * Userfunc to render alternative label for media elements
 */
class ItemsProcFunc
{



    /**
     * Itemsproc function to extend the selection of single albums to display in flexform
     *
     * @param array &$config configuration array
     */
    public function user_singleAlbum(array &$config)
    {
        $optionList = array();

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $albumRepository = $objectManager->get('Skar\Skfbalbums\Domain\Repository\AlbumRepository');

        $pluginUid = 0;
        $recordStoragePagesComma = null;
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('compatibility6')) {
            $pluginUid = intval($config['row']['uid']);
        } else {
            $pluginUid = intval($config['flexParentDatabaseRow']['uid']);
        }
        if ($pluginUid) {
            // retrieve the set record storage page of plugin
            $pluginTtContentRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $pluginUid, 'pages');
            if ($pluginTtContentRecord && is_array($pluginTtContentRecord) && count($pluginTtContentRecord) > 0) {
                $recordStoragePagesComma = $pluginTtContentRecord['pages'];
            }
        }


        if (!$recordStoragePagesComma) {

            $text = htmlspecialchars($GLOBALS['LANG']->sL('LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:flexforms.album_single.record_storage_page_not_set'));
            //$optionList[] = array(0 => '--Please set the Record Storage Page and save--', 1 => '0');
            $optionList[] = array(0 => "--$text--", 1 => '0');
        }
        else {
            $configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
            $frameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $persistenceConfiguration = array('persistence' => array('storagePid' => $recordStoragePagesComma));
            $configurationManager->setConfiguration(array_merge($frameworkConfiguration, $persistenceConfiguration));
            $albums = $albumRepository->findAll();

            // $albums = $albumRepository->findAllInAllPages();
            if (count($albums) == 0) {
                $text = htmlspecialchars($GLOBALS['LANG']->sL('LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:flexforms.album_single.no_albums_found'));
                $optionList[] = array(0 => "--$text--", 1 => '0');
            }
            else {
                foreach($albums as $album) {
                    $optionList[] = array(
                        0 => htmlspecialchars($album->getEffectiveName().' (Uid:'.$album->getUid().', Page Id='.$album->getPid().')', ENT_QUOTES), 
                        1 => $album->getUid()
                    );
                }
            }
        }


//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($albums); 
//        $config['items'] = array_merge($config['items'],$optionList);
        $config['items'] = $optionList;
        return $config;
    }

}
