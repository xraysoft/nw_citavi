<?php
namespace Netzweber\NwCitavi\Domain\Repository;

use Netzweber\NwCitavi\Domain\Model\Category;
use Netzweber\NwCitavi\Domain\Model\Keyword;
use Netzweber\NwCitavi\Domain\Model\KnowledgeItem;
use Netzweber\NwCitavi\Domain\Model\Library;
use Netzweber\NwCitavi\Domain\Model\Location;
use Netzweber\NwCitavi\Domain\Model\Periodical;
use Netzweber\NwCitavi\Domain\Model\Person;
use Netzweber\NwCitavi\Domain\Model\Publisher;
use Netzweber\NwCitavi\Domain\Model\Reference;
use Netzweber\NwCitavi\Domain\Model\Seriestitle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\File\ExtendedFileUtility;
use ZipArchive;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Netzweber\NwCitavi\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Netzweber\NwCitavi\Domain\Repository\KeywordRepository;
use Netzweber\NwCitavi\Domain\Repository\LibraryRepository;
use Netzweber\NwCitavi\Domain\Repository\PeriodicalRepository;
use Netzweber\NwCitavi\Domain\Repository\PersonRepository;
use Netzweber\NwCitavi\Domain\Repository\PublisherRepository;
use Netzweber\NwCitavi\Domain\Repository\SeriestitleRepository;
use Netzweber\NwCitavi\Domain\Repository\KnowledgeItemRepository;
use Netzweber\NwCitavi\Domain\Repository\LocationRepository;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Netzweber\NwCitavi\Domain\Model\FileReference;
use TYPO3\CMS\Frontend\Page\PageRepository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

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

