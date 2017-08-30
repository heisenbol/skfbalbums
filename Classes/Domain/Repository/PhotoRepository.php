<?php
namespace Skar\Skfbalbums\Domain\Repository;

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
 * The repository for Albums
 */
class PhotoRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param \Skar\Skfbalbums\Domain\Model\Album $album
     * @param boolean $includeHidden
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getPhotosByAlbum(\Skar\Skfbalbums\Domain\Model\Album $album, $includeHidden) {
    	
        $query = $this->createQuery();
    		//$query->getQuerySettings()->setRespectStoragePage(false);
     		if ($includeHidden) {
    	        $query->getQuerySettings()->setIgnoreEnableFields(TRUE)->setIncludeDeleted(false);;
     		}
        $query->getQuerySettings()->setStoragePageIds(array($album->getPid())); // may be called from scheduler. So set here the pid to look for

        $constraint[] = $query->equals('album', $album);

 
        $result = $query->matching($query->logicalAnd($constraint))->execute();
  		return $result;
    }

    public function findByFbId( $fbId) {
    	
        $query = $this->createQuery();

        // this is used only from the page plugin, so no need to setStoragePageIds 

        $constraint[] = $query->equals('facebook_id', $fbId);

 
        $query->setLimit(1);
        $result = $query->matching($query->logicalAnd($constraint))->execute()->getFirst();
        
  		return $result;
    }
}
