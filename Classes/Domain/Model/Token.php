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
 * Token
 */
class Token extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{


    /**
     * albumRepository
     *
     * @var \Skar\Skfbalbums\Domain\Repository\AlbumRepository
     * @inject
     */
    protected $albumRepository = null;


    /**
     * albumRepository
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




    /**
     * name
     *
     * @var string
     * @validate NotEmpty
     */
    protected $name = '';

    /**
     * Facebook App ID
     *
     * @var string
     * @validate NotEmpty
     */
    protected $appId = '';

    /**
     * Facebook App Secret
     *
     * @var string
     * @validate NotEmpty
     */
    protected $appSecret = '';

    /**
     * Facebook Page ID
     *
     * @var string
     * @validate NotEmpty
     */
    protected $pageId = '';

    /**
     * excludeAlbumIds
     *
     * @var string
     */
    protected $excludeAlbumIds = '';

    /**
     * includeAlbumIds
     *
     * @var string
     */
    protected $includeAlbumIds = '';






    /**
     * Returns the appId
     *
     * @return string $appId
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Sets the appId
     *
     * @param string $appId
     * @return void
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * Returns the appSecret
     *
     * @return string $appSecret
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Sets the appSecret
     *
     * @param string $appSecret
     * @return void
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }

    /**
     * Returns the pageId
     *
     * @return string $pageId
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Sets the pageId
     *
     * @param string $pageId
     * @return void
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
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

    /**
     * Returns the excludeAlbumIds
     *
     * @return string $excludeAlbumIds
     */
    public function getExcludeAlbumIds()
    {
        return $this->excludeAlbumIds;
    }

    /**
     * Sets the excludeAlbumIds
     *
     * @param string $excludeAlbumIds
     * @return void
     */
    public function setExcludeAlbumIds($excludeAlbumIds)
    {
        $this->excludeAlbumIds = $excludeAlbumIds;
    }

    /**
     * Returns the includeAlbumIds
     *
     * @return string $includeAlbumIds
     */
    public function getIncludeAlbumIds()
    {
        return $this->includeAlbumIds;
    }

    /**
     * Sets the includeAlbumIds
     *
     * @param string $includeAlbumIds
     * @return void
     */
    public function setIncludeAlbumIds($includeAlbumIds)
    {
        $this->includeAlbumIds = $includeAlbumIds;
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
     * @param int $mode
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getAlbums($mode) {
        return $this->albumRepository->getAlbums($this, $mode, false);
    }

    /**
     * @param int $mode
     * @return int
     */
    private function getAlbumCount($mode) {
        return $this->albumRepository->getAlbums($this, $mode, true);
    }


    /**
     * @return int
     */
    public function getAlbumCountHidden() {
        return $this->getAlbumCount(\Skar\Skfbalbums\Domain\Repository\AlbumRepository::ONLY_HIDDEN);
    }

    /**
     * @return int
     */
    public function getAlbumCountNonHidden() {
        return $this->getAlbumCount(\Skar\Skfbalbums\Domain\Repository\AlbumRepository::ONLY_NONHIDDEN);
    }

    private function logError($message) {
        global $BE_USER;
        $BE_USER->simplelog($message, $extKey='skfbalbums', 2);
        /*
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $logger->info('Everything went fine.'. "  includeFolders is $includeFolders");
        $logger->warning('Something went awry, check your configuration!');
        $logger->error(
          'This was not a good idea',
          array(
            'foo' => 1,
            'bar' => 2,
          )
        );
        */
    }

    private function retrieveAccessToken() {
        $appId = $this->getAppId();
        $appSecret = $this->getAppSecret();
        $graphActLink = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&client_secret={$appSecret}&grant_type=client_credentials";
        $accessTokenJson = file_get_contents($graphActLink);
        if ($accessTokenJson === FALSE) {
            $this->logError("Error getting client credentials from Facebook. The app id or app secret might be wrong, or there may be other kind of restrictions like an IP restriction Token id: ".$this->getUid());
            return FALSE;
        }

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump("access token:".$accessTokenJson); 

        $accessTokenObj = json_decode($accessTokenJson);
        if ($accessTokenObj === NULL) {
            $this->logError("Error decoding json response after call for getting client credentials from Facebook. Token id: ".$this->getUid());
            return FALSE;
        }
        $accessToken = $accessTokenObj->access_token;
        if (!$accessToken) {
            $this->logError("Error getting access token from client credential response. Token id: ".$this->getUid());
            return FALSE;
        }

        return $accessToken;
    }

    private function retrievePageAlbums($accessToken) {
        $pageId = $this->getPageId();
        $fields = "id,name,description,link,cover_photo,count";
        $graphAlbLink = "https://graph.facebook.com/v2.10/{$pageId}/albums?fields={$fields}&access_token={$accessToken}";

        $jsonData = file_get_contents($graphAlbLink);
        $fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

        if ($fbAlbumObj && isset($fbAlbumObj['data'])) {
            $fbAlbumData = $fbAlbumObj['data'];
        }
        else {
            return FALSE;
        }
        while ($fbAlbumObj && isset($fbAlbumObj['paging']) && isset($fbAlbumObj['paging']['next'])) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump("PAGED RESULT FOR ALBUM"); 
            $jsonData = file_get_contents($fbAlbumObj['paging']['next']);
            $fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
            if ($fbAlbumObj && isset($fbAlbumObj['data'])) {
                $fbAlbumData = $fbAlbumData + $fbAlbumObj['data'];
            }
        }

        return $fbAlbumData;

    }

