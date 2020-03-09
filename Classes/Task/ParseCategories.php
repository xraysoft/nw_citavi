<?php
namespace Netzweber\NwCitavi\Task;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Netzweber\NwCitavi\Domain\Repository\ReferenceRepository;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class ParseCategories extends AbstractTask {
  /**
   * @return bool
   */
  public function execute(): bool {
    $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    $referenceRepository = $objectManager->get(ReferenceRepository::class);
    if(file_exists(GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload').'/scheduler.txt')) {
      $referenceRepository->taskParseXMLCategories();
      return true;
    }
    return true;
  }
}

