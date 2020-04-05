<?php
namespace Skar\Skfbalbums\ViewHelpers;


class FbImageOrientationViewHelper extends  \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper  {

   
	/**
	* @param \Skar\Skfbalbums\Domain\Model\Photo $photo
	* @param string $textHorizontal
	* @param string $textVertical
	* @return string
	*/
	public function render($photo, $textHorizontal, $textVertical) {


		if ($photo && $photo->getImages()) {
			$imagesArray = json_decode($photo->getImages(), true);
			if ($imagesArray && is_array($imagesArray) && count($imagesArray) > 0) {
				if ($imagesArray[0]['width'] > $imagesArray[0]['height']) {
					return $textHorizontal;
				}
				if ($imagesArray[0]['width'] < $imagesArray[0]['height']) {
					return $textVertical;
				}

				return $textHorizontal;
			}
		}
		return '';
	}

}