class ReferenceRepository extends Repository
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

    protected $hashRepository = NULL;

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sortDate' => QueryInterface::ORDER_DESCENDING
    );

    public function initializeObject() {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Parse complete xml import at once
     *
     * @param $step
     * @param $startTime
     * @param $uniqueId
     * @return int|null
     */
    public function parseXML($step, $startTime, $uniqueId): ?int
    {
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
        $key = $_POST['import_key'];
        $fileInfo = $this->lastModification($dir);
        $fileName = $fileInfo['dir'].$fileInfo['file'];
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi_citavilist.']['settings.'];
        if($step === 99) {
            file_put_contents($dir.'/scheduler.txt', $fileName.'|'.$key.'|'.$uniqueId, FILE_APPEND);
            return 99;
        }
        $xml = new \XMLReader();
        $xml->open($fileName);
        $xmlString = implode('', file($fileName));
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $xmlObj = new \SimpleXMLElement($xmlString);
        switch($step) {
            case 1:
                $startTime = time();
                file_put_contents($dir.'/log/upload.txt', date('d.m.Y H:i:s', $startTime).'|', FILE_APPEND);
                try {
                    if ( is_array( $array['Categories']['Category'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_categoryhash');
                        foreach ($array['Categories']['Category'] as $iValue) {
                            $category = $iValue;
                            if(!empty($category['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($category);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'CategoryHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('CategoryRepository', $category['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                                $this->setDatabase($category, 'Category', $columnExists, $hash, $key, $uniqueId, $settings);
                                if ( is_array( $category['Categories']['Category'] ) ) {
                                    foreach ($category['Categories']['Category'] as $jValue) {
                                        $subcategory = $jValue;
                                        if(!empty($subcategory['@attributes']['ID'])) {
                                            $refStr = $this->generatedHash($subcategory);
                                            $hash = hash('md5', $refStr);
                                            $this->insertInHashTable( 'CategoryHash', $hash, ($settings['sPid']) ?: '0');
                                            $columnExists = $this->columnExists('CategoryRepository', $subcategory['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                                            $this->setDatabase($subcategory, 'Category', $columnExists, $hash, $key, $uniqueId, $settings, $category['@attributes']['ID']);
                                        }
                                        if ( is_array( $subcategory['Categories']['Category'] ) ) {
                                            foreach ($subcategory['Categories']['Category'] as $kValue) {
                                                $subSubCategory = $kValue;
                                                if(!empty($subSubCategory['@attributes']['ID'])) {
                                                    $refStr = $this->generatedHash($subSubCategory);
                                                    $hash = hash('md5', $refStr);
                                                    $this->insertInHashTable( 'CategoryHash', $hash, ($settings['sPid']) ?: '0');
                                                    $columnExists = $this->columnExists('CategoryRepository', $subSubCategory['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');
                                                    $this->setDatabase($subSubCategory, 'Category', $columnExists, $hash, $key, $uniqueId, $settings, $subcategory['@attributes']['ID']);
                                                }
                                            }
                                        }
                                        unset($subSubCategory, $subSubCategoryAttributes, $dbSubSubCat, $subSubCatUid);
                                    }
                                }
                                unset($subcategory, $subCategoryAttributes, $db_subCat, $subCatUid);
                            }
                            unset($category, $categoryAttributes, $db_cat, $catUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Categories could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 2:
                try {
                    if ( is_array( $array['Keywords']['Keyword'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_keywordhash');
                        foreach ($array['Keywords']['Keyword'] as $iValue) {
                            $keyword = $iValue;
                            if(!empty($keyword['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($keyword);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'KeywordHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('KeywordRepository', $keyword['@attributes']['ID'], 'tx_nwcitavi_domain_model_keyword');
                                $this->setDatabase($keyword, 'Keyword', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($keyword, $keywordAttributes, $db_keyword, $keywordUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Keywords could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 3:
                try {
                    if ( is_array( $array['Libraries']['Library'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_libraryhash');
                        foreach ($array['Libraries']['Library'] as $iValue) {
                            $library = $iValue;
                            if(!empty($library['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($library);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'LibraryHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('LibraryRepository', $library['@attributes']['ID'], 'tx_nwcitavi_domain_model_library');
                                $this->setDatabase($library, 'Library', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($library, $libraryAttributes, $db_library, $libraryUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Libraries could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 4:
                try {
                    if ( is_array( $array['Periodicals']['Periodical'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_periodicalhash');
                        foreach ($array['Periodicals']['Periodical'] as $iValue) {
                            $periodical = $iValue;
                            if(!empty($periodical['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($periodical);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'PeriodicalHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('PeriodicalRepository', $periodical['@attributes']['ID'], 'tx_nwcitavi_domain_model_periodical');
                                $this->setDatabase($periodical, 'Periodical', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($periodical, $periodicalAttributes, $db_periodical, $periodicalUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Periodicals could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 5:
                try {
                    if ( is_array( $array['Persons']['Person'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_personhash');
                        foreach ($array['Persons']['Person'] as $iValue) {
                            $person = $iValue;
                            if(!empty($person['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($person);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'PersonHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('PersonRepository', $person['@attributes']['ID'], 'tx_nwcitavi_domain_model_person');
                                $this->setDatabase($person, 'Person', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($person, $personAttributes, $db_person, $personUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Persons could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 6:
                try {
                    if ( is_array( $array['Publishers']['Publisher'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_publisherhash');
                        foreach ($array['Publishers']['Publisher'] as $iValue) {
                            $publisher = $iValue;
                            if(!empty($publisher['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($publisher);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'PublisherHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('PublisherRepository', $publisher['@attributes']['ID'], 'tx_nwcitavi_domain_model_publisher');
                                $this->setDatabase($publisher, 'Publisher', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($publisher, $publisherAttributes, $db_publisher, $publisherUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Publishers could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 7:
                try {
                    if ( is_array( $array['SeriesTitles']['SeriesTitle'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_seriestitlehash');
                        foreach ($array['SeriesTitles']['SeriesTitle'] as $iValue) {
                            $seriesTitle = $iValue;
                            if(!empty($seriesTitle['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($seriesTitle);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'SeriestitleHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('SeriestitleRepository', $seriesTitle['@attributes']['ID'], 'tx_nwcitavi_domain_model_seriestitle');
                                $this->setDatabase($seriesTitle, 'SeriesTitle', $columnExists, $hash, $key, $uniqueId, $settings);
                            }
                            unset($seriesTitle, $seriesTitleAttributes, $dbSeriesTitle, $seriesTitleUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Seriestitles could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 8:
                try {
                    if ( is_array( $array['Thoughts']['KnowledgeItem'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_knowledgeitemhash');
                        foreach ($array['Thoughts']['KnowledgeItem'] as $iValue) {
                        	$knowledgeItem = $iValue;
                        	if(!empty($knowledgeItem['@attributes']['ID'])) {
                        	    $refStr = $this->generatedHash($knowledgeItem);
                        	    $hash = hash('md5', $refStr);
                        	    $this->insertInHashTable( 'KnowledgeItemHash', $hash, ($settings['sPid']) ?: '0');
                        	    $columnExists = $this->columnExists('KnowledgeItemRepository', $knowledgeItem['@attributes']['ID'], 'tx_nwcitavi_domain_model_knowledgeitem');
                        	    $this->setDatabase($knowledgeItem, 'KnowledgeItem', $columnExists, $hash, $key, $uniqueId, $settings);
                        	}
                        	unset($knowledgeItem, $knowledgeItemAttributes, $dbKnowledgeItem, $knowledgeItemUid);
                        }
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Knowledgeitems could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
            case 9:
                try {
                    if ( is_array( $array['References']['Reference'] ) ) {
                        $this->truncateDatabase('tx_nwcitavi_domain_model_referencehash');
                        $this->truncateDatabase('tx_nwcitavi_domain_model_locationhash');
                        foreach ($array['References']['Reference'] as $i => $iValue) {
                            $ref = $iValue;
                            switch($ref['@attributes']['ReferenceType']) {
                                case 'BookEdited':
                                case 'InternetDocument':
                                case 'Book':
                                case 'JournalArticle':
                                case 'ConferenceProceedings':
                                case 'SpecialIssue':
                                    $ref['sortDate'] = $ref['Year'];
                                    break;
                                case 'NewspaperArticle':
                                case 'PersonalCommunication':
                                case 'Unknown':
                                case 'UnpublishedWork':
                                case 'Lecture':
                                case 'PressRelease':
                                case 'Thesis':
                                    $ref['sortDate'] = $ref['Date'];
                                    break;
                                case 'Contribution':
                                    $dbParentRef = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                                    $ref['sortDate'] = $dbParentRef['sort_date'];
                                    break;
                                default:
                                    $ref['sortDate'] = $ref['Date'];
                            }
                            if(!empty($ref['ParentReferenceID'])) {
                                $parent = $xmlObj->xpath("//Reference[@ID='".$ref['ParentReferenceID']."']");
                                $ref['parentReferenceType'] = (string)$parent[0]['ReferenceType'];
                                $ref['dbParentRef'] = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                                $ref['sortDate'] = $dbParentRef['sort_date'];
                            }
                            if(!empty($ref['@attributes']['ID'])) {
                                $refStr = $this->generatedHash($ref);
                                $hash = hash('md5', $refStr);
                                $this->insertInHashTable( 'ReferenceHash', $hash, ($settings['sPid']) ?: '0');
                                $columnExists = $this->columnExists('ReferenceRepository', $ref['@attributes']['ID'], 'tx_nwcitavi_domain_model_reference');
                                $this->setDatabase($ref, 'Reference', $columnExists, $hash, $key, $uniqueId, $settings);
                                // Locations speichern
                                if($iValue['Locations']['Location']['@attributes']) {
                                    $location = $array['References']['Reference'][$i]['Locations']['Location'];
                                    if(!empty($location['@attributes']['ID'])) {
                                        $locationStr = $this->generatedHash($location);
                                        $hash = hash('md5', $locationStr);
                                        $this->insertInHashTable( 'LocationHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                                        $this->setDatabase($location, 'Location', $columnExists, $hash, $key, $uniqueId, $settings);
                                        // Location verknüpfen
                                        $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                                        //Libraries verknüpfen
                                        if(!empty($iValue['Locations']['Location']['LibraryID'])) {
                                            $this->updateLocationLibrary($location['@attributes']['ID'], $iValue['Locations']['Location']['LibraryID']);
                                        }
                                    }
                                    unset($location, $locationAttributes, $db_location, $locationUid);
                                } else {
                                    if ( is_array( $iValue['Locations']['Location'] ) ) {
                                        $locationsCount = count($iValue['Locations']['Location']);
                                        for($j = 0; $j < $locationsCount; $j++) {
                                            $location = $array['References']['Reference'][$i]['Locations']['Location'][$j];
                                            if(!empty($location['@attributes']['ID'])) {
                                                $locationStr = $this->generatedHash($location);
                                                $hash = hash('md5', $locationStr);
                                                $this->insertInHashTable( 'LocationHash', $hash, ($settings['sPid']) ?: '0');
                                                $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                                                $this->setDatabase($location, 'Location', $columnExists, $hash, $key, $uniqueId, $settings);
                                                // Location verknüpfen
                                                $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                                                //Libraries verknüpfen
                                                if(!empty($iValue['Locations']['Location'][$j]['LibraryID'])) {
                                                    $this->updateLocationLibrary($location['@attributes']['ID'], $iValue['Locations']['Location'][$j]['LibraryID']);
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
                    $this->logRepository->addLog(1, 'References could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                unlink($fileName);
                $endTime = time();
                file_put_contents($dir.'/log/upload.txt', date('d.m.Y H:i:s', $endTime).chr(13).chr(10), FILE_APPEND);
                $to = $settings['email'];
                $html = '<p>Der Scheduler wurde um '.date('H:i:s', $startTime).' Uhr gestartet und wurde für '.date('H:i:s', ($endTime - $startTime - 3600)).' Minuten ausgeführt. Beendet wurder der Scheduler um '.date('H:i:s', $endTime).' Uhr</p>';
                $subject = $settings['subject'];
                $plain = strip_tags($html);
                $fromEmail = 'noreply@netzweber.de';
                $fromName = 'Netzweber GmbH';
                $replyToEmail = 'lutz.eckelmann@netzweber.de';
                $replyToName = 'Netzweber GmbH';
                $returnPath = '';
                $this->sendMail($to, $subject, $html, $plain, $fromEmail, $fromName, $replyToEmail, $replyToName, $returnPath, $attachements = array());
                $params['step'] = $step + 1;
                $params['startTime'] = $startTime;
                $params['uniqid'] = $uniqueId;
                return $params;
                break;
        }
    }

    /**
     * Find last modified file in directory
     *
     * @param $dir
     * @param string $todo
     * @param string $format
     * @return mixed
     */
    public function lastModification ( $dir, $todo = 'new', $format = 'd.m.Y H:i:s' )
    {
        if ( is_file ( $dir ) ) {
            return false;
        }
        $lastFile = '';
        if( strlen( $dir ) - 1 !== '\\' || strlen( $dir ) - 1 !== '/' ) {
            $dir .= '/';
        }
        $handle = @opendir( $dir );
        if( !$handle ) {
            return false;
        }
        while ( ( $file = readdir( $handle ) ) !== false ) {
            if( $file !== '.' && $file !== '..' && is_file ( $dir.$file ) ) {
                if ( $todo === 'old' ) {
                    if( filemtime( $dir.$file ) <= filemtime( $dir.$lastFile ) ) {
                        $lastFile = $file;
                    }
                } else if( filemtime( $dir.$file ) >= filemtime( $dir.$lastFile ) ) {
                    $lastFile = $file;
                }
                if ( empty( $lastFile ) ) {
                    $lastFile = $file;
                }
            }
        }
        $fileInfo['dir'] = $dir;
        $fileInfo['file'] = $lastFile;
        $fileInfo['time'] = filemtime( $dir.$lastFile );
        $fileInfo['formattime'] = date( $format, filemtime( $dir.$lastFile ) );
        closedir( $handle );
        return $fileInfo;
    }

    /**
     * Return the message to the passed status code
     *
     * @param null $code
     * @param string $extraMsg
     * @param string $errorMsg
     * @return mixed
     */
    public function getStatusCodeText($code = NULL, $extraMsg = '', $errorMsg = '') {
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
            case 434: $msg['text'] = 'Database error'; $msg['msg'] = 'Unabled to write to database '.$extraMsg.'. Error: '.$errorMsg; break;
            case 435: $msg['text'] = 'Function not available'; $msg['msg'] = 'The function is not implement at the moment'; break;
            case 500: $msg['text'] = 'Internal Server Error'; break;
            case 501: $msg['text'] = 'Not Implemented'; break;
            case 502: $msg['text'] = 'Bad Gateway'; break;
            case 503: $msg['text'] = 'Service Unavailable'; break;
            case 504: $msg['text'] = 'Gateway Time-out'; break;
            case 505: $msg['text'] = 'HTTP Version not supported'; break;
            case 506: $msg['text'] = 'Export busy'; $msg['msg'] = 'Currently, the export is busy, please try again later.'; break;
            case 999: $msg['text'] = 'Test'; $msg['msg'] = var_dump($_POST); break;
            default: $msg['text'] = 'Unknown http status code "' . htmlentities($code) . '"';
        }
        return $msg;
    }

    /**
     * Return the title to the passed status code
     *
     * @param null $code
     * @param string $extraMsg
     * @param string $errorMsg
     * @return int|mixed|null
     */
    public function getStatusCode($code = NULL, $extraMsg = '', $errorMsg = '') {
        if ($code !== NULL) {
            $msg = '';
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
            	case 434: $text = 'Database error'; $msg = 'Unabled to write to database '.$extraMsg.'. Error: '.$errorMsg; break;
            	case 435: $text = 'Function not available'; $msg = 'The function is not implement at the moment'; break;
            	case 500: $text = 'Internal Server Error'; break;
            	case 501: $text = 'Not Implemented'; break;
            	case 502: $text = 'Bad Gateway'; break;
            	case 503: $text = 'Service Unavailable'; break;
            	case 504: $text = 'Gateway Time-out'; break;
            	case 505: $text = 'HTTP Version not supported'; break;
            	case 506: $text = 'Export busy'; $msg = 'Currently, the export is busy, please try again later.'; break;
            	case 999: $text = 'Test'; $msg = var_dump($_POST); break;
            	default: exit('Unknown http status code "' . htmlentities($code) . '"'); break;
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

    /**
     * Initializes the $GLOBALS['TSFE']
     *
     * @param int $id
     * @param int $typeNum
     */
    public function initTSFE($id = 1, $typeNum = 0): void
    {
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(TypoScriptFrontendController::class,  $GLOBALS['TYPO3_CONF_VARS'], $id, $typeNum);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
    }

    /**
     * Clears the database table that is passed
     *
     * @param $table
     */
    public function truncateDatabase($table): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $queryBuilder->truncate($table);
    }

    /**
     * Generates a unique hash
     */
    public function generatedHash($ref) {
        $hash = null;
        foreach($ref as $key => $value) {
            if(is_array($value)) {
                $hash .= $this->generatedHash($value);
            } else {
                $hash .= $value;
            }
        }
        return $hash;
    }

    /**
     * Insert current hash values in the passed model
     *
     * @param $repo
     * @param $model
     * @param $hash
     * @param $pid
     */
    public function insertInHashTable($model, $hash, $pid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nwcitavi_domain_model_'.strtolower($model));
        $queryBuilder->insert('tx_nwcitavi_domain_model_'.strtolower($model));
        $queryBuilder->values([
            'citavi_hash' => $hash,
            'crdate' => time(),
            'pid' => $pid,
        ]);
        $queryBuilder->execute();
    }

    /**
     * Check if a column with the passed Citavi ID already exists
     *
     * @param $repo
     * @param $citaviId
     * @param $table
     * @return |null
     */
    public function columnExists($repo, $citaviId, $table) {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $repository = null;
        $result = null;
        if($repo === 'CategoryRepository') {
            $repository = $objectManager->get(CategoryRepository::class);
        } else {
            $repository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\'.$repo);
        }
        $res = $repository->findByCitaviId($citaviId, $table);
        $i = 0;
        foreach($res as $obj) {
            if($i === 0) {
                $result = $obj;
                $i++;
            }
        }
        return $result;
    }

    /**
     * Delete all items wish no longer have a hash
     *
     * @param $t1
     * @param $t2
     * @param $settings
     */
    public function deletedDatabaseColumns($t1, $t2, $settings): void
    {
        $query = $this->createQuery();
        if($t1 === 'tx_nwcitavi_domain_model_category') {
            $query->statement('DELETE r FROM '.$t1.' r LEFT JOIN '.$t2.' rh ON r.citavi_hash = rh.citavi_hash WHERE rh.citavi_hash IS NULL AND r.pid = '.$settings['sPid']);
        } else {
            $query->statement('DELETE r FROM '.$t1.' r LEFT JOIN '.$t2.' rh ON r.citavi_hash = rh.citavi_hash WHERE rh.citavi_hash IS NULL');
        }
    }

    /**
     * Writes the data of the xml file into the database
     *
     * @param $ref
     * @param $modelTitle
     * @param $columnExists
     * @param $hash
     * @param $key
     * @param $uniqueId
     * @param null $settings
     * @param null $parent
     * @throws \Exception
     */
    public function setDatabase($ref, $modelTitle, $columnExists, $hash, $key, $uniqueId, $settings = null, $parent = null): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        switch($modelTitle) {
            case 'Reference':
                try {
                    $repository = $objectManager->get(__CLASS__);
                    if($columnExists) {
                        $newReference = $columnExists;
                    } else {
                        $newReference = new Reference();
                    }
                    $newReference->setPid(($settings['sPid']) ?: '0');
                    $newReference->setCitaviHash($hash);
                    $newReference->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newReference->setReferenceType(($ref['@attributes']['ReferenceType']) ?: '');
                    $newReference->setParentReferenceType(($ref['parentReferenceType']) ?: '');
                    $newReference->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newReference->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    try {
                        $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                        $newReference->setCreatedOn(($createdOn->getTimestamp()) ?: 0);
                    } catch (Exception $e) {
                        $this->logRepository->addLog(1, 'DateTime [CreatedOn] "'.$ref['@attributes']['CreatedOn'].'" could not be parsed for Reference '.$ref['@attributes']['ID'].'. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                    }
                    $newReference->setISBN(($ref['@attributes']['ISBN']) ?: '');
                    $newReference->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newReference->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    try {
                        $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                        $newReference->setModifiedOn(($modifiedOn->getTimestamp()) ?: 0);
                    } catch (Exception $e) {
                        $this->logRepository->addLog(1, 'DateTime [ModifiedOn] "'.$ref['@attributes']['ModifiedOn'].'" could not be parsed for Reference '.$ref['@attributes']['ID'].'. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                    }
                    $newReference->setSequenceNumber(($ref['@attributes']['SequenceNumber']) ?: '0');
                    $newReference->setAbstract(($ref['Abstract']) ?: '');
                    $newReference->setAbstractRTF(($ref['AbstractRTF']) ?: '');
                    $newReference->setAccessDate(($ref['AccessDate']) ?: '');
                    $newReference->setAdditions(($ref['Additions']) ?: '');
                    $newReference->setRefAuthors(($ref['Authors']) ?: '');
                    if(!empty($ref['Authors'])) {
                        $authors = $this->getRelatedObjectStorage($ref['Authors'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
                        if($authors) {
                            $newReference->setAuthors($authors);
                        }
                    }
                    $newReference->setRefCategories(($ref['Categories']) ?: '');
                    if(!empty($ref['Categories'])) {
                        $categories = $this->getRelatedCategories($ref['Categories']);
                        if($categories) {
                            $newReference->setCategories($categories);
                        }
                    }
                    $newReference->setCitationKeyUpdateType(($ref['CitationKeyUpdateType']) ?: '');
                    $newReference->setRefCollaborators(($ref['Collaborators']) ?: '');
                    if(!empty($ref['Collaborators'])) {
                        $collaborators = $this->getRelatedObjectStorage($ref['Collaborators'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
                        if($collaborators) {
                            $newReference->setCollaborators($collaborators);
                        }
                    }
                    $newReference->setCustomField1(($ref['CustomField1']) ?: '');
                    $newReference->setCustomField2(($ref['CustomField2']) ?: '');
                    $newReference->setCustomField3(($ref['CustomField3']) ?: '');
                    $newReference->setCustomField4(($ref['CustomField4']) ?: '');
                    $newReference->setCustomField5(($ref['CustomField5']) ?: '');
                    $newReference->setCustomField6(($ref['CustomField6']) ?: '');
                    $newReference->setCustomField7(($ref['CustomField7']) ?: '');
                    $newReference->setCustomField8(($ref['CustomField8']) ?: '');
                    $newReference->setCustomField9(($ref['CustomField9']) ?: '');
                    $newReference->setBookDate(($ref['Date']) ?: '');
                    $newReference->setDefaultLocationID(($ref['DefaultLocationID']) ?: '');
                    $newReference->setEdition(($ref['Edition']) ?: '');
                    $newReference->setRefEditors(($ref['Editors']) ?: '');
                    if(!empty($ref['Editors'])) {
                        $editors = $this->getRelatedObjectStorage($ref['Editors'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
                        if($editors) {
                            $newReference->setEditors($editors);
                        }
                    }
                    $newReference->setEvaluation(($ref['Evaluation']) ?: '');
                    $newReference->setEvaluationRTF(($ref['EvaluationRTF']) ?: '');
                    $newReference->setRefKeywords(($ref['Keywords']) ?: '');
                    if(!empty($ref['Keywords'])) {
                        $keywords = $this->getRelatedObjectStorage($ref['Keywords'], 'citavi_id', 'tx_nwcitavi_domain_model_keyword', 'KeywordRepository');
                        if($keywords) {
                            $newReference->setKeywords($keywords);
                        }
                    }
                    $newReference->setBookLanguage(($ref['Language']) ?: '');
                    $newReference->setBookNote(($ref['Notes']) ?: '');
                    $newReference->setNumber(($ref['Number']) ?: '');
                    $newReference->setNumberOfVolumes(($ref['NumberOfVolumes']) ?: '');
                    $newReference->setOnlineAddress(($ref['OnlineAddress']) ?: '');
                    $newReference->setRefOrganizations(($ref['Organizations']) ?: '');
                    if(!empty($ref['Organizations'])) {
                        $organizations = $this->getRelatedObjectStorage($ref['Organizations'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
                        if($organizations) {
                            $newReference->setOrganizations($organizations);
                        }
                    }
                    $newReference->setOriginalCheckedBy(($ref['OriginalCheckedBy']) ?: '');
                    $newReference->setOriginalPublication(($ref['OriginalPublication']) ?: '');
                    $newReference->setRefOthersInvolved(($ref['OthersInvolved']) ?: '');
                    if(!empty($ref['OthersInvolved'])) {
                        $othersInvolved = $this->getRelatedObjectStorage($ref['OthersInvolved'], 'citavi_id', 'tx_nwcitavi_domain_model_person', 'PersonRepository');
                        if($othersInvolved) {
                            $newReference->setOthersInvolved($othersInvolved);
                        }
                    }
                    $newReference->setPageCount(($ref['PageCount']) ?: '');
                    $newReference->setPageRange($ref['PageRange']['@attributes']['StartPage'].';'.$ref['PageRange']['@attributes']['EndPage']);
                    $newReference->setParallelTitle(($ref['ParallelTitle']) ?: '');
                    $newReference->setRefPeriodical(($ref['PeriodicalID']) ?: '');
                    if(!empty($ref['PeriodicalID'])) {
                        $periodicals = $this->getRelatedObjectStorage($ref['PeriodicalID'], 'citavi_id', 'tx_nwcitavi_domain_model_periodical', 'PeriodicalRepository');
                        if($periodicals) {
                            $newReference->setPeriodicals($periodicals);
                        }
                    }
                    $newReference->setPlaceOfPublication(($ref['PlaceOfPublication']) ?: '');
                    $newReference->setPrice(($ref['Price']) ? (float)$ref['Price'] : 0);
                    $newReference->setRefPublishers(($ref['Publishers']) ?: '');
                    if(!empty($ref['Publishers'])) {
                        $publishers = $this->getRelatedObjectStorage($ref['Publishers'], 'citavi_id', 'tx_nwcitavi_domain_model_publisher', 'PublisherRepository');
                        if($publishers) {
                            $newReference->setPublishers($publishers);
                        }
                    }
                    $newReference->setRating(($ref['Rating']) ? (int)$ref['Rating'] : 0);
                    $newReference->setRefSeriesTitle(($ref['SeriesTitleID']) ?: '');
                    if(!empty($ref['SeriesTitleID'])) {
                        $seriestitles = $this->getRelatedObjectStorage($ref['SeriesTitleID'], 'citavi_id', 'tx_nwcitavi_domain_model_seriestitle', 'SeriestitleRepository');
                        if($seriestitles) {
                            $newReference->setSeriestitles($seriestitles);
                        }
                    }
                    $newReference->setShortTitle(($ref['ShortTitle']) ?: '');
                    $newReference->setSourceOfBibliographicInformation(($ref['SourceOfBibliographicInformation']) ?: '');
                    $newReference->setSpecificField1(($ref['SpecificField1']) ?: '');
                    $newReference->setSpecificField2(($ref['SpecificField2']) ?: '');
                    $newReference->setSpecificField4(($ref['SpecificField4']) ?: '');
                    $newReference->setSpecificField7(($ref['SpecificField7']) ?: '');
                    $newReference->setStorageMedium(($ref['StorageMedium']) ?: '');
                    $newReference->setSubtitle(($ref['Subtitle']) ?: '');
                    $newReference->setTableOfContents(($ref['TableOfContents']) ?: '');
                    $newReference->setTableOfContentsRTF(($ref['TableOfContentsRTF']) ?: '');
                    $newReference->setTitle(($ref['Title']) ?: '');
                    $newReference->setTitleInOtherLanguages(($ref['TitleInOtherLanguages']) ?: '');
                    $newReference->setTitleSupplement(($ref['TitleSupplement']) ?: '');
                    $newReference->setTranslatedTitle(($ref['TranslatedTitle']) ?: '');
                    $newReference->setUniformTitle(($ref['UniformTitle']) ?: '');
                    $newReference->setBookVolume(($ref['Volume']) ?: '');
                    $newReference->setBookYear(($ref['Year']) ?: '');
                    $newReference->setLiteraturlistId($key);
                    $newReference->setPageRangeStart(($ref['PageRange']['@attributes']['StartPage']) ?: '');
                    $newReference->setPageRangeEnd(($ref['PageRange']['@attributes']['EndPage']) ?: '');
                    $newReference->setDoi(($ref['@attributes']['Doi']) ?: '0');
                    $sortDateParts = explode('.', $ref['sortDate']);
                    if(count($sortDateParts) > 0) {
                        foreach($sortDateParts as $part) {
                            if(strlen($part) === 4) {
                                $ref['sortDate'] = $part;
                            }
                        }
                    }
                    $newReference->setSortDate(($ref['sortDate']) ?: '');
                    $newReference->setTxExtbaseType('Tx_NwCitaviFe_Reference');
                    if(!empty($ref['ParentReferenceID'])) {
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
                    $this->logRepository->addLog(1, 'Reference "'.$ref['Title'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Keyword':
                try {
                    $repository = $objectManager->get(KeywordRepository::class);
                    if($columnExists) {
                        $newKeyword = $columnExists;
                    } else {
                    	$newKeyword = new Keyword();
                    }
                    $newKeyword->setPid(($settings['sPid']) ?: '0');
                    $newKeyword->setCitaviHash($hash);
                    $newKeyword->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newKeyword->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newKeyword->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newKeyword->setCreatedOn($createdOn->getTimestamp());
                    $newKeyword->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newKeyword->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newKeyword->setModifiedOn($modifiedOn->getTimestamp());
                    $newKeyword->setName(($ref['@attributes']['Name']) ?: '');
                    $newKeyword->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newKeyword);
                    } else {
                        $repository->add($newKeyword);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Keyword "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Library':
                try {
                	$repository = $objectManager->get(LibraryRepository::class);
                	if($columnExists) {
                	    $newLibrary = $columnExists;
                	} else {
                	    $newLibrary = new Library();
                	}
                	$newLibrary->setPid(($settings['sPid']) ?: '0');
                	$newLibrary->setCitaviHash($hash);
                	$newLibrary->setCitaviID(($ref['@attributes']['ID']) ?: '0');
                	$newLibrary->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                	$newLibrary->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                	$createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                	$newLibrary->setCreatedOn($createdOn->getTimestamp());
                	$newLibrary->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                	$newLibrary->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                	$modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                	$newLibrary->setModifiedOn($modifiedOn->getTimestamp());
                	$newLibrary->setName(($ref['@attributes']['Abbreviation']) ?: '');
                	$newLibrary->setLiteraturlistId($key);
                	if($columnExists) {
                	    $repository->update($newLibrary);
                	} else {
                	    $repository->add($newLibrary);
                	}
                } catch (Exception $e) {
                	$this->logRepository->addLog(1, 'Library "'.$ref['@attributes']['Abbreviation'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Periodical':
                try {
                    $repository = $objectManager->get(PeriodicalRepository::class);
                    if($columnExists) {
                        $newPeriodical = $columnExists;
                    } else {
                        $newPeriodical = new Periodical();
                    }
                    $newPeriodical->setPid(($settings['sPid']) ?: '0');
                    $newPeriodical->setCitaviHash($hash);
                    $newPeriodical->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newPeriodical->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newPeriodical->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newPeriodical->setCreatedOn($createdOn->getTimestamp());
                    $newPeriodical->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newPeriodical->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newPeriodical->setModifiedOn($modifiedOn->getTimestamp());
                    $newPeriodical->setName(($ref['@attributes']['Name']) ?: '');
                    $newPeriodical->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newPeriodical);
                    } else {
                        $repository->add($newPeriodical);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Periodical "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Person':
                try {
                    $repository = $objectManager->get(PersonRepository::class);
                    if($columnExists) {
                        $newPerson = $columnExists;
                    } else {
                        $newPerson = new Person();
                    }
                    $newPerson->setPid(($settings['sPid']) ?: '0');
                    $newPerson->setCitaviHash($hash);
                    $newPerson->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newPerson->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newPerson->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newPerson->setCreatedOn($createdOn->getTimestamp());
                    $newPerson->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newPerson->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newPerson->setModifiedOn($modifiedOn->getTimestamp());
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
                    $newPerson->setFullName(($fullName) ?: '');
                    $newPerson->setFirstName(($ref['@attributes']['FirstName']) ?: '');
                    $newPerson->setLastName(($ref['@attributes']['LastName']) ?: '');
                    $newPerson->setMiddleName(($ref['@attributes']['MiddleName']) ?: '');
                    $newPerson->setPref(($ref['@attributes']['Prefix']) ?: '');
                    $newPerson->setSuff(($ref['@attributes']['Suffix']) ?: '');
                    $newPerson->setAbbreviation(($ref['@attributes']['Abbreviation']) ?: '');
                    $newPerson->setSex(($ref['@attributes']['Sex']) ?: '');
                    $newPerson->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newPerson);
                    } else {
                        $repository->add($newPerson);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Person "'.$fullName.'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Publisher':
                try {
                    $repository = $objectManager->get(PublisherRepository::class);
                    if($columnExists) {
                        $newPublisher = $columnExists;
                    } else {
                        $newPublisher = new Publisher();
                    }
                    $newPublisher->setPid(($settings['sPid']) ?: '0');
                    $newPublisher->setCitaviHash($hash);
                    $newPublisher->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newPublisher->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newPublisher->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newPublisher->setCreatedOn($createdOn->getTimestamp());
                    $newPublisher->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newPublisher->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newPublisher->setModifiedOn($modifiedOn->getTimestamp());
                    $newPublisher->setName(($ref['@attributes']['Name']) ?: '');
                    $newPublisher->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newPublisher);
                    } else {
                        $repository->add($newPublisher);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Publisher "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'SeriesTitle':
                try {
                    $repository = $objectManager->get(SeriestitleRepository::class);
                    if($columnExists) {
                        $newSeriesTitle = $columnExists;
                    } else {
                        $newSeriesTitle = new Seriestitle();
                    }
                    $newSeriesTitle->setPid(($settings['sPid']) ?: '0');
                    $newSeriesTitle->setCitaviHash($hash);
                    $newSeriesTitle->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newSeriesTitle->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newSeriesTitle->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newSeriesTitle->setCreatedOn($createdOn->getTimestamp());
                    $newSeriesTitle->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newSeriesTitle->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newSeriesTitle->setModifiedOn($modifiedOn->getTimestamp());
                    $newSeriesTitle->setName(($ref['@attributes']['Name']) ?: '');
                    $newSeriesTitle->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newSeriesTitle);
                    } else {
                        $repository->add($newSeriesTitle);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'SeriesTitle "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'KnowledgeItem':
                try {
                    $repository = $objectManager->get(KnowledgeItemRepository::class);
                    if($columnExists) {
                        $newKnowledgeItem = $columnExists;
                    } else {
                        $newKnowledgeItem = new KnowledgeItem();
                    }
                    $newKnowledgeItem->setPid(($settings['sPid']) ?: '0');
                    $newKnowledgeItem->setCitaviHash($hash);
                    $newKnowledgeItem->setCitaviID(($ref['@attributes']['ID']) ?: '0');
                    $newKnowledgeItem->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newKnowledgeItem->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newKnowledgeItem->setCreatedOn($createdOn->getTimestamp());
                    $newKnowledgeItem->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newKnowledgeItem->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newKnowledgeItem->setModifiedOn($modifiedOn->getTimestamp());
                    $newKnowledgeItem->setKnowledgeItemType(($ref['@attributes']['KnowledgeItemType']) ?: '0');
                    $newKnowledgeItem->setCoreStatement(($ref['CoreStatement']) ?: '');
                    $newKnowledgeItem->setCoreStatementUpdateType(($ref['CoreStatementUpdateType']) ?: '');
                    $newKnowledgeItem->setText(($ref['Text']) ?: '');
                    $newKnowledgeItem->setTextRTF(($ref['TextRTF']) ?: '');
                    $newKnowledgeItem->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newKnowledgeItem);
                    } else {
                        $repository->add($newKnowledgeItem);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'KnowledgeItem "'.$ref['Text'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Category':
                try {
                    $repository = $objectManager->get(CategoryRepository::class);
                    if($columnExists) {
                        $newCategory = $columnExists;
                    } else {
                        $newCategory = new Category();
                    }
                    $newCategory->setPid(($settings['sPid']) ?: '0');
                    $newCategory->setCitaviHash($hash);
                    $newCategory->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newCategory->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newCategory->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newCategory->setCreatedOn($createdOn->getTimestamp());
                    $newCategory->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newCategory->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newCategory->setModifiedOn($modifiedOn->getTimestamp());
                    $newCategory->setTitle(($ref['@attributes']['Name']) ?: '');
                    $newCategory->setLiteraturlistId($key);
                    if($parent) {
                        $res = $repository->findByCitaviId($parent);
                        $parentObj = new ObjectStorage();
                        $i = 0;
                        $result = null;
                        foreach($res as $obj) {
                            if($i === 0) {
                                $parentObj->attach($obj);
                                $result = $obj;
                                $i++;
                            }
                        }
                        if($result) {
                            $newCategory->setParent($parentObj);
                        }
                    }
                    if($columnExists) {
                        $repository->update($newCategory);
                    } else {
                        $repository->add($newCategory);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Category "'.$ref['@attributes']['Name'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
            case 'Location':
                try {
                    $repository = $objectManager->get(LocationRepository::class);
                    if($columnExists) {
                        $newLocation = $columnExists;
                    } else {
                        $newLocation = new Location();
                    }
                    $newLocation->setPid(($settings['sPid']) ?: '0');
                    $newLocation->setCitaviHash($hash);
                    $newLocation->setCitaviId(($ref['@attributes']['ID']) ?: '0');
                    $newLocation->setCreatedBy(($ref['@attributes']['CreatedBy']) ?: '0');
                    $newLocation->setCreatedBySid(($ref['@attributes']['CreatedBySid']) ?: '0');
                    $createdOn = new \DateTime(($ref['@attributes']['CreatedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['CreatedOn']) : '1000-01-01 00:00:00');
                    $newLocation->setCreatedOn($createdOn->getTimestamp());
                    $newLocation->setModifiedBy(($ref['@attributes']['ModifiedBy']) ?: '0');
                    $newLocation->setModifiedBySid(($ref['@attributes']['ModifiedBySid']) ?: '0');
                    $modifiedOn = new \DateTime(($ref['@attributes']['ModifiedOn']) ? $this->setDatetimeByCitaviDate($ref['@attributes']['ModifiedOn']) : '1000-01-01 00:00:00');
                    $newLocation->setModifiedOn($modifiedOn->getTimestamp());
                    $newLocation->setAddress(($ref['Address']) ?: '0');
                    $newLocation->setNotes(($ref['Notes']) ?: '0');
                    $newLocation->setLocationType(($ref['@attributes']['LocationType']) ?: '0');
                    $newLocation->setMirrorsReferencePropertyId(($ref['@attributes']['MirrorsReferencePropertyId']) ?: '0');
                    $newLocation->setLiteraturlistId($key);
                    if($columnExists) {
                        $repository->update($newLocation);
                    } else {
                        $repository->add($newLocation);
                    }
                } catch (Exception $e) {
                    $this->logRepository->addLog(1, 'Location "'.$ref['Address'].'" could not be parsed. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                }
                break;
        }
        $persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $persistenceManager->persistAll();
    }

    /**
     * Generates a datetime for the database
     *
     * @param $citaviDate
     * @return string|string[]
     */
    public function setDatetimeByCitaviDate($citaviDate) {
        return str_replace('T', ' ', $citaviDate);
    }

    /**
     * Returns related object from reference
     *
     * @param $keys
     * @param $field_name
     * @param $table
     * @param $repository
     * @return bool|ObjectStorage
     */
    public function getRelatedObjectStorage($keys, $field_name, $table, $repository) {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $objectRepository = $objectManager->get('Netzweber\\NwCitavi\\Domain\\Repository\\'.$repository);
        $query = $objectRepository->createQuery();
        $keyArray = explode(';', $keys);
        if(!empty($keyArray[0])) {
            $where = '';
            $orderBy = '';
            foreach ($keyArray as $i => $iValue) {
                if($i === 0) {
                	$where .= ' '.$field_name.' LIKE \''. $iValue .'\'';
                	$orderBy .= ' ORDER BY FIELD('.$field_name.', \''. $iValue .'\'';
                } else {
                    $where .= ' OR '.$field_name.' LIKE \''. $iValue .'\'';
                    $orderBy .= ', \''. $iValue .'\'';
                }
            }
            if(!empty($orderBy)) {
                $orderBy .= ') ASC';
            }
            $sql = 'SELECT * FROM '.$table.' WHERE '.$where.$orderBy;
            $query->statement($sql);
            $res = $query->execute();
            $objectStorage = new ObjectStorage;
            foreach($res as $obj) {
            	$objectStorage->attach($obj);
            }
            return $objectStorage;
        }
        return false;
    }

    /**
     * Returns related category
     *
     * @param $categories
     * @return bool|ObjectStorage
     */
    public function getRelatedCategories($categories) {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $categoryRepository = $objectManager->get(CategoryRepository::class);
        $query = $categoryRepository->createQuery();
        $categoriesArray = explode(';', $categories);
        if(!empty($categoriesArray[0])) {
            $where = '';
            foreach ($categoriesArray as $i => $iValue) {
                if($i === 0) {
                    $where .= ' citavi_id LIKE \''. $iValue .'\'';
                } else {
                    $where .= ' OR citavi_id LIKE \''. $iValue .'\'';
                }
            }
            $sql = 'SELECT * FROM tx_nwcitavi_domain_model_category WHERE '.$where;
            $query->statement($sql);
            $res = $query->execute();
            $objectStorage = new ObjectStorage;
            foreach($res as $obj) {
            	$objectStorage->attach($obj);
            }
            return $objectStorage;
        }
        return false;
    }

    /**
     * Update the relation between reference and location in the database
     *
     * @param $ref
     * @param $locationId
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateReferenceLocation($ref, $locationId): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $locationRepository = $objectManager->get(LocationRepository::class);
        $refQuery = $this->createQuery();
        $refSql = 'SELECT * FROM tx_nwcitavi_domain_model_reference WHERE citavi_id LIKE \''.$ref['@attributes']['ID'].'\'';
        $refQuery->statement($refSql);
        $refResult = $refQuery->execute();
        if(isset($refResult[0])) {
            $updateReference = $refResult[0];
            $locationQuery = $locationRepository->createQuery();
            $locationSql = 'SELECT * FROM tx_nwcitavi_domain_model_location WHERE citavi_id LIKE \''.$locationId.'\'';
            $locationQuery->statement($locationSql);
            $locationResult = $locationQuery->execute();
            $objectStorage = new ObjectStorage;
            foreach($locationResult as $obj) {
                $objectStorage->attach($obj);
            }
            $updateReference->setLocations($objectStorage);
            $this->update($updateReference);
            $persistenceManager = $this->objectManager->get(PersistenceManager::class);
            $persistenceManager->persistAll();
        }
    }

    /**
     * Update the relation between location and library in the database
     *
     * @param $locationId
     * @param $libraryId
     */
    public function updateLocationLibrary($locationId, $libraryId): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $locationRepository = $objectManager->get(LocationRepository::class);
        $libraryRepository = $objectManager->get(LibraryRepository::class);
        $locationQuery = $locationRepository->createQuery();
        $locationSql = 'SELECT * FROM tx_nwcitavi_domain_model_location WHERE citavi_id LIKE \''.$locationId.'\'';
        $locationQuery->statement($locationSql);
        $locationResult = $locationQuery->execute();
        $updateLocation = $locationResult[0];
        $libraryQuery = $libraryRepository->createQuery();
        $librarySql = 'SELECT * FROM tx_nwcitavi_domain_model_library WHERE citavi_id LIKE \''.$libraryId.'\'';
        $libraryQuery->statement($librarySql);
        $libraryResult = $libraryQuery->execute();
        $objectStorage = new ObjectStorage;
        foreach($libraryResult as $obj) {
            $objectStorage->attach($obj);
        }
        $updateLocation->setLibrarys($objectStorage);
        $locationRepository->update($updateLocation);
        $persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $persistenceManager->persistAll();
    }

    /**
     * Compares duplicate entries in every database table with the hash entries
     *
     * @param $settings
     */
    public function compareHashData($settings): void
    {
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

    /**
     * Delete duplicate entries in database table
     *
     * @param $t
     */
    public function deletedDoubleDatabaseColumns($t): void
    {
        $query = $this->createQuery();
        $query->statement('DELETE t1 FROM '.$t.' t1 INNER JOIN '.$t.' t2 WHERE t1.uid < t2.uid AND t1.citavi_hash = t2.citavi_hash;');
    }

    /**
     * Generates the structure of the categories
     *
     * @param $level
     * @param $array
     * @param $settings
     * @param $key
     * @param $uniqueId
     * @param null $parent
     */
    public function parseCategories($level, $array, $settings, $key, $uniqueId, $parent = null): void
    {
        foreach ($array['Categories']['Category'] as $iValue) {
            if($array['Categories']['Category']['@attributes']) {
                $category = $array['Categories']['Category'];
            } else {
                $category = $iValue;
            }
            if(!empty($category['@attributes']['ID'])) {
                $refStr = $this->generatedHash($category);
                $hash = hash('md5', $refStr);
                $this->insertInHashTable( 'CategoryHash', $hash, ($settings['sPid']) ?: '0');
                $columnExists = $this->columnExists('CategoryRepository', $category['@attributes']['ID'], 'tx_nwcitavi_domain_model_category');

                $this->setDatabase($category, 'Category', $columnExists, $hash, $key, $uniqueId, $settings, $parent);
                if ( is_array( $category['Categories']['Category'] ) ) {
                    $this->parseCategories($level++, $category, $settings, $key, $uniqueId, $category['@attributes']['ID']);
                }
            }
        }
        unset($category);
    }

    /**
     * Parse the categories xml data for the database
     */
    public function taskParseXMLCategories(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
            //$this->initTSFE($this->getRootpage($objectManager));
            //DebuggerUtility::var_dump($fullTs);
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check && (int)$settings['sPid'] > 0) {
                $taskExists = file_exists($dir.'/task.txt');
                if(!$taskExists) {
                    $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                    $schedulerCols = explode('|', $schedulerString);
                    [$fileName, $key, $uniqueId] = $schedulerCols;
                    $xml = new \XMLReader();
                    $xml->open($fileName);
                    $xmlString = implode('', file($fileName));
                    $xml = simplexml_load_string($xmlString);
                    $json = json_encode($xml);
                    $array = json_decode($json,TRUE);
                    $level = 0;
                    try {
                        if ( is_array( $array['Categories']['Category'] ) ) {
                            $this->truncateDatabase('tx_nwcitavi_domain_model_categoryhash');
                            $this->parseCategories($level, $array, $settings, $key, $uniqueId);
                            file_put_contents($dir.'/task.txt', 1);
                        } else {
                        	file_put_contents($dir.'/task.txt', 1);
                        }
                    } catch (Exception $e) {
                        $this->logRepository->addLog(1, 'Categories could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the keywords xml data for the database
     */
    public function taskParseXMLKeywords(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 1) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['Keywords']['Keyword'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_keywordhash');
                                foreach ($array['Keywords']['Keyword'] as $iValue) {
                                    if($array['Keywords']['Keyword']['@attributes']) {
                                        $keyword = $array['Keywords']['Keyword'];
                                    } else {
                                        $keyword = $iValue;
                                    }
                                    if(!empty($keyword['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($keyword);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'KeywordHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('KeywordRepository', $keyword['@attributes']['ID'], 'tx_nwcitavi_domain_model_keyword');
                                        $this->setDatabase($keyword, 'Keyword', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($keyword, $keywordAttributes, $db_keyword, $keywordUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_keyword', 'tx_nwcitavi_domain_model_keywordhash', $settings);
                                file_put_contents($dir.'/task.txt', 2);
                            } else {
                                file_put_contents($dir.'/task.txt', 2);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Keywords could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the libraries xml data for the database
     */
    public function taskParseXMLLibraries(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 2) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                        	if ( is_array( $array['Libraries']['Library'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_libraryhash');
                                foreach ($array['Libraries']['Library'] as $iValue) {
                                    if($array['Libraries']['Library']['@attributes']) {
                                        $library = $array['Libraries']['Library'];
                                    } else {
                                        $library = $iValue;
                                    }
                                    if(!empty($library['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($library);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'LibraryHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('LibraryRepository', $library['@attributes']['ID'], 'tx_nwcitavi_domain_model_library');
                                        $this->setDatabase($library, 'Library', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($library, $libraryAttributes, $db_library, $libraryUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_library', 'tx_nwcitavi_domain_model_libraryhash', $settings);
                                file_put_contents($dir.'/task.txt', 3);
                        	} else {
                        	    file_put_contents($dir.'/task.txt', 3);
                        	}
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Libraries could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the periodicals xml data for the database
     */
    public function taskParseXMLPeriodicals(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 3) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['Periodicals']['Periodical'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_periodicalhash');
                                foreach ($array['Periodicals']['Periodical'] as $iValue) {
                                    if($array['Periodicals']['Periodical']['@attributes']) {
                                        $periodical = $array['Periodicals']['Periodical'];
                                    } else {
                                        $periodical = $iValue;
                                    }
                                    if(!empty($periodical['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($periodical);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'PeriodicalHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('PeriodicalRepository', $periodical['@attributes']['ID'], 'tx_nwcitavi_domain_model_periodical');
                                        $this->setDatabase($periodical, 'Periodical', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($periodical, $periodicalAttributes, $db_periodical, $periodicalUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_periodical', 'tx_nwcitavi_domain_model_periodicalhash', $settings);
                                file_put_contents($dir.'/task.txt', 4);
                            } else {
                                file_put_contents($dir.'/task.txt', 4);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Periodicals could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the persons xml data for the database
     */
    public function taskParseXMLPersons(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 4) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['Persons']['Person'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_personhash');
                                foreach ($array['Persons']['Person'] as $iValue) {
                                    if($array['Persons']['Person']['@attributes']) {
                                        $person = $array['Persons']['Person'];
                                    } else {
                                        $person = $iValue;
                                    }
                                    if(!empty($person['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($person);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'PersonHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('PersonRepository', $person['@attributes']['ID'], 'tx_nwcitavi_domain_model_person');
                                        $this->setDatabase($person, 'Person', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($person, $personAttributes, $db_person, $personUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_person', 'tx_nwcitavi_domain_model_personhash', $settings);
                                file_put_contents($dir.'/task.txt', 5);
                            } else {
                                file_put_contents($dir.'/task.txt', 5);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Persons could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the publishers xml data for the database
     */
    public function taskParseXMLPublishers(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 5) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['Publishers']['Publisher'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_publisherhash');
                                foreach ($array['Publishers']['Publisher'] as $iValue) {
                                    if($array['Publishers']['Publisher']['@attributes']) {
                                        $publisher = $array['Publishers']['Publisher'];
                                    } else {
                                        $publisher = $iValue;
                                    }
                                    if(!empty($publisher['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($publisher);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'PublisherHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('PublisherRepository', $publisher['@attributes']['ID'], 'tx_nwcitavi_domain_model_publisher');
                                        $this->setDatabase($publisher, 'Publisher', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($publisher, $publisherAttributes, $db_publisher, $publisherUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_publisher', 'tx_nwcitavi_domain_model_publisherhash', $settings);
                                file_put_contents($dir.'/task.txt', 6);
                            } else {
                                file_put_contents($dir.'/task.txt', 6);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Publishers could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the seriestitles xml data for the database
     */
    public function taskParseXMLSeriestitles(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 6) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['SeriesTitles']['SeriesTitle'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_seriestitlehash');
                                foreach ($array['SeriesTitles']['SeriesTitle'] as $iValue) {
                                    if($array['SeriesTitles']['SeriesTitle']['@attributes']) {
                                        $seriesTitle = $array['SeriesTitles']['SeriesTitle'];
                                    } else {
                                        $seriesTitle = $iValue;
                                    }
                                    if(!empty($seriesTitle['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($seriesTitle);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'SeriestitleHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('SeriestitleRepository', $seriesTitle['@attributes']['ID'], 'tx_nwcitavi_domain_model_seriestitle');
                                        $this->setDatabase($seriesTitle, 'SeriesTitle', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($seriesTitle, $seriesTitleAttributes, $db_seriestitle, $seriestitleUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_seriestitle', 'tx_nwcitavi_domain_model_seriestitlehash', $settings);
                                file_put_contents($dir.'/task.txt', 7);
                            } else {
                                file_put_contents($dir.'/task.txt', 7);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Seriestitles could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the knowledgeitems xml data for the database
     */
    public function taskParseXMLKnowledgeitems(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 7) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['Thoughts']['KnowledgeItem'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_knowledgeitemhash');
                                foreach ($array['Thoughts']['KnowledgeItem'] as $iValue) {
                                    if($array['Thoughts']['KnowledgeItem']['@attributes']) {
                                        $knowledgeItem = $array['Thoughts']['KnowledgeItem'];
                                    } else {
                                        $knowledgeItem = $iValue;
                                    }
                                    if(!empty($knowledgeItem['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($knowledgeItem);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'KnowledgeItemHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('KnowledgeItemRepository', $knowledgeItem['@attributes']['ID'], 'tx_nwcitavi_domain_model_knowledgeitem');
                                        $this->setDatabase($knowledgeItem, 'KnowledgeItem', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($knowledgeItem, $knowledgeItemAttributes, $dbKnowledgeItem, $knowledgeItemUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_knowledgeitem', 'tx_nwcitavi_domain_model_knowledgeitemhash', $settings);
                                file_put_contents($dir.'/task.txt', 8);
                            } else {
                                file_put_contents($dir.'/task.txt', 8);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Knowledgeitems could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the references xml data for the database
     */
    public function taskParseXMLReferences(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        $dbParentRef = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 8) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        $xmlObj = new \SimpleXMLElement($xmlString);
                        try {
                            if ( is_array( $array['References']['Reference'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_referencehash');
                                foreach ($array['References']['Reference'] as $iValue) {
                                    if($array['References']['Reference']['@attributes']) {
                                        $ref = $array['References']['Reference'];
                                    } else {
                                        $ref = $iValue;
                                    }
                                    switch($ref['@attributes']['ReferenceType']) {
                                        case 'ConferenceProceedings':
                                        case 'InternetDocument':
                                        case 'BookEdited':
                                        case 'Book':
                                        case 'JournalArticle':
                                        case 'SpecialIssue':
                                            $ref['sortDate'] = $ref['Year'];
                                            break;
                                        case 'UnpublishedWork':
                                        case 'NewspaperArticle':
                                        case 'PersonalCommunication':
                                        case 'Lecture':
                                        case 'PressRelease':
                                        case 'Thesis':
                                            $ref['sortDate'] = $ref['Date'];
                                            break;
                                        case 'Unknown':
                                            $ref['sortDate'] = (!empty($ref['Date'])) ? $ref['Date'] : $ref['Year'];
                                            break;
                                        case 'Contribution':
                                            $dbParentRef = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                                            $ref['sortDate'] = $dbParentRef['sort_date'];
                                            break;
                                        default:
                                            $ref['sortDate'] = $ref['Date'];
                                    }
                                    if(!empty($ref['ParentReferenceID'])) {
                                        $parent = $xmlObj->xpath("//Reference[@ID='".$ref['ParentReferenceID']."']");
                                        $ref['parentReferenceType'] = (string)$parent[0]['ReferenceType'];
                                        $ref['dbParentRef']
                                         = $this->checkIfEntryExists('tx_nwcitavi_domain_model_reference', 'citavi_id', $ref['ParentReferenceID']);
                                        $ref['sortDate'] = $dbParentRef['sort_date'];
                                    }
                                    if(!empty($ref['@attributes']['ID'])) {
                                        $refStr = $this->generatedHash($ref);
                                        $hash = hash('md5', $refStr);
                                        $this->insertInHashTable( 'ReferenceHash', $hash, ($settings['sPid']) ?: '0');
                                        $columnExists = $this->columnExists('ReferenceRepository', $ref['@attributes']['ID'], 'tx_nwcitavi_domain_model_reference');
                                        $this->setDatabase($ref, 'Reference', $columnExists, $hash, $key, $uniqueId, $settings);
                                    }
                                    unset($ref, $refAttributes, $db_ref, $refUid);
                                }
                                $this->deletedDatabaseColumns('tx_nwcitavi_domain_model_reference', 'tx_nwcitavi_domain_model_referencehash', $settings);
                                file_put_contents($dir.'/task.txt', 9);
                            } else {
                                file_put_contents($dir.'/task.txt', 9);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'References could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the locations xml data for the database
     */
    public function taskParseXMLLocations(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 9) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        $xml = new \XMLReader();
                        $xml->open($fileName);
                        $xmlString = implode('', file($fileName));
                        $xml = simplexml_load_string($xmlString);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        try {
                            if ( is_array( $array['References']['Reference'] ) ) {
                                $this->truncateDatabase('tx_nwcitavi_domain_model_locationhash');
                                foreach ($array['References']['Reference'] as $i => $iValue) {
                                    if($array['References']['Reference']['@attributes']) {
                                        $ref = $array['References']['Reference'];
                                    } else {
                                        $ref = $iValue;
                                    }
                                    if(!empty($ref['@attributes']['ID'])) {
                                        // Locations speichern
                                        if($iValue['Locations']['Location']['@attributes']) {
                                            $location = $array['References']['Reference'][$i]['Locations']['Location'];
                                            if(!empty($location['@attributes']['ID'])) {
                                                $locationStr = $this->generatedHash($location);
                                                $hash = hash('md5', $locationStr);
                                                $this->insertInHashTable( 'LocationHash', $hash, ($settings['sPid']) ?: '0');
                                                $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                                                $this->setDatabase($location, 'Location', $columnExists, $hash, $key, $uniqueId, $settings);
                                                // Location verknüpfen
                                                $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                                                //Libraries verknüpfen
                                                if(!empty($iValue['Locations']['Location']['LibraryID'])) {
                                                    $this->updateLocationLibrary($location['@attributes']['ID'], $iValue['Locations']['Location']['LibraryID']);
                                                }
                                            }
                                            unset($location, $locationAttributes, $db_location, $locationUid);
                                        } else {
                                            if ( is_array( $iValue['Locations']['Location'] ) ) {
                                                $locationsCount = count($iValue['Locations']['Location']);
                                                for($j = 0; $j < $locationsCount; $j++) {
                                                    $location = $array['References']['Reference'][$i]['Locations']['Location'][$j];
                                                    if(!empty($location['@attributes']['ID'])) {
                                                        $locationStr = $this->generatedHash($location);
                                                        $hash = hash('md5', $locationStr);
                                                        $this->insertInHashTable( 'LocationHash', $hash, ($settings['sPid']) ?: '0');
                                                        $columnExists = $this->columnExists('LocationRepository', $location['@attributes']['ID'], 'tx_nwcitavi_domain_model_location');
                                                        $this->setDatabase($location, 'Location', $columnExists, $hash, $key, $uniqueId, $settings);
                                                        // Location verknüpfen
                                                        $this->updateReferenceLocation($ref, $location['@attributes']['ID']);
                                                        //Libraries verknüpfen
                                                        if(!empty($iValue['Locations']['Location'][$j]['LibraryID'])) {
                                                            $this->updateLocationLibrary($location['@attributes']['ID'], $iValue['Locations']['Location'][$j]['LibraryID']);
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
                            	file_put_contents($dir.'/task.txt', 10);
                            } else {
                                file_put_contents($dir.'/task.txt', 10);
                            }
                        } catch (Exception $e) {
                            $this->logRepository->addLog(1, 'Locations could not be generated. Fehler: '.$e, 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Parse the files send with the xml data
     */
    public function taskParseXMLFiles(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $uniqueId = null;
        $key = null;
        try {
            $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
                $taskExists = file_exists($dir.'/task.txt');
                if($taskExists) {
                    $taskString = file_get_contents ( $dir.'/task.txt' );
                    $taskCols = explode('|', $taskString);
                    if((int)$taskCols[0] === 10) {
                        $schedulerString = file_get_contents ( $dir.'/scheduler.txt' );
                        $schedulerCols = explode('|', $schedulerString);
                        [$fileName, $key, $uniqueId] = $schedulerCols;
                        unlink($fileName);
                        if(file_exists($fileName)) {
                            $this->logRepository->addLog(1, 'File "'.$fileName.'" could not be deleted', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task still working.', ''.$key.'');
                        }
                        // FileReference erstellen
                        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/files/');
                        $fileCheck = file_exists($dir.'/files.txt');
                        if($fileCheck) {
                            $file = $dir.'/files.txt';
                            $file_handle = fopen($file, 'r');
                            while (!feof($file_handle)) {
                                $line = fgets($file_handle);
                                if(!empty($line)) {
                                    $fileCols = explode('|', $line);
                                    [$referenceId, $file, $fileType] = $fileCols;
                                    $this->setFileReferences($referenceId, $file, $fileType);
                                }
                            }
                            fclose($file_handle);
                            unlink($dir.'/files.txt');
                            if(file_exists($dir.'/files.txt')) {
                                $this->logRepository->addLog(1, 'File "'.$dir.'/files.txt" could not be deleted', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task still working.', ''.$key.'');
                            }
                        }
                        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
                        file_put_contents($dir.'/task.txt', 11);
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Cleans all tables and the exports after a import
     */
    public function taskParseXMLCleaner(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $schedulerCols = explode('|', $dir.'/scheduler.txt');
        [$fileName, $key, $uniqueId] = $schedulerCols;
        try {
            //$this->initTSFE($this->getRootpage($objectManager));
            //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $check = file_exists($dir.'/scheduler.txt');
            if($check) {
            	$taskExists = file_exists($dir.'/task.txt');
            	if($taskExists) {
            	    $taskString = file_get_contents ( $dir.'/task.txt' );
            	    $taskCols = explode('|', $taskString);
            	    if((int)$taskCols[0] === 11) {
            	        $this->compareHashData($settings);
            	        $this->compareExportFiles($settings);

            	        file_put_contents($dir.'/task.txt', 12);
            	    }
            	}
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Generates person sorting in the database
     *
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function taskParseXMLSorting(): void
    {
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
        $schedulerCols = explode('|', $dir.'/scheduler.txt');
        [$fileName, $key, $uniqueId] = $schedulerCols;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        try {
            //$this->initTSFE($this->getRootpage($objectManager));
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $settings = $fullTs['plugin.']['tx_nwcitavi.']['settings.'];
            $taskExists = file_exists($dir . '/task.txt');
            if ($taskExists) {
                $taskString = file_get_contents($dir . '/task.txt');
                $taskCols = explode('|', $taskString);
                if ((int)$taskCols[0] === 12) {
                    $query = $this->createQuery();
                    $res = $query->execute();
                    foreach($res as $obj) {
                        $reference = $obj;
                        $authors = $reference->getAuthors();
                        $i = 0;
                        foreach ($authors as $author) {
                            if($i === 0) {
                                $reference->setSortPerson($author->getLastName());
                            }
                            $i++;
                        }
                        if(count($authors) === 0) {
                            $editors = $reference->getEditors();
                            $i = 0;
                            foreach ($editors as $editor) {
                                if($i === 0) {
                                    $reference->setSortPerson($editor->getLastName());
                                }
                                $i++;
                            }
                            if(count($editors) === 0) {
                                $organizations = $reference->getOrganizations();
                                $i = 0;
                                foreach ($organizations as $organization) {
                                    if($i === 0) {
                                        $reference->setSortPerson($organization->getLastName());
                                    }
                                    $i++;
                                }
                            }
                        }
                        $this->update($reference);
                        $persistenceManager = $this->objectManager->get(PersistenceManager::class);
                        $persistenceManager->persistAll();
                    }

                    unlink($dir.'/task.txt');

                    if(file_exists($dir.'/task.txt')) {
                        $this->logRepository->addLog(1, 'File "'.$dir.'/task.txt" could not be deleted', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task still working.', ''.$key.'');
                    }

                    unlink($dir.'/scheduler.txt');

                    if(file_exists($dir.'/scheduler.txt')) {
                        $this->logRepository->addLog(1, 'File "'.$dir.'/scheduler.txt" could not be deleted', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task still working.', ''.$key.'');
                    }
                }
            }
        } catch (Exception $e) {
            $this->logRepository->addLog(1, 'Fehler: '.$e.'', 'Parser', ''.$uniqueId.'', '[Citavi Parser]: Task was terminated.', ''.$key.'');
        }
    }

    /**
     * Compare the export files in temp and export dir and unlink if nessesary
     *
     * @param $settings
     */
    public function compareExportFiles($settings): void
    {
        $exportDir = GeneralUtility::getFileAbsFileName($settings['export.']['path']);
        $tempDir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/temp/');
        $filesInExportDir = scandir($exportDir);
        $filesInExportDir = count($filesInExportDir)-2;
        $filesInTempDir = scandir($tempDir);
        $filesInTempDir = count($filesInTempDir)-2;
        if(($filesInExportDir > 0) && is_dir($exportDir)) {
            array_map(function($value) {
                $this->delete($value);
                rmdir($value);
            },glob($exportDir . '*', GLOB_ONLYDIR));
            array_map('unlink', glob($exportDir. '*'));
        }
        if(($filesInTempDir > 0) && is_dir($exportDir) && is_dir($tempDir)) {
            $src = 'temp';
            $dst = 'export';
            $files = glob($tempDir . '*');
            foreach($files as $file){
                $file_to_go = str_replace($src,$dst,$file);
                copy($file, $file_to_go);
            }
            array_map(function($value) {
                $this->delete($value);
                rmdir($value);
            },glob($tempDir . '*', GLOB_ONLYDIR));
            array_map('unlink', glob($tempDir. '*'));
        }
    }

    /**
     * Set file reference relation between reference and file
     *
     * @param $referenceId
     * @param $file
     * @param $fileType
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @throws FileDoesNotExistException
     */
    public function setFileReferences($referenceId, $file, $fileType): void
    {
        $reference = $this->getByCitaviId($referenceId);
        if($reference) {
            $resourceFactory = ResourceFactory::getInstance();
            $fileReference = $resourceFactory->getFileObject((int)$file);
            $newFileReference = $this->objectManager->get(FileReference::class);
            $newFileReference->setFile($fileReference);
            if(trim($fileType) === 'cover') {
                $reference->setCover($newFileReference);
                $this->update($reference);
                $persistenceManager = $this->objectManager->get(PersistenceManager::class);
                $persistenceManager->persistAll();
            }
            if(trim($fileType) === 'attachment') {
            	$reference->setAttachment($newFileReference);
            	$this->update($reference);
            	$persistenceManager = $this->objectManager->get(PersistenceManager::class);
            	$persistenceManager->persistAll();
            }
        }
    }

    /**
     * Return the reference from passed id
     *
     * @param $referenceId
     * @return mixed|null
     */
    public function getByCitaviId($referenceId) {
        $reference = null;
  	    $query = $this->createQuery();
  	    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_reference WHERE citavi_id LIKE \''.$referenceId.'\'';
  	    $query->statement($sql);
  	    $res = $query->execute();
  	    foreach($res as $obj) {
  	        $reference = $obj;
  	    }
  	    return $reference;
  	}

    /**
     * action importXML
     *
     * @param $uniqueId
     * @param $logRepository
     * @return string JSON
     * @throws InsufficientFolderAccessPermissionsException
     */
    public function importXML($uniqueId, $logRepository): ?string
    {
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
        file_put_contents($dir.'/log/upload.txt', 'importXML|start'.chr(10), FILE_APPEND);
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_nwcitavi.']['settings.'];
        if(is_writable($dir)) {
            file_put_contents($dir.'/log/upload.txt', 'dir|'.$dir.chr(13).chr(10), FILE_APPEND);
            if ($_POST['import_key']) {
                file_put_contents($dir.'/log/upload.txt', 'import_key|'.$_POST['import_key'].chr(13).chr(10), FILE_APPEND);
                if($_FILES['file']) {
                    file_put_contents($dir.'/log/upload.txt', 'file name|'.$_FILES['file']['name'].chr(13).chr(10), FILE_APPEND);
                    $fileProcessor = GeneralUtility::makeInstance(ExtendedFileUtility::class);

                    $fileName = $fileProcessor->getUniqueName(
                        $_FILES['file']['name'],
                        $dir
                    );

                    GeneralUtility::upload_copy_move(
                        $_FILES['file']['tmp_name'],
                        $fileName
                    );

                    $zip = new ZipArchive;
                    if ($zip->open($fileName) === TRUE) {
                        $res = $zip->extractTo($dir);
                        $zip->close();
                        if($res) {
                            unlink($fileName);
                        }
                    } else {
                        $statusCodeText = $this->getStatusCodeText(418);
                        $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
                        $this->getStatusCode(418);
                        file_put_contents($dir.'/log/upload.txt', 'error|418'.chr(13).chr(10), FILE_APPEND);
                    }

                    $resourceFactory = ResourceFactory::getInstance();
                    $defaultStorage = $resourceFactory->getDefaultStorage();
                    $folder = $defaultStorage->getFolder('/user_upload/citavi_upload/');
                    $files = $defaultStorage->getFilesInFolder($folder);
                    if(is_array($files)) {
                        foreach($files as $file) {
                            $thisFile = $file->getProperties();
                            if($thisFile['extension'] === 'xml') {
                                $folder2 = $defaultStorage->getFolder('/user_upload/citavi_upload/backup/');
                                $files2 = $defaultStorage->getFilesInFolder($folder2);
                                $uploadDir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/');
                                $backupDir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/backup/');
                                if(is_array($files2)) {
                                    $numFiles = count($files2);
                                    if($numFiles < 10) {
                                        GeneralUtility::upload_copy_move(
                                            $uploadDir.$thisFile['name'],
                                            $backupDir.time().'_'.$thisFile['name']
                                        );
                                        return true;
                                    }
                                    $i = 0;
                                    foreach($files2 as $file2) {
                                        if($i === 0) {
                                            $thisFile2 = $file2->getProperties();
                                            unlink($backupDir.$thisFile2['name']);
                                        }
                                        $i++;
                                    }
                                    GeneralUtility::upload_copy_move(
                                        $uploadDir.$thisFile['name'],
                                        $backupDir.time().'_'.$thisFile['name']
                                    );
                                    return true;
                                }
                            }
                        }
                    }
                } else {
                    $statusCodeText = $this->getStatusCodeText(419);
                    $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
                    $this->getStatusCode(419);
                    file_put_contents($dir.'/log/upload.txt', 'error|419'.chr(13).chr(10), FILE_APPEND);
                }
            } else {
                $statusCodeText = $this->getStatusCodeText(417);
                $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
                $this->getStatusCode(417);
                file_put_contents($dir.'/log/upload.txt', 'error|417'.chr(13).chr(10), FILE_APPEND);
                exit;
            }
        } else {
            $statusCodeText = $this->getStatusCodeText(432);
            $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi XML Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
            $this->getStatusCode(432);
            file_put_contents($dir.'/log/upload.txt', 'error|432'.chr(13).chr(10), FILE_APPEND);
            exit;
        }
        return true;
    }

    /**
     * action importExport
     *
     * @param $uniqueId
     * @return bool JSON
     */
  	public function importExport($uniqueId): bool
    {
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload');
        file_put_contents($dir.'/log/import.txt', '$_FILES: '.serialize($_FILES), FILE_APPEND);
        file_put_contents($dir.'/log/import.txt', '$_POST: '.serialize($_POST), FILE_APPEND);
        $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/temp/');
        if(is_writable($dir)) {
            if ($_FILES['file'] && $_POST['import_key']) {
                $fileProcessor = GeneralUtility::makeInstance(ExtendedFileUtility::class);

                $fileName = $fileProcessor->getUniqueName(
                    $_FILES['file']['name'],
                    $dir
                );

                $upload_copy_move = GeneralUtility::upload_copy_move(
                    $_FILES['file']['tmp_name'],
                    $fileName
                );

                $zip = new ZipArchive;
                if ($zip->open($fileName) === TRUE) {
                    $res = $zip->extractTo($dir);
                    $zip->close();
                    if($res) {
                        unlink($fileName);
                    }
                } else {
                    $statusCodeText = $this->getStatusCodeText(418);
                    $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi Export Upload]: Upload was terminated (418). ['.$upload_copy_move.'] '.$fileName.' could not be open. '.serialize($_FILES).'', ''.$_POST['import_key'].'');
                    $this->getStatusCode(418);
                }
            } else {
                $statusCodeText = $this->getStatusCodeText(417);
                $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi Export Upload]: Upload was terminated (417).', ''.$_POST['import_key'].'');
                $this->getStatusCode(417);
                exit;
            }
        } else {
            $statusCodeText = $this->getStatusCodeText(432);
            $this->logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi Export Upload]: Upload was terminated (432).', ''.$_POST['import_key'].'');
            $this->getStatusCode(432);
            exit;
        }
        return true;
    }

    /**
     * Check if entry already exists in the passed database table
     *
     * @param $table
     * @param $field
     * @param $valueObj
     * @return mixed
     */
    public function checkIfEntryExists($table, $field, $valueObj) {
  	    $value = (array)$valueObj;
        $data = array();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $statement = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->like($field, $queryBuilder->createNamedParameter($value[0]))
            )
            ->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
            if($i === 0) {
                $data = $row;
            }
            $i++;
        }
  	    /*$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
  	        '*',
            $table,
            $field.' LIKE \''.$value[0].'\'',
            '',
            '',
            '');
  		$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);*/
  	    return $data;
    }

    /**
     * action importFile
     *
     * @param $uniqueId
     * @param $logRepository
     * @param $settings
     * @return string JSON
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
  	public function importFile($uniqueId, $logRepository, $settings): ?string
    {
        $movedNewFile = null;
  	    $dir = GeneralUtility::getFileAbsFileName('fileadmin/user_upload/citavi_upload/files/');
  	    if(is_writable($dir)) {
  	        if ($_FILES['file'] && $_POST['import_key']) {
  	            if ($settings['scheduler'] === 1) {
  	                $originalFilePath = $_FILES['file']['tmp_name'];
  	                $newFileName = $_FILES['file']['name'];
  	                $localDriver = $this->objectManager->get(LocalDriver::class);
  	                if (!file_exists($dir.$localDriver->sanitizeFileName($newFileName))) {
  	                    $storageRepository = $this->objectManager->get(StorageRepository::class);
  	                    $storage = $storageRepository->findByUid((int)$settings['fileStoragePid']);
  	                    $targetFolder = $storage->getFolder('files');
  	                    if (file_exists($originalFilePath)) {
  	                        $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
  	                        $newFileReference = $this->objectManager->get(FileReference::class);
  	                        $newFileReference->setFile($movedNewFile);
  	                    }
  	                    $persistenceManager = $this->objectManager->get(PersistenceManager::class);
  	                    $persistenceManager->persistAll();
  	                    file_put_contents($dir.'/files.txt', $_POST['referenceId'].'|'.$movedNewFile->getUid().'|'.$_POST['fileType'].chr(13).chr(10), FILE_APPEND);
  	                }
  	                return true;
  	            } else {
  	                $referenceQueryResult = $this->findByCitaviId($_POST['referenceId']);
  	                $reference = $referenceQueryResult[0];
  	                if($reference) {
  	                    if($_POST['fileType'] === 'cover') {
  	                        $originalFilePath = $_FILES['file']['tmp_name'];
  	                        $newFileName = $_FILES['file']['name'];
  	                        $localDriver = $this->objectManager->get(LocalDriver::class);
  	                        if (!file_exists($dir.$localDriver->sanitizeFileName($newFileName))) {
  	                            $storageRepository = $this->objectManager->get(StorageRepository::class);
  	                            $storage = $storageRepository->findByUid((int)$settings['fileStoragePid']);
  	                            $targetFolder = $storage->getFolder('files');
  	                            if (file_exists($originalFilePath)) {
  	                                $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
  	                                $newFileReference = $this->objectManager->get(FileReference::class);
  	                                $newFileReference->setFile($movedNewFile);
  	                                $reference->setCover($newFileReference);
  	                            }
  	                            $this->update($reference);
  	                            $persistenceManager = $this->objectManager->get(PersistenceManager::class);
  	                            $persistenceManager->persistAll();
  	                        }
  	                    }
  	                    if($_POST['fileType'] === 'attachment') {
  	                        $originalFilePath = $_FILES['file']['tmp_name'];
  	                        $newFileName = $_FILES['file']['name'];
  	                        $localDriver = $this->objectManager->get(LocalDriver::class);
  	                        if (!file_exists($dir.$localDriver->sanitizeFileName($newFileName))) {
  	                            $storageRepository = $this->objectManager->get(StorageRepository::class);
  	                            $storage = $storageRepository->findByUid((int)$settings['fileStoragePid']);
  	                            $targetFolder = $storage->getFolder('files');
  	                            if (file_exists($originalFilePath)) {
  	                                $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
  	                                $newFileReference = $this->objectManager->get(FileReference::class);
  	                                $newFileReference->setFile($movedNewFile);
  	                                if($newFileReference !== null) {
  	                                    $reference->setAttachment($newFileReference);
  	                                }
  	                            }
  	                            $this->update($reference);
  	                            $persistenceManager = $this->objectManager->get(PersistenceManager::class);
  	                            $persistenceManager->persistAll();
  	                        }
  	                    }
  	                }
  	                return true;
  	            }
  	        } else {
  	            $statusCodeText = $this->getStatusCodeText(417);
  	            $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
  	            $this->getStatusCode(417);
  	            exit;
  	        }
  	    } else {
  	        $statusCodeText = $this->getStatusCodeText(432);
  	        $logRepository->addLog(1, ''.$statusCodeText['text'].': '.$statusCodeText['msg'].'', 'Upload', ''.$uniqueId.'', '[Citavi Upload]: Upload was terminated.', ''.$_POST['import_key'].'', $settings['sPid']);
  	        $this->getStatusCode(432);
  	        exit;
  	    }
  	}

    /**
     * Get all available reference types
     *
     * @return null |null
     */
  	public function findAllReferenceTypOptions() {
  	    $option = null;
        $options = null;
        $reference = null;
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $statement = $queryBuilder
            ->select('reference_type')
            ->from('tx_nwcitavi_domain_model_reference')
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
            )
            ->groupBy('tx_nwcitavi_domain_model_reference.reference_type')
            ->orderBy('tx_nwcitavi_domain_model_reference.reference_type')
            ->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
            $options[$i]['id'] = $row['reference_type'];
            $options[$i]['title'] = LocalizationUtility::translate('tx_nwcitavi_domain_model_reference.'.$row['reference_type'], 'nw_citavi_fe');
            $i++;
        }
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $row) {
                $option[$key] = $row['title'];
            }
            if (is_array($option) || is_object($option)) {
                array_multisort($option, SORT_ASC, $options);
            }
        }
        return $options;
    }

    /**
     * Get all available years
     *
     * @return null |null
     */
    public function findAllYearOptions() {
        $option = null;
        $options = null;
        $reference = null;
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $statement = $queryBuilder
            ->select('sort_date')
            ->from('tx_nwcitavi_domain_model_reference')
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
            )
            ->groupBy('tx_nwcitavi_domain_model_reference.sort_date')
            ->orderBy('tx_nwcitavi_domain_model_reference.sort_date')
            ->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
            if(!empty($row['sort_date'])) {
                $options[$i]['id'] = $row['sort_date'];
                $options[$i]['title'] = $row['sort_date'];
                $i++;
            }
        }
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $row) {
                $option[$key] = $row['title'];
            }
            if (is_array($option) || is_object($option)) {
                array_multisort($option, SORT_DESC, $options);
            }
        }
        return $options;
    }

    /**
     * Check if the advenced search should be displayed
     *
     * @param $settings
     * @return bool
     */
    public function displayAdvancedSearch($settings): bool
    {
  	    if($settings['searchYearfrom'] && $settings['searchYearfrom'] != '-1') {
            return true;
        }
  	    if($settings['searchYearto'] && $settings['searchYearto'] != '-1') {
            return true;
        }
  	    if($settings['searchReferencetype'] && $settings['searchReferencetype'] != '-1') {
            return true;
        }
  	    if($settings['searchCategories'] && $settings['searchCategories'] != '-1') {
            return true;
        }
  	    if($settings['searchKeywords'] && $settings['searchKeywords'] != '-1') {
            return true;
        }
  	    if($settings['searchKnowledgeitems'] && $settings['searchKnowledgeitems'] != '-1') {
            return true;
        }
  	    if($settings['searchLibraries'] && $settings['searchLibraries'] != '-1') {
            return true;
        }
  	    if($settings['searchLocations'] && $settings['searchLocations'] != '-1') {
            return true;
        }
  	    if($settings['searchPeriodicals'] && $settings['searchPeriodicals'] != '-1') {
            return true;
        }
  	    if($settings['searchPersons'] && $settings['searchPersons'] != '-1') {
            return true;
        }
  	    if($settings['searchEditors'] && $settings['searchEditors'] != '-1') {
            return true;
        }
  	    if($settings['searchAuthors'] && $settings['searchAuthors'] != '-1') {
            return true;
        }
  	    if($settings['searchPublishers'] && $settings['searchPublishers'] != '-1') {
            return true;
        }
  	    if($settings['searchSeriestitles'] && $settings['searchSeriestitles'] != '-1') {
            return true;
        }
  	    if($settings['searchSpecialcategories'] && $settings['searchSpecialcategories'] != '-1') {
            return true;
        }
        return false;
  	}

    /**
     * Get the UID of the root page
     *
     * @param $objectManager
     * @return mixed
     */
  	public function getRootpage($objectManager) {
        $pageRepository = $objectManager->get(PageRepository::class);
        //$pageRepository->init(FALSE);
        $defaultPageIds = \array_keys($pageRepository->getMenu(0, 'uid'));
        return $defaultPageIds[0];
    }

    /**
     *
     *
     * @param $settings
     * @param $page
     * @return array|QueryResultInterface
     */
    public function findAllByFilter($settings, $page): object {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($this->getAndOrConstraints($query, $settings)));
        if($settings['showAllOnOnePage'] === '0') {
            $query->setLimit((int)$settings['pagelimit']);
            $query->setOffset($page);
        }
        if($settings['showreferencetype'] === '1') {
            $query->setOrderings(
                [
                    'referenceType' => QueryInterface::ORDER_ASCENDING,
                    'sortDate' => QueryInterface::ORDER_DESCENDING,
                    'sortPerson' => QueryInterface::ORDER_ASCENDING
                ]
            );
        } else if ($settings['showyearheadline'] === '1') {
            $query->setOrderings(
                [
                    'sortDate' => QueryInterface::ORDER_DESCENDING,
                    'sortPerson' => QueryInterface::ORDER_ASCENDING
                ]
            );
        } else {
            $query->setOrderings(
                [
                    'sortPerson' => QueryInterface::ORDER_ASCENDING
                ]
            );
        }
        /*$queryParser = $this->objectManager->get(Typo3DbQueryParser::class);
        DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
        DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters());*/
        return $query->execute();
    }

    public function numAllByFilter($settings): int
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($this->getAndOrConstraints($query, $settings)));
        return $query->count();
    }

    /**
     * @param $query
     * @param $settings
     * @return array
     */
    public function getAndOrConstraints($query, $settings): array
    {
        //DebuggerUtility::var_dump($settings);
        $selectedAuthor = null;
        $authorConstraints = null;
        $author = null;
        $selectedPublisher = null;
        $publisher = null;
        $publisherConstraints = null;
        $selectedReferenceType = null;
        $referenceType = null;
        $referenceTypeConstraints = null;
        $selectedKeyword = null;
        $keyword = null;
        $keywordsConstraints = null;
        $selectedCategory = null;
        $category = null;
        $categoryConstraints = null;
        $categoriesConstraints = null;
        $selectedPersons = null;
        $person = null;
        $personsConstraints = null;
        $selectedEditors = null;
        $editor = null;
        $editorsConstraints = null;
        $selectedAuthors = null;
        $authorsConstraints = null;
        $selectedSpecialCategories = null;
        $specialCategory = null;
        $specialCategoriesConstraints = null;
        $selectedSeriesTitle = null;
        $seriesTitle = null;
        $seriesTitlesConstraints = null;
        $selectedPeriodical = null;
        $periodical = null;
        $periodicalsConstraints = null;
        $selectedSupervisors = null;
        $supervisor = null;
        $supervisorsConstraints = null;
        $constraints[] = $query->like('literaturlistId', $settings['import_key']);
        if(!empty($settings['searchstr'])) {
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
        if($settings['searchReferencetype'] > -1 && !empty($settings['searchReferencetype']) && !empty($settings['searchReferencetype'])) {
            if($settings['searchReferencetype'] === 'JournalArticlepeer-reviewed') {
                $constraints[] = $query->like('referenceType', 'JournalArticle');
                $constraints[] = $query->like('customField1', 'peer-reviewed');
            } else if($settings['searchReferencetype'] === 'JournalArticle') {
                $constraints[] = $query->like('referenceType', $settings['searchReferencetype']);
                $constraints[] = $query->logicalNot(
                    $query->like('customField1', 'peer-reviewed')
                );
            } else if($settings['searchReferencetype'] === 'ContributionBookEdited') {
                $constraints[] = $query->like('referenceType', 'Contribution');
                $constraints[] = $query->like('parentReferenceType', 'BookEdited');
            } else if($settings['searchReferencetype'] === 'ContributionConferenceProceedings') {
                $constraints[] = $query->like('referenceType', 'Contribution');
                $constraints[] = $query->like('parentReferenceType', 'ConferenceProceedings');
            } else {
                $constraints[] = $query->like('referenceType', $settings['searchReferencetype']);
            }
        }
        $selectedAuthor = explode(',', $settings['selectedauthor']);
        if(empty($selectedAuthor[0])) {
            unset($selectedAuthor);
        }
        if ( is_array( $selectedAuthor ) ) {
            $numberOfSelectedAuthor = count($selectedAuthor);
            if($numberOfSelectedAuthor > 0) {
                foreach($selectedAuthor as $key => $value) {
                    $author[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedAuthor; $i++) {
                    $authorConstraints[] = $query->contains('authors', $author[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $authorConstraints
                );
            }
        }
        $selectedPublisher = explode(',', $settings['selectedpublisher']);
        if(empty($selectedPublisher[0])) {
            unset($selectedPublisher);
        }
        if ( is_array( $selectedPublisher ) ) {
            $numberOfSelectedPublisher = count($selectedPublisher);
            if($numberOfSelectedPublisher > 0) {
                foreach($selectedPublisher as $key => $value) {
                    $publisher[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedPublisher; $i++) {
                    $publisherConstraints[] = $query->contains('publishers', $publisher[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $publisherConstraints
                );
            }
        }
        $selectedReferenceType = explode(',', $settings['selectedreferencetype']);
        if(empty($selectedReferenceType[0])) {
            unset($selectedReferenceType);
        }
        if ( is_array( $selectedReferenceType ) ) {
            $numberOfSelectedReferenceType = count($selectedReferenceType);
            if($numberOfSelectedReferenceType > 0) {
                foreach($selectedReferenceType as $key => $value) {
                    $referenceType[$key] = $value;
                }
                for($i = 0; $i < $numberOfSelectedReferenceType; $i++) {
                    if(!empty($referenceType[$i])) {
                        $referenceTypeConstraints[] = $query->like('referenceType', $referenceType[$i]);
                    }
                }
                $constraints[] = $query->logicalOr(
                    $referenceTypeConstraints
                );
            }
        }
        $selectedKeyword = explode(',', $settings['selectedkeyword']);
        if(empty($selectedKeyword[0])) {
            unset($selectedKeyword);
        }
        if ( is_array( $selectedKeyword ) ) {
            $numberOfSelectedKeyword = count($selectedKeyword);
            if($numberOfSelectedKeyword > 0) {
                foreach($selectedKeyword as $key => $value) {
                    $keyword[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedKeyword; $i++) {
                    $keywordsConstraints[] = $query->contains('keywords', $keyword[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $keywordsConstraints
                );
            }
        }
        $selectedSeriesTitle = explode(',', $settings['selectedseriestitle']);
        if(empty($selectedSeriesTitle[0])) {
            unset($selectedSeriesTitle);
        }
        if ( is_array( $selectedSeriesTitle ) ) {
            $numberOfSelectedSeriesTitle = count($selectedSeriesTitle);
            if($numberOfSelectedSeriesTitle > 0) {
                foreach($selectedSeriesTitle as $key => $value) {
                    $seriesTitle[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedSeriesTitle; $i++) {
                    $seriesTitlesConstraints[] = $query->contains('seriestitles', $seriesTitle[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $seriesTitlesConstraints
                );
            }
        }
        $selectedPeriodical = explode(',', $settings['selectedperiodical']);
        if(empty($selectedPeriodical[0])) {
            unset($selectedPeriodical);
        }
        if ( is_array( $selectedPeriodical ) ) {
            $numberOfSelectedPeriodical = count($selectedPeriodical);
            if($numberOfSelectedPeriodical > 0) {
                foreach($selectedPeriodical as $key => $value) {
                    $periodical[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedPeriodical; $i++) {
                    $periodicalsConstraints[] = $query->contains('periodicals', $periodical[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $periodicalsConstraints
                );
            }
        }
        $subcategories = $this->getSubCategories(explode(',', $settings['selectedcategory']), explode(',', $settings['selectedcategory']), count(explode(',', $settings['selectedcategory'])));
        if($subcategories) {
            $selectedCategory = $subcategories;
        } else {
            $selectedCategory = explode(',', $settings['selectedcategory']);
        }
        if(empty($selectedCategory[0])) {
            unset($selectedCategory);
        }
        if ( is_array( $selectedCategory ) ) {
            $numberOfSelectedCategory = count($selectedCategory);
            if($numberOfSelectedCategory > 0) {
                foreach($selectedCategory as $key => $value) {
                    $category[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedCategory; $i++) {
                    $categoryConstraints[] = $query->contains('categories', $category[$i]);
                }
                if($settings['selectedcategoryoperand'] === '0') {
                    $constraints[] = $query->logicalOr(
                        $categoryConstraints
                    );
                }
                else {
                    $constraints[] = $query->logicalAnd(
                        $categoryConstraints
                    );
                }
            }
        }
        $selectedCategories = explode(',', $settings['searchCategories']);
        if(empty($selectedCategories[0]) || $selectedCategories[0] === '-1') {
            unset($selectedCategories);
        }
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
        $selectedPersons = explode(',', $settings['searchPersons']);
        if(empty($selectedPersons[0]) || $selectedPersons[0] === '-1') {
            unset($selectedPersons);
        }
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
        $selectedEditors = explode(',', $settings['searchEditors']);
        if(empty($selectedEditors[0]) || $selectedEditors[0] === '-1') {
            unset($selectedEditors);
        }
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
        $selectedAuthors = explode(',', $settings['searchAuthors']);
        if(empty($selectedAuthors[0]) || $selectedAuthors[0] === '-1') {
            unset($selectedAuthors);
        }
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
        $selectedSupervisors = explode(',', $settings['searchSupervisors']);
        if(empty($selectedSupervisors[0]) || $selectedSupervisors[0] === '-1') {
            unset($selectedSupervisors);
        }
        if ( is_array( $selectedSupervisors ) ) {
            $numberOfSelectedSupervisors = count($selectedSupervisors);
            if($numberOfSelectedSupervisors > 0) {
                foreach($selectedSupervisors as $key => $value) {
                    $supervisor[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedSupervisors; $i++) {
                    $supervisorsConstraints[] = $query->contains('collaborators', $supervisor[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $supervisorsConstraints
                );
            }
        }
        $selectedSeriesTitles = explode(',', $settings['searchSeriestitles']);
        if(empty($selectedSeriesTitles[0]) || $selectedSeriesTitles[0] === '-1') {
            unset($selectedSeriesTitles);
        }
        if ( is_array( $selectedSeriesTitles ) ) {
            $numberOfSelectedSeriesTitles = count($selectedSeriesTitles);
            if($numberOfSelectedSeriesTitles > 0) {
                foreach($selectedSeriesTitles as $key => $value) {
                    $seriesTitle[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSelectedSeriesTitles; $i++) {
                    $seriesTitlesConstraints[] = $query->contains('seriestitles', $seriesTitle[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $seriesTitlesConstraints
                );
            }
        }
        $selectedSpecialCategories = explode(',', $settings['searchSpecialcategories']);
        if(empty($selectedSpecialCategories[0]) || $selectedSpecialCategories[0] === '-1') {
            unset($selectedSpecialCategories);
        }
        if ( is_array( $selectedSpecialCategories ) ) {
            $numberOfSpecialCategories = count($selectedSpecialCategories);
            if($numberOfSpecialCategories > 0) {
                foreach($selectedSpecialCategories as $key => $value) {
                    $specialCategory[$key] = (int)$value;
                }
                for($i = 0; $i < $numberOfSpecialCategories; $i++) {
                    $specialCategoriesConstraints[] = $query->contains('categories', $specialCategory[$i]);
                }
                $constraints[] = $query->logicalOr(
                    $specialCategoriesConstraints
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
        } else if((int)$settings['searchYearfrom'] > 0 && (int)$settings['searchYearto'] === -1) {
            $constraints[] = $query->logicalAnd(
                $query->logicalOr(
                    $query->greaterThanOrEqual('bookDate', (int)$settings['searchYearfrom']),
                    $query->greaterThanOrEqual('bookYear', (int)$settings['searchYearfrom'])
                )
            );
        } else if((int)$settings['searchYearfrom'] === -1 && (int)$settings['searchYearto'] > 0) {
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
        $notConstraints = $this->getNotConstraints($query, $settings);
        if($notConstraints) {
            $constraints[] = $notConstraints;
        }
        return $constraints;
    }

    public function getNotConstraints($query, $settings) {
        $referenceType = $settings['referencetype']['ignore'];
        $notConstraints = null;
        $constraints = null;
        if ( is_array( $referenceType ) ) {
            $numberOfIgnoredReferenceType = count($referenceType);
            if($numberOfIgnoredReferenceType > 0) {
                foreach ($referenceType as $i => $iValue) {
                    if(!empty($iValue)) {
                        $notConstraints[] = $query->logicalNot($query->like('referenceType', $referenceType[$i]));
                    }
                }
            }
            if (is_array($notConstraints) && count($notConstraints) > 0) {
                $constraints = $query->logicalAnd($notConstraints);
            }
        }

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
                    'parentReferenceType' => QueryInterface::ORDER_DESCENDING,
                    'customField1' => QueryInterface::ORDER_DESCENDING,
                    'sortDate' => QueryInterface::ORDER_DESCENDING
                );
            } else {
                $ordering = array(
                    'custom_field7, \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_DESCENDING,
                    'reference_type, \'JournalArticle\', \'Book\', \'Contribution\', \'Unknown\', \'SpecialIssue\', \'UnpublishedWork\', \'ConferenceProceedings\', \'BookEdited\', \'Lecture\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
                    'parentReferenceType' => QueryInterface::ORDER_DESCENDING,
                    'customField1' => QueryInterface::ORDER_DESCENDING,
                    'sortDate' => QueryInterface::ORDER_DESCENDING
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
                    'parentReferenceType' => QueryInterface::ORDER_DESCENDING,
                    'customField1' => QueryInterface::ORDER_DESCENDING,
                    'sortDate' => QueryInterface::ORDER_DESCENDING
                );
            } else {
                $ordering = array(
                    'reference_type, \'JournalArticle\', \'Book\', \'Contribution\', \'Unknown\', \'SpecialIssue\', \'UnpublishedWork\'' => \Netzweber\NwCitavi\Xclass\Typo3DbQueryParser::ORDER_FIELD_ASCENDING,
                    'parentReferenceType' => QueryInterface::ORDER_DESCENDING,
                    'customField1' => QueryInterface::ORDER_DESCENDING,
                    'sortDate' => QueryInterface::ORDER_DESCENDING
                );
            }
        }

        return $ordering;
    }
}
