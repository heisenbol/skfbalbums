<?php
namespace Skar\Skfbalbums\ViewHelpers;


class FbImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * Initialize additional argument
     */
    public function initializeArguments()
    {
        $this->registerArgument('photo', 'object', 'The photo object', TRUE);
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
		$useFbRedirectUrls = $this->arguments['useFbRedirectUrls'];
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments); 

        $uri = 'EXT:skfbalbums/Resources/Public/Images/defaultalbumcover.png';
        //$uri = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($uri);
        //$uri = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix($uri);
		$uri = $this->renderingContext->getControllerContext()->getRequest()->getBaseUri().$uri;

		$result = $uri;

		if ($photo && $photo->getImages()) {
			$imagesArray = json_decode($photo->getImages(), true);
			if ($imagesArray && is_array($imagesArray) && count($imagesArray) > 0) {
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
}

