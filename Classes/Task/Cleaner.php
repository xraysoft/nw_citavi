<?php
namespace Netzweber\NwCitavi\Task;

class Cleaner extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
  
  public function execute() {
    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
    $ReferenceRepository = $objectManager->get('Netzweber\NwCitavi\Domain\Repository\ReferenceRepository');
    
    $ReferenceRepository->compareHashData();    
    
    return TRUE;
  }
}
?>
