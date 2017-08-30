<?php
namespace Skar\Skfbalbums\Controller;

/***
 *
 * This file is part of the "sktestnamefff" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017
 *
 ***/

/**
 * TokenController
 */
class TokenController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * TokenRepository
     *
     * @var \Skar\Skfbalbums\Domain\Repository\TokenRepository
     * @inject
     */
    protected $tokenRepository = null;



    /**
     * action list
     *
     * @return void
     */
    public function synclistAction()
    {

        $pageId = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');

        
        $frameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $persistenceConfiguration = array('persistence' => array('storagePid' => $pageId));
        $this->configurationManager->setConfiguration(array_merge($frameworkConfiguration, $persistenceConfiguration));

        $tokens = $this->tokenRepository->findAll();

//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($tokens); 

        $this->view->assign('tokens', $tokens);
    }

    /**
     * action sync
     * @param \Skar\Skfbalbums\Domain\Model\Token $token
     * @return void
     */
    public function syncAction(\Skar\Skfbalbums\Domain\Model\Token $token)
    {

        $syncResult = $token->sync();
        $this->view->assign('token', $token);
        $this->view->assign('albumsUpdated', $syncResult['albumsUpdated']);
        $this->view->assign('albumsImported', $syncResult['albumsImported']);
        $this->view->assign('albumsHidden', $syncResult['albumsHidden']);
    }
}
