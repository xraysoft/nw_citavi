<?php
namespace Netzweber\NwCitavi\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Netzweber\NwCitavi\Domain\Repository\LogRepository;

/***
 * This file is part of the "Citavi" Extension for TYPO3 CMS.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *  (c) 2018 Lutz Eckelmann <lutz.eckelmann@netzweber.de>, Netzweber GmbH
 *           Wolfgang Schröder <wolfgang.schroeder@netzweber.de>, Netzweber GmbH
 ***/

/**
 * LogController
 */
class LogController extends ActionController
{
    /**
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * @param LogRepository $logRepository
     */
    public function injectLogRepository(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction(): void
    {
        $logs = $this->logRepository->findAll();
        $this->view->assign('logs', $logs);
    }

    /**
     * action clear
     *
     * @return void
     */
    public function clearAction(): void
    {

    }

    /**
     * action scheduler
     *
     * @return void
     */
    public function schedulerAction(): void
    {
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
        $check = file_exists($dir.'/scheduler.txt');
        $this->view->assign('activeScheduler', false);
        if($check) {
            $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
            $schedulerCols = explode('|', $schedulerString);
            [$fileName, $key, $uniqueId] = $schedulerCols;
            $xml = new \XMLReader();
            $xml->open($fileName);
            $xmlString = implode('', file($fileName));
            $xml = simplexml_load_string($xmlString);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            $taskExists = file_exists($dir.'/task.txt');
            $this->view->assign('taskStep', 0);
            $this->view->assign('taskPercent', 0);
            if($taskExists) {
                $taskString = file_get_contents ( $dir.'/task.txt' );
                $this->view->assign('taskStep', $taskString);
                $this->view->assign('taskPercent', (int)$taskString*100/12);
            }
            $this->view->assign('activeScheduler', true);
            $this->view->assign('uploadStart', filemtime ($fileName));
            $this->view->assign('ciatviVersion', $array['@attributes']['Version']);
            $this->view->assign('ciatviFilePath', $array['@attributes']['FilePath']);
            $this->view->assign('ciatviProjectRecordVersion', $array['ProjectRecordVersion']);
            $this->view->assign('importKey', $key);
            $this->view->assign('uniqueId', $uniqueId);
        }
    }
}
