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

        // I need to get the list of albums to display to the user
        // For this, I would need the storage folder selected in the plugin
        // I can get it through $config['flexParentDatabaseRow']['pages'], which is in a strange form like
        // pages_7|single,pages_3|tuc%20isotope,pages_2|aaa 
        // where adter the underscore is the page id(s)
        // I could then use the following to retrieve the albums.
        // But I do not like this. So just display all the available albums to the user
        // Moreover, it seems that flexParentDatabaseRow conflicts with compatibility6 which has these values in $config['row'] instead

/*
        $configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
        $frameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $persistenceConfiguration = array('persistence' => array('storagePid' => '7,5'));
        $configurationManager->setConfiguration(array_merge($frameworkConfiguration, $persistenceConfiguration));
        $albums = $albumRepository->findAll();
*/

        // TODO - make sure that the below does not fetch albums from pages the user does not have access
        $albums = $albumRepository->findAllInAllPages();
        if (!$albums) {
            $optionList[] = array(0 => '--No albums found anywhere in the tree--', 1 => '0');
        }
        else {
            foreach($albums as $album) {
                $optionList[] = array(
                                    0 => htmlspecialchars($album->getEffectiveName().' (Uid:'.$album->getUid().', Page Id='.$album->getPid().')', ENT_QUOTES), 
                                    1 => $album->getUid()
                                    );
            }
        }

//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($albums); 
//        $config['items'] = array_merge($config['items'],$optionList);
        $config['items'] = $optionList;
        return $config;
    }

}
