<?php
namespace Skar\Skfbalbums\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class FbImageViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper  {

    /**
     * Initialize additional argument
     */
    public function initializeArguments()
    {
        $this->registerArgument('photo', 'object', 'The photo object', TRUE);
        $this->registerArgument('download', 'boolean', 'Serve from local server', TRUE);
        $this->registerArgument('size', 'string', 'Size of image', FALSE, 'medium');
        $this->registerArgument('useFbRedirectUrls', 'bool', 'Use of redirect urls', FALSE, FALSE);
        parent::initializeArguments();
    }

	/**
	* @return string
	*/
	public function render() {
		//$uriBuilder = $this->controllerContext->getUriBuilder();
		$photo = $this->arguments['photo'];
		$size = $this->arguments['size'];
		$download = $this->arguments['download'];
		$useFbRedirectUrls = $this->arguments['useFbRedirectUrls'];
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments); 

        $defaultImageUri = 'Resources/Public/Images/defaultalbumcover.png';
        //$uri = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($uri);
        //$uri = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix($uri);
        //$uri = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath($uri);
        $uri = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('skfbalbums')).$defaultImageUri ;

        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);

		//$uri = $this->renderingContext->getControllerContext()->getRequest()->getBaseUri().$defaultImageUri;

		$result = $uri;

		if ($photo && $photo->getImages()) {
			$imagesArray = json_decode($photo->getImages(), true);
			if ($imagesArray && is_array($imagesArray) && count($imagesArray) > 0) {
				if ($download) {
					// check if already downloaded
					$folder = $photo->getLocalFolder();
					$absoluteUploadDir = $this->getAbsoluteUploadDir();
					$uploadDir = $absoluteUploadDir . $folder;
				    if (!file_exists($uploadDir)) { // upload dir does not exist yet. Create it
						$mkdirResult = mkdir($uploadDir, 0700, TRUE);
						if ($mkdirResult === false) {
                            $logger->error("uploads/tx_skfbalbums folder does not exist and could not be created. Full path: ".$uploadDir);
							return false;
						}
					}
				    $dst = $this->getAbsoluteFilePath($photo->getFacebookId(), $folder);

					// if not, download it and store it in upload folder
				    if (!file_exists($dst)) { // not downloaded yet
				    	$url = $this->getSizedImageUrl($imagesArray, 'large');
					    $file = file_get_contents($url);
					    if ($file === FALSE) {
					      return false;
					    }
					    $saveResult = file_put_contents($dst, $file);
					    if ($saveResult === FALSE) {
					      return false;
					    }
				    }
				    
					// if everything was ok, return it's url
					switch ($size) {
					    case 'large':
					        return $this->getImageUrl($dst, 3000, 3000);
					    case 'small':
					        return $this->getImageUrl($dst, 400, 400);
					    case 'medium':
					        return $this->getImageUrl($dst, 1000, 1000);
					}
					return $result.$absoluteUploadDir;
				}
				if ($useFbRedirectUrls) {
					// 		https://graph.facebook.com/PICTURE_FB_ID/picture?type=thumbnail|album|normal 
					$fbId = $photo->getFacebookId();
					// for album photos, only up to "normal" is supported, which is quite low resolution
					if ($size == 'small') {
						return "https://graph.facebook.com/$fbId/picture?type=thumbnail";
					}
					return "https://graph.facebook.com/$fbId/picture?type=normal";
				}
				else {
					$result = $this->getSizedImageUrl($imagesArray, $size);
				}
			}
		}

		return $result; // the viewhelper itself does not print enything
	}
	private function getImageUrl($absoluteFilePath, $maxWidth, $maxHeight, $quality = 95) {
		$img = array();
		$img['image.']['file.']['maxH']   = $maxWidth;
		$img['image.']['file.']['maxW']   = $maxHeight;
		$img['image.']['file.']['params']  ='-quality '.$quality;
		$img['image.']['file'] = $absoluteFilePath;  
		$configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
		$cObj = $configurationManager->getContentObject();
		return $cObj->cObjGetSingle('IMG_RESOURCE', $img['image.']);
	}
	private function getSizedImageUrl($imagesArray, $size) {
		$noOfImgs = count($imagesArray);
		if ($noOfImgs == 1) {
			return $imagesArray[0]['source'];
		}
		$smallestNdx = 0;
		$largestNdx = 0;
		$mediumNdx = 0;

		if ($noOfImgs > 1) {
			for($ndx=1; $ndx<$noOfImgs;$ndx++) {
				if ( $imagesArray[$ndx]['width'] < $imagesArray[$smallestNdx]['width'] ) {
					$smallestNdx = $ndx;
				}
				if ( $imagesArray[$ndx]['width'] > $imagesArray[$largestNdx]['width'] ) {
					$largestNdx = $ndx;
				}
			}
			if ($size == 'medium') {
				for($ndx=1; $ndx<$noOfImgs;$ndx++) {
					if ( // find something in between smallest and largest that is less than 500 px
						$imagesArray[$ndx]['width'] > $imagesArray[$smallestNdx]['width'] 
						&& $imagesArray[$ndx]['width'] < $imagesArray[$largestNdx]['width'] 
						&& $imagesArray[$ndx]['width'] < 500
						) {
						$mediumNdx = $ndx;
					}
				}
			}
		}

		switch ($size) {
		    case 'large':
		        return $imagesArray[$largestNdx]['source'];
		    case 'small':
		        return $imagesArray[$smallestNdx]['source'];
		    case 'medium':
		        return $imagesArray[$mediumNdx]['source'];
		}
		
	}
	private function getAbsoluteUploadDir() {
		return \TYPO3\CMS\Core\Core\Environment::getPublicPath().'/'.$this->getRelativeUploadFolder();
	}

	private function getRelativeUploadFolder() {
		return 'uploads/tx_skfbalbums/';
	}
  private function getAbsoluteFilePath($facebookId, $folder) {
    $uploadDir = $this->getAbsoluteUploadDir();
    return $uploadDir.$folder.'/'.$facebookId.'.jpg';
  }
}

