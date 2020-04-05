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
 * Represents a Facebook Album
 */
class Album extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Override name retrieved from FB
     *
     * @var string
     */
    protected $nameOverride = '';

    /**
     * Override description retrieved from FB
     *
     * @var string
     */
    protected $descriptionOverride = '';

    /**
     * facebookId
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $facebookId = '';

    /**
     * link
     *
     * @var string
     */
    protected $link = '';

    /**
     * coverPhotoFbId
     *
     * @var string
     */
    protected $coverPhotoFbId = '';

    /**
     * token
     *
     * @var \Skar\Skfbalbums\Domain\Model\Token
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $token = null;

    /**
     * lastSynced
     *
     * @var \DateTime
     */
    protected $lastSynced = null;


    /**
     * hidden
     *
     * @var bool
     */
    protected $hidden = false;


    /**
     * download
     *
     * @var bool
     */
    protected $download = false;


    /**
     * Returns the download
     *
     * @return bool $download
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Sets the download
     *
     * @param bool $download
     * @return void
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }

    /**
     * Returns the boolean state of download
     *
     * @return bool
     */
    public function isDownload()
    {
        return $this->download;
    }


/**
     * Returns the hidden
     *
     * @return bool $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the hidden
     *
     * @param bool $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the boolean state of hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEffectiveDescription() {
        if ($this->descriptionOverride) {
            return $this->descriptionOverride;
        }
        return $this->description;
    }
    public function getEffectiveName() {
        if ($this->nameOverride) {
            return $this->nameOverride;
        }
        return $this->name;
    }    

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the nameOverride
     *
     * @return string $nameOverride
     */
    public function getNameOverride()
    {
        return $this->nameOverride;
    }

    /**
     * Sets the nameOverride
     *
     * @param string $nameOverride
     * @return void
     */
    public function setNameOverride($nameOverride)
    {
        $this->nameOverride = $nameOverride;
    }

    /**
     * Returns the descriptionOverride
     *
     * @return string $descriptionOverride
     */
    public function getDescriptionOverride()
    {
        return $this->descriptionOverride;
    }

    /**
     * Sets the descriptionOverride
     *
     * @param string $descriptionOverride
     * @return void
     */
    public function setDescriptionOverride($descriptionOverride)
    {
        $this->descriptionOverride = $descriptionOverride;
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
     * Returns the link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link
     *
     * @param string $link
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Returns the coverPhotoFbId
     *
     * @return string $coverPhotoFbId
     */
    public function getCoverPhotoFbId()
    {
        return $this->coverPhotoFbId;
    }

    /**
     * Sets the coverPhotoFbId
     *
     * @param string $coverPhotoFbId
     * @return void
     */
    public function setCoverPhotoFbId($coverPhotoFbId)
    {
        $this->coverPhotoFbId = $coverPhotoFbId;
    }

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
     * Returns the token
     *
     * @return \Skar\Skfbalbums\Domain\Model\Token $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the token
     *
     * @param \Skar\Skfbalbums\Domain\Model\Token $token
     * @return void
     */
    public function setToken(\Skar\Skfbalbums\Domain\Model\Token $token)
    {
        $this->token = $token;
    }

    /**
     * Returns the lastSynced
     *
     * @return \DateTime $lastSynced
     */
    public function getLastSynced()
    {
        return $this->lastSynced;
    }

    /**
     * Sets the lastSynced
     *
     * @param \DateTime $lastSynced
     * @return void
     */
    public function setLastSynced(\DateTime $lastSynced)
    {
        $this->lastSynced = $lastSynced;
    }
}
