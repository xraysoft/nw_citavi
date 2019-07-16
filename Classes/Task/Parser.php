<?php
namespace Netzweber\NwCitavi\Task;

class Parser extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
  
  public function execute() {
    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
    $ReferenceRepository = $objectManager->get('Netzweber\NwCitavi\Domain\Repository\ReferenceRepository');
    $logRepository = $objectManager->get('Netzweber\NwCitavi\Domain\Repository\LogRepository');
    
    $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
    if(file_exists($this->dir.'/scheduler.txt')) {
      //$resTruncateDatabase = $ReferenceRepository->truncateDatabase();
      $resParseXML = $ReferenceRepository->taskParseXML(0, $logRepository);
    }    
    
    return TRUE;
  }
}
?>
