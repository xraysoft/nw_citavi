<?php
namespace Netzweber\NwCitavi\Controller;

/***
 *
 * This file is part of the "Citavi" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Lutz Eckelmann <lutz.eckelmann@netzweber.de>, Netzweber GmbH
 *           Wolfgang Schröder <wolfgang.schroeder@netzweber.de>, Netzweber GmbH
 *
 ***/

/**
 * ReferenceController
 */
class ReferenceController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * referenceRepository
     *
     * @var \Netzweber\NwCitavi\Domain\Repository\ReferenceRepository
     * @inject
     */
    protected $referenceRepository = null;
    
    /**
  	 * logRepository
  	 *
  	 * @var \Netzweber\NwCitavi\Domain\Repository\LogRepository
  	 * @inject
  	 */
  	protected $logRepository = NULL;

    /**
     * action parser
     *
     * @return void
     */
    public function parserAction()
    {
        $params = $this->request->getArguments();
        $typo3version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version());
        if($typo3version['version_main'] == 6) {
          $this->settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi_citavilist.']['settings.'];
        }
        if ($this->settings['scheduler'] == 1) {
            $resParseXML = $this->referenceRepository->parseXML(99, $params['startTime'], $params['uniqid'], $this->logRepository);
            if ($resParseXML == 99) {
                $statusCodeText = $this->referenceRepository->getStatusCodeText(200);
                $this->logRepository->addLog(0, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $params['uniqid'] . '', '[Citavi Upload]: Upload was successful.', '' . $params['import_key'] . '', $this->settings['sPid']);
                $this->referenceRepository->getStatusCode(200);
                die;
            }
        } else {
            if (empty($params['step'])) {
                $params['step'] = 1;
            }
            $resParseXML = $this->referenceRepository->parseXML($params['step'], $params['startTime'], $params['uniqid'], $this->logRepository);
            if ($resParseXML['step'] < 10) {
                sleep(1);
                $this->forward('parser', 'Reference', NULL, array('step' => $resParseXML['step'], 'startTime' => $resParseXML['startTime'], 'uniqid' => $uniqid, 'import_key' => $_POST['import_key']));
            } else {
                if ($resParseXML['step'] == 10) {
                    $statusCodeText = $this->referenceRepository->getStatusCodeText(200);
                    $this->logRepository->addLog(0, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $params['uniqid'] . '', '[Citavi Upload]: Upload was successful.', '' . $params['import_key'] . '', $this->settings['sPid']);
                    $this->referenceRepository->getStatusCode(200);
                    die;
                } else {
                    $statusCodeText = $this->referenceRepository->getStatusCodeText(500);
                    $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $params['uniqid'] . '', '[Citavi Upload]: Upload was terminated.', '' . $params['import_key'] . '', $this->settings['sPid']);
                    $this->referenceRepository->getStatusCode(500);
                    die;
                }
            }
        }  
    }

    /**
     * action import
     *
     * @return void
     */
    public function importAction()
    {
        $uniqid = uniqid();
        $this->logRepository->addLog(0, '', 'Upload', '' . $uniqid . '', '[Citavi Upload]: Upload was started.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
        file_put_contents($this->dir.'/log/upload.txt', time().'|importAction|start'.chr(10), FILE_APPEND);
        $typo3version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version());        
        if($typo3version['version_main'] == 6) {
          $this->settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi_citavilist.']['settings.'];
        }
                
        if ($this->settings['no_upload'] == 0) {          
          file_put_contents($this->dir.'/log/upload.txt', time().'|func|'.$_POST['func'].chr(10), FILE_APPEND);
          switch ($_POST['func']) {
            case 'xml':                  
              if (file_exists($this->dir . '/scheduler.txt')) {
                $statusCodeText = $this->referenceRepository->getStatusCodeText(506);
                $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi XML Upload]: Upload was terminated.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                $this->referenceRepository->getStatusCode(506);
              } else {                
                $resXML = $this->referenceRepository->importXML($uniqid, $this->logRepository);
                if ($resXML) {
                  $this->forward('parser', 'Reference', NULL, array('step' => 1, 'uniqid' => $uniqid, 'import_key' => $_POST['import_key']));
                } else {
                  $statusCodeText = $this->referenceRepository->getStatusCodeText(502);
                  $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi XML Upload]: Upload was terminated.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                  $this->referenceRepository->getStatusCode(502);
                  die;
                }
              }
              break;
            case 'file':    
              $resFile = $this->referenceRepository->importFile($uniqid, $this->logRepository, $this->settings);
              if ($resFile) {
                $statusCodeText = $this->referenceRepository->getStatusCodeText(200);
                $this->logRepository->addLog(0, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi File Upload]: Upload was successful.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                $this->referenceRepository->getStatusCode(200);
                die;
              } else {
                $statusCodeText = $this->referenceRepository->getStatusCodeText(500);
                $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi File Upload]: Upload was terminated.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                $this->referenceRepository->getStatusCode(500);
                die;
              }
              die;
              break;
            case 'export':    
              $resFile = $this->referenceRepository->importExport($uniqid);
                if ($resFile) {
                  $statusCodeText = $this->referenceRepository->getStatusCodeText(200);
                  $this->logRepository->addLog(0, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi Export Upload]: Upload was successful.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                  $this->referenceRepository->getStatusCode(200);
                  die;
                } else {
                  $statusCodeText = $this->referenceRepository->getStatusCodeText(500);
                  $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi Export Upload]: Upload was terminated.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
                  $this->referenceRepository->getStatusCode(500);
                  die;
                }
                die;
                break;
            default:    
              $statusCodeText = $this->referenceRepository->getStatusCodeText(433);
              $this->logRepository->addLog(1, '' . $statusCodeText['text'] . ': ' . $statusCodeText['msg'] . '', 'Upload', '' . $uniqid . '', '[Citavi Unkown Upload]: Upload was terminated.', '' . $_POST['import_key'] . '', $this->settings['sPid']);
              $this->referenceRepository->getStatusCode(433);
              die;
            }
        }
    }
}
