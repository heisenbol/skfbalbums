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
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     * @inject
     */
    protected $pageRenderer;

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
        $this->view->assign('useFbRedirectUrls', $this->settings['useFbRedirectUrls']);
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
                . 'Resources/Public/Css/Layouts/'.$this->settings['photolayout'].'/styles.css" />');
        }
        if ($this->settings['photolayout'] == 'CssMasonry') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/jsOnlyLightbox/css/lightbox.css" />');
        }
        $uniteOptions = [];
        if ($this->settings['photolayout'] == 'Unitegallery') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Css/Layouts/'.$this->settings['photolayout'].'/styles.css" />');
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/Unitegallery/css/unite-gallery.css" />');

            if (!$this->settings || !array_key_exists('unitetheme',$this->settings) || !$this->settings['unitetheme'] 
                || !in_array(
                    $this->settings['unitetheme'], 
                    ['default','tilescolumns','tilesjustified','tilesnested','tilesgrid','carousel','compact','gridtheme','slider']
                    )
                ) {
                $this->settings['unitetheme'] = "default";
            }




            $uniteLibJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Libs/Unitegallery/js/unitegallery.min.js';


            $this->pageRenderer->addJsFooterLibrary("skfbalbumsunitelib", $uniteLibJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
    

            $uniteTheme = $this->settings['unitetheme'];
            $uniteLibThemeJsFile = null;
            if ($uniteTheme == 'default') {
                $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                    . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/default/ug-theme-default.css" />');

                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/default/ug-theme-default.js';
            }
            else if ($uniteTheme == 'tilescolumns') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";

            }
            else if ($uniteTheme == 'tilesjustified') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";
                $uniteOptions['tiles_type'] = "justified";
            }
            else if ($uniteTheme == 'tilesnested') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";
                $uniteOptions['tiles_type'] = "nested";
            }
            else if ($uniteTheme == 'tilesgrid') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tilesgrid/ug-theme-tilesgrid.js';
                $uniteOptions['gallery_theme'] = "tilesgrid";
            }
            else if ($uniteTheme == 'carousel') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/carousel/ug-theme-carousel.js';
                $uniteOptions['gallery_theme'] = "carousel";
            }
            else if ($uniteTheme == 'compact') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/compact/ug-theme-compact.js';
                $uniteOptions['gallery_theme'] = "compact";
            }
            else if ($uniteTheme == 'gridtheme') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/grid/ug-theme-grid.js';
                $uniteOptions['gallery_theme'] = "grid";
            }
            else if ($uniteTheme == 'slider') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                    . 'Resources/Public/Libs/Unitegallery/themes/slider/ug-theme-slider.js';
                $uniteOptions['gallery_theme'] = "slider";
            }
            if ($uniteLibThemeJsFile) {
                $this->pageRenderer->addJsFooterLibrary("skfbalbumsunitethemelib".$uniteTheme, $uniteLibThemeJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
            }
            
            $uniteInitJsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) 
                . 'Resources/Public/Js/Layouts/'.$this->settings['photolayout'].'/uniteinit.js';
            $this->pageRenderer->addJsFooterFile($uniteInitJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
            //$GLOBALS['TSFE']->getPageRenderer()->addJsFooterLibrary ($name, $file, $type= 'text/javascript', $compress=false, $forceOnTop=false, $allWrap= '', $excludeFromConcatenation=false, $splitChar= '|', $async=false, $integrity= '')
            //$GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 

            if (!$this->settings || !array_key_exists('uniteparams',$this->settings) || !$this->settings['uniteparams'] ) {
                $this->settings['uniteparams'] = "";
            }
            
            if (trim($this->settings['uniteparams'])) {
                $additionalParams = preg_split("/\r\n|\n|\r/", $this->settings['uniteparams']);
                foreach($additionalParams as $additionalParam) {
                    $parts = explode(':', $additionalParam);
                    if (count($parts) == 2) {
                        $uniteOptions[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }

        }
    
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( json_encode($uniteOptions, JSON_FORCE_OBJECT)); 


        $this->addCacheTags('tx_skfbalbums_domain_model_album_'.$album->getUid()); // the database table name of your domain model plus the UID of your record
        //in view use {settings.photolayout}. But this does not work when changing the setting. So use a variable instead
        $this->view->assign('photolayout', $this->settings['photolayout']);
        $this->view->assign('album', $album);
        $this->view->assign('uniteOptions', json_encode($uniteOptions, JSON_FORCE_OBJECT));
        $this->view->assign('skata', 'skata1');
        $this->view->assign('showBacklink', $showBacklink);
        $this->view->assign('cobjUid', $this->configurationManager->getContentObject()->data['uid']);
        $this->view->assign('useFbRedirectUrls', $this->settings['useFbRedirectUrls']);
//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( $this->settings); 
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
