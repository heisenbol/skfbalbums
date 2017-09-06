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
class AlbumRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    const ONLY_HIDDEN = 10;
    const ONLY_NONHIDDEN = 20;
    const ONLY_BOTH = 30;

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param \Skar\Skfbalbums\Domain\Model\Token $token
	 * @param boolean $includeHidden
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getAlbumsByToken(\Skar\Skfbalbums\Domain\Model\Token $token, $includeHidden) {
/*    	
        $query = $this->createQuery();
 		if ($includeHidden) {
	        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
 		}
        $query->getQuerySettings()->setStoragePageIds(array($token->getPid())); // may be called from scheduler. So set here the pid to look for

        $constraint[] = $query->equals('token', $token);

 
        $result = $query->matching($query->logicalAnd($constraint))->execute();
  		return $result;
*/
        $mode = self::ONLY_NONHIDDEN;
        if ($includeHidden) {
            $mode = self::ONLY_BOTH;
        }
        return $this->getAlbums($token, $mode, false);
    }

    /**
     * @param \Skar\Skfbalbums\Domain\Model\Token $token
     * @param int $mode
     * @param bool $onlyCount
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface|int
     */
    public function getAlbums(\Skar\Skfbalbums\Domain\Model\Token $token, $mode, $onlyCount) {

        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(false);
        $query->getQuerySettings()->setStoragePageIds(array($token->getPid())); // may be called from scheduler. So set here the pid to look for

        $constraint[] = $query->equals('token', $token);
        $constraint[] = $query->equals('deleted', false);
        $constraint[] = $query->equals('pid', $token->getPid());

        if ($mode == self::ONLY_HIDDEN) {
            $constraint[] = $query->equals('hidden', true);
        }
        if ($mode == self::ONLY_NONHIDDEN) {
            $constraint[] = $query->equals('hidden', false);
        }

        if ($onlyCount) {
             return $query->matching($query->logicalAnd($constraint))->count();
        }

        return $query->matching($query->logicalAnd($constraint))->execute();
    }

    public function getAllAlbums(\Skar\Skfbalbums\Domain\Model\Token $token, $mode, $onlyCount) {

        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(false);
        $query->getQuerySettings()->setStoragePageIds(array($token->getPid())); // may be called from scheduler. So set here the pid to look for

        $constraint[] = $query->equals('token', $token);
        $constraint[] = $query->equals('deleted', false);
        $constraint[] = $query->equals('pid', $token->getPid());

        if ($mode == self::ONLY_HIDDEN) {
            $constraint[] = $query->equals('hidden', true);
        }
        if ($mode == self::ONLY_NONHIDDEN) {
            $constraint[] = $query->equals('hidden', false);
        }

        if ($onlyCount) {
             return $query->matching($query->logicalAnd($constraint))->count();
        }

        return $query->matching($query->logicalAnd($constraint))->execute();
    }
    
    public function findAllInAllPages() {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);


        return $query->execute();
    }
}