    private function retrieveAlbumPhotos($accessToken, $albumId) {
        // although in the api it says that name is deprecated and we should use caption instead, caption is empty and name has the correct text. So get both
        $graphPhoLink = "https://graph.facebook.com/v2.10/{$albumId}/photos?fields=id,source,images,caption,name&access_token={$accessToken}";
        $jsonData = file_get_contents($graphPhoLink);
        $fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

        if ($fbPhotoObj && isset($fbPhotoObj['data'])) {
            $fbPhotoData = $fbPhotoObj['data'];
        }
        else {
            $fbPhotoData = [];
        }
        while ($fbPhotoObj && isset($fbPhotoObj['paging']) && isset($fbPhotoObj['paging']['next'])) {
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump("PAGED RESULT FOR PHOTOS"); 
            $jsonData = file_get_contents($fbPhotoObj['paging']['next']);
            $fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
            if ($fbPhotoObj && isset($fbPhotoObj['data'])) {
                $fbPhotoData = $fbPhotoData + $fbPhotoObj['data'];
            }
        }

        return $fbPhotoData;
    }

    /**
     * sync
     *
     * @return void
     */
    public function sync() {
        // todo - do not sync if it was synced recently. Have a parameter to force it

        $accessToken = $this->retrieveAccessToken();
        if ($accessToken === FALSE) {
            return FALSE;
        }


        $albums = $this->retrievePageAlbums($accessToken);
        if ($albums === FALSE) {
            // TODO some logging or better throw an exception?
            return FALSE;
        }
        $photos = [];
        //$excludedAlbums = [];
        $includedAlbums = [];
        foreach($albums as $album) {
            if ($this->allowedFromInclude($album['id']) && $this->allowedFromExclude($album['id'])) {
                $includedAlbums[] = $album;
                $photos[$album['id']] = $this->retrieveAlbumPhotos($accessToken, $album['id']);
            }
            else {
               // $excludedAlbums[] = $album;
            }
        }

        // load all existing db albums that belong to this token
        // if an existing db album is not in the includedAlbums, hide or delete it
        // if an includedAlbum is already in the db, update it and add it to albums to sync photos
        // if an includedAlbum is not in the db, insert it add it to albums to sync photos
        // sync photos 

        
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $persistenceManager = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManagerInterface');

        $existingAlbums = $this->albumRepository->getAlbumsByToken($this, true);

        $albumsHidden = 0;
        $albumsImported = 0;
        $albumsUpdated = 0;

        $dbAlbumsToSyncPhotos = [];

        foreach($existingAlbums as $existingAlbum) {
            $album = $this->dbAlbumExistsInFbAlbum($existingAlbum, $includedAlbums);
            if ($album === FALSE) { // an album is in the db that is not in the allowed facebook albums. So hide it
                $existingAlbum->setHidden(true);
                $this->albumRepository->update($existingAlbum);
                $albumsHidden++;
            }
        }


        foreach($includedAlbums as $includedAlbum) {
            $album = $this->fbObjExistsInDb($includedAlbum, $existingAlbums);
            $name = isset($includedAlbum['name'])?$includedAlbum['name']:'';
            $description = isset($includedAlbum['description'])?$includedAlbum['description']:'';
            $link = isset($includedAlbum['link'])?$includedAlbum['link']:'';
            $cover_photo = (isset($includedAlbum['cover_photo']) && isset($includedAlbum['cover_photo']['id'])) ?$includedAlbum['cover_photo']['id']:'';

            if ($album !== FALSE) { // update existing
                // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('Existing album--> update'); 
                $album->setName($name);
                $album->setDescription($description);
                $album->setLink($link);
                $album->setCoverPhotoFbId($cover_photo);
                $album->setLastSynced(new \DateTime());
                $album->setHidden(false); // in case it was previously excluded
                $this->albumRepository->update($album);
                $albumsUpdated++;
                $dbAlbumsToSyncPhotos[] = $album;
                 
            }
            else {
                // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('New album--> insert'); 

                $album = $objectManager->get('Skar\\Skfbalbums\\Domain\\Model\\Album');
                $album->setName($name);
                $album->setDescription($description);
                $album->setFacebookId($includedAlbum['id']);
                $album->setLink($link);
                $album->setCoverPhotoFbId($cover_photo);
                $album->setToken($this);
                $album->setLastSynced(new \DateTime());
                $album->setPid($this->getPid()); // needed in case called from scheduler
                $this->albumRepository->add($album);
                $albumsImported++;
                $dbAlbumsToSyncPhotos[] = $album;
            }
        }

        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
        $cacheManager->getCache('cache_pages')->flushByTag('tx_skfbalbums_domain_model_album');

        $persistenceManager->persistAll(); // call it here to be sure that all ojects have an id in case a new album was inserted

        $synResultPhotos = array();
        foreach ($dbAlbumsToSyncPhotos as $album) {

            $synResultPhotos[] = $this->syncPhotos($album, $photos[$album->getFacebookId()], $accessToken);
            $cacheManager->getCache('cache_pages')->flushByTag('tx_skfbalbums_domain_model_album_' . $album->getUid());
        } 
        
        // TODO - return also synResultPhotos for each album
        return [
            'albumsHidden' => $albumsHidden,
            'albumsImported' => $albumsImported,
            'albumsUpdated' => $albumsUpdated
        ];
    }

