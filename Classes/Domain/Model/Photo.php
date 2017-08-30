<?php
namespace Skar\Skfbalbums\Domain\Model;

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
 * Photo
 */
class Photo extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * facebookId
     *
     * @var string
     */
    protected $facebookId = '';

    /**
     * List of image versions.
     *
     * @var string
     */
    protected $images = '';

    /**
     * caption
     *
     * @var string
     */
    protected $caption = '';

    /**
     * captionOverride
     *
     * @var string
     */
    protected $captionOverride = '';

    /**
     * album
     *
     * @var \Skar\Skfbalbums\Domain\Model\Album
     * @lazy
     */
    protected $album = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {

    }

    /**
     * Returns the facebookId
     *
     * @return string $facebookId
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Sets the facebookId
     *
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * Returns the images
     *
     * @return string $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param string $images
     * @return void
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Returns the caption
     *
     * @return string $caption
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Sets the caption
     *
     * @param string $caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Returns the captionOverride
     *
     * @return string $captionOverride
     */
    public function getCaptionOverride()
    {
        return $this->captionOverride;
    }

    /**
     * Sets the captionOverride
     *
     * @param string $captionOverride
     * @return void
     */
    public function setCaptionOverride($captionOverride)
    {
        $this->captionOverride = $captionOverride;
    }


    public function getEffectiveCaption() {
        if ($this->captionOverride) {
            return $this->captionOverride;
        }
        return $this->caption;
    }

    /**
     * Returns the album
     *
     * @return \Skar\Skfbalbums\Domain\Model\Album $album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Sets the album
     *
     * @param \Skar\Skfbalbums\Domain\Model\Album $album
     * @return void
     */
    public function setAlbum(\Skar\Skfbalbums\Domain\Model\Album $album)
    {
        $this->album = $album;
    }
}
