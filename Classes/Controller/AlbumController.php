<?php
namespace Skar\Skfbalbums\Controller;

/***
 *
 * This file is part of the "FB Albums" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Stefanos Karasavvidis <sk@karasavvidis.gr>
 *
 ***/

/**
 * AlbumController
 */
class AlbumController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    // http://coding.musikinsnetz.de/typo3-extbase-fluid/general/automatic-page-cache-clearance-on-extension-record-update
    /**
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    /**
     * albumRepository
     *
     * @var \Skar\Skfbalbums\Domain\Repository\AlbumRepository
     * @inject
     */
    protected $albumRepository = null;

    /**
     * tokenRepository
     *
     * @var \Skar\Skfbalbums\Domain\Repository\TokenRepository
     * @inject
     */
    protected $tokenRepository = null;



    /**
     * photoRepository
     *
     * @var \Skar\Skfbalbums\Domain\Repository\PhotoRepository
     * @inject
     */
    protected $photoRepository = null;


    // http://coding.musikinsnetz.de/typo3-extbase-fluid/general/automatic-page-cache-clearance-on-extension-record-update
    public function addCacheTags($cacheTag) {
        // do this only in frontend
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            // only set the tag once in one request, so cache statically if it has been done
            static $cacheTagsSet = FALSE;

            /** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController  */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(array($cacheTag));
                $cacheTagsSet = TRUE;
            }
            $this->typoScriptFrontendController = $typoScriptFrontendController;
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
//        $token = $this->tokenRepository->findByUid(4);
//         $data = $token->sync();
//         \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($data); 

        $albums = $this->albumRepository->findAll();
        foreach($albums as $album) {
            if ($album->getCoverPhotoFbId()) {
                $album->coverPhoto = $this->photoRepository->findByFbId($album->getCoverPhotoFbId());
            }
            else
            {
                $album->coverPhoto = null;
            }
        }
        
        if (!$this->settings || !array_key_exists('albumlayout',$this->settings) || !$this->settings['albumlayout']) {
            $this->settings['albumlayout'] = "Default";
        }

        if ($this->settings['albumlayout'] == 'Default' || $this->settings['albumlayout'] == 'CssMasonry') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Css/Layouts/'.$this->settings['albumlayout'].'/styles.css" />');
        }

        $this->addCacheTags('tx_skfbalbums_domain_model_album'); // the database table name of your domain model

        //in view use {settings.photolayout}. But this does not work when changing the setting. So use a variable instead
        $this->view->assign('albumlayout', $this->settings['albumlayout']);
        $this->view->assign('albums', $albums);
    }

    /**
     * action show
     *
     * @param \Skar\Skfbalbums\Domain\Model\Album $album
     * @param bool $showBacklink
     * @return void
     */
    public function showAction(\Skar\Skfbalbums\Domain\Model\Album $album, $showBacklink = true)
    {
        $GLOBALS['TSFE']->page['title'] = $album->getNameOverride()?htmlspecialchars($album->getNameOverride()):htmlspecialchars($album->getName());
        $album->photos = $this->photoRepository->getPhotosByAlbum($album, false);

        if (!$this->settings || !array_key_exists('photolayout',$this->settings) || !$this->settings['photolayout']) {
            $this->settings['photolayout'] = "Default";
        }


        if ($this->settings['photolayout'] == 'Default' || $this->settings['photolayout'] == 'CssMasonry') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Css/Layouts/'.$this->settings['albumlayout'].'/styles.css" />');
        }
        if ($this->settings['photolayout'] == 'CssMasonry') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/jsOnlyLightbox/css/lightbox.css" />');
        }
        if ($this->settings['photolayout'] == 'unitegallerythumb') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/unitegallery/css/unite-gallery.css" />');
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/unitegallery/themes/default/ug-theme-default.css" />');

        }

        $this->addCacheTags('tx_skfbalbums_domain_model_album_'.$album->getUid()); // the database table name of your domain model plus the UID of your record

        //in view use {settings.photolayout}. But this does not work when changing the setting. So use a variable instead
        $this->view->assign('photolayout', $this->settings['photolayout']);
        $this->view->assign('album', $album);
        $this->view->assign('showBacklink', $showBacklink);
        $this->view->assign('cobjUid', $this->configurationManager->getContentObject()->data['uid']);
    }

    /**
     * initialize action show
     * @return void
     */
    public function initializeShowAction() {
        // if there is no album parameter for the show action, retrieve it from flexform.
        // USE switchable action with flexform
        $album = null;
        $showBacklink = true;
        if($this->request->hasArgument('album')){
            //$album=$this->albumRepository->findByUid( intval($this->request->getArgument('album')) );
            $albumId = $this->request->getArgument('album');
            $showBacklink = true;
        }
        else { // try to get it from flexform
            if ($this->settings && array_key_exists('albumForSingle',$this->settings)) {
                $albumId = $this->settings['albumForSingle'];
                $showBacklink = false;
            }
        }

        if ($albumId) {
            $album = $this->albumRepository->findByUid( intval($albumId) );
        }

        if( $album ){
            $this->request->setArgument('album',$album);
            $this->request->setArgument('showBacklink',$showBacklink);
            return;
        }
        else {
            echo 'No album selected. TODO better handling';
            exit();
        }
        
    }
}