    private function syncPhotos(\Skar\Skfbalbums\Domain\Model\Album $album, $albumFbPhotos, $accessToken) {
        // load all existing db photos that belong to this db album
        // we import all photos. User can hide them (NOT DELETE!) in the BE
        // if an includedPhoto is already in the db, update it
        // if an includedPhoto is not in the db, insert it


        
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $persistenceManager = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManagerInterface');

        $existingPhotos = $this->photoRepository->getPhotosByAlbum($album, true);

        $photosHidden = 0;
        $photosImported = 0;
        $photosUpdated = 0;

        foreach($albumFbPhotos as $albumFbPhoto) {
            $photo = $this->fbObjExistsInDb($albumFbPhoto, $existingPhotos);
            if ($photo !== FALSE) { // update existing
                //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('Existing photo--> update'); 

                if ( isset($albumFbPhoto['images'])) {
                    $photo->setImages(json_encode($albumFbPhoto['images']));

                    // name is supposed to be deprecated by FB API and we should use caption. But nevertheless, only name is returend. So use both
                    $caption = isset($albumFbPhoto['caption'])?
                        $albumFbPhoto['caption']:
                        isset($albumFbPhoto['name'])?$albumFbPhoto['name']:''
                        ;

                    $photo->setCaption($caption);

                    $this->photoRepository->update($photo);
                    $photosUpdated++;
                }             
            }
            else {
                //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('New photo--> insert'); 

                if (isset($albumFbPhoto['id']) && isset($albumFbPhoto['images'])) {
                    $photo = $objectManager->get('Skar\\Skfbalbums\\Domain\\Model\\Photo');
                    $photo->setFacebookId($albumFbPhoto['id']);
                    $photo->setImages(json_encode($albumFbPhoto['images']));

                    // name is supposed to be deprecated by FB API and we should use caption. But nevertheless, only name is returend. So use both
                    $caption = isset($albumFbPhoto['caption'])?
                        $albumFbPhoto['caption']:
                        isset($albumFbPhoto['name'])?$albumFbPhoto['name']:''
                        ;

                    $photo->setCaption($caption);
                    $photo->setAlbum($album);
                    $photo->setPid($this->getPid()); // needed in case called from scheduler
                    $this->photoRepository->add($photo);
                    $photosImported++;
                }
            }
        }
        $persistenceManager->persistAll(); // call it here to be sure that all ojects have an id in case a new album was inserted
        
        return [
            'photosHidden' => $photosHidden,
            'photosImported' => $photosImported,
            'photosUpdated' => $photosUpdated
        ];
    }



    private function dbAlbumExistsInFbAlbum( \Skar\Skfbalbums\Domain\Model\Album $album, $includedFbAlbums) {
        if (!$includedFbAlbums || !$album) { // checking !$album makes no sense
            return FALSE;
        }

        foreach($includedFbAlbums as $includedFbAlbum) {
            if ($album->getFacebookId() == $includedFbAlbum['id']) {
                return $album;
            }
        }
        return FALSE;
    }

    private function fbObjExistsInDb($fbObj, $existingDbItems) {
        if (!$existingDbItems || !$fbObj || !isset($fbObj['id'])) {
            return FALSE;
        }

        foreach($existingDbItems as $existingDbItem) {
            if ($existingDbItem->getFacebookId() == $fbObj['id']) {
                return $existingDbItem;
            }
        }
        return FALSE;
    }

    private function allowedFromInclude($albumId) {
        if (!$albumId) {
            return FALSE;
        }

        // if includeAlbumIds is empty, allow all
        if (!$this->includeAlbumIds) {
            return true;
        }

        $allowedIds = explode(',', $this->includeAlbumIds);
        if (in_array($albumId, $allowedIds)) {
            return true;
        }

        return FALSE;
    }

    private function allowedFromExclude($albumId) {
        if (!$albumId) {
            return FALSE;
        }

        // if excludeAlbumIds is empty, allow all
        if (!$this->excludeAlbumIds) {
            return true;
        }

        $excludedIds = explode(',', $this->excludeAlbumIds);
        if (in_array($albumId, $excludedIds)) {
            return FALSE;
        }

        return true;
    }
}
