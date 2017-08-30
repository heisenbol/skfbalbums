<?php
namespace Skar\Skfbalbums\ViewHelpers;


class FbImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

   
	/**
	* @param \Skar\Skfbalbums\Domain\Model\Photo $photo
	* @param string $size
	* @return string
	*/
	public function render($photo, $size = 'medium') {
		//$uriBuilder = $this->controllerContext->getUriBuilder();


        $uri = 'EXT:skfbalbums/Resources/Public/Images/defaultalbumcover.png';
        $uri = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($uri);
        $uri = \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix($uri);
		$uri = $this->controllerContext->getRequest()->getBaseUri().$uri;

		$result = $uri;

		if ($photo && $photo->getImages()) {
			$imagesArray = json_decode($photo->getImages(), true);
			if ($imagesArray && is_array($imagesArray) && count($imagesArray) > 0) {
				$result = $this->getSizedImageUrl($imagesArray, $size);
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
		        break;
		    case 'small':
		        return $imagesArray[$smallestNdx]['source'];
		        break;
		    case 'medium':
		        return $imagesArray[$mediumNdx]['source'];
		        break;
		}
		
	}
}

