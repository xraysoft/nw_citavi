<?php
namespace Netzweber\NwCitavi\Task;

class ParseSeriestitles extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
  
  public function execute() {
    if(is_null($referenceRepository)) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
      $referenceRepository = $objectManager->get('Netzweber\NwCitavi\Domain\Repository\ReferenceRepository');      
    }
    $res = false;
    
    $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
    if(file_exists($this->dir.'/scheduler.txt')) {
      $referenceRepository->taskParseXMLSeriestitles(0);
      
      $res = true;
    } else {
      $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
         'There is no upload to process',
         'Task can not be started',
         \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
         TRUE
      );
      
      $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
      $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
      $messageQueue->addMessage($message);
      
      $res = true;
    }   
    
    return $res;
  }
}
?>
