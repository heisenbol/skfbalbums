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
    const ALBUMLAYOUT = 'albumlayout';
    const PLAIN = 'Plain';
    const CSSMASONRY = 'CssMasonry';
    const EXTKEY = 'skfbalbums';
    const PHOTOLAYOUT = 'photolayout';
    const ALBUM = 'album';

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


    public function addCacheTags($cacheTag) {
        // do this only in frontend
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            // only set the tag once in one request, so cache statically if it has been done
            static $cacheTagsSet = FALSE;

            /** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController  */
            $this->typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $this->typoScriptFrontendController->addCacheTags(array($cacheTag));
                $cacheTagsSet = TRUE;
            }
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $albums = $this->albumRepository->findAll(); 

        $noAlbums = true;
        $albumsForView = [];
        foreach($albums as $album) {
            $photoCount = $this->photoRepository->getPhotosByAlbum($album, false,true);
            if ($photoCount) {
                $noAlbums = false;

                $album->photoCount = $photoCount;
                if ($album->getCoverPhotoFbId()) {
                    $album->coverPhoto = $this->photoRepository->findByFbId($album->getCoverPhotoFbId());
                }
                else
                {
                    $album->coverPhoto = null;
                }
                $albumsForView[] = $album;
            }
        }
        
        if (!$this->settings || !array_key_exists(self::ALBUMLAYOUT,$this->settings) || !$this->settings[self::ALBUMLAYOUT]) {
            $this->settings[self::ALBUMLAYOUT] = self::PLAIN;
        }

        if ($this->settings[self::ALBUMLAYOUT] == self::PLAIN || $this->settings[self::ALBUMLAYOUT] == self::CSSMASONRY) {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Css/Layouts/'.$this->settings[self::ALBUMLAYOUT].'/styles.css" />');
        }
        if ($noAlbums) {
            $this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_skfbalbums_noalbumsavailableerror',self::EXTKEY), 
                '', 
                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                );
        }

        // the database table name of your domain model
        $this->addCacheTags('tx_skfbalbums_domain_model_album'); 


        //in view use {settings.photolayout}. But this does not work when changing the setting. So use a variable instead
        $this->view->assign(self::ALBUMLAYOUT, $this->settings[self::ALBUMLAYOUT]);
        $this->view->assign('albums', $albumsForView);
        $this->view->assign('useFbRedirectUrls', $this->settings['useFbRedirectUrls']);
        $this->view->assign('albumlistHideTitle', $this->settings['albumlistHideTitle']);
        $this->view->assign('albumlistHideDescription', $this->settings['albumlistHideDescription']);
        $this->view->assign('albumlistCssMasonryColumns', intval($this->settings['albumlistCssMasonryColumns']));


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
        if (!$album->getUid()) {
            $this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_skfbalbums_nosinglealbumerror',self::EXTKEY), 
                '', 
                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                );
            $this->view->assign(self::PHOTOLAYOUT, self::PLAIN);
            $this->view->assign(self::ALBUM, $album);

            return;
        }
        if (!$this->settings['photolistNoTitleHead']) {
            $GLOBALS['TSFE']->page['title'] = $album->getNameOverride()?htmlspecialchars($album->getNameOverride()):htmlspecialchars($album->getName());
        }
        $album->photos = $this->photoRepository->getPhotosByAlbum($album, false);

        if (!$this->settings || !array_key_exists(self::PHOTOLAYOUT,$this->settings) || !$this->settings[self::PHOTOLAYOUT]) {
            $this->settings[self::PHOTOLAYOUT] = self::PLAIN;
        }


        if ($this->settings[self::PHOTOLAYOUT] == self::PLAIN || $this->settings[self::PHOTOLAYOUT] == self::CSSMASONRY) {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Css/Layouts/'.$this->settings[self::PHOTOLAYOUT].'/styles.css" />');
        }
        if ($this->settings[self::PHOTOLAYOUT] == self::CSSMASONRY) {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Libs/jsOnlyLightbox/css/lightbox.css" />');
        }
        $uniteOptions = [];
        if ($this->settings[self::PHOTOLAYOUT] == 'Unitegallery') {
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Css/Layouts/'.$this->settings[self::PHOTOLAYOUT].'/styles.css" />');
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Libs/Unitegallery/css/unite-gallery.css" />');

            if (!$this->settings || !array_key_exists('unitetheme',$this->settings) || !$this->settings['unitetheme'] 
                || !in_array(
                    $this->settings['unitetheme'], 
                    ['default','tilescolumns','tilesjustified','tilesnested','tilesgrid','carousel','compact','gridtheme','slider']
                    )
                ) {
                $this->settings['unitetheme'] = "default";
            }




            $uniteLibJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Libs/Unitegallery/js/unitegallery.min.js';


            $this->pageRenderer->addJsFooterLibrary("skfbalbumsunitelib", $uniteLibJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
    

            $uniteTheme = $this->settings['unitetheme'];
            $uniteLibThemeJsFile = null;
            if ($uniteTheme == 'default') {
                $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' 
                    . \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/default/ug-theme-default.css" />');

                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/default/ug-theme-default.js';
            }
            else if ($uniteTheme == 'tilescolumns') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";

            }
            else if ($uniteTheme == 'tilesjustified') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";
                $uniteOptions['tiles_type'] = "justified";
            }
            else if ($uniteTheme == 'tilesnested') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tiles/ug-theme-tiles.js';
                $uniteOptions['gallery_theme'] = "tiles";
                $uniteOptions['tiles_type'] = "nested";
            }
            else if ($uniteTheme == 'tilesgrid') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/tilesgrid/ug-theme-tilesgrid.js';
                $uniteOptions['gallery_theme'] = "tilesgrid";
            }
            else if ($uniteTheme == 'carousel') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/carousel/ug-theme-carousel.js';
                $uniteOptions['gallery_theme'] = "carousel";
            }
            else if ($uniteTheme == 'compact') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/compact/ug-theme-compact.js';
                $uniteOptions['gallery_theme'] = "compact";
            }
            else if ($uniteTheme == 'gridtheme') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/grid/ug-theme-grid.js';
                $uniteOptions['gallery_theme'] = "grid";
            }
            else if ($uniteTheme == 'slider') {
                $uniteLibThemeJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                    . 'Resources/Public/Libs/Unitegallery/themes/slider/ug-theme-slider.js';
                $uniteOptions['gallery_theme'] = "slider";
            }
            if ($uniteLibThemeJsFile) {
                $this->pageRenderer->addJsFooterLibrary("skfbalbumsunitethemelib".$uniteTheme, $uniteLibThemeJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
            }
            
            $uniteInitJsFile = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(self::EXTKEY)) 
                . 'Resources/Public/Js/Layouts/'.$this->settings[self::PHOTOLAYOUT].'/uniteinit.js';
            $this->pageRenderer->addJsFooterFile($uniteInitJsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 
            //$GLOBALS['TSFE']->getPageRenderer()->addJsFooterLibrary ($name, $file, $type= 'text/javascript', $compress=false, $forceOnTop=false, $allWrap= '', $excludeFromConcatenation=false, $splitChar= '|', $async=false, $integrity= '')
            //$GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', TRUE, FALSE, '', TRUE); 

            if (!$this->settings || !array_key_exists('uniteparams',$this->settings) || !$this->settings['uniteparams'] ) {
                $this->settings['uniteparams'] = "";
            }
            
            if (array_key_exists('uniteparam', $this->settings) && is_array($this->settings['uniteparam'])) {
                foreach ($this->settings['uniteparam'] as $uniteParam => $uniteValue) {
                    if (trim($uniteValue)) {
                        if ($uniteParam == 'autoplay') {
                            if ($uniteValue) {
                                $uniteValue = true;
                            }
                            else {
                                $uniteValue = false;
                            }
                            $autoPlayProperty = 'gallery_autoplay';
                            if ($uniteTheme == 'carousel') {
                                $autoPlayProperty = 'carousel_autoplay';
                            }

                            $uniteOptions[$autoPlayProperty] = $uniteValue;
                        }
                        else if ($uniteParam == 'autoplayinterval') {
                            $autoPlayProperty = 'gallery_play_interval';
                            if ($uniteTheme == 'carousel') {
                                $autoPlayProperty = 'carousel_autoplay_timeout';
                            }

                            $uniteOptions[$autoPlayProperty] = intval($uniteValue);
                        }
                        else {
                            if (strpos($uniteValue,'%') === FALSE) {
                                $uniteOptions[$uniteParam] = intval(trim($uniteValue));
                            }
                            else {
                                $uniteOptions[$uniteParam] = trim($uniteValue);
                            }
                        }
                    }
                }
            }

            if (trim($this->settings['uniteparams'])) {
                $additionalParams = preg_split("/\r\n|\n|\r/", $this->settings['uniteparams']);
                foreach($additionalParams as $additionalParam) {
                    $parts = explode(':', $additionalParam);
                    if (count($parts) == 2) {
                        if (strpos($uniteValue,'%') === FALSE) {
                            $uniteOptions[trim($parts[0])] = intval(trim($parts[1]));
                        }
                        else {
                            $uniteOptions[trim($parts[0])] = trim($parts[1]);
                        }
                    }
                }
            }
        }
    


        $this->addCacheTags('tx_skfbalbums_domain_model_album_'.$album->getUid()); // the database table name of your domain model plus the UID of your record
        //in view use {settings.photolayout}. But this does not work when changing the setting. So use a variable instead
        $this->view->assign(self::PHOTOLAYOUT, $this->settings[self::PHOTOLAYOUT]);
        $this->view->assign(self::ALBUM, $album);
        $this->view->assign('uniteOptions', json_encode($uniteOptions, JSON_FORCE_OBJECT));
        $this->view->assign('showBacklink', $showBacklink);
        $this->view->assign('cobjUid', $this->configurationManager->getContentObject()->data['uid']);
        $this->view->assign('useFbRedirectUrls', $this->settings['useFbRedirectUrls']);
        $this->view->assign('photolistHideCaption', $this->settings['photolistHideCaption']);
        $this->view->assign('photolistHideAlbumTitle', $this->settings['photolistHideAlbumTitle']);
        $this->view->assign('photolistHideAlbumDescription', $this->settings['photolistHideAlbumDescription']);

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
        if($this->request->hasArgument(self::ALBUM)){
            //$album=$this->albumRepository->findByUid( intval($this->request->getArgument('album')) );
            $albumId = $this->request->getArgument(self::ALBUM);
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
            $this->request->setArgument(self::ALBUM,$album);
            $this->request->setArgument('showBacklink',$showBacklink);
            return;
        }
        else {
            $album = new \Skar\Skfbalbums\Domain\Model\Album;
            $this->request->setArgument(self::ALBUM,$album);
            return;
        }
        
    }
}
