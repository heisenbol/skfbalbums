<?php

namespace Skar\Skfbalbums\Helper;


class FlexformPreview
{

    private $flexformData = [];

    const LLPATH = 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:';
    /**
     * Preview plugin settings in page module
     *
     * @param array $params configuration array
     */
    public function getExtensionSummary(array $params)
    {
        $flexFormService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\FlexFormService::class);
        $this->flexformData = $flexFormService->convertFlexFormContentToArray($params['row']['pi_flexform']);

        $header = '<strong>' . htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'pi1_title')) . '</strong>';
        $displayMode = '';
        $storage = '';
        $albumLayout = '';
        $singleLayout = '';
        $singleAlbum = '';
        $uniteTheme = '';
        $redirectUrls = '';

        if ($params['row']['list_type'] == 'skfbalbums_fbalbumsdisplay') {
            if (isset($this->flexformData['switchableControllerActions']) &&  $this->flexformData['switchableControllerActions'] == 'Album->show;') {
                $displayMode = htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms.album_single'));
                $album = null;
                if (isset($this->flexformData['settings']['albumForSingle'])) {
                    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
                    $albumRepository = $objectManager->get('Skar\Skfbalbums\Domain\Repository\AlbumRepository');
                    $album = $albumRepository->findByUid($this->flexformData['settings']['albumForSingle']);
                }
                if ($album) {
                    $singleAlbum = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.singlealbum')).':</strong> '.htmlspecialchars($album->getEffectiveName());
                }
                else {
                    $singleAlbum = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.singlealbum')).':</strong> '
                                        .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.albumnoneselected'));
                }
            }
            else if (isset($this->flexformData['switchableControllerActions']) &&  $this->flexformData['switchableControllerActions'] == 'Album->list;Album->show;') {
                $displayMode = htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms.album_list'));
                if (isset($this->flexformData['settings']['albumlayout'])) {
                    $albumLayout = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.albumlistlayout')).':</strong> '
                                            .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms.album_list.layout'.strtolower($this->flexformData['settings']['albumlayout'])));
                }
            }
            if (isset($this->flexformData['settings']['photolayout'])) {
                $singleLayout = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.singlealbumlayout')).':</strong> '
                .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'flexforms.album_single.layout'.strtolower($this->flexformData['settings']['photolayout'])));
                if (strtolower($this->flexformData['settings']['photolayout']) == 'unitegallery') {
                    
                    if (isset($this->flexformData['settings']['unitetheme'])) {
                        $uniteTheme = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.unitetheme')).':</strong> '
                                            .htmlspecialchars($this->flexformData['settings']['unitetheme']);
                    }
                    else {
                        $uniteTheme = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.unitetheme')).':</strong> '
                                                .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.unitethemenotset'));
                    }
                }
            }

            if (isset($this->flexformData['settings']['useFbRedirectUrls'])) {

                $redirectUrls = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.redirecturls')).':</strong> '
                                            .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.redirectno'));
                if ($this->flexformData['settings']['useFbRedirectUrls']) {
                    $redirectUrls = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.redirecturls')).':</strong> '
                                                .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.redirectyes'));
                }
            }

            $storageId = intval($params['row']['pages']);
            if (!$storageId) {
                $storage = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.stored')).':</strong> '
                                        .htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.storednoneselected'));
            }
            else {
                $storagePageTitles = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $storageId, 'title');
                if ($storagePageTitles && is_array($storagePageTitles) && count($storagePageTitles) ) {
                    $storage = '<strong>'.htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'preview.label.stored')).':</strong> '
                                            .htmlspecialchars($storagePageTitles['title']. " ($storageId)");
                }
                else {

                }
            }

            $result = $header;
            if ($displayMode) {
                $result .= "<br>$displayMode";
            }
            $result .= '<ul>';
            if ($singleAlbum) {
                $result .= "<li>$singleAlbum</li>";
            }
            if ($storage) {
                $result .= "<li>$storage</li>";
            }
            if ($albumLayout) {
                $result .= "<li>$albumLayout</li>";
            }
            if ($singleLayout) {
                $result .= "<li>$singleLayout</li>";
            }
            if ($uniteTheme) {
                $result .= "<li>$uniteTheme</li>";
            }
            if ($redirectUrls) {
                $result .= "<li>$redirectUrls</li>";
            }

            $result .= '</ul>';
            return $result;
//                .'<br><pre>aaaaa'.print_r($this->flexformData,true).'</pre>'
//                .'<br><pre>bbbb'.htmlspecialchars(print_r($params,true)).'</pre>'
                ;
        }
        return '';
    }



}