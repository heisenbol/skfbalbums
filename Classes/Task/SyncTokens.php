<?php
namespace  Skar\Skfbalbums\Task;

class SyncTokens extends \TYPO3\CMS\Scheduler\Task\AbstractTask implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

    private $includeFolders;

    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
        // Initialize selected fields
        if (!isset($taskInfo['includeFolders'])) {
            $taskInfo['includeFolders'] = '';
            if ($parentObject->CMD === 'edit') {
                $taskInfo['includeFolders'] = $task->includeFolders;
            }
        }
        $fieldName = 'tx_scheduler[includeFolders]';
        $fieldId = 'includeFolders';
        $fieldValue = $taskInfo['includeFolders'];
        $fieldHtml = '<input type="text" name="' . $fieldName . '" id="' . $fieldId . '" value="' . htmlspecialchars($fieldValue) . '" />';

        $additionalFields[$fieldId] = array(
            'code' => $fieldHtml,
            'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_be.xlf:scheduler_pageidstoinclude',
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldId
        );
        
        
        return $additionalFields;
    }
    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
        //$task->includeFolders = $submittedData['includeFolders'];

        return true;
    }
    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
        $task->includeFolders = $submittedData['includeFolders'];
    }

    public function execute() {
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
/*
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

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $persistenceManager = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManagerInterface');
        $tokenRepository = $objectManager->get('Skar\Skfbalbums\Domain\Repository\TokenRepository');
        $albumRepository = $objectManager->get('Skar\Skfbalbums\Domain\Repository\AlbumRepository');



        if (!intval(trim($this->includeFolders))) { // explode returns array with 1 element for empty string. So catch it here
            $pageIdsToInclude = [];
        }
        else {
            $pageIdsToInclude = array_map (
                'intval', 
                explode(',',$this->includeFolders)
                );
        }
        // retrieve tokens
        $tokens = $tokenRepository->getTokensToSync($pageIdsToInclude);



    //    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($tokens); 

        foreach($tokens as $token) {
            $token->sync();
        }

        return true; // false for errors
    }

   
}
