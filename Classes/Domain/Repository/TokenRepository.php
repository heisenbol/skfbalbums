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
class TokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{



    /**
     * @param array $pageIdsToInclude
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getTokensToSync($pageIdsToInclude) {
    	
		$querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        if (!$pageIdsToInclude || count($pageIdsToInclude) == 0) {
			$querySettings->setRespectStoragePage(false);
		}
		else {
		    $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
		    $querySettings->setStoragePageIds($pageIdsToInclude);
		}
	    $this->setDefaultQuerySettings($querySettings);
 		
  		return $this->findAll();
    }


}
