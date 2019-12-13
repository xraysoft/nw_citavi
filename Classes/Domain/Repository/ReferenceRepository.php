<?php
namespace Netzweber\NwCitavi\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * The repository for Logs
 */
class ReferenceRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
  	 * logRepository
  	 *
  	 * @var \Netzweber\NwCitavi\Domain\Repository\LogRepository
  	 * @inject
  	 */
  	protected $logRepository = NULL;
    
    protected $hashrepository = NULL;
    
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sortDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    );
    
    public function initializeObject() {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        // go for $defaultQuerySettings = $this->createQuery()->getQuerySettings(); if you want to make use of the TS persistence.storagePid with defaultQuerySettings(), see #51529 for details

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(FALSE);
        // set the storagePids to respect
        //$querySettings->setStoragePageIds(array(1, 26, 989));

        // don't add fields from enablecolumns constraint
        // this function is deprecated!
        //$querySettings->setRespectEnableFields(FALSE);

        // define the enablecolumn fields to be ignored
        // if nothing else is given, all enableFields are ignored
        //$querySettings->setIgnoreEnableFields(TRUE);       
        // define single fields to be ignored
        //$querySettings->setEnableFieldsToBeIgnored(array('disabled','starttime'));

        // add deleted rows to the result
        //$querySettings->setIncludeDeleted(TRUE);

        // don't add sys_language_uid constraint
        //$querySettings->setRespectSysLanguage(FALSE);

        // perform translation to dedicated language
        //$querySettings->setSysLanguageUid(42);
        $this->setDefaultQuerySettings($querySettings);
    }
    
    /**
  	 * action importXML
  	 * @return \string JSON
  	 */
  	public function importXML($uniqid, $logRepository) {          
  	  $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
      file_put_contents($this->dir.'/log/upload.txt', 'importXML|start'.chr(10), FILE_APPEND);
      $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
      if(is_writeable($this->dir)) {
        file_put_contents($this->dir.'/log/upload.txt', 'dir|'.$this->dir.chr(13).chr(10), FILE_APPEND);
        if ($_POST['import_key']) {
          file_put_contents($this->dir.'/log/upload.txt', 'import_key|'.$_POST['import_key'].chr(13).chr(10), FILE_APPEND);
          if($_FILES['file']) {
            file_put_contents($this->dir.'/log/upload.txt', 'file name|'.$_FILES['file']['name'].chr(13).chr(10), FILE_APPEND);
            $this->fileProcessor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\ExtendedFileUtility');
       
      		$fileName = $this->fileProcessor->getUniqueName(
                $_FILES['file']['name'],
                $this->dir
            );
            
            $upload = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move(
              $_FILES['file']['tmp_name'],
              $fileName);
              
            $zip = new \ZipArchive;
            if ($zip->open($fileName) === TRUE) {
              $res = $zip->extractTo($this->dir);
              $zip->close();
              if($res) {
                unlink($fileName);
              }
            } else {
              $statusCodeText = $this->getStatusCodeText(418);
              $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
              $this->getStatusCode(418);
              file_put_contents($this->dir.'/log/upload.txt', 'error|418'.chr(13).chr(10), FILE_APPEND);
            }
              
            $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
            $defaultStorage = $resourceFactory->getDefaultStorage();
            $folder = $defaultStorage->getFolder('/user_upload/citavi_upload/');
            $files = $defaultStorage->getFilesInFolder($folder);
            if(is_array($files)) {
              foreach($files as $file) {
                $thisFile = $file->getProperties();
                if($thisFile['extension'] === 'xml') {
                  $folder2 = $defaultStorage->getFolder('/user_upload/citavi_upload/backup/');
                  $files2 = $defaultStorage->getFilesInFolder($folder2);
                  $uploaddir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
                  $backupdir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/backup/');
                  if(is_array($files2)) {
                    $numFiles = count($files2);
                    if($numFiles < 10) {
                      // Kopiere die neue Datei in den Ordner
                      $upload = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move(
                        $uploaddir.$thisFile['name'],
                        $backupdir.time().'_'.$thisFile['name']);
                        
                      return 1;
                    } else {
                      // Lösche die älteste Datei
                      $i = 0;
                      foreach($files2 as $file2) {
                        if($i == 0) {
                          $thisFile2 = $file2->getProperties();
                          unlink($backupdir.$thisFile2['name']);  
                        }
                        $i++;                    
                      }
                      // Kopiere die neue Datei in den Ordner
                      $upload = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move(
                        $uploaddir.$thisFile['name'],
                        $backupdir.time().'_'.$thisFile['name']);
                        
                      return 1;
                    }
                  }
                }    
              }
            }
          } else {
            $statusCodeText = $this->getStatusCodeText(419);
            $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
            $this->getStatusCode(419);
            file_put_contents($this->dir.'/log/upload.txt', 'error|419'.chr(13).chr(10), FILE_APPEND);
          }
        } else {
          $statusCodeText = $this->getStatusCodeText(417);
          $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
          $this->getStatusCode(417);
          file_put_contents($this->dir.'/log/upload.txt', 'error|417'.chr(13).chr(10), FILE_APPEND);
          exit;
        } 
      } else {
        $statusCodeText = $this->getStatusCodeText(432);
        $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
        $this->getStatusCode(432);
        file_put_contents($this->dir.'/log/upload.txt', 'error|432'.chr(13).chr(10), FILE_APPEND);
        exit;
      }
    }
    
    public function parseXML($step, $startTime, $uniqid, $LogRepository) {
      $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
      $this->key = $_POST['import_key'];
      $fileInfo = $this->lastModification($this->dir);
      $fileName = $fileInfo['dir'].$fileInfo['file'];
      $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi_citavilist.']['settings.'];
      if($step == 99) {
        file_put_contents($this->dir.'/scheduler.txt', $fileName.'|'.$this->key.'|'.$uniqid, FILE_APPEND);
        
        return 99;
      } else {
        $this->xml = new \XMLReader();
        $this->xml->open($fileName);
        $xmlstring = implode("", file($fileName));
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $contents = var_export($array, true);
        $xmlObj = new \SimpleXMLElement($xmlstring);
        
        switch($step) {
          case 1:
            $startTime = time();
            file_put_contents($this->dir.'/log/upload.txt', date('d.m.Y H:i:s', $startTime).'|', FILE_APPEND);
            try {
              if ( is_array( $array['Categories']['Category'] ) ) {
                $categoryCount = count($array['Categories']['Category']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_categoryhash');
                for($i = 0; $i < $categoryCount; $i++) {
                  $category = $array['Categories']['Category'][$i];
                  
                  if(strlen($category['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($category);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('CategoryHashRepository', 'CategoryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('CategoryRepository', $category['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                    
                    $this->setDatabase($category, 'Category', $columnExists, $settings);
                    
                    if ( is_array( $category['Categories']['Category'] ) ) {
                      $subCategoryCount = count($category['Categories']['Category']);
                      for($j = 0; $j < $subCategoryCount; $j++) {
                        $subcategory = $category['Categories']['Category'][$j];
                        if(strlen($subcategory['@attributes']['ID']) > 0) {
                          $refstr = $this->generatedHash($subcategory);
                          $this->hash = hash("md5", $refstr);
                          $this->insertInHashTable('CategoryHashRepository', 'CategoryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                          $columnExists = $this->columnExists('CategoryRepository', $subcategory['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                          
                          $this->setDatabase($subcategory, 'Category', $columnExists, $settings, $category['@attributes']['ID']);
                        }
                        
                        if ( is_array( $subcategory['Categories']['Category'] ) ) {
                          $subsubCategoryCount = count($subcategory['Categories']['Category']);
                          for($k = 0; $k < $subsubCategoryCount; $k++) {
                            $subsubcategory = $subcategory['Categories']['Category'][$k];
                            if(strlen($subsubcategory['@attributes']['ID']) > 0) {
                              $refstr = $this->generatedHash($subsubcategory);
                              $this->hash = hash("md5", $refstr);
                              $this->insertInHashTable('CategoryHashRepository', 'CategoryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                              $columnExists = $this->columnExists('CategoryRepository', $subsubcategory['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                              
                              $this->setDatabase($subsubcategory, 'Category', $columnExists, $settings, $subcategory['@attributes']['ID']);
                            }
                            
                          }
                        }
                        unset($subsubcategory, $subsubCategoryAttributes, $db_subsubCat, $subsubCatUid);
                      }
                    }
                    unset($subcategory, $subCategoryAttributes, $db_subCat, $subCatUid);
                  }
                  unset($category, $categoryAttributes, $db_cat, $catUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Categories could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 2:  
            try {
              if ( is_array( $array['Keywords']['Keyword'] ) ) {      
                $keywordsCount = count($array['Keywords']['Keyword']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_keywordhash');
                for($i = 0; $i < $keywordsCount; $i++) {
                  $keyword = $array['Keywords']['Keyword'][$i];
                  if(strlen($keyword['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($keyword);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('KeywordHashRepository', 'KeywordHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('KeywordRepository', $keyword['@attributes']['ID'], 'tx_nwcitavi_domain_model_keyword');
                    
                    $this->setDatabase($keyword, 'Keyword', $columnExists, $settings);
                  }
                  unset($keyword, $keywordAttributes, $db_keyword, $keywordUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Keywords could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 3:        
            try {
              if ( is_array( $array['Libraries']['Library'] ) ) {        
                $librariesCount = count($array['Libraries']['Library']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_libraryhash');
                for($i = 0; $i < $librariesCount; $i++) {
                  $library = $array['Libraries']['Library'][$i];
                  if(strlen($library['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($library);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('LibraryHashRepository', 'LibraryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('LibraryRepository', $library['@attributes']['ID'], 'tx_nwcitavi_domain_model_library');
                    
                    $this->setDatabase($library, 'Library', $columnExists, $settings);
                  }
                  unset($library, $libraryAttributes, $db_library, $libraryUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Libraries could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 4:        
            try {
              if ( is_array( $array['Periodicals']['Periodical'] ) ) {    
                $periodicalsCount = count($array['Periodicals']['Periodical']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_periodicalhash');
                for($i = 0; $i < $periodicalsCount; $i++) {
                  $periodical = $array['Periodicals']['Periodical'][$i];
                  if(strlen($periodical['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($periodical);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('PeriodicalHashRepository', 'PeriodicalHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('PeriodicalRepository', $periodical['@attributes']['ID'], 'tx_nwcitavi_domain_model_periodical');
                    
                    $this->setDatabase($periodical, 'Periodical', $columnExists, $settings);
                  }
                  unset($periodical, $periodicalAttributes, $db_periodical, $periodicalUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Periodicals could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 5:        
            try {
              if ( is_array( $array['Persons']['Person'] ) ) {            
                $personsCount = count($array['Persons']['Person']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_personhash');
                for($i = 0; $i < $personsCount; $i++) {
                  $person = $array['Persons']['Person'][$i];
                  if(strlen($person['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($person);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('PersonHashRepository', 'PersonHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('PersonRepository', $person['@attributes']['ID'], 'tx_nwcitavi_domain_model_person');
                    
                    $this->setDatabase($person, 'Person', $columnExists, $settings);
                  }
                  unset($person, $personAttributes, $db_person, $personUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Persons could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 6:        
            try {
              if ( is_array( $array['Publishers']['Publisher'] ) ) {            
                $publishersCount = count($array['Publishers']['Publisher']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_publisherhash');
                for($i = 0; $i < $publishersCount; $i++) {
                  $publisher = $array['Publishers']['Publisher'][$i];
                  if(strlen($publisher['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($publisher);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('PublisherHashRepository', 'PublisherHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('PublisherRepository', $publisher['@attributes']['ID'], 'tx_nwcitavi_domain_model_publisher');
                    
                    $this->setDatabase($publisher, 'Publisher', $columnExists, $settings);
                  }
                  unset($publisher, $publisherAttributes, $db_publisher, $publisherUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Publishers could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 7:        
            try {
              if ( is_array( $array['SeriesTitles']['SeriesTitle'] ) ) {            
                $seriestitlesCount = count($array['SeriesTitles']['SeriesTitle']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_seriestitlehash');
                for($i = 0; $i < $seriestitlesCount; $i++) {
                  $seriestitle = $array['SeriesTitles']['SeriesTitle'][$i];
                  if(strlen($seriestitle['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($seriestitle);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('SeriestitleHashRepository', 'SeriestitleHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('SeriestitleRepository', $seriestitle['@attributes']['ID'], 'tx_nwcitavi_domain_model_seriestitle');
                    
                    $this->setDatabase($seriestitle, 'SeriesTitle', $columnExists, $settings);
                  }
                  unset($seriestitle, $seriestitleAttributes, $db_seriestitle, $seriestitleUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Seriestitles could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 8:        
            try {
              if ( is_array( $array['Thoughts']['KnowledgeItem'] ) ) {            
                $knowledgeitemsCount = count($array['Thoughts']['KnowledgeItem']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_knowledgeitemhash');
                for($i = 0; $i < $knowledgeitemsCount; $i++) {
                  $knowledgeitem = $array['Thoughts']['KnowledgeItem'][$i];
                  if(strlen($knowledgeitem['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($knowledgeitem);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('KnowledgeItemHashRepository', 'KnowledgeItemHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('KnowledgeItemRepository', $knowledgeitem['@attributes']['ID'], 'tx_nwcitavi_domain_model_knowledgeitem');
                    
                    $this->setDatabase($seriestitle, 'KnowledgeItem', $columnExists, $settings);
                  }
                  unset($knowledgeitem, $knowledgeitemAttributes, $db_knowledgeitem, $knowledgeitemUid);
                }
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Knowledgeitems could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
        
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
          
          case 9:
            try {
              if ( is_array( $array['References']['Reference'] ) ) {    
                $refCount = count($array['References']['Reference']);
                $this->truncateDatabase('tx_nwcitavi_domain_model_referencehash');
                $this->truncateDatabase('tx_nwcitavi_domain_model_locationhash');
                for($i = 0; $i < $refCount; $i++) {
                  $ref = $array['References']['Reference'][$i];
                  switch($ref['@attributes']['ReferenceType']) {
                    case 'BookEdited';
                      $this->sortDate = $ref['Year'];
                      break;
                    case 'Book';
                      $this->sortDate = $ref['Year'];
                      break;
                    case 'JournalArticle';
                      $this->sortDate = $ref['Year'];
                      break;
                    case 'NewspaperArticle';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'PersonalCommunication';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'Unknown';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'UnpublishedWork';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'ConferenceProceedings';
                      $this->sortDate = $ref['Year'];
                      break;
                    case 'Contribution';
                      $db_parentref = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                      $this->sortDate = $db_parentref['sort_date'];
                      break;
                    case 'Lecture';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'PressRelease';
                      $this->sortDate = $ref['Date'];
                      break;
                    case 'SpecialIssue';
                      $this->sortDate = $ref['Year'];
                      break;
                    case 'Thesis';
                      $this->sortDate = $ref['Date'];
                      break;
                    default:
                      $this->sortDate = $ref['Date'];
                  }
                  if(strlen($ref['ParentReferenceID']) > 0) {
                    $parent = $xmlObj->xpath("//Reference[@ID='".$ref['ParentReferenceID']."']");
                    $this->parentReferenceType = (string)$parent[0]['ReferenceType'];
                    $this->db_parentref = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                    $this->sortDate = $db_parentref['sort_date'];
                  }
                  
                  if(strlen($ref['@attributes']['ID']) > 0) {
                    $refstr = $this->generatedHash($ref);
                    $this->hash = hash("md5", $refstr);
                    $this->insertInHashTable('ReferenceHashRepository', 'ReferenceHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                    $columnExists = $this->columnExists('ReferenceRepository', $ref['@attributes']['ID'], 'tx_nwcitavi_domain_model_reference');
                    
                    $this->setDatabase($ref, 'Reference', $columnExists, $settings);
                    
                    // Locations speichern
                    if($array['References']['Reference'][$i]['Locations']['Location']['@attributes']) {
                      $location = $array['References']['Reference'][$i]['Locations']['Location'];
                      if(strlen($location['@attributes']['ID']) > 0) {    
                        $locationstr = $this->generatedHash($location);
                        $this->hash = hash("md5", $locationstr);
                        $this->insertInHashTable('LocationHashRepository', 'LocationHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                        $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                        
                        $this->setDatabase($location, 'Location', $columnExists, $settings);
                        
                        // Location verknüpfen
                        $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                          
                        //Libraries verknüpfen
                        if(strlen($array['References']['Reference'][$i]['Locations']['Location']['LibraryID']) > 0) {
                          $this->updateLocationLibrary($location['@attributes']['ID'], $array['References']['Reference'][$i]['Locations']['Location']['LibraryID']);
                        }
                      }
                      unset($location, $locationAttributes, $db_location, $locationUid);
                    } else {
                      if ( is_array( $array['References']['Reference'][$i]['Locations']['Location'] ) ) {
                        $locationsCount = count($array['References']['Reference'][$i]['Locations']['Location']);
                        for($j = 0; $j < $locationsCount; $j++) {
                          $location = $array['References']['Reference'][$i]['Locations']['Location'][$j];
                          if(strlen($location['@attributes']['ID']) > 0) {
                            $locationstr = $this->generatedHash($location);
                            $this->hash = hash("md5", $locationstr);
                            $this->insertInHashTable('LocationHashRepository', 'LocationHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                            $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                            
                            $this->setDatabase($location, 'Location', $columnExists, $settings);
                            
                            // Location verknüpfen
                            $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                            
                            //Libraries verknüpfen
                            if(strlen($array['References']['Reference'][$i]['Locations']['Location'][$j]['LibraryID']) > 0) {
                              $this->updateLocationLibrary($location['@attributes']['ID'], $array['References']['Reference'][$i]['Locations']['Location'][$j]['LibraryID']);
                            }
                          }
                        }
                      }
                      unset($location, $locationAttributes, $db_location, $locationUid);
                    }
                  }
                  unset($ref, $refAttributes, $db_ref, $refUid);
                }
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_reference', 'tx_nwcitavi_domain_model_referencehash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_keyword', 'tx_nwcitavi_domain_model_keywordhash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_library', 'tx_nwcitavi_domain_model_libraryhash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_periodical', 'tx_nwcitavi_domain_model_periodicalhash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_person', 'tx_nwcitavi_domain_model_personhash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_publisher', 'tx_nwcitavi_domain_model_publisherhash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_seriestitle', 'tx_nwcitavi_domain_model_seriestitlehash', $settings);
                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_knowledgeitem', 'tx_nwcitavi_domain_model_knowledgeitemhash', $settings);
              }
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'References could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $this->xml->close();
            
            unlink($fileName);
            
            $endTime = time();
        
            file_put_contents($this->dir.'/log/upload.txt', date('d.m.Y H:i:s', $endTime).chr(13).chr(10), FILE_APPEND);
            
            $to = $settings['email'];
            $html = '<p>Der Scheduler wurde um '.date('H:i:s', $startTime).' Uhr gestartet und wurde für '.date('H:i:s', ($endTime - $startTime - 3600)).' Minuten ausgeführt. Beendet wurder der Scheduler um '.date('H:i:s', $endTime).' Uhr</p>';
            $subject = $settings['subject']; 
            $plain = strip_tags($html);
            $fromEmail = 'noreply@netzweber.de';
            $fromName = 'Netzweber GmbH';
            $replyToEmail = 'lutz.eckelmann@netzweber.de';
            $replyToName = 'Netzweber GmbH';
            $returnPath = '';
            
            $resParseXML = $this->sendMail($to, $subject, $html, $plain, $fromEmail, $fromName, $replyToEmail, $replyToName, $returnPath, $attachements = array());
            
            $params['step'] = $step + 1;
            $params['startTime'] = $startTime;
            $params['uniqid'] = $uniqid;
        
            return $params;
          exit;
        }
      }
    }
    
    public function lastModification ( $dir, $todo = 'new', $format = 'd.m.Y H:i:s' ) {
      if ( is_file ( $dir ) )
        return false;
      
      $lastfile = '';
      if( strlen( $dir ) - 1 != '\\' || strlen( $dir ) - 1 != '/' )
        $dir .= '/'; 
           
      $handle = @opendir( $dir ); 
       
      if( !$handle )
        return false; 
           
      while ( ( $file = readdir( $handle ) ) !== false ) {
        if( $file != '.' && $file != '..' && is_file ( $dir.$file ) ) {
          if ( $todo == 'old' ) {
            if( filemtime( $dir.$file ) <= filemtime( $dir.$lastfile ) ) {
              $lastfile = $file;
            }
          } else {
            if( filemtime( $dir.$file ) >= filemtime( $dir.$lastfile ) ) {
              $lastfile = $file;
            }
          }
          if ( empty( $lastfile ) )
            $lastfile = $file;
        }
      } 
       
      $fileInfo['dir'] = $dir; 
      $fileInfo['file'] = $lastfile; 
      $fileInfo['time'] = filemtime( $dir.$lastfile ); 
      $fileInfo['formattime'] = date( $format, filemtime( $dir.$lastfile ) );
      
      closedir( $handle );
      
      return $fileInfo;
    }
    
    public function getStatusCodeText($code = NULL, $extramsg = '', $errormsg = '') {
      switch ($code) {
        case 100: $msg['text'] = 'Continue'; break;
        case 101: $msg['text'] = 'Switching Protocols'; break;
        case 102: $msg['text'] = 'Processing'; break;
        case 200: $msg['text'] = 'OK'; $msg['msg'] = 'All went fine.'; break;
        case 201: $msg['text'] = 'Created'; break;
        case 202: $msg['text'] = 'Accepted'; break;
        case 203: $msg['text'] = 'Non-Authoritative Information'; break;
        case 204: $msg['text'] = 'No Content'; break;
        case 205: $msg['text'] = 'Reset Content'; break;
        case 206: $msg['text'] = 'Partial Content'; break;
        case 300: $msg['text'] = 'Multiple Choices'; break;
        case 301: $msg['text'] = 'Moved Permanently'; break;
        case 302: $msg['text'] = 'Moved Temporarily'; break;
        case 303: $msg['text'] = 'See Other'; break;
        case 304: $msg['text'] = 'Not Modified'; break;
        case 305: $msg['text'] = 'Use Proxy'; break;
        case 400: $msg['text'] = 'Bad Request'; break;
        case 401: $msg['text'] = 'Unauthorized'; break;
        case 402: $msg['text'] = 'Payment Required'; break;
        case 403: $msg['text'] = 'Forbidden'; break;
        case 404: $msg['text'] = 'Not Found'; break;
        case 405: $msg['text'] = 'Method Not Allowed'; break;
        case 406: $msg['text'] = 'Not Acceptable'; break;
        case 407: $msg['text'] = 'Proxy Authentication Required'; break;
        case 408: $msg['text'] = 'Request Time-out'; break;
        case 409: $msg['text'] = 'Conflict'; break;
        case 410: $msg['text'] = 'Gone'; break;
        case 411: $msg['text'] = 'Length Required'; break;
        case 412: $msg['text'] = 'Precondition Failed'; break;
        case 413: $msg['text'] = 'Request Entity Too Large'; break;
        case 414: $msg['text'] = 'Request-URI Too Large'; break;
        case 415: $msg['text'] = 'Unsupported Media Type'; break;
        case 417: $msg['text'] = 'Expectation Failed'; $msg['msg'] = 'No import_key-parameter set or import_key was invalid'; break;
        case 418: $msg['text'] = 'Not Found'; $msg['msg'] = 'The Zip-Archiv could not be found'; break;
        case 419: $msg['text'] = 'Expectation Failed'; $msg['msg'] = 'No file to upload or file was invalid'; break;
        case 432: $msg['text'] = 'Wrong write permission'; $msg['msg'] = 'Missing write permission for the file directory'; break;
        case 433: $msg['text'] = 'Missing parameter'; $msg['msg'] = 'The parameter func is missing'; break;
        case 434: $msg['text'] = 'Database error'; $msg['msg'] = 'Unabled to write to database '.$extramsg.'. Error: '.$errormsg; break;
        case 435: $msg['text'] = 'Function not available'; $msg['msg'] = 'The function is not implement at the moment'; break;
        case 500: $msg['text'] = 'Internal Server Error'; break;
        case 501: $msg['text'] = 'Not Implemented'; break;
        case 502: $msg['text'] = 'Bad Gateway'; break;
        case 503: $msg['text'] = 'Service Unavailable'; break;
        case 504: $msg['text'] = 'Gateway Time-out'; break;
        case 505: $msg['text'] = 'HTTP Version not supported'; break;
        case 506: $msg['text'] = 'Export busy'; $msg['msg'] = 'Currently, the export is busy, please try again later.'; break;
        case 999: $msg['text'] = 'Test'; $msg['msg'] = var_dump($_POST); t3lib_div::devLog('Citavi', 'nw_citavilist', -1, $_POST); break;
        default:
          $msg['text'] = 'Unknown http status code "' . htmlentities($code) . '"';
      }
      
      return $msg;
    }
    
    public function getStatusCode($code = NULL, $extramsg = '', $errormsg = '') {
      if ($code !== NULL) {
        switch ($code) {
          case 100: $text = 'Continue'; break;
          case 101: $text = 'Switching Protocols'; break;
          case 102: $text = 'Processing'; break;
          case 200: $text = 'OK'; $msg = 'All went fine.'; break;
          case 201: $text = 'Created'; break;
          case 202: $text = 'Accepted'; break;
          case 203: $text = 'Non-Authoritative Information'; break;
          case 204: $text = 'No Content'; break;
          case 205: $text = 'Reset Content'; break;
          case 206: $text = 'Partial Content'; break;
          case 300: $text = 'Multiple Choices'; break;
          case 301: $text = 'Moved Permanently'; break;
          case 302: $text = 'Moved Temporarily'; break;
          case 303: $text = 'See Other'; break;
          case 304: $text = 'Not Modified'; break;
          case 305: $text = 'Use Proxy'; break;
          case 400: $text = 'Bad Request'; break;
          case 401: $text = 'Unauthorized'; break;
          case 402: $text = 'Payment Required'; break;
          case 403: $text = 'Forbidden'; break;
          case 404: $text = 'Not Found'; break;
          case 405: $text = 'Method Not Allowed'; break;
          case 406: $text = 'Not Acceptable'; break;
          case 407: $text = 'Proxy Authentication Required'; break;
          case 408: $text = 'Request Time-out'; break;
          case 409: $text = 'Conflict'; break;
          case 410: $text = 'Gone'; break;
          case 411: $text = 'Length Required'; break;
          case 412: $text = 'Precondition Failed'; break;
          case 413: $text = 'Request Entity Too Large'; break;
          case 414: $text = 'Request-URI Too Large'; break;
          case 415: $text = 'Unsupported Media Type'; break;
          case 417: $text = 'Expectation Failed'; $msg = 'No import_key-parameter set or import_key was invalid'; break;
          case 418: $text = 'Not Found'; $msg = 'The Zip-Archiv could not be found'; break;
          case 419: $text = 'Expectation Failed'; $msg = 'No file to upload or file was invalid'; break;
          case 432: $text = 'Wrong write permission'; $msg = 'Missing write permission for the file directory'; break;
          case 433: $text = 'Missing parameter'; $msg = 'The parameter func is missing'; break;
          case 434: $text = 'Database error'; $msg = 'Unabled to write to database '.$extramsg.'. Error: '.$errormsg; break;
          case 435: $text = 'Function not available'; $msg = 'The function is not implement at the moment'; break;
          case 500: $text = 'Internal Server Error'; break;
          case 501: $text = 'Not Implemented'; break;
          case 502: $text = 'Bad Gateway'; break;
          case 503: $text = 'Service Unavailable'; break;
          case 504: $text = 'Gateway Time-out'; break;
          case 505: $text = 'HTTP Version not supported'; break;
          case 506: $text = 'Export busy'; $msg = 'Currently, the export is busy, please try again later.'; break;
          case 999: $text = 'Test'; $msg = var_dump($_POST); t3lib_div::devLog('Citavi', 'nw_citavilist', -1, $_POST); break;
          default:
              exit('Unknown http status code "' . htmlentities($code) . '"');
          break;
        }
        
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

        header($protocol . ' ' . $code . ' ' . $text);
        echo $msg;
        $GLOBALS['http_response_code'] = $code;

      } else {
        $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
      }
      
      return $code;
    }
    
    function initTSFE($id = 1, $typeNum = 0) {
      \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
      if (!is_object($GLOBALS['TT'])) {
        $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
        $GLOBALS['TT']->start();
      }
      $GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',  $GLOBALS['TYPO3_CONF_VARS'], $id, $typeNum);
      $GLOBALS['TSFE']->connectToDB();
      $GLOBALS['TSFE']->initFEuser();
      $GLOBALS['TSFE']->determineId();
      $GLOBALS['TSFE']->initTemplate();
      $GLOBALS['TSFE']->getConfigArray();
    }
    
    public function truncateDatabase($table) {
      $GLOBALS['TYPO3_DB']->exec_TRUNCATEquery($table);
    }
    
    public function generatedHash($ref) {      
      foreach($ref as $key => $value) {
        if(is_array($value)) {
          $hash .= $this->generatedHash($value);
        } else {
          $hash .= $value;
        }
      }
      
      return $hash;
    }
    
    public function insertInHashTable($repo, $model, $hash, $pid) { 
      $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nwcitavi_domain_model_'.strtolower($model));
      $queryBuilder->insert('tx_nwcitavi_domain_model_'.strtolower($model));
      $queryBuilder->values([
            'citavi_hash' => $hash,
            'crdate' => time(),
            'pid' => $pid,
        ]);
      $res = $queryBuilder->execute();
      /*$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      $this->hashrepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\'.$repo);    
      
      $newModel = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Model\\'.$model);
      $newModel->setPid($pid);    
      $newModel->setCitaviHash($hash);
      $this->hashrepository->add($newModel);*/
      
      //$persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
      //$persistenceManager->persistAll();
    }
    
    public function columnExists($repo, $citaviId, $table) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      switch($repo) {
        case 'CategoryRepository':
          $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\CategoryRepository');
          break;
        default:
          $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\'.$repo);
      }    
      $res = $repository->findByCitaviId($citaviId, $table);
      $i = 0;
      foreach($res as $obj) {
        if($i == 0) {
          $result = $obj;
          $i++;
        }
      }
      
      return $result;
    }
    
    public function deletedDatabaseColumns($t1, $t2, $settings) {
      if($t1 == 'tx_nwcitavi_domain_model_category') {
        $GLOBALS['TYPO3_DB']->sql_query('DELETE r FROM '.$t1.' r LEFT JOIN '.$t2.' rh ON r.citavi_hash = rh.citavi_hash WHERE rh.citavi_hash IS NULL AND r.pid = '.$settings['sPid']);
      } else {
        $GLOBALS['TYPO3_DB']->sql_query('DELETE r FROM '.$t1.' r LEFT JOIN '.$t2.' rh ON r.citavi_hash = rh.citavi_hash WHERE rh.citavi_hash IS NULL');
      } 
    }
    
    public function setDatabase($ref, $modelTitle, $columnExists, $settings = null, $parent = null) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      switch($modelTitle) {
        case 'Reference':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\ReferenceRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');        
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setReferenceType(($ref['@attributes']['ReferenceType']) ? $ref['@attributes']['ReferenceType'] : '');
            $newReference->setParentReferenceType(($this->parentReferenceType) ? $this->parentReferenceType : '');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            try {
              $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
              $newReference->setCreatedOn(($createdOn->getTimestamp()) ? $createdOn->getTimestamp() : 0);
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'DateTime [CreatedOn] "'.$ref['@attributes']['CreatedOn'].'" could not be parsed for Reference '.$ref['@attributes']['ID'].'. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $newReference->setISBN(($ref['@attributes']['ISBN']) ? $ref['@attributes']['ISBN'] : '');
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            try {
              $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
              $newReference->setModifiedOn(($modifiedOn->getTimestamp()) ? $modifiedOn->getTimestamp() : 0);
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'DateTime [ModifiedOn] "'.$ref['@attributes']['ModifiedOn'].'" could not be parsed for Reference '.$ref['@attributes']['ID'].'. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
            }
            $newReference->setSequenceNumber(($ref['@attributes']['SequenceNumber']) ? $ref['@attributes']['SequenceNumber'] : '0');
            $newReference->setAbstract(($ref['Abstract']) ? $ref['Abstract'] : '');
            $newReference->setAbstractRTF(($ref['AbstractRTF']) ? $ref['AbstractRTF'] : '');
            if($ref['AccessDate']) {
              try {
                $accessDate = new \DateTime(($ref['AccessDate']) ? $this->setDatetimeByCitaviDate($ref['AccessDate']) : '1000-01-01 00:00:00');
                $newReference->setAccessDate(($accessDate->getTimestamp()) ? $accessDate->getTimestamp() : 0);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'DateTime [AccessDate] "'.$ref['AccessDate'].'" could not be parsed for Reference '.$ref['@attributes']['ID'].'. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
              }              
            }
            $newReference->setAdditions(($ref['Additions']) ? $ref['Additions'] : '');
            $newReference->setRefAuthors(($ref['Authors']) ? $ref['Authors'] : '');
            if($ref['Authors'] != '') {          
              $authors = $this->getRelatedObjectStorage($ref['Authors'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
              if($authors) {
                $newReference->setAuthors($authors);
              }
            }
            $newReference->setRefCategories(($ref['Categories']) ? $ref['Categories'] : '');
            if($ref['Categories'] != '') {
              $categories = $this->getRelatedCategories($ref['Categories']);
              if($categories) {
                $newReference->setCategories($categories);
              }
            }
            $newReference->setCitationKeyUpdateType(($ref['CitationKeyUpdateType']) ? $ref['CitationKeyUpdateType'] : '');
            $newReference->setRefCollaborators(($ref['Collaborators']) ? $ref['Collaborators'] : '');
            if($ref['Collaborators'] != '') {
              $collaborators = $this->getRelatedObjectStorage($ref['Collaborators'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
              if($collaborators) {
                $newReference->setCollaborators($collaborators);
              }
            }
            $newReference->setCustomField1(($ref['CustomField1']) ? $ref['CustomField1'] : '');
            $newReference->setCustomField2(($ref['CustomField2']) ? $ref['CustomField2'] : '');
            $newReference->setCustomField3(($ref['CustomField3']) ? $ref['CustomField3'] : '');
            $newReference->setCustomField4(($ref['CustomField4']) ? $ref['CustomField4'] : '');
            $newReference->setCustomField5(($ref['CustomField5']) ? $ref['CustomField5'] : '');
            $newReference->setCustomField6(($ref['CustomField6']) ? $ref['CustomField6'] : '');
            $newReference->setCustomField7(($ref['CustomField7']) ? $ref['CustomField7'] : '');
            $newReference->setCustomField8(($ref['CustomField8']) ? $ref['CustomField8'] : '');
            $newReference->setCustomField9(($ref['CustomField9']) ? $ref['CustomField9'] : '');
            $newReference->setBookDate(($ref['Date']) ? $ref['Date'] : '');
            $newReference->setDefaultLocationID(($ref['DefaultLocationID']) ? $ref['DefaultLocationID'] : '');
            $newReference->setEdition(($ref['Edition']) ? $ref['Edition'] : '');
            $newReference->setRefEditors(($ref['Editors']) ? $ref['Editors'] : '');
            if($ref['Editors'] != '') {
              $editors = $this->getRelatedObjectStorage($ref['Editors'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
              if($editors) {
                $newReference->setEditors($editors);
              }
            }
            $newReference->setEvaluation(($ref['Evaluation']) ? $ref['Evaluation'] : '');
            $newReference->setEvaluationRTF(($ref['EvaluationRTF']) ? $ref['EvaluationRTF'] : '');        
            $newReference->setRefKeywords(($ref['Keywords']) ? $ref['Keywords'] : '');
            if($ref['Keywords'] != '') {
              $keywords = $this->getRelatedObjectStorage($ref['Keywords'], 'citavi_id', 'tx_nwcitavi_domain_model_keyword', 'KeywordRepository');
              if($keywords) {
                $newReference->setKeywords($keywords);
              }
            }
            $newReference->setBookLanguage(($ref['Language']) ? $ref['Language'] : '');
            $newReference->setBookNote(($ref['Notes']) ? $ref['Notes'] : '');
            $newReference->setNumber(($ref['Number']) ? $ref['Number'] : '');
            $newReference->setNumberOfVolumes(($ref['NumberOfVolumes']) ? $ref['NumberOfVolumes'] : '');
            $newReference->setOnlineAddress(($ref['OnlineAddress']) ? $ref['OnlineAddress'] : '');
            $newReference->setRefOrganizations(($ref['Organizations']) ? $ref['Organizations'] : '');
            if($ref['Organizations'] != '') {
              $organizations = $this->getRelatedObjectStorage($ref['Organizations'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
              if($organizations) {
                $newReference->setOrganizations($organizations);
              }
            }
            $newReference->setOriginalCheckedBy(($ref['OriginalCheckedBy']) ? $ref['OriginalCheckedBy'] : '');
            $newReference->setOriginalPublication(($ref['OriginalPublication']) ? $ref['OriginalPublication'] : '');
            $newReference->setRefOthersInvolved(($ref['OthersInvolved']) ? $ref['OthersInvolved'] : '');
            if($ref['OthersInvolved'] != '') {
              $othersInvolved = $this->getRelatedObjectStorage($ref['OthersInvolved'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
              if($othersInvolved) {
                $newReference->setOthersInvolved($othersInvolved);
              }
            }
            $newReference->setPageCount(($ref['PageCount']) ? $ref['PageCount'] : '');
            $newReference->setPageRange($ref['PageRange']['@attributes']['StartPage'].';'.$ref['PageRange']['@attributes']['EndPage']);
            $newReference->setParallelTitle(($ref['ParallelTitle']) ? $ref['ParallelTitle'] : '');
            $newReference->setRefPeriodical(($ref['PeriodicalID']) ? $ref['PeriodicalID'] : '');
            if($ref['PeriodicalID'] != '') {
              $periodicals = $this->getRelatedObjectStorage($ref['PeriodicalID'], 'citavi_id', 'tx_nwcitavi_domain_model_periodical', 'PeriodicalRepository');
              if($periodicals) {
                $newReference->setPeriodicals($periodicals);
              }
            }
            $newReference->setPlaceOfPublication(($ref['PlaceOfPublication']) ? $ref['PlaceOfPublication'] : '');
            $newReference->setPrice(($ref['Price']) ? floatval($ref['Price']) : 0);
            $newReference->setRefPublishers(($ref['Publishers']) ? $ref['Publishers'] : '');
            if($ref['Publishers'] != '') {
              $publishers = $this->getRelatedObjectStorage($ref['Publishers'], 'citavi_id', 'tx_nwcitavi_domain_model_publisher', 'PublisherRepository');
              if($publishers) {
                $newReference->setPublishers($publishers);
              }
            }
            $newReference->setRating(($ref['Rating']) ? intval($ref['Rating']) : 0);
            $newReference->setRefSeriesTitle(($ref['SeriesTitleID']) ? $ref['SeriesTitleID'] : '');
            if($ref['SeriesTitleID'] != '') {
              $seriestitles = $this->getRelatedObjectStorage($ref['SeriesTitleID'], 'citavi_id', 'tx_nwcitavi_domain_model_seriestitle', 'SeriestitleRepository');
              if($seriestitles) {
                $newReference->setSeriestitles($seriestitles);
              }
            }
            $newReference->setShortTitle(($ref['ShortTitle']) ? $ref['ShortTitle'] : '');
            $newReference->setSourceOfBibliographicInformation(($ref['SourceOfBibliographicInformation']) ? $ref['SourceOfBibliographicInformation'] : '');
            $newReference->setSpecificField1(($ref['SpecificField1']) ? $ref['SpecificField1'] : '');
            $newReference->setSpecificField2(($ref['SpecificField2']) ? $ref['SpecificField2'] : '');
            $newReference->setSpecificField4(($ref['SpecificField4']) ? $ref['SpecificField4'] : '');
            $newReference->setSpecificField7(($ref['SpecificField7']) ? $ref['SpecificField7'] : '');
            $newReference->setStorageMedium(($ref['StorageMedium']) ? $ref['StorageMedium'] : '');
            $newReference->setSubtitle(($ref['Subtitle']) ? $ref['Subtitle'] : '');
            $newReference->setTableOfContents(($ref['TableOfContents']) ? $ref['TableOfContents'] : '');
            $newReference->setTableOfContentsRTF(($ref['TableOfContentsRTF']) ? $ref['TableOfContentsRTF'] : '');
            $newReference->setTitle(($ref['Title']) ? $ref['Title'] : '');
            $newReference->setTitleInOtherLanguages(($ref['TitleInOtherLanguages']) ? $ref['TitleInOtherLanguages'] : '');
            $newReference->setTitleSupplement(($ref['TitleSupplement']) ? $ref['TitleSupplement'] : '');
            $newReference->setTranslatedTitle(($ref['TranslatedTitle']) ? $ref['TranslatedTitle'] : '');
            $newReference->setUniformTitle(($ref['UniformTitle']) ? $ref['UniformTitle'] : '');
            $newReference->setBookVolume(($ref['Volume']) ? $ref['Volume'] : '');
            $newReference->setBookYear(($ref['Year']) ? $ref['Year'] : '');
            $newReference->setLiteraturlistId($this->key);
            $newReference->setPageRangeStart(($ref['PageRange']['@attributes']['StartPage']) ? $ref['PageRange']['@attributes']['StartPage'] : '');
            $newReference->setPageRangeEnd(($ref['PageRange']['@attributes']['EndPage']) ? $ref['PageRange']['@attributes']['EndPage'] : '');
            $newReference->setDoi(($ref['@attributes']['Doi']) ? $ref['@attributes']['Doi'] : '0');
            $newReference->setSortDate(($this->sortDate) ? $this->sortDate : '');
            $newReference->setTxExtbaseType('Tx_NwCitaviFe_Reference');
            if($ref['ParentReferenceID'] != '') {
              $parentReference = $this->getRelatedObjectStorage($ref['ParentReferenceID'], 'citavi_id', 'tx_nwcitavi_domain_model_reference', 'ReferenceRepository');
              if($parentReference) {
                $newReference->setParentReferences($parentReference);
              }
            }                
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Reference "'.$ref['Title'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }                    
          break;
        case 'Keyword':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\KeywordRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Keyword();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setName(($ref['@attributes']['Name']) ? $ref['@attributes']['Name'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Keyword "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'Library':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\LibraryRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Library();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviID(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setName(($ref['@attributes']['Abbreviation']) ? $ref['@attributes']['Abbreviation'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Library "'.$ref['@attributes']['Abbreviation'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          } 
          break;
        case 'Periodical':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\PeriodicalRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Periodical();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setName(($ref['@attributes']['Name']) ? $ref['@attributes']['Name'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Periodical "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'Person':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\PersonRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Person();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $fullName = '';
            if($ref['@attributes']['LastName']) {
              $fullName .= $ref['@attributes']['LastName'];
            }
            if($ref['@attributes']['FirstName']) {
              $fullName .= ', '.$ref['@attributes']['FirstName'];
            }
            if($ref['@attributes']['MiddleName']) {
              $fullName .= ' '.$ref['@attributes']['MiddleName'];
            }
            $newReference->setFullName(($fullName) ? $fullName : '');
            $newReference->setFirstName(($ref['@attributes']['FirstName']) ? $ref['@attributes']['FirstName'] : '');
            $newReference->setLastName(($ref['@attributes']['LastName']) ? $ref['@attributes']['LastName'] : '');
            $newReference->setMiddleName(($ref['@attributes']['MiddleName']) ? $ref['@attributes']['MiddleName'] : '');
            $newReference->setPref(($ref['@attributes']['Prefix']) ? $ref['@attributes']['Prefix'] : '');
            $newReference->setSuff(($ref['@attributes']['Suffix']) ? $ref['@attributes']['Suffix'] : '');
            $newReference->setAbbreviation(($ref['@attributes']['Abbreviation']) ? $ref['@attributes']['Abbreviation'] : '');
            $newReference->setSex(($ref['@attributes']['Sex']) ? $ref['@attributes']['Sex'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Person "'.$fullName.'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'Publisher':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\PublisherRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Publisher();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setName(($ref['@attributes']['Name']) ? $ref['@attributes']['Name'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Publisher "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'SeriesTitle':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\SeriestitleRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Seriestitle();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setName(($ref['@attributes']['Name']) ? $ref['@attributes']['Name'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'SeriesTitle "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'KnowledgeItem':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\KnowledgeItemRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItem();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviID(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setKnowledgeItemType(($ref['@attributes']['KnowledgeItemType']) ? $ref['@attributes']['KnowledgeItemType'] : '0');
            $newReference->setCoreStatement(($ref['CoreStatement']) ? $ref['CoreStatement'] : '');
            $newReference->setCoreStatementUpdateType(($ref['CoreStatementUpdateType']) ? $ref['CoreStatementUpdateType'] : '');
            $newReference->setText(($ref['Text']) ? $ref['Text'] : '');
            $newReference->setTextRTF(($ref['TextRTF']) ? $ref['TextRTF'] : '');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            } 
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'KnowledgeItem "'.$ref['Text'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'Category':
          try {        
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\CategoryRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {          
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Category();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setTitle(($ref['@attributes']['Name']) ? $ref['@attributes']['Name'] : '');
            $newReference->setLiteraturlistId($this->key);          
            if($parent) {          
              $res = $repository->findByCitaviId($parent);            
              $parentObj = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
              $i = 0;
              foreach($res as $obj) {
                if($i == 0) {
                  $parentObj->attach($obj);
                  $result = $obj;
                  $i++;
                }
              }
              if($result) {
                $newReference->setParent($parentObj);
              }
            }
            //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($newReference);
            
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Category "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
        case 'Location':
          try {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\LocationRepository');
            if($columnExists) {
              $newReference = $columnExists;
            } else {
              $newReference = new \Netzweber\NwCitavi\Domain\Model\Location();
            }
            $newReference->setPid(($settings['sPid']) ? $settings['sPid'] : '0');
            $newReference->setCitaviHash($this->hash);
            $newReference->setCitaviId(($ref['@attributes']['ID']) ? $ref['@attributes']['ID'] : '0');
            $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ? $ref['@attributes']['CreatedBy'] : '0');
            $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ? $ref['@attributes']['CreatedBySid'] : '0');
            $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
            $newReference->setCreatedOn($createdOn->getTimestamp());
            $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ? $ref['@attributes']['ModifiedBy'] : '0');
            $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ? $ref['@attributes']['ModifiedBySid'] : '0');
            $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
            $newReference->setModifiedOn($modifiedOn->getTimestamp());
            $newReference->setAddress(($ref['Address']) ? $ref['Address'] : '0');
            $newReference->setNotes(($ref['Notes']) ? $ref['Notes'] : '0');
            $newReference->setLocationType(($ref['@attributes']['LocationType']) ? $ref['@attributes']['LocationType'] : '0');
            //$newReference->setAddressUri(($ref['Address']['@attributes']['AddressUri']) ? $ref['Address']['@attributes']['AddressUri'] : '0');
            $newReference->setMirrorsReferencePropertyId(($ref['@attributes']['MirrorsReferencePropertyId']) ? $ref['@attributes']['MirrorsReferencePropertyId'] : '0');
            $newReference->setLiteraturlistId($this->key);
            if($columnExists) {
              $repository->update($newReference);
            } else {
              $repository->add($newReference);
            }
          } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Location "'.$ref['Address'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
          }
          break;
      }
      $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
      $persistenceManager->persistAll();
    }
    
    public function setDatetimeByCitaviDate($citaviDate) {
      return str_replace("T", " ", $citaviDate);
    }
    
    public function getRelatedObjectStorage($keys, $field_name, $table, $repository) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      $objectRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\'.$repository);
      $query = $objectRepository->createQuery();
      $keyArray = explode(";", $keys);
      if($keyArray[0] != '') {
        if ( is_array( $keyArray ) ) {
          $keyArrayNum = count($keyArray);
        }
        $where = '';
        $orderby = '';
        for($i = 0; $i < $keyArrayNum; $i++) {
          if($i == 0) {
            $where .= ' '.$field_name.' LIKE \''.$keyArray[$i].'\'';
            $orderby .= ' ORDER BY FIELD('.$field_name.', \''.$keyArray[$i].'\'';
          } else {
            $where .= ' OR '.$field_name.' LIKE \''.$keyArray[$i].'\'';
            $orderby .= ', \''.$keyArray[$i].'\'';
          } 
        }
        if(strlen($orderby) > 0) {
          $orderby .= ') ASC';
        }
        $sql = 'SELECT * FROM '.$table.' WHERE '.$where.$orderby;
        $query->statement($sql);
        $res = $query->execute();
        $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
        foreach($res as $obj) {
          $objectStorage->attach($obj);
        }
        return $objectStorage;
      } else {
        return false;
      }
    }
    
    public function getRelatedCategories($categories) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      $categoryRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\CategoryRepository');
      $query = $categoryRepository->createQuery();
      $categoriesArray = explode(";", $categories);
      if($categoriesArray[0] != '') {
        if ( is_array( $categoriesArray ) ) {
          $categoriesArrayNum = count($categoriesArray);
        }
        $where = '';
        for($i = 0; $i < $categoriesArrayNum; $i++) {
          if($i == 0) {
            $where .= ' citavi_id LIKE \''.$categoriesArray[$i].'\'';
          } else {
            $where .= ' OR citavi_id LIKE \''.$categoriesArray[$i].'\'';
          } 
        }
        $sql = 'SELECT * FROM tx_nwcitavi_domain_model_category WHERE '.$where;
        $query->statement($sql);
        $res = $query->execute();
        
        $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
        foreach($res as $obj) {
          $objectStorage->attach($obj);
        }
        
        return $objectStorage;
      } else {
        return false;
      }
    }
    
    public function updateReferenceLocation($ref, $locationId) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      $locationRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\LocationRepository');
      $refQuery = $this->createQuery();
      $refSql = 'SELECT * FROM tx_nwcitavi_domain_model_reference WHERE citavi_id LIKE \''.$ref['@attributes']['ID'].'\'';
      $refQuery->statement($refSql);
      $refResult = $refQuery->execute();
      $updateReference = $refResult[0];
      
      $locationQuery = $locationRepository->createQuery();
      $locationSql = 'SELECT * FROM tx_nwcitavi_domain_model_location WHERE citavi_id LIKE \''.$locationId.'\'';
      $locationQuery->statement($locationSql);
      $locationResult = $locationQuery->execute();
      $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
      foreach($locationResult as $obj) {
        $objectStorage->attach($obj);
      }
      
      $updateReference->setLocations($objectStorage);
      $this->update($updateReference);
      
      $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
      $persistenceManager->persistAll();
    }
    
    public function updateLocationLibrary($locationId, $libraryId) {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
      $locationRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\LocationRepository');
      $libraryRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\LibraryRepository');
      
      $locationQuery = $locationRepository->createQuery();
      $locationSql = 'SELECT * FROM tx_nwcitavi_domain_model_location WHERE citavi_id LIKE \''.$locationId.'\'';
      $locationQuery->statement($locationSql);
      $locationResult = $locationQuery->execute();
      $updateLocation = $locationResult[0];
      
      $libraryQuery = $libraryRepository->createQuery();
      $librarySql = 'SELECT * FROM tx_nwcitavi_domain_model_library WHERE citavi_id LIKE \''.$libraryId.'\'';
      $libraryQuery->statement($librarySql);
      $libraryResult = $libraryQuery->execute();
      
      $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
      foreach($libraryResult as $obj) {
        $objectStorage->attach($obj);
      }
      
      $updateLocation->setLibrarys($objectStorage);
      $locationRepository->update($updateLocation);
      
      $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
      $persistenceManager->persistAll();
    }
    
    public function compareHashData($settings) {
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_reference');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_keyword');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_library');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_periodical');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_person');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_publisher');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_seriestitle');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_knowledgeitem');
      $this->deletedDoubleDatabaseColumns('tx_nwcitavi_domain_model_category');
    
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_reference', 'tx_nwcitavi_domain_model_referencehash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_keyword', 'tx_nwcitavi_domain_model_keywordhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_library', 'tx_nwcitavi_domain_model_libraryhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_periodical', 'tx_nwcitavi_domain_model_periodicalhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_person', 'tx_nwcitavi_domain_model_personhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_publisher', 'tx_nwcitavi_domain_model_publisherhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_seriestitle', 'tx_nwcitavi_domain_model_seriestitlehash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_knowledgeitem', 'tx_nwcitavi_domain_model_knowledgeitemhash', $settings);
      $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_category', 'tx_nwcitavi_domain_model_categoryhash', $settings);
    }
    
    public function deletedDoubleDatabaseColumns($t) {
      $GLOBALS['TYPO3_DB']->sql_query('DELETE t1 FROM '.$t.' t1 INNER JOIN '.$t.' t2 WHERE t1.uid < t2.uid AND t1.citavi_hash = t2.citavi_hash;');
    }
    
    public function parseCategories($level, $array, $settings, $parent = null) {
      $categoryCount = count($array['Categories']['Category']);
      for($i = 0; $i < $categoryCount; $i++) {
        if($array['Categories']['Category']['@attributes']) {
          $category = $array['Categories']['Category'];                                        
        } else {
          $category = $array['Categories']['Category'][$i];                    
        }
        if(strlen($category['@attributes']['ID']) > 0) {
          $refstr = $this->generatedHash($category);
          $this->hash = hash("md5", $refstr);
          $this->insertInHashTable('CategoryHashRepository', 'CategoryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
          $columnExists = $this->columnExists('CategoryRepository', $category['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
          
          $this->setDatabase($category, 'Category', $columnExists, $settings, $parent);
          if ( is_array( $category['Categories']['Category'] ) ) {
            $this->parseCategories($level++, $category, $settings, $category['@attributes']['ID']);
          }
        }
      }
      unset($category);
    }
    
    public function taskParseXMLCategories($numEntries) {      
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');        
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');                
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];        
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check && (int)$settings['sPid'] > 0) {
          $taskExists = file_exists($this->dir.'/task.txt');
          if(!$taskExists) {
            $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
            $schedulerCols = explode("|", $schedulerString);
            $fileName = $schedulerCols[0];
            $this->key = $schedulerCols[1];
            $uniqid = $schedulerCols[2]; 
            $this->xml = new \XMLReader();
            $this->xml->open($fileName);
            $xmlstring = implode("", file($fileName));
            $xml = simplexml_load_string($xmlstring);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            $contents = var_export($array, true);
            $xmlObj = new \SimpleXMLElement($xmlstring);
            $level = 0;
            
            try {
              if ( is_array( $array['Categories']['Category'] ) ) {
                $this->truncateDatabase('tx_nwcitavi_domain_model_categoryhash');
                $this->parseCategories($level, $array, $settings);
                file_put_contents($this->dir.'/task.txt', 1);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   'The categories have been created successfully',
                   'Successful',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                   TRUE
                );
              } else {
                file_put_contents($this->dir.'/task.txt', 1);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   'No categories to create',
                   'Info',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                   TRUE
                );
              }
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            } catch (Exception $e) {
              $this->logRepository->addLog(1, 'Categories could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 $e,
                 'Error: Categories could not be generated',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
            $this->xml->close();
          } else {
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
               'Task parse categories is not your turn',
               'Waiting',
               \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
               TRUE
            );
            
            $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
            $messageQueue->addMessage($message);
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }    
    }
    
    public function taskParseXMLKeywords($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');        
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 1) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);
              
              try {
                if ( is_array( $array['Keywords']['Keyword'] ) ) {      
                  $keywordsCount = count($array['Keywords']['Keyword']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_keywordhash');
                  for($i = 0; $i < $keywordsCount; $i++) {
                    if($array['Keywords']['Keyword']['@attributes']) {
                      $keyword = $array['Keywords']['Keyword'];                                        
                    } else {
                      $keyword = $array['Keywords']['Keyword'][$i];                    
                    }
                    
                    if(strlen($keyword['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($keyword);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('KeywordHashRepository', 'KeywordHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('KeywordRepository', $keyword['@attributes']['ID'], 'tx_nwcitavi_domain_model_keyword');
                      
                      $this->setDatabase($keyword, 'Keyword', $columnExists, $settings);
                    }
                    unset($keyword, $keywordAttributes, $db_keyword, $keywordUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_keyword', 'tx_nwcitavi_domain_model_keywordhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 2);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The keywords have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 2);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No keywords to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Keywords could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Keywords could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse keywords is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $This->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLLibraries($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 2) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['Libraries']['Library'] ) ) {        
                  $librariesCount = count($array['Libraries']['Library']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_libraryhash');
                  for($i = 0; $i < $librariesCount; $i++) {
                    if($array['Libraries']['Library']['@attributes']) {
                      $library = $array['Libraries']['Library'];                                        
                    } else {
                      $library = $array['Libraries']['Library'][$i];                    
                    }
                    
                    if(strlen($library['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($library);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('LibraryHashRepository', 'LibraryHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('LibraryRepository', $library['@attributes']['ID'], 'tx_nwcitavi_domain_model_library');
                      
                      $this->setDatabase($library, 'Library', $columnExists, $settings);
                    }
                    unset($library, $libraryAttributes, $db_library, $libraryUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_library', 'tx_nwcitavi_domain_model_libraryhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 3);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The libraries have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 3);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No libraries to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Libraries could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Libraries could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse libraries is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLPeriodicals($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 3) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['Periodicals']['Periodical'] ) ) {    
                  $periodicalsCount = count($array['Periodicals']['Periodical']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_periodicalhash');
                  for($i = 0; $i < $periodicalsCount; $i++) {
                    if($array['Periodicals']['Periodical']['@attributes']) {
                      $periodical = $array['Periodicals']['Periodical'];                                        
                    } else {
                      $periodical = $array['Periodicals']['Periodical'][$i];                    
                    }
                    
                    if(strlen($periodical['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($periodical);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('PeriodicalHashRepository', 'PeriodicalHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('PeriodicalRepository', $periodical['@attributes']['ID'], 'tx_nwcitavi_domain_model_periodical');
                      
                      $this->setDatabase($periodical, 'Periodical', $columnExists, $settings);
                    }
                    unset($periodical, $periodicalAttributes, $db_periodical, $periodicalUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_periodical', 'tx_nwcitavi_domain_model_periodicalhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 4);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The periodicals have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 4);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No periodicals to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Periodicals could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Periodicals could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse periodicals is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLPersons($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 4) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['Persons']['Person'] ) ) {            
                  $personsCount = count($array['Persons']['Person']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_personhash');
                  for($i = 0; $i < $personsCount; $i++) {
                    if($array['Persons']['Person']['@attributes']) {
                      $person = $array['Persons']['Person'];                                        
                    } else {
                      $person = $array['Persons']['Person'][$i];                    
                    }
                    
                    if(strlen($person['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($person);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('PersonHashRepository', 'PersonHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('PersonRepository', $person['@attributes']['ID'], 'tx_nwcitavi_domain_model_person');
                      
                      $this->setDatabase($person, 'Person', $columnExists, $settings);
                    }
                    unset($person, $personAttributes, $db_person, $personUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_person', 'tx_nwcitavi_domain_model_personhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 5);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The persons have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 5);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No persons to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Persons could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Persons could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse persons is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLPublishers($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 5) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['Publishers']['Publisher'] ) ) {            
                  $publishersCount = count($array['Publishers']['Publisher']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_publisherhash');
                  for($i = 0; $i < $publishersCount; $i++) {
                    if($array['Publishers']['Publisher']['@attributes']) {
                      $publisher = $array['Publishers']['Publisher'];                                        
                    } else {
                      $publisher = $array['Publishers']['Publisher'][$i];                    
                    }
                    
                    if(strlen($publisher['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($publisher);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('PublisherHashRepository', 'PublisherHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('PublisherRepository', $publisher['@attributes']['ID'], 'tx_nwcitavi_domain_model_publisher');
                      
                      $this->setDatabase($publisher, 'Publisher', $columnExists, $settings);
                    }
                    unset($publisher, $publisherAttributes, $db_publisher, $publisherUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_publisher', 'tx_nwcitavi_domain_model_publisherhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 6);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The publishers have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 6);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No publishers to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Publishers could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Publishers could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse publishers is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLSeriestitles($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 6) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {            
                if ( is_array( $array['SeriesTitles']['SeriesTitle'] ) ) {
                  $seriestitlesCount = count($array['SeriesTitles']['SeriesTitle']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_seriestitlehash');
                  for($i = 0; $i < $seriestitlesCount; $i++) {
                    if($array['SeriesTitles']['SeriesTitle']['@attributes']) {
                      $seriestitle = $array['SeriesTitles']['SeriesTitle'];                                        
                    } else {
                      $seriestitle = $array['SeriesTitles']['SeriesTitle'][$i];                    
                    }
                    
                    if(strlen($seriestitle['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($seriestitle);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('SeriestitleHashRepository', 'SeriestitleHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('SeriestitleRepository', $seriestitle['@attributes']['ID'], 'tx_nwcitavi_domain_model_seriestitle');
                      
                      $this->setDatabase($seriestitle, 'SeriesTitle', $columnExists, $settings);
                    }
                    unset($seriestitle, $seriestitleAttributes, $db_seriestitle, $seriestitleUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_seriestitle', 'tx_nwcitavi_domain_model_seriestitlehash', $settings);
                  file_put_contents($this->dir.'/task.txt', 7);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The seriestitles have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 7);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No seriestitles to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Seriestitles could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Seriestitles could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse publishers is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLKnowledgeitems($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 7) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['Thoughts']['KnowledgeItem'] ) ) {                              
                  $knowledgeitemsCount = count($array['Thoughts']['KnowledgeItem']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_knowledgeitemhash');
                  for($i = 0; $i < $knowledgeitemsCount; $i++) {
                    if($array['Thoughts']['KnowledgeItem']['@attributes']) {
                      $knowledgeitem = $array['Thoughts']['KnowledgeItem'];                                        
                    } else {
                      $knowledgeitem = $array['Thoughts']['KnowledgeItem'][$i];                    
                    }
                    
                    if(strlen($knowledgeitem['@attributes']['ID']) > 0) {
                      $refstr = $this->generatedHash($knowledgeitem);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('KnowledgeItemHashRepository', 'KnowledgeItemHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('KnowledgeItemRepository', $knowledgeitem['@attributes']['ID'], 'tx_nwcitavi_domain_model_knowledgeitem');
                      
                      $this->setDatabase($seriestitle, 'KnowledgeItem', $columnExists, $settings);
                    }
                    unset($knowledgeitem, $knowledgeitemAttributes, $db_knowledgeitem, $knowledgeitemUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_knowledgeitem', 'tx_nwcitavi_domain_model_knowledgeitemhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 8);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The knowledgeitems have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 8);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No knowledgeitems to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Knowledgeitems could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Knowledgeitems could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse publishers is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLReferences($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 8) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {
                if ( is_array( $array['References']['Reference'] ) ) {    
                  $refCount = count($array['References']['Reference']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_referencehash');
                  for($i = 0; $i < $refCount; $i++) {
                    if($array['References']['Reference']['@attributes']) {
                      $ref = $array['References']['Reference'];                                        
                    } else {
                      $ref = $array['References']['Reference'][$i];                    
                    }
                    
                    switch($ref['@attributes']['ReferenceType']) {
                      case 'BookEdited';
                        $this->sortDate = $ref['Year'];
                        break;
                      case 'Book';
                        $this->sortDate = $ref['Year'];
                        break;
                      case 'JournalArticle';
                        $this->sortDate = $ref['Year'];
                        break;
                      case 'NewspaperArticle';
                        $this->sortDate = $ref['Date'];
                        break;
                      case 'PersonalCommunication';
                        $this->sortDate = $ref['Date'];
                        break;
                      case 'Unknown';
                        $this->sortDate = (!empty($ref['Date'])) ? $ref['Date'] : $ref['Year'];
                        break;
                      case 'UnpublishedWork';
                        $this->sortDate = $ref['Date'];
                        break;
                      case 'ConferenceProceedings';
                        $this->sortDate = $ref['Year'];
                        break;
                      case 'Contribution';
                        $db_parentref = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                        $this->sortDate = $db_parentref['sort_date'];
                        break;
                      case 'Lecture';
                        $this->sortDate = $ref['Date'];
                        break;
                      case 'PressRelease';
                        $this->sortDate = $ref['Date'];
                        break;
                      case 'SpecialIssue';
                        $this->sortDate = $ref['Year'];
                        break;
                      case 'Thesis';
                        $this->sortDate = $ref['Date'];
                        break;
                      default:
                        $this->sortDate = $ref['Date'];
                    }
                    if(strlen($ref['ParentReferenceID']) > 0) {
                      $parent = $xmlObj->xpath("//Reference[@ID='".$ref['ParentReferenceID']."']");
                      $this->parentReferenceType = (string)$parent[0]['ReferenceType'];
                      $this->db_parentref = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                      $this->sortDate = $db_parentref['sort_date'];
                    }
                    if(strlen($ref['@attributes']['ID']) > 0) {                  
                      $refstr = $this->generatedHash($ref);
                      $this->hash = hash("md5", $refstr);
                      $this->insertInHashTable('ReferenceHashRepository', 'ReferenceHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                      $columnExists = $this->columnExists('ReferenceRepository', $ref['@attributes']['ID'], 'tx_nwcitavi_domain_model_reference');                  
                      
                      $this->setDatabase($ref, 'Reference', $columnExists, $settings);                  
                    }
                    unset($ref, $refAttributes, $db_ref, $refUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_reference', 'tx_nwcitavi_domain_model_referencehash', $settings);
                  file_put_contents($this->dir.'/task.txt', 9);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The references have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 9);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No references to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'References could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: References could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse publishers is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLLocations($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 9) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              $this->key = $schedulerCols[1];
              $uniqid = $schedulerCols[2]; 
              $this->xml = new \XMLReader();
              $this->xml->open($fileName);
              $xmlstring = implode("", file($fileName));
              $xml = simplexml_load_string($xmlstring);
              $json = json_encode($xml);
              $array = json_decode($json,TRUE);
              $contents = var_export($array, true);
              $xmlObj = new \SimpleXMLElement($xmlstring);            
              try {    
                if ( is_array( $array['References']['Reference'] ) ) {
                  $refCount = count($array['References']['Reference']);
                  $this->truncateDatabase('tx_nwcitavi_domain_model_locationhash');
                  for($i = 0; $i < $refCount; $i++) {
                    if($array['References']['Reference']['@attributes']) {
                      $ref = $array['References']['Reference'];                                        
                    } else {
                      $ref = $array['References']['Reference'][$i];                    
                    }
                                    
                    if(strlen($ref['@attributes']['ID']) > 0) {                  
                      // Locations speichern
                      if($array['References']['Reference'][$i]['Locations']['Location']['@attributes']) {
                        $location = $array['References']['Reference'][$i]['Locations']['Location'];
                        if(strlen($location['@attributes']['ID']) > 0) {
                          $locationstr = $this->generatedHash($location);
                          $this->hash = hash("md5", $locationstr);
                          $this->insertInHashTable('LocationHashRepository', 'LocationHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                          $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                          
                          $this->setDatabase($location, 'Location', $columnExists, $settings);
                          
                          // Location verknüpfen
                          $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                            
                          //Libraries verknüpfen
                          if(strlen($array['References']['Reference'][$i]['Locations']['Location']['LibraryID']) > 0) {
                            $this->updateLocationLibrary($location['@attributes']['ID'], $array['References']['Reference'][$i]['Locations']['Location']['LibraryID']);
                          }
                        }
                        unset($location, $locationAttributes, $db_location, $locationUid);
                      } else {
                        if ( is_array( $array['References']['Reference'][$i]['Locations']['Location'] ) ) {
                          $locationsCount = count($array['References']['Reference'][$i]['Locations']['Location']);
                          for($j = 0; $j < $locationsCount; $j++) {
                            $location = $array['References']['Reference'][$i]['Locations']['Location'][$j];
                            if(strlen($location['@attributes']['ID']) > 0) {
                              $locationstr = $this->generatedHash($location);
                              $this->hash = hash("md5", $locationstr);
                              $this->insertInHashTable('LocationHashRepository', 'LocationHash', $this->hash, ($settings['sPid']) ? $settings['sPid'] : '0');
                              $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                              
                              $this->setDatabase($location, 'Location', $columnExists, $settings);
                              
                              // Location verknüpfen
                              $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                              
                              //Libraries verknüpfen
                              if(strlen($array['References']['Reference'][$i]['Locations']['Location'][$j]['LibraryID']) > 0) {
                                $this->updateLocationLibrary($location['@attributes']['ID'], $array['References']['Reference'][$i]['Locations']['Location'][$j]['LibraryID']);
                              }
                            }
                          }
                        }
                        unset($location, $locationAttributes, $db_location, $locationUid);
                      }                  
                    }
                    unset($ref, $refAttributes, $db_ref, $refUid);
                  }
                  $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_location', 'tx_nwcitavi_domain_model_locationhash', $settings);
                  file_put_contents($this->dir.'/task.txt', 10);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'The locations have been created successfully',
                     'Successful',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                     TRUE
                  );
                } else {
                  file_put_contents($this->dir.'/task.txt', 10);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'No locations to create',
                     'Info',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::INFO,
                     TRUE
                  );
                }
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              } catch (Exception $e) {
                $this->logRepository->addLog(1, 'Locations could not be generated. Fehler: '.$e, 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   $e,
                   'Error: Locations could not be generated',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              $this->xml->close();
            } else {
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'Task parse publishers is not your turn',
                 'Waiting',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLFiles($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];        
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 10) {
              $schedulerString = file_get_contents ( $this->dir.'/scheduler.txt' );
              $schedulerCols = explode("|", $schedulerString);
              $fileName = $schedulerCols[0];
              if($numEntries == 0) {
                unlink($fileName);
                              
                if(file_exists($fileName)) {
                  $this->logRepository->addLog(1, 'File "'.$fileName.'" could not be deleted', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task still working.', ''.$this->key.'', $settings['sPid']);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'File "'.$fileName.'" could not be deleted',
                     'Warning',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                     TRUE
                  );
                  
                  $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                  $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                  $messageQueue->addMessage($message);
                }              
              }
              
              // FileReference erstellen
              $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/files/');
              $filecheck = file_exists($this->dir.'/files.txt');
              if($filecheck) {
                $file = $this->dir.'/files.txt';
                $file_handle = fopen($file, 'r');
                while (!feof($file_handle)) {
                  $line = fgets($file_handle);
                  if($line != '') {
                    $fileCols = explode("|", $line);
                    $referenceId = $fileCols[0];
                    $file = $fileCols[1];
                    $fileType = $fileCols[2];
                    $this->setFileReferences($referenceId, $file, $fileType, $settings);
                  }
                }
                fclose($file_handle);
                unlink($this->dir.'/files.txt');
                
                if(file_exists($this->dir.'/files.txt')) {
                  $this->logRepository->addLog(1, 'File "'.$this->dir.'/files.txt" could not be deleted', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task still working.', ''.$this->key.'', $settings['sPid']);
                  $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                     'File "'.$this->dir.'/files.txt" could not be deleted',
                     'Warning',
                     \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                     TRUE
                  );
                  
                  $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                  $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                  $messageQueue->addMessage($message);
                }
              }
              $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                 'The files have been created successfully',
                 'Successful',
                 \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                 TRUE
              );
              
              $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
              $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
              $messageQueue->addMessage($message);
              
              $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
              file_put_contents($this->dir.'/task.txt', 11);
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function taskParseXMLCleaner($numEntries) {
      try {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $this->initTSFE($this->getRootpage($objectManager));
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        $check = file_exists($this->dir.'/scheduler.txt');      
        if($check) {
          $taskExists = file_exists($this->dir.'/task.txt');        
          if($taskExists) {
            $taskString = file_get_contents ( $this->dir.'/task.txt' );
            $taskCols = explode("|", $taskString);
            if((int)$taskCols[0] == 11) {
              $this->compareHashData($settings);
              
              unlink($this->dir.'/scheduler.txt');
              
              if(file_exists($this->dir.'/scheduler.txt')) {
                $this->logRepository->addLog(1, 'File "'.$this->dir.'/scheduler.txt" could not be deleted', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task still working.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   'File "'.$this->dir.'/scheduler.txt" could not be deleted',
                   'Warning',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
              
              unlink($this->dir.'/task.txt');
              
              if(file_exists($this->dir.'/task.txt')) {
                $this->logRepository->addLog(1, 'File "'.$this->dir.'/task.txt" could not be deleted', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task still working.', ''.$this->key.'', $settings['sPid']);
                $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                   'File "'.$this->dir.'/task.txt" could not be deleted',
                   'Warning',
                   \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
                   TRUE
                );
                
                $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
              }
            }
          }
        }
      } catch (Exception $e) {
        $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqid.'', '[Citavi Parser]: Task was terminated.', ''.$this->key.'', $settings['sPid']);
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
           $e,
           'Error: Task was terminated',
           \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
           TRUE
        );
        
        $flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
      }
    }
    
    public function setFileReferences($referenceId, $file, $fileType, $settings) {    
      $reference = $this->getByCitaviId($referenceId);
      if($reference) {
        $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
        $fileReference = $resourceFactory->getFileObject((int)$file);
        $newFileReference = $this->objectManager->get('Netzweber\\NwCitavi\\Domain\\Model\\FileReference');
        $newFileReference->setFile($fileReference);
        if(trim($fileType) == 'cover') {
          $reference->setCover($newFileReference);
          $this->update($reference);
  
          $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
          $persistenceManager->persistAll();
        }
        
        if(trim($fileType) == 'attachment') {
          $reference->setAttachment($newFileReference);
          $this->update($reference);
  
          $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
          $persistenceManager->persistAll();
        }
      }
    }
    
    public function getByCitaviId($referenceId) {
      $query = $this->createQuery();
      $sql = 'SELECT * FROM tx_nwcitavi_domain_model_reference WHERE citavi_id LIKE \''.$referenceId.'\'';
      $query->statement($sql);
      $res = $query->execute();
      $i = 0;
      foreach($res as $obj) {
        $reference = $obj;
        $i++;
      }
      return $reference;
    }
    
    public function findAllByFilter($settings, $page) {
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($settings);
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($page);    
      $query = $this->createQuery();
      $query->matching($query->logicalAnd($this->getAndOrConstraints($query, $settings)));
      if($settings['showAllOnOnePage'] == false) {
        $query->setLimit((int)$settings['pagelimit']);
        $query->setOffset($page);
      }
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->getOrderings($settings));
      
      return $query->execute();  
    }
    
    public function numAllByFilter($settings) {    
      $query = $this->createQuery();
      $query->matching($query->logicalAnd($this->getAndOrConstraints($query, $settings)));            
      $result = $query->count();
    	return $result;  
    }
    
    public function getAndOrConstraints($query, $settings) {
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($settings);
      $constraints[] = $query->like('literaturlistId', $settings['import_key']);      
      if($settings['searchstr'] != '') {
        switch($settings['searchOptions']) {
          case 'title':
            $constraints[] = $query->logicalAnd(
              $query->logicalOr(
                $query->like('title', '%'.$settings['searchstr'].'%'),
                $query->like('title', ''.$settings['searchstr'].'%'),
                $query->like('title', '%'.$settings['searchstr'].'')
              )
            );
            break;
          case 'abstract':
            $constraints[] = $query->logicalAnd(
              $query->logicalOr(
                $query->like('abstract', '%'.$settings['searchstr'].'%'),
                $query->like('abstract', ''.$settings['searchstr'].'%'),
                $query->like('abstract', '%'.$settings['searchstr'].'')
              )
            );
            break;
          case 'author':
            $constraints[] = $query->logicalAnd(
              $query->logicalOr(
                $query->like('authors.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('authors.firstName', ''.$settings['searchstr'].'%'),
                $query->like('authors.firstName', '%'.$settings['searchstr'].''),
                $query->like('authors.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('authors.lastName', ''.$settings['searchstr'].'%'),
                $query->like('authors.lastName', '%'.$settings['searchstr'].'')
              )
            );
            break;
          case 'keyword':
            $constraints[] = $query->logicalAnd(
              $query->logicalOr(
                $query->like('keywords.name', '%'.$settings['searchstr'].'%'),
                $query->like('keywords.name', ''.$settings['searchstr'].'%'),
                $query->like('keywords.name', '%'.$settings['searchstr'].'')
              )
            );
            break;
          default:
            $constraints[] = $query->logicalAnd(
              $query->logicalOr(
                $query->like('authors.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('authors.firstName', ''.$settings['searchstr'].'%'),
                $query->like('authors.firstName', '%'.$settings['searchstr'].''),
                $query->like('authors.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('authors.lastName', ''.$settings['searchstr'].'%'),
                $query->like('authors.lastName', '%'.$settings['searchstr'].''),
                $query->like('editors.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('editors.firstName', ''.$settings['searchstr'].'%'),
                $query->like('editors.firstName', '%'.$settings['searchstr'].''),
                $query->like('editors.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('editors.lastName', ''.$settings['searchstr'].'%'),
                $query->like('editors.lastName', '%'.$settings['searchstr'].''),
                $query->like('othersInvolved.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('othersInvolved.firstName', ''.$settings['searchstr'].'%'),
                $query->like('othersInvolved.firstName', '%'.$settings['searchstr'].''),
                $query->like('othersInvolved.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('othersInvolved.lastName', ''.$settings['searchstr'].'%'),
                $query->like('othersInvolved.lastName', '%'.$settings['searchstr'].''),
                $query->like('collaborators.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('collaborators.firstName', ''.$settings['searchstr'].'%'),
                $query->like('collaborators.firstName', '%'.$settings['searchstr'].''),
                $query->like('collaborators.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('collaborators.lastName', ''.$settings['searchstr'].'%'),
                $query->like('collaborators.lastName', '%'.$settings['searchstr'].''),
                $query->like('organizations.firstName', '%'.$settings['searchstr'].'%'),
                $query->like('organizations.firstName', ''.$settings['searchstr'].'%'),
                $query->like('organizations.firstName', '%'.$settings['searchstr'].''),
                $query->like('organizations.lastName', '%'.$settings['searchstr'].'%'),
                $query->like('organizations.lastName', ''.$settings['searchstr'].'%'),
                $query->like('organizations.lastName', '%'.$settings['searchstr'].''),
                $query->like('title', '%'.$settings['searchstr'].'%'),
                $query->like('title', ''.$settings['searchstr'].'%'),
                $query->like('title', '%'.$settings['searchstr'].''),
                $query->like('abstract', '%'.$settings['searchstr'].'%'),
                $query->like('abstract', ''.$settings['searchstr'].'%'),
                $query->like('abstract', '%'.$settings['searchstr'].''),
                $query->like('customField1', '%'.$settings['searchstr'].'%'),
                $query->like('customField1', ''.$settings['searchstr'].'%'),
                $query->like('customField1', '%'.$settings['searchstr'].''),
                $query->like('customField2', '%'.$settings['searchstr'].'%'),
                $query->like('customField2', ''.$settings['searchstr'].'%'),
                $query->like('customField2', '%'.$settings['searchstr'].''),
                $query->like('customField3', '%'.$settings['searchstr'].'%'),
                $query->like('customField3', ''.$settings['searchstr'].'%'),
                $query->like('customField3', '%'.$settings['searchstr'].''),
                $query->like('customField4', '%'.$settings['searchstr'].'%'),
                $query->like('customField4', ''.$settings['searchstr'].'%'),
                $query->like('customField4', '%'.$settings['searchstr'].''),
                $query->like('customField5', '%'.$settings['searchstr'].'%'),
                $query->like('customField5', ''.$settings['searchstr'].'%'),
                $query->like('customField5', '%'.$settings['searchstr'].''),
                $query->like('customField6', '%'.$settings['searchstr'].'%'),
                $query->like('customField6', ''.$settings['searchstr'].'%'),
                $query->like('customField6', '%'.$settings['searchstr'].''),
                $query->like('customField7', '%'.$settings['searchstr'].'%'),
                $query->like('customField7', ''.$settings['searchstr'].'%'),
                $query->like('customField7', '%'.$settings['searchstr'].''),
                $query->like('customField8', '%'.$settings['searchstr'].'%'),
                $query->like('customField8', ''.$settings['searchstr'].'%'),
                $query->like('customField8', '%'.$settings['searchstr'].''),
                $query->like('customField9', '%'.$settings['searchstr'].'%'),
                $query->like('customField9', ''.$settings['searchstr'].'%'),
                $query->like('customField9', '%'.$settings['searchstr'].''),
                $query->like('subtitle', '%'.$settings['searchstr'].'%'),
                $query->like('subtitle', ''.$settings['searchstr'].'%'),
                $query->like('subtitle', '%'.$settings['searchstr'].''),
                $query->like('titleSupplement', '%'.$settings['searchstr'].'%'),
                $query->like('titleSupplement', ''.$settings['searchstr'].'%'),
                $query->like('titleSupplement', '%'.$settings['searchstr'].''),
                $query->like('bookYear', '%'.$settings['searchstr'].'%'),
                $query->like('bookYear', ''.$settings['searchstr'].'%'),
                $query->like('bookYear', '%'.$settings['searchstr'].''),
                $query->like('placeOfPublication', '%'.$settings['searchstr'].'%'),
                $query->like('placeOfPublication', ''.$settings['searchstr'].'%'),
                $query->like('placeOfPublication', '%'.$settings['searchstr'].''),
                $query->like('edition', '%'.$settings['searchstr'].'%'),
                $query->like('edition', ''.$settings['searchstr'].'%'),
                $query->like('edition', '%'.$settings['searchstr'].''),
                $query->like('iSBN', '%'.$settings['searchstr'].'%'),
                $query->like('iSBN', ''.$settings['searchstr'].'%'),
                $query->like('iSBN', '%'.$settings['searchstr'].''),
                $query->like('bookNote', '%'.$settings['searchstr'].'%'),
                $query->like('bookNote', ''.$settings['searchstr'].'%'),
                $query->like('bookNote', '%'.$settings['searchstr'].''),
                $query->like('parallelTitle', '%'.$settings['searchstr'].'%'),
                $query->like('parallelTitle', ''.$settings['searchstr'].'%'),
                $query->like('parallelTitle', '%'.$settings['searchstr'].''),
                $query->like('titleInOtherLanguages', '%'.$settings['searchstr'].'%'),
                $query->like('titleInOtherLanguages', ''.$settings['searchstr'].'%'),
                $query->like('titleInOtherLanguages', '%'.$settings['searchstr'].''),
                $query->like('translatedTitle', '%'.$settings['searchstr'].'%'),
                $query->like('translatedTitle', ''.$settings['searchstr'].'%'),
                $query->like('translatedTitle', '%'.$settings['searchstr'].''),
                $query->like('evaluation', '%'.$settings['searchstr'].'%'),
                $query->like('evaluation', ''.$settings['searchstr'].'%'),
                $query->like('evaluation', '%'.$settings['searchstr'].''),
                $query->like('shortTitle', '%'.$settings['searchstr'].'%'),
                $query->like('shortTitle', ''.$settings['searchstr'].'%'),
                $query->like('shortTitle', '%'.$settings['searchstr'].''),
                $query->like('tableOfContents', '%'.$settings['searchstr'].'%'),
                $query->like('tableOfContents', ''.$settings['searchstr'].'%'),
                $query->like('tableOfContents', '%'.$settings['searchstr'].'')
              )
            );
        }
      }
      
      if($settings['searchReferencetype'] > -1 && !empty($settings['searchReferencetype']) && $settings['searchReferencetype'] != '') {
        if($settings['searchReferencetype'] == 'JournalArticlepeer-reviewed') {
          $constraints[] = $query->like('referenceType', 'JournalArticle');
          $constraints[] = $query->like('customField1', 'peer-reviewed');
        } else if($settings['searchReferencetype'] == 'JournalArticle') {
          $constraints[] = $query->like('referenceType', $settings['searchReferencetype']);
          $constraints[] = $query->logicalNot(
            $query->like('customField1', 'peer-reviewed')
          );
        } else if($settings['searchReferencetype'] == 'ContributionBookEdited') {
          $constraints[] = $query->like('referenceType', 'Contribution');
          $constraints[] = $query->like('parentReferenceType', 'BookEdited');
        } else if($settings['searchReferencetype'] == 'ContributionConferenceProceedings') {
          $constraints[] = $query->like('referenceType', 'Contribution');
          $constraints[] = $query->like('parentReferenceType', 'ConferenceProceedings');
        } else {
          $constraints[] = $query->like('referenceType', $settings['searchReferencetype']);
        }
      }
      
      $selectedauthor = explode(",", $settings['selectedauthor']);
      if($selectedauthor[0] == '') unset($selectedauthor);
      if ( is_array( $selectedauthor ) ) {  
        $numberOfSelectedauthor = count($selectedauthor);
        if($numberOfSelectedauthor > 0) {
          foreach($selectedauthor as $key => $value) {
            $author[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedauthor; $i++) {
            $authorConstraints[] = $query->contains('authors', $author[$i]);
          }
          $constraints[] = $query->logicalOr(
            $authorConstraints
          );      
        }
      }
      $selectedpublisher = explode(",", $settings['selectedpublisher']);
      if($selectedpublisher[0] == '') unset($selectedpublisher);
      if ( is_array( $selectedpublisher ) ) {
        $numberOfSelectedpublisher = count($selectedpublisher);
        if($numberOfSelectedpublisher > 0) {
          foreach($selectedpublisher as $key => $value) {
            $publisher[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedpublisher; $i++) {
            $publisherConstraints[] = $query->contains('publishers', $publisher[$i]);
          }
          $constraints[] = $query->logicalOr(
            $publisherConstraints
          );
        }
      }
      $selectedreferencetype = explode(",", $settings['selectedreferencetype']);
      if($selectedreferencetype[0] == '') unset($selectedreferencetype);
      if ( is_array( $selectedreferencetype ) ) {
        $numberOfSelectedreferencetype = count($selectedreferencetype);
        if($numberOfSelectedreferencetype > 0) {
          foreach($selectedreferencetype as $key => $value) {
            $referencetype[$key] = $value;  
          }
          for($i = 0; $i < $numberOfSelectedreferencetype; $i++) {
            if($referencetype[$i] != '') {
              $referenceTypeConstraints[] = $query->like('referenceType', $referencetype[$i]);
            }
          }
          $constraints[] = $query->logicalOr(
            $referenceTypeConstraints
          );      
        }
      }
      $selectedkeyword = explode(",", $settings['selectedkeyword']);
      if($selectedkeyword[0] == '') unset($selectedkeyword);
      if ( is_array( $selectedkeyword ) ) {
        $numberOfSelectedkeyword = count($selectedkeyword);
        if($numberOfSelectedkeyword > 0) {
          foreach($selectedkeyword as $key => $value) {
            $keyword[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedkeyword; $i++) {
            $keywordsConstraints[] = $query->contains('keywords', $keyword[$i]);
          }
          $constraints[] = $query->logicalOr(
            $keywordsConstraints
          );
        }
      }
      $subcategories = $this->getSubCategories(explode(",", $settings['selectedcategory']), explode(",", $settings['selectedcategory']), count(explode(",", $settings['selectedcategory'])));
      if($subcategories) {
        $selectedcategory = $subcategories;
      } else {
        $selectedcategory = explode(",", $settings['selectedcategory']);
      }
      if($selectedcategory[0] == '') unset($selectedcategory);
      if ( is_array( $selectedcategory ) ) {
        $numberOfSelectedcategory = count($selectedcategory);
        if($numberOfSelectedcategory > 0) {
          foreach($selectedcategory as $key => $value) {
            $category[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedcategory; $i++) {
            $categoryConstraints[] = $query->contains('categories', $category[$i]);
          }
          $constraints[] = $query->logicalOr(
            $categoryConstraints
          );
        }
      }
      
      $selectedCategories = explode(",", $settings['searchCategories']);
      if($selectedCategories[0] == '' || $selectedCategories[0] == '-1') unset($selectedCategories);
      if ( is_array( $selectedCategories ) ) {
        $numberOfSelectedCategories = count($selectedCategories);
        if($numberOfSelectedCategories > 0) {
          foreach($selectedCategories as $key => $value) {
            $category[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedCategories; $i++) {
            $categoriesConstraints[] = $query->contains('categories', $category[$i]);
          }
          $constraints[] = $query->logicalOr(
            $categoriesConstraints
          );
        }
      }
      
      $selectedPersons = explode(",", $settings['searchPersons']);
      if($selectedPersons[0] == '' || $selectedPersons[0] == '-1') unset($selectedPersons);
      if ( is_array( $selectedPersons ) ) {
        $numberOfSelectedPersons = count($selectedPersons);
        if($numberOfSelectedPersons > 0) {
          foreach($selectedPersons as $key => $value) {
            $person[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedPersons; $i++) {
            $personsConstraints[] = $query->contains('authors', $person[$i]);
          }
          $constraints[] = $query->logicalOr(
            $personsConstraints
          );
        }
      }
      
      $selectedEditors = explode(",", $settings['searchEditors']);
      if($selectedEditors[0] == '' || $selectedEditors[0] == '-1') unset($selectedEditors);
      if ( is_array( $selectedEditors ) ) {
        $numberOfSelectedEditors = count($selectedEditors);
        if($numberOfSelectedEditors > 0) {
          foreach($selectedEditors as $key => $value) {
            $editor[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedEditors; $i++) {
            $editorsConstraints[] = $query->contains('editors', $editor[$i]);
          }
          $constraints[] = $query->logicalOr(
            $editorsConstraints
          );
        }
      }
      
      $selectedAuthors = explode(",", $settings['searchAuthors']);
      if($selectedAuthors[0] == '' || $selectedAuthors[0] == '-1') unset($selectedAuthors);
      if ( is_array( $selectedAuthors ) ) {
        $numberOfSelectedAuthors = count($selectedAuthors);
        if($numberOfSelectedAuthors > 0) {
          foreach($selectedAuthors as $key => $value) {
            $author[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSelectedAuthors; $i++) {
            $authorsConstraints[] = $query->contains('authors', $author[$i]);
          }
          $constraints[] = $query->logicalOr(
            $authorsConstraints
          );
        }
      }
      
      $selectedSpecialcategories = explode(",", $settings['searchSpecialcategories']);
      if($selectedSpecialcategories[0] == '' || $selectedSpecialcategories[0] == '-1') unset($selectedSpecialcategories);
      if ( is_array( $selectedSpecialcategories ) ) {
        $numberOfSpecialcategories = count($selectedSpecialcategories);
        if($numberOfSpecialcategories > 0) {
          foreach($selectedSpecialcategories as $key => $value) {
            $specialcategory[$key] = (int)$value;  
          }
          for($i = 0; $i < $numberOfSpecialcategories; $i++) {
            $specialcategoriesConstraints[] = $query->contains('categories', $specialcategory[$i]);
          }
          $constraints[] = $query->logicalOr(
            $specialcategoriesConstraints
          );
        }
      }
      
      if((int)$settings['searchYearfrom'] > 0 && (int)$settings['searchYearto'] > 0) {
        $constraints[] = $query->logicalAnd(
          $query->logicalOr(
            $query->logicalAnd(
              $query->greaterThanOrEqual('bookDate', (int)$settings['searchYearfrom']),
              $query->lessThanOrEqual('bookDate', (int)$settings['searchYearto'])
            ),
            $query->logicalAnd(
              $query->greaterThanOrEqual('bookYear', (int)$settings['searchYearfrom']),
              $query->lessThanOrEqual('bookYear', (int)$settings['searchYearto'])
            )
          )
        );
      } else if((int)$settings['searchYearfrom'] > 0 && (int)$settings['searchYearto'] == -1) {
        $constraints[] = $query->logicalAnd(
          $query->logicalOr(
            $query->greaterThanOrEqual('bookDate', (int)$settings['searchYearfrom']),
            $query->greaterThanOrEqual('bookYear', (int)$settings['searchYearfrom'])
          )
        );
      } else if((int)$settings['searchYearfrom'] == -1 && (int)$settings['searchYearto'] > 0) {
        $constraints[] = $query->logicalAnd(
          $query->logicalOr(
            $query->logicalAnd(
              $query->lessThanOrEqual('bookDate', (int)$settings['searchYearto']),
              $query->greaterThan('bookDate', 0)
            ),
            $query->logicalAnd(
              $query->lessThanOrEqual('bookYear', (int)$settings['searchYearto']),
              $query->greaterThan('bookYear', 0)
            )
          )
        );
      }
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($constraints);
      $notConstraints = $this->getNotConstraints($query, $settings);
      if($notConstraints) {
        $constraints[] = $notConstraints;
      }            
      
      return $constraints;
    }
    
    public function getNotConstraints($query, $settings) {
      $referencetype = $settings['referencetype']['ignore'];
      if ( is_array( $referencetype ) ) {
        $numberOfIgnoredreferencetype = count($referencetype);      
        if($numberOfIgnoredreferencetype > 0) {        
          for($i = 0; $i < $numberOfIgnoredreferencetype; $i++) {          
            if($referencetype[$i] != '') {
              $notconstraints[] = $query->logicalNot($query->like('referenceType', $referencetype[$i]));
            }
          }
        }
        if ( is_array( $notconstraints ) ) {
          if(count($notconstraints) > 0) {        
            $constraints = $query->logicalAnd($notconstraints);
          }
        }
      }
      //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($constraints);
      
      return $constraints;
    }
    
    public function getSubCategories($categories, array $subcategories = [], $count = 0) {
      $count = count($subcategories);
      if(is_array($categories)) {
        foreach($categories as $category) {
          $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nwcitavi_category_category_mm');
          $statement = $queryBuilder
             ->select('uid_local')
             ->from('tx_nwcitavi_category_category_mm')
             ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($category))
             )
             ->execute();
          while ($row = $statement->fetch()) {
             $subcategories[$count] = $row['uid_local'];             
             $uid[0] = $row['uid_local'];
             $subcategories = $this->getSubCategories($uid, $subcategories, $count);
             $count = count($subcategories);
          }
        }
      } else {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nwcitavi_category_category_mm');
        $statement = $queryBuilder
           ->select('uid_local')
           ->from('tx_nwcitavi_category_category_mm')
           ->where(
              $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($categories))
           )
           ->execute();
        while ($row = $statement->fetch()) {
           $subcategories[$count] = $row['uid_local'];           
           $uid[0] = $row['uid_local'];
           $subcategories = $this->getSubCategories($uid, $subcategories, $count);
           $count = count($subcategories);
        }
      }
      
      return $subcategories;
    }
    
    public function getOrderings($settings) {
      $selectedpublicationsreferences = null;
      if($settings['selectedpublicationsreferences'] > -1 || $settings['selectedpublicationsreferences'] != '') {
        $selectedpublicationsreferences = explode(",", $settings['selectedpublicationsreferences']);
        if($selectedpublicationsreferences[0] == '') {
          unset($selectedpublicationsreferences);
        }
      }
       
      if((int)$settings['selectedprofessor'] > 0 && (int)$settings['sortbyprofessor'] == 1) {
        if(!empty($settings['sorting'])) {
          $ordering = array(
            'custom_field7, \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_DESCENDING,
            'reference_type, '.$sort.'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
            'parentReferenceType' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            'customField1' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,          
            'sortDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
          );
        } else {
          $ordering = array(
            'custom_field7, \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_DESCENDING,
            'reference_type, \'JournalArticle\', \'Book\', \'Contribution\', \'Unknown\', \'SpecialIssue\', \'UnpublishedWork\', \'ConferenceProceedings\', \'BookEdited\', \'Lecture\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
            'parentReferenceType' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            'customField1' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,          
            'sortDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
          );
        }
      } else if(is_array($selectedpublicationsreferences)) {      
        $sorting = '';
        $i = 0;
        foreach($selectedpublicationsreferences as $selectedpublicationsreference) {
          if($i == 0) {
            $sorting = $selectedpublicationsreference;
          } else {
            $sorting .= ', '.$selectedpublicationsreference;
          }
          $i++; 
        }      
        $ordering = array(
          'uid, '.$sorting =>  \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING
        );
      } else {
        if(!empty($settings['sorting'])) {
          $ordering = array(
            'reference_type, '.$sort.'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
            'parentReferenceType' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            'customField1' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,          
            'sortDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
          );
        } else {
          $ordering = array(
            'reference_type, \'JournalArticle\', \'Book\', \'Contribution\', \'Unknown\', \'SpecialIssue\', \'UnpublishedWork\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
            'parentReferenceType' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            'customField1' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,          
            'sortDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
          );
        }
      }
      
      return $ordering;
    }
    
    /**
  	 * action importExport
  	 * @return \string JSON
  	 */
  	public function importExport($uniqid) {
      $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
      file_put_contents($this->dir.'/log/import.txt', '$_FILES: '.serialize($_FILES), FILE_APPEND);
      file_put_contents($this->dir.'/log/import.txt', '$_POST: '.serialize($_POST), FILE_APPEND);
      $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/export/');
      if(is_writeable($this->dir)) {
        if ($_FILES['file'] && $_POST['import_key']) {
          $this->fileProcessor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\ExtendedFileUtility');
     
    		  $fileName = $this->fileProcessor->getUniqueName(
    			  $_FILES['file']['name'],
            $this->dir
          );
          
          $upload = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move(
            $_FILES['file']['tmp_name'],
            $this->dir.$_FILES['file']['name']);
            
          $zip = new \ZipArchive;
          if ($zip->open($fileName) === TRUE) {
            $res = $zip->extractTo($this->dir);
            $zip->close();
            if($res) {
              unlink($fileName);
              return 1;
            }
          } else {
            $statusCodeText = $this->getStatusCodeText(418);
            $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi Export Upload]: Upload was terminated.', ''.$_POST['import_key'].'');
            $this->getStatusCode(418);
          }
        } else {
          $statusCodeText = $this->getStatusCodeText(417);
          $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi Export Upload]: Upload was terminated.', ''.$_POST['import_key'].'');
          $this->getStatusCode(417);
          exit;
        } 
      } else {
        $statusCodeText = $this->getStatusCodeText(432);
        $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi Export Upload]: Upload was terminated.', ''.$_POST['import_key'].'');
        $this->getStatusCode(432);
        exit;
      }
    }
    
    public function checkIfEntryExists($table, $field, $valueObj) {
      $value = (array)$valueObj;
      $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
  		  '*',
  			$table,
  			$field.' LIKE \''.$value[0].'\'',
  			'',
  			'',
  			''
  		);
  		$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
  		
  		return $data;  
    }
    
    /**
  	 * action importFile
  	 * @return \string JSON
  	 */
  	public function importFile($uniqid, $logRepository, $settings) {
      $this->dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/files/');
      if(is_writeable($this->dir)) {
        if ($_FILES['file'] && $_POST['import_key']) {
          if ($settings['scheduler'] == 1) {            
            $originalFilePath = $_FILES['file']['tmp_name'];
            $newFileName = $_FILES['file']['name'];
            $localDriver = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\Driver\\LocalDriver');                    
            if (!file_exists($this->dir.$localDriver->sanitizeFileName($newFileName))) {
              $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');              
              $storage = $storageRepository->findByUid((int)$settings['fileStoragePid']);
              $targetFolder = $storage->getFolder('files');
                          
      
              if (file_exists($originalFilePath)) {
                  $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
                  $newFileReference = $this->objectManager->get('Netzweber\\NwCitavi\\Domain\\Model\\FileReference');
                  $newFileReference->setFile($movedNewFile);                
              }
    
              $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
              $persistenceManager->persistAll();
       
              file_put_contents($this->dir.'/files.txt', $_POST['referenceId'].'|'.$movedNewFile->getUid().'|'.$_POST['fileType'].chr(13).chr(10), FILE_APPEND);
            }
            
            return true;          
          } else {
            $referenceQueryResult = $this->findByCitaviId($_POST['referenceId']);
            $reference = $referenceQueryResult[0];
            if($reference) {
              if($_POST['fileType'] == 'cover') {
                $originalFilePath = $_FILES['file']['tmp_name'];
                $newFileName = $_FILES['file']['name'];
                $localDriver = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\Driver\\LocalDriver');          
                if (!file_exists($this->dir.$localDriver->sanitizeFileName($newFileName))) {
                  $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
                  $storage = $storageRepository->findByUid('3');
                  $targetFolder = $storage->getFolder('files');
          
                  if (file_exists($originalFilePath)) {
                      $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
                      $newFileReference = $this->objectManager->get('Netzweber\\NwCitavi\\Domain\\Model\\FileReference');
                      $newFileReference->setFile($movedNewFile);
                      $reference->setCover($newFileReference);
                  }
                  $this->update($reference);
        
                  $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
                  $persistenceManager->persistAll();
                }
              }
              
              if($_POST['fileType'] == 'attachment') {
                $originalFilePath = $_FILES['file']['tmp_name'];
                $newFileName = $_FILES['file']['name'];
                $localDriver = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\Driver\\LocalDriver');          
                if (!file_exists($this->dir.$localDriver->sanitizeFileName($newFileName))) {
                  $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
                  $storage = $storageRepository->findByUid('3');
                  $targetFolder = $storage->getFolder('files');
          
                  if (file_exists($originalFilePath)) {
                      $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
                      $newFileReference = $this->objectManager->get('Netzweber\\NwCitavi\\Domain\\Model\\FileReference');
                      $newFileReference->setFile($movedNewFile);
                      if($newFileReference != null) {
                        $reference->setAttachment($newFileReference);
                      }
                  }
                  $this->update($reference);
        
                  $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
                  $persistenceManager->persistAll();
                }
              }
            }        
            return true;
          }
        } else {
          $statusCodeText = $this->getStatusCodeText(417);
          $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
          $this->getStatusCode(417);
          exit;
        } 
      } else {
        $statusCodeText = $this->getStatusCodeText(432);
        $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqid.'', '[Citavi Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
        $this->getStatusCode(432);
        exit;
      }
    }
    
    public function findAllReferenceTypOptions() {
      $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
  		  'reference_type',
  			'tx_nwcitavi_domain_model_reference',
  			'deleted = 0 AND hidden = 0',
  			'reference_type',
  			'reference_type',
  			''
  		);
      if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
        $i = 0;                             
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
          $options[$i]['id'] = $row['reference_type'];
          $options[$i]['title'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_nwcitavi_domain_model_reference.'.$row['reference_type'], 'nw_citavi_fe');          
          $i++;              
        }
      }
      foreach ($options as $key => $row) {
          $option[$key] = $row['title'];
      }
      array_multisort($option, SORT_ASC, $options);
      
      return $options;   
    }
    
    public function displayAdvancedSearch($settings) {
      $res = false;
      if($settings['searchYearfrom'] && $settings['searchYearfrom'] != '-1') $res = true; 
      if($settings['searchYearto'] && $settings['searchYearto'] != '-1') $res = true;
      if($settings['searchReferencetype'] && $settings['searchReferencetype'] != '-1') $res = true;
      if($settings['searchCategories'] && $settings['searchCategories'] != '-1') $res = true;
      if($settings['searchKeywords'] && $settings['searchKeywords'] != '-1') $res = true;
      if($settings['searchKnowledgeitems'] && $settings['searchKnowledgeitems'] != '-1') $res = true;
      if($settings['searchLibraries'] && $settings['searchLibraries'] != '-1') $res = true;
      if($settings['searchLocations'] && $settings['searchLocations'] != '-1') $res = true;
      if($settings['searchPeriodicals'] && $settings['searchPeriodicals'] != '-1') $res = true;
      if($settings['searchPersons'] && $settings['searchPersons'] != '-1') $res = true;
      if($settings['searchEditors'] && $settings['searchEditors'] != '-1') $res = true;
      if($settings['searchAuthors'] && $settings['searchAuthors'] != '-1') $res = true;
      if($settings['searchPublishers'] && $settings['searchPublishers'] != '-1') $res = true;
      if($settings['searchSeriestitles'] && $settings['searchSeriestitles'] != '-1') $res = true;
      if($settings['searchSpecialcategories'] && $settings['searchSpecialcategories'] != '-1') $res = true;
      
      return $res;    
    }
    
    public function getRootpage($objectManager) {
      $pageRepository = $objectManager->get('TYPO3\CMS\Frontend\Page\PageRepository');  
      $pageRepository->init(FALSE); 
      $defaultPageIds = \array_keys($pageRepository->getMenu(0, 'uid'));
      return $defaultPageIds[0];
    }
    
    /**
    * Render the generated SQL of a query in TYPO3 8
    * 
    * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
    * @param bool $format
    * @param bool $exit
    */
    private function debugQuery($query, $format = true, $exit = true)
    {
      function getFormattedSQL($sql_raw) { if (empty($sql_raw) || !is_string($sql_raw)) { return false; } $sql_reserved_all = array( 'ACCESSIBLE', 'ACTION', 'ADD', 'AFTER', 'AGAINST', 'AGGREGATE', 'ALGORITHM', 'ALL', 'ALTER', 'ANALYSE', 'ANALYZE', 'AND', 'AS', 'ASC', 'AUTOCOMMIT', 'AUTO_INCREMENT', 'AVG_ROW_LENGTH', 'BACKUP', 'BEGIN', 'BETWEEN', 'BINLOG', 'BOTH', 'BY', 'CASCADE', 'CASE', 'CHANGE', 'CHANGED', 'CHARSET', 'CHECK', 'CHECKSUM', 'COLLATE', 'COLLATION', 'COLUMN', 'COLUMNS', 'COMMENT', 'COMMIT', 'COMMITTED', 'COMPRESSED', 'CONCURRENT', 'CONSTRAINT', 'CONTAINS', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_TIMESTAMP', 'DATABASE', 'DATABASES', 'DAY', 'DAY_HOUR', 'DAY_MINUTE', 'DAY_SECOND', 'DEFINER', 'DELAYED', 'DELAY_KEY_WRITE', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DO', 'DROP', 'DUMPFILE', 'DUPLICATE', 'DYNAMIC', 'ELSE', 'ENCLOSED', 'END', 'ENGINE', 'ENGINES', 'ESCAPE', 'ESCAPED', 'EVENTS', 'EXECUTE', 'EXISTS', 'EXPLAIN', 'EXTENDED', 'FAST', 'FIELDS', 'FILE', 'FIRST', 'FIXED', 'FLUSH', 'FOR', 'FORCE', 'FOREIGN', 'FROM', 'FULL', 'FULLTEXT', 'FUNCTION', 'GEMINI', 'GEMINI_SPIN_RETRIES', 'GLOBAL', 'GRANT', 'GRANTS', 'GROUP', 'HAVING', 'HEAP', 'HIGH_PRIORITY', 'HOSTS', 'HOUR', 'HOUR_MINUTE', 'HOUR_SECOND', 'IDENTIFIED', 'IF', 'IGNORE', 'IN', 'INDEX', 'INDEXES', 'INFILE', 'INNER', 'INSERT', 'INSERT_ID', 'INSERT_METHOD', 'INTERVAL', 'INTO', 'INVOKER', 'IS', 'ISOLATION', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LAST_INSERT_ID', 'LEADING', 'LEFT', 'LEVEL', 'LIKE', 'LIMIT', 'LINEAR', 'LINES', 'LOAD', 'LOCAL', 'LOCK', 'LOCKS', 'LOGS', 'LOW_PRIORITY', 'MARIA', 'MASTER', 'MASTER_CONNECT_RETRY', 'MASTER_HOST', 'MASTER_LOG_FILE', 'MASTER_LOG_POS', 'MASTER_PASSWORD', 'MASTER_PORT', 'MASTER_USER', 'MATCH', 'MAX_CONNECTIONS_PER_HOUR', 'MAX_QUERIES_PER_HOUR', 'MAX_ROWS', 'MAX_UPDATES_PER_HOUR', 'MAX_USER_CONNECTIONS', 'MEDIUM', 'MERGE', 'MINUTE', 'MINUTE_SECOND', 'MIN_ROWS', 'MODE', 'MODIFY', 'MONTH', 'MRG_MYISAM', 'MYISAM', 'NAMES', 'NATURAL', 'NOT', 'NULL', 'OFFSET', 'ON', 'OPEN', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUTER', 'OUTFILE', 'PACK_KEYS', 'PAGE', 'PARTIAL', 'PARTITION', 'PARTITIONS', 'PASSWORD', 'PRIMARY', 'PRIVILEGES', 'PROCEDURE', 'PROCESS', 'PROCESSLIST', 'PURGE', 'QUICK', 'RAID0', 'RAID_CHUNKS', 'RAID_CHUNKSIZE', 'RAID_TYPE', 'RANGE', 'READ', 'READ_ONLY', 'READ_WRITE', 'REFERENCES', 'REGEXP', 'RELOAD', 'RENAME', 'REPAIR', 'REPEATABLE', 'REPLACE', 'REPLICATION', 'RESET', 'RESTORE', 'RESTRICT', 'RETURN', 'RETURNS', 'REVOKE', 'RIGHT', 'RLIKE', 'ROLLBACK', 'ROW', 'ROWS', 'ROW_FORMAT', 'SECOND', 'SECURITY', 'SELECT', 'SEPARATOR', 'SERIALIZABLE', 'SESSION', 'SET', 'SHARE', 'SHOW', 'SHUTDOWN', 'SLAVE', 'SONAME', 'SOUNDS', 'SQL', 'SQL_AUTO_IS_NULL', 'SQL_BIG_RESULT', 'SQL_BIG_SELECTS', 'SQL_BIG_TABLES', 'SQL_BUFFER_RESULT', 'SQL_CACHE', 'SQL_CALC_FOUND_ROWS', 'SQL_LOG_BIN', 'SQL_LOG_OFF', 'SQL_LOG_UPDATE', 'SQL_LOW_PRIORITY_UPDATES', 'SQL_MAX_JOIN_SIZE', 'SQL_NO_CACHE', 'SQL_QUOTE_SHOW_CREATE', 'SQL_SAFE_UPDATES', 'SQL_SELECT_LIMIT', 'SQL_SLAVE_SKIP_COUNTER', 'SQL_SMALL_RESULT', 'SQL_WARNINGS', 'START', 'STARTING', 'STATUS', 'STOP', 'STORAGE', 'STRAIGHT_JOIN', 'STRING', 'STRIPED', 'SUPER', 'TABLE', 'TABLES', 'TEMPORARY', 'TERMINATED', 'THEN', 'TO', 'TRAILING', 'TRANSACTIONAL', 'TRUNCATE', 'TYPE', 'TYPES', 'UNCOMMITTED', 'UNION', 'UNIQUE', 'UNLOCK', 'UPDATE', 'USAGE', 'USE', 'USING', 'VALUES', 'VARIABLES', 'VIEW', 'WHEN', 'WHERE', 'WITH', 'WORK', 'WRITE', 'XOR', 'YEAR_MONTH' ); $sql_skip_reserved_words = array('AS', 'ON', 'USING'); $sql_special_reserved_words = array('(', ')'); $sql_raw = str_replace("\n", " ", $sql_raw); $sql_formatted = ""; $prev_word = ""; $word = ""; for ($i = 0, $j = strlen($sql_raw); $i < $j; $i++) { $word .= $sql_raw[$i]; $word_trimmed = trim($word); if ($sql_raw[$i] == " " || in_array($sql_raw[$i], $sql_special_reserved_words)) { $word_trimmed = trim($word); $trimmed_special = false; if (in_array($sql_raw[$i], $sql_special_reserved_words)) { $word_trimmed = substr($word_trimmed, 0, -1); $trimmed_special = true; } $word_trimmed = strtoupper($word_trimmed); if (in_array($word_trimmed, $sql_reserved_all) && !in_array($word_trimmed, $sql_skip_reserved_words)) { if (in_array($prev_word, $sql_reserved_all)) { $sql_formatted .= '<b>' . strtoupper(trim($word)) . '</b>' . '&nbsp;'; } else { $sql_formatted .= '<br/>&nbsp;'; $sql_formatted .= '<b>' . strtoupper(trim($word)) . '</b>' . '&nbsp;'; } $prev_word = $word_trimmed; $word = ""; } else { $sql_formatted .= trim($word) . '&nbsp;'; $prev_word = $word_trimmed; $word = ""; } } } $sql_formatted .= trim($word); return $sql_formatted; } $queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class); $preparedStatement = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL(); $parameters = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters(); $stringParams = []; foreach ($parameters as $key => $parameter) { $stringParams[':' . $key] = $parameter; } $statement = strtr($preparedStatement, $stringParams); if ($format) { echo '<code>' . getFormattedSQL($statement) . '</code>'; } else { echo $statement; } if ($exit) { exit; }
    }  
}