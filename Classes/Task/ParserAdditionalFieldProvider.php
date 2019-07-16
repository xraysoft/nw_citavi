<?php
namespace Netzweber\NwCitavi\Task;

class ParserAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {
  /**
  * This method is used to define new fields for adding or editing a task
  * In this case, it adds an email field
  *
  * @param array $taskInfo Reference to the array containing the info used in the add/edit form
  * @param object $task When editing, reference to the current task object. Null when adding.
  * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler’s BE module)
  * @return array Array containing all the information pertaining to the additional fields
  */
  public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
    $additionalFields = array();
    if (empty($taskInfo['additionalFieldEmail'])) {
      if ($parentObject->CMD == 'add') {
        // In case of new task and if field is empty, set default email address
        $taskInfo['additionalFieldEmail'] = '';
      } elseif ($parentObject->CMD == 'edit') {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo['additionalFieldEmail'] = $task->additionalFieldEmail;
      } else {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo['additionalFieldEmail'] = '';
      }
    }
    // Write the code for the field
    $fieldID = 'task_additionalFieldEmail';
    $fieldCode = '<input type="text" name="tx_scheduler[additionalFieldEmail]" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo['additionalFieldEmail']) . '" size="55" />';
    $additionalFields[$fieldID] = array(
      'code' => $fieldCode,
      'label' => 'LLL:EXT:nw_citavilist/Resources/Private/Language/locallang.xlf:tx_nwcitavi_domain_model_reference.task.label.email',
      'cshKey' => '_MOD_system_txschedulerM1',
      'cshLabel' => $fieldID
    );
    if (empty($taskInfo['additionalFieldSubject'])) {
      if ($parentObject->CMD == 'add') {
        // In case of new task and if field is empty, set default email address
        $taskInfo['additionalFieldSubject'] = '';
      } elseif ($parentObject->CMD == 'edit') {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo['additionalFieldSubject'] = $task->additionalFieldSubject;
      } else {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo['additionalFieldSubject'] = '';
      }
    }
    // Write the code for the field
    $fieldID = 'task_additionalFieldSubject';
    $fieldCode = '<input type="text" name="tx_scheduler[additionalFieldSubject]" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo['additionalFieldSubject']) . '" size="55" />';
    $additionalFields[$fieldID] = array(
      'code' => $fieldCode,
      'label' => 'LLL:EXT:nw_citavilist/Resources/Private/Language/locallang.xlf:tx_nwcitavi_domain_model_reference.task.label.subject',
      'cshKey' => '_MOD_system_txschedulerM1',
      'cshLabel' => $fieldID
    );
    return $additionalFields;
  }
  
  /**
  * This method checks any additional data that is relevant to the specific task
  * If the task class is not relevant, the method is expected to return TRUE
  *
  * @param array $submittedData Reference to the array containing the data submitted by the user
  * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler’s BE module)
  * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
  */
  public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
    return TRUE;
  }
  
  /**
  * This method is used to save any additional input into the current task object
  * if the task class matches
  *
  * @param array $submittedData Array containing the data submitted by the user
  * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the current task object
  * @return void
  */
  public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
    $task->additionalFieldEmail = $submittedData['additionalFieldEmail'];
    $task->additionalFieldSubject = $submittedData['additionalFieldSubject'];
  }
  
}

?>