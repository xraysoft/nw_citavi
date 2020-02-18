<?php
namespace Netzweber\NwCitavi\Task;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Update extension list from TER task
 */
class ParseCategoriesTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
  public function execute() {
    if(is_null($referenceRepository)) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
      $referenceRepository = $objectManager->get('Netzweber\NwCitavi\Domain\Repository\ReferenceRepository');
    }
    $res = false;
    $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
    if(file_exists($this->dir.'/scheduler.txt')) {
      $referenceRepository->taskParseXMLCategories();

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
