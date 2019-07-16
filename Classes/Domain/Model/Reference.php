<?php
namespace Netzweber\NwCitavi\Domain\Model;

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
 * Reference
 */
class Reference extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * citaviHash
     *
     * @var string
     */
    protected $citaviHash = '';

    /**
     * citaviId
     *
     * @var string
     */
    protected $citaviId = '';

    /**
     * createdBy
     *
     * @var string
     */
    protected $createdBy = '';

    /**
     * createdBySid
     *
     * @var string
     */
    protected $createdBySid = '';

    /**
     * createdOn
     *
     * @var int
     */
    protected $createdOn = 0;

    /**
     * modifiedBy
     *
     * @var string
     */
    protected $modifiedBy = '';

    /**
     * modifiedBySid
     *
     * @var string
     */
    protected $modifiedBySid = '';

    /**
     * modifiedOn
     *
     * @var int
     */
    protected $modifiedOn = 0;

    /**
     * referenceType
     *
     * @var string
     */
    protected $referenceType = '';

    /**
     * parentReferenceType
     *
     * @var string
     */
    protected $parentReferenceType = '';

    /**
     * iSBN
     *
     * @var string
     */
    protected $iSBN = '';

    /**
     * sequenceNumber
     *
     * @var string
     */
    protected $sequenceNumber = '';

    /**
     * abstract
     *
     * @var string
     */
    protected $abstract = '';

    /**
     * abstractRTF
     *
     * @var string
     */
    protected $abstractRTF = '';

    /**
     * accessDate
     *
     * @var string
     */
    protected $accessDate = '';

    /**
     * additions
     *
     * @var string
     */
    protected $additions = '';

    /**
     * refAuthors
     *
     * @var string
     */
    protected $refAuthors = '';

    /**
     * refCategories
     *
     * @var string
     */
    protected $refCategories = '';

    /**
     * citationKeyUpdateType
     *
     * @var string
     */
    protected $citationKeyUpdateType = '';

    /**
     * refCollaborators
     *
     * @var string
     */
    protected $refCollaborators = '';

    /**
     * customField1
     *
     * @var string
     */
    protected $customField1 = '';

    /**
     * customField2
     *
     * @var string
     */
    protected $customField2 = '';

    /**
     * customField3
     *
     * @var string
     */
    protected $customField3 = '';

    /**
     * customField4
     *
     * @var string
     */
    protected $customField4 = '';

    /**
     * customField5
     *
     * @var string
     */
    protected $customField5 = '';

    /**
     * customField6
     *
     * @var string
     */
    protected $customField6 = '';

    /**
     * customField7
     *
     * @var string
     */
    protected $customField7 = '';

    /**
     * customField8
     *
     * @var string
     */
    protected $customField8 = '';

    /**
     * customField9
     *
     * @var string
     */
    protected $customField9 = '';

    /**
     * bookDate
     *
     * @var string
     */
    protected $bookDate = '';

    /**
     * defaultLocationID
     *
     * @var string
     */
    protected $defaultLocationID = '';

    /**
     * edition
     *
     * @var string
     */
    protected $edition = '';

    /**
     * refEditors
     *
     * @var string
     */
    protected $refEditors = '';

    /**
     * evaluation
     *
     * @var string
     */
    protected $evaluation = '';

    /**
     * evaluationRTF
     *
     * @var string
     */
    protected $evaluationRTF = '';

    /**
     * refKeywords
     *
     * @var string
     */
    protected $refKeywords = '';

    /**
     * bookLanguage
     *
     * @var string
     */
    protected $bookLanguage = '';

    /**
     * bookNote
     *
     * @var string
     */
    protected $bookNote = '';

    /**
     * number
     *
     * @var string
     */
    protected $number = '';

    /**
     * numberOfVolumes
     *
     * @var string
     */
    protected $numberOfVolumes = '';

    /**
     * onlineAddress
     *
     * @var string
     */
    protected $onlineAddress = '';

    /**
     * refOrganizations
     *
     * @var string
     */
    protected $refOrganizations = '';

    /**
     * originalCheckedBy
     *
     * @var string
     */
    protected $originalCheckedBy = '';

    /**
     * originalPublication
     *
     * @var string
     */
    protected $originalPublication = '';

    /**
     * refOthersInvolved
     *
     * @var string
     */
    protected $refOthersInvolved = '';

    /**
     * pageCount
     *
     * @var string
     */
    protected $pageCount = '';

    /**
     * pageRange
     *
     * @var string
     */
    protected $pageRange = '';

    /**
     * parallelTitle
     *
     * @var string
     */
    protected $parallelTitle = '';

    /**
     * refPeriodical
     *
     * @var string
     */
    protected $refPeriodical = '';

    /**
     * placeOfPublication
     *
     * @var string
     */
    protected $placeOfPublication = '';

    /**
     * price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * refPublishers
     *
     * @var string
     */
    protected $refPublishers = '';

    /**
     * rating
     *
     * @var int
     */
    protected $rating = 0;

    /**
     * refSeriesTitle
     *
     * @var string
     */
    protected $refSeriesTitle = '';

    /**
     * shortTitle
     *
     * @var string
     */
    protected $shortTitle = '';

    /**
     * sourceOfBibliographicInformation
     *
     * @var string
     */
    protected $sourceOfBibliographicInformation = '';

    /**
     * specificField1
     *
     * @var string
     */
    protected $specificField1 = '';

    /**
     * specificField2
     *
     * @var string
     */
    protected $specificField2 = '';

    /**
     * specificField4
     *
     * @var string
     */
    protected $specificField4 = '';

    /**
     * specificField7
     *
     * @var string
     */
    protected $specificField7 = '';

    /**
     * storageMedium
     *
     * @var string
     */
    protected $storageMedium = '';

    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle = '';

    /**
     * tableOfContents
     *
     * @var string
     */
    protected $tableOfContents = '';

    /**
     * tableOfContentsRTF
     *
     * @var string
     */
    protected $tableOfContentsRTF = '';

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * titleInOtherLanguages
     *
     * @var string
     */
    protected $titleInOtherLanguages = '';

    /**
     * titleSupplement
     *
     * @var string
     */
    protected $titleSupplement = '';

    /**
     * translatedTitle
     *
     * @var string
     */
    protected $translatedTitle = '';

    /**
     * uniformTitle
     *
     * @var string
     */
    protected $uniformTitle = '';

    /**
     * bookVolume
     *
     * @var string
     */
    protected $bookVolume = '';

    /**
     * bookYear
     *
     * @var string
     */
    protected $bookYear = '';

    /**
     * pageRangeStart
     *
     * @var string
     */
    protected $pageRangeStart = '';

    /**
     * pageRangeEnd
     *
     * @var string
     */
    protected $pageRangeEnd = '';

    /**
     * doi
     *
     * @var string
     */
    protected $doi = '';

    /**
     * sortDate
     *
     * @var string
     */
    protected $sortDate = '';
    
    /**
     * txExtbaseType
     *
     * @var string
     */
    protected $txExtbaseType = '';

    /**
     * literaturlistId
     *
     * @var string
     */
    protected $literaturlistId = '';

    /**
     * attachment
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $attachment = null;

    /**
     * cover
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $cover = null;

    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category>
     */
    protected $categories = null;

    /**
     * keywords
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Keyword>
     */
    protected $keywords = null;

    /**
     * editors
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person>
     */
    protected $editors = null;

    /**
     * authors
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person>
     */
    protected $authors = null;

    /**
     * othersInvolved
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person>
     */
    protected $othersInvolved = null;

    /**
     * collaborators
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person>
     */
    protected $collaborators = null;

    /**
     * organizations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person>
     */
    protected $organizations = null;

    /**
     * publishers
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Publisher>
     */
    protected $publishers = null;

    /**
     * periodicals
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Periodical>
     */
    protected $periodicals = null;

    /**
     * seriestitles
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Seriestitle>
     */
    protected $seriestitles = null;

    /**
     * knowledgeItems
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\KnowledgeItem>
     */
    protected $knowledgeItems = null;

    /**
     * locations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Location>
     */
    protected $locations = null;

    /**
     * parentReferences
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference>
     */
    protected $parentReferences = null;

    /**
     * childReferences
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference>
     */
    protected $childReferences = null;

    /**
     * Returns the citaviHash
     *
     * @return string $citaviHash
     */
    public function getCitaviHash()
    {
        return $this->citaviHash;
    }

    /**
     * Sets the citaviHash
     *
     * @param string $citaviHash
     * @return void
     */
    public function setCitaviHash($citaviHash)
    {
        $this->citaviHash = $citaviHash;
    }

    /**
     * Returns the citaviId
     *
     * @return string $citaviId
     */
    public function getCitaviId()
    {
        return $this->citaviId;
    }

    /**
     * Sets the citaviId
     *
     * @param string $citaviId
     * @return void
     */
    public function setCitaviId($citaviId)
    {
        $this->citaviId = $citaviId;
    }

    /**
     * Returns the createdBy
     *
     * @return string $createdBy
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets the createdBy
     *
     * @param string $createdBy
     * @return void
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Returns the createdBySid
     *
     * @return string $createdBySid
     */
    public function getCreatedBySid()
    {
        return $this->createdBySid;
    }

    /**
     * Sets the createdBySid
     *
     * @param string $createdBySid
     * @return void
     */
    public function setCreatedBySid($createdBySid)
    {
        $this->createdBySid = $createdBySid;
    }

    /**
     * Returns the createdOn
     *
     * @return int $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Sets the createdOn
     *
     * @param int $createdOn
     * @return void
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * Returns the modifiedBy
     *
     * @return string $modifiedBy
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Sets the modifiedBy
     *
     * @param string $modifiedBy
     * @return void
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Returns the modifiedBySid
     *
     * @return string $modifiedBySid
     */
    public function getModifiedBySid()
    {
        return $this->modifiedBySid;
    }

    /**
     * Sets the modifiedBySid
     *
     * @param string $modifiedBySid
     * @return void
     */
    public function setModifiedBySid($modifiedBySid)
    {
        $this->modifiedBySid = $modifiedBySid;
    }

    /**
     * Returns the modifiedOn
     *
     * @return int $modifiedOn
     */
    public function getModifiedOn()
    {
        return $this->modifiedOn;
    }

    /**
     * Sets the modifiedOn
     *
     * @param int $modifiedOn
     * @return void
     */
    public function setModifiedOn($modifiedOn)
    {
        $this->modifiedOn = $modifiedOn;
    }

    /**
     * Returns the referenceType
     *
     * @return string $referenceType
     */
    public function getReferenceType()
    {
        return $this->referenceType;
    }

    /**
     * Sets the referenceType
     *
     * @param string $referenceType
     * @return void
     */
    public function setReferenceType($referenceType)
    {
        $this->referenceType = $referenceType;
    }

    /**
     * Returns the parentReferenceType
     *
     * @return string $parentReferenceType
     */
    public function getParentReferenceType()
    {
        return $this->parentReferenceType;
    }

    /**
     * Sets the parentReferenceType
     *
     * @param string $parentReferenceType
     * @return void
     */
    public function setParentReferenceType($parentReferenceType)
    {
        $this->parentReferenceType = $parentReferenceType;
    }

    /**
     * Returns the iSBN
     *
     * @return string $iSBN
     */
    public function getISBN()
    {
        return $this->iSBN;
    }

    /**
     * Sets the iSBN
     *
     * @param string $iSBN
     * @return void
     */
    public function setISBN($iSBN)
    {
        $this->iSBN = $iSBN;
    }

    /**
     * Returns the sequenceNumber
     *
     * @return string $sequenceNumber
     */
    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * Sets the sequenceNumber
     *
     * @param string $sequenceNumber
     * @return void
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * Returns the abstract
     *
     * @return string $abstract
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Sets the abstract
     *
     * @param string $abstract
     * @return void
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * Returns the abstractRTF
     *
     * @return string $abstractRTF
     */
    public function getAbstractRTF()
    {
        return $this->abstractRTF;
    }

    /**
     * Sets the abstractRTF
     *
     * @param string $abstractRTF
     * @return void
     */
    public function setAbstractRTF($abstractRTF)
    {
        $this->abstractRTF = $abstractRTF;
    }

    /**
     * Returns the accessDate
     *
     * @return string $accessDate
     */
    public function getAccessDate()
    {
        return $this->accessDate;
    }

    /**
     * Sets the accessDate
     *
     * @param string $accessDate
     * @return void
     */
    public function setAccessDate($accessDate)
    {
        $this->accessDate = $accessDate;
    }

    /**
     * Returns the additions
     *
     * @return string $additions
     */
    public function getAdditions()
    {
        return $this->additions;
    }

    /**
     * Sets the additions
     *
     * @param string $additions
     * @return void
     */
    public function setAdditions($additions)
    {
        $this->additions = $additions;
    }

    /**
     * Returns the refAuthors
     *
     * @return string $refAuthors
     */
    public function getRefAuthors()
    {
        return $this->refAuthors;
    }

    /**
     * Sets the refAuthors
     *
     * @param string $refAuthors
     * @return void
     */
    public function setRefAuthors($refAuthors)
    {
        $this->refAuthors = $refAuthors;
    }

    /**
     * Returns the refCategories
     *
     * @return string $refCategories
     */
    public function getRefCategories()
    {
        return $this->refCategories;
    }

    /**
     * Sets the refCategories
     *
     * @param string $refCategories
     * @return void
     */
    public function setRefCategories($refCategories)
    {
        $this->refCategories = $refCategories;
    }

    /**
     * Returns the citationKeyUpdateType
     *
     * @return string $citationKeyUpdateType
     */
    public function getCitationKeyUpdateType()
    {
        return $this->citationKeyUpdateType;
    }

    /**
     * Sets the citationKeyUpdateType
     *
     * @param string $citationKeyUpdateType
     * @return void
     */
    public function setCitationKeyUpdateType($citationKeyUpdateType)
    {
        $this->citationKeyUpdateType = $citationKeyUpdateType;
    }

    /**
     * Returns the refCollaborators
     *
     * @return string $refCollaborators
     */
    public function getRefCollaborators()
    {
        return $this->refCollaborators;
    }

    /**
     * Sets the refCollaborators
     *
     * @param string $refCollaborators
     * @return void
     */
    public function setRefCollaborators($refCollaborators)
    {
        $this->refCollaborators = $refCollaborators;
    }

    /**
     * Returns the customField1
     *
     * @return string $customField1
     */
    public function getCustomField1()
    {
        return $this->customField1;
    }

    /**
     * Sets the customField1
     *
     * @param string $customField1
     * @return void
     */
    public function setCustomField1($customField1)
    {
        $this->customField1 = $customField1;
    }

    /**
     * Returns the customField2
     *
     * @return string $customField2
     */
    public function getCustomField2()
    {
        return $this->customField2;
    }

    /**
     * Sets the customField2
     *
     * @param string $customField2
     * @return void
     */
    public function setCustomField2($customField2)
    {
        $this->customField2 = $customField2;
    }

    /**
     * Returns the customField3
     *
     * @return string $customField3
     */
    public function getCustomField3()
    {
        return $this->customField3;
    }

    /**
     * Sets the customField3
     *
     * @param string $customField3
     * @return void
     */
    public function setCustomField3($customField3)
    {
        $this->customField3 = $customField3;
    }

    /**
     * Returns the customField4
     *
     * @return string $customField4
     */
    public function getCustomField4()
    {
        return $this->customField4;
    }

    /**
     * Sets the customField4
     *
     * @param string $customField4
     * @return void
     */
    public function setCustomField4($customField4)
    {
        $this->customField4 = $customField4;
    }

    /**
     * Returns the customField5
     *
     * @return string $customField5
     */
    public function getCustomField5()
    {
        return $this->customField5;
    }

    /**
     * Sets the customField5
     *
     * @param string $customField5
     * @return void
     */
    public function setCustomField5($customField5)
    {
        $this->customField5 = $customField5;
    }

    /**
     * Returns the customField6
     *
     * @return string $customField6
     */
    public function getCustomField6()
    {
        return $this->customField6;
    }

    /**
     * Sets the customField6
     *
     * @param string $customField6
     * @return void
     */
    public function setCustomField6($customField6)
    {
        $this->customField6 = $customField6;
    }

    /**
     * Returns the customField7
     *
     * @return string $customField7
     */
    public function getCustomField7()
    {
        return $this->customField7;
    }

    /**
     * Sets the customField7
     *
     * @param string $customField7
     * @return void
     */
    public function setCustomField7($customField7)
    {
        $this->customField7 = $customField7;
    }

    /**
     * Returns the customField8
     *
     * @return string $customField8
     */
    public function getCustomField8()
    {
        return $this->customField8;
    }

    /**
     * Sets the customField8
     *
     * @param string $customField8
     * @return void
     */
    public function setCustomField8($customField8)
    {
        $this->customField8 = $customField8;
    }

    /**
     * Returns the customField9
     *
     * @return string $customField9
     */
    public function getCustomField9()
    {
        return $this->customField9;
    }

    /**
     * Sets the customField9
     *
     * @param string $customField9
     * @return void
     */
    public function setCustomField9($customField9)
    {
        $this->customField9 = $customField9;
    }

    /**
     * Returns the bookDate
     *
     * @return string $bookDate
     */
    public function getBookDate()
    {
        return $this->bookDate;
    }

    /**
     * Sets the bookDate
     *
     * @param string $bookDate
     * @return void
     */
    public function setBookDate($bookDate)
    {
        $this->bookDate = $bookDate;
    }

    /**
     * Returns the defaultLocationID
     *
     * @return string $defaultLocationID
     */
    public function getDefaultLocationID()
    {
        return $this->defaultLocationID;
    }

    /**
     * Sets the defaultLocationID
     *
     * @param string $defaultLocationID
     * @return void
     */
    public function setDefaultLocationID($defaultLocationID)
    {
        $this->defaultLocationID = $defaultLocationID;
    }

    /**
     * Returns the edition
     *
     * @return string $edition
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Sets the edition
     *
     * @param string $edition
     * @return void
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;
    }

    /**
     * Returns the refEditors
     *
     * @return string $refEditors
     */
    public function getRefEditors()
    {
        return $this->refEditors;
    }

    /**
     * Sets the refEditors
     *
     * @param string $refEditors
     * @return void
     */
    public function setRefEditors($refEditors)
    {
        $this->refEditors = $refEditors;
    }

    /**
     * Returns the evaluation
     *
     * @return string $evaluation
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * Sets the evaluation
     *
     * @param string $evaluation
     * @return void
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * Returns the evaluationRTF
     *
     * @return string $evaluationRTF
     */
    public function getEvaluationRTF()
    {
        return $this->evaluationRTF;
    }

    /**
     * Sets the evaluationRTF
     *
     * @param string $evaluationRTF
     * @return void
     */
    public function setEvaluationRTF($evaluationRTF)
    {
        $this->evaluationRTF = $evaluationRTF;
    }

    /**
     * Returns the refKeywords
     *
     * @return string $refKeywords
     */
    public function getRefKeywords()
    {
        return $this->refKeywords;
    }

    /**
     * Sets the refKeywords
     *
     * @param string $refKeywords
     * @return void
     */
    public function setRefKeywords($refKeywords)
    {
        $this->refKeywords = $refKeywords;
    }

    /**
     * Returns the bookLanguage
     *
     * @return string $bookLanguage
     */
    public function getBookLanguage()
    {
        return $this->bookLanguage;
    }

    /**
     * Sets the bookLanguage
     *
     * @param string $bookLanguage
     * @return void
     */
    public function setBookLanguage($bookLanguage)
    {
        $this->bookLanguage = $bookLanguage;
    }

    /**
     * Returns the bookNote
     *
     * @return string $bookNote
     */
    public function getBookNote()
    {
        return $this->bookNote;
    }

    /**
     * Sets the bookNote
     *
     * @param string $bookNote
     * @return void
     */
    public function setBookNote($bookNote)
    {
        $this->bookNote = $bookNote;
    }

    /**
     * Returns the number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets the number
     *
     * @param string $number
     * @return void
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Returns the numberOfVolumes
     *
     * @return string $numberOfVolumes
     */
    public function getNumberOfVolumes()
    {
        return $this->numberOfVolumes;
    }

    /**
     * Sets the numberOfVolumes
     *
     * @param string $numberOfVolumes
     * @return void
     */
    public function setNumberOfVolumes($numberOfVolumes)
    {
        $this->numberOfVolumes = $numberOfVolumes;
    }

    /**
     * Returns the onlineAddress
     *
     * @return string $onlineAddress
     */
    public function getOnlineAddress()
    {
        return $this->onlineAddress;
    }

    /**
     * Sets the onlineAddress
     *
     * @param string $onlineAddress
     * @return void
     */
    public function setOnlineAddress($onlineAddress)
    {
        $this->onlineAddress = $onlineAddress;
    }

    /**
     * Returns the refOrganizations
     *
     * @return string $refOrganizations
     */
    public function getRefOrganizations()
    {
        return $this->refOrganizations;
    }

    /**
     * Sets the refOrganizations
     *
     * @param string $refOrganizations
     * @return void
     */
    public function setRefOrganizations($refOrganizations)
    {
        $this->refOrganizations = $refOrganizations;
    }

    /**
     * Returns the originalCheckedBy
     *
     * @return string $originalCheckedBy
     */
    public function getOriginalCheckedBy()
    {
        return $this->originalCheckedBy;
    }

    /**
     * Sets the originalCheckedBy
     *
     * @param string $originalCheckedBy
     * @return void
     */
    public function setOriginalCheckedBy($originalCheckedBy)
    {
        $this->originalCheckedBy = $originalCheckedBy;
    }

    /**
     * Returns the originalPublication
     *
     * @return string $originalPublication
     */
    public function getOriginalPublication()
    {
        return $this->originalPublication;
    }

    /**
     * Sets the originalPublication
     *
     * @param string $originalPublication
     * @return void
     */
    public function setOriginalPublication($originalPublication)
    {
        $this->originalPublication = $originalPublication;
    }

    /**
     * Returns the refOthersInvolved
     *
     * @return string $refOthersInvolved
     */
    public function getRefOthersInvolved()
    {
        return $this->refOthersInvolved;
    }

    /**
     * Sets the refOthersInvolved
     *
     * @param string $refOthersInvolved
     * @return void
     */
    public function setRefOthersInvolved($refOthersInvolved)
    {
        $this->refOthersInvolved = $refOthersInvolved;
    }

    /**
     * Returns the pageCount
     *
     * @return string $pageCount
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * Sets the pageCount
     *
     * @param string $pageCount
     * @return void
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }

    /**
     * Returns the pageRange
     *
     * @return string $pageRange
     */
    public function getPageRange()
    {
        return $this->pageRange;
    }

    /**
     * Sets the pageRange
     *
     * @param string $pageRange
     * @return void
     */
    public function setPageRange($pageRange)
    {
        $this->pageRange = $pageRange;
    }

    /**
     * Returns the parallelTitle
     *
     * @return string $parallelTitle
     */
    public function getParallelTitle()
    {
        return $this->parallelTitle;
    }

    /**
     * Sets the parallelTitle
     *
     * @param string $parallelTitle
     * @return void
     */
    public function setParallelTitle($parallelTitle)
    {
        $this->parallelTitle = $parallelTitle;
    }

    /**
     * Returns the refPeriodical
     *
     * @return string $refPeriodical
     */
    public function getRefPeriodical()
    {
        return $this->refPeriodical;
    }

    /**
     * Sets the refPeriodical
     *
     * @param string $refPeriodical
     * @return void
     */
    public function setRefPeriodical($refPeriodical)
    {
        $this->refPeriodical = $refPeriodical;
    }

    /**
     * Returns the placeOfPublication
     *
     * @return string $placeOfPublication
     */
    public function getPlaceOfPublication()
    {
        return $this->placeOfPublication;
    }

    /**
     * Sets the placeOfPublication
     *
     * @param string $placeOfPublication
     * @return void
     */
    public function setPlaceOfPublication($placeOfPublication)
    {
        $this->placeOfPublication = $placeOfPublication;
    }

    /**
     * Returns the price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param float $price
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Returns the refPublishers
     *
     * @return string $refPublishers
     */
    public function getRefPublishers()
    {
        return $this->refPublishers;
    }

    /**
     * Sets the refPublishers
     *
     * @param string $refPublishers
     * @return void
     */
    public function setRefPublishers($refPublishers)
    {
        $this->refPublishers = $refPublishers;
    }

    /**
     * Returns the rating
     *
     * @return int $rating
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Sets the rating
     *
     * @param int $rating
     * @return void
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * Returns the refSeriesTitle
     *
     * @return string $refSeriesTitle
     */
    public function getRefSeriesTitle()
    {
        return $this->refSeriesTitle;
    }

    /**
     * Sets the refSeriesTitle
     *
     * @param string $refSeriesTitle
     * @return void
     */
    public function setRefSeriesTitle($refSeriesTitle)
    {
        $this->refSeriesTitle = $refSeriesTitle;
    }

    /**
     * Returns the shortTitle
     *
     * @return string $shortTitle
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * Sets the shortTitle
     *
     * @param string $shortTitle
     * @return void
     */
    public function setShortTitle($shortTitle)
    {
        $this->shortTitle = $shortTitle;
    }

    /**
     * Returns the sourceOfBibliographicInformation
     *
     * @return string $sourceOfBibliographicInformation
     */
    public function getSourceOfBibliographicInformation()
    {
        return $this->sourceOfBibliographicInformation;
    }

    /**
     * Sets the sourceOfBibliographicInformation
     *
     * @param string $sourceOfBibliographicInformation
     * @return void
     */
    public function setSourceOfBibliographicInformation($sourceOfBibliographicInformation)
    {
        $this->sourceOfBibliographicInformation = $sourceOfBibliographicInformation;
    }

    /**
     * Returns the specificField1
     *
     * @return string $specificField1
     */
    public function getSpecificField1()
    {
        return $this->specificField1;
    }

    /**
     * Sets the specificField1
     *
     * @param string $specificField1
     * @return void
     */
    public function setSpecificField1($specificField1)
    {
        $this->specificField1 = $specificField1;
    }

    /**
     * Returns the specificField2
     *
     * @return string $specificField2
     */
    public function getSpecificField2()
    {
        return $this->specificField2;
    }

    /**
     * Sets the specificField2
     *
     * @param string $specificField2
     * @return void
     */
    public function setSpecificField2($specificField2)
    {
        $this->specificField2 = $specificField2;
    }

    /**
     * Returns the specificField4
     *
     * @return string $specificField4
     */
    public function getSpecificField4()
    {
        return $this->specificField4;
    }

    /**
     * Sets the specificField4
     *
     * @param string $specificField4
     * @return void
     */
    public function setSpecificField4($specificField4)
    {
        $this->specificField4 = $specificField4;
    }

    /**
     * Returns the specificField7
     *
     * @return string $specificField7
     */
    public function getSpecificField7()
    {
        return $this->specificField7;
    }

    /**
     * Sets the specificField7
     *
     * @param string $specificField7
     * @return void
     */
    public function setSpecificField7($specificField7)
    {
        $this->specificField7 = $specificField7;
    }

    /**
     * Returns the storageMedium
     *
     * @return string $storageMedium
     */
    public function getStorageMedium()
    {
        return $this->storageMedium;
    }

    /**
     * Sets the storageMedium
     *
     * @param string $storageMedium
     * @return void
     */
    public function setStorageMedium($storageMedium)
    {
        $this->storageMedium = $storageMedium;
    }

    /**
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     *
     * @param string $subtitle
     * @return void
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Returns the tableOfContents
     *
     * @return string $tableOfContents
     */
    public function getTableOfContents()
    {
        return $this->tableOfContents;
    }

    /**
     * Sets the tableOfContents
     *
     * @param string $tableOfContents
     * @return void
     */
    public function setTableOfContents($tableOfContents)
    {
        $this->tableOfContents = $tableOfContents;
    }

    /**
     * Returns the tableOfContentsRTF
     *
     * @return string $tableOfContentsRTF
     */
    public function getTableOfContentsRTF()
    {
        return $this->tableOfContentsRTF;
    }

    /**
     * Sets the tableOfContentsRTF
     *
     * @param string $tableOfContentsRTF
     * @return void
     */
    public function setTableOfContentsRTF($tableOfContentsRTF)
    {
        $this->tableOfContentsRTF = $tableOfContentsRTF;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the titleInOtherLanguages
     *
     * @return string $titleInOtherLanguages
     */
    public function getTitleInOtherLanguages()
    {
        return $this->titleInOtherLanguages;
    }

    /**
     * Sets the titleInOtherLanguages
     *
     * @param string $titleInOtherLanguages
     * @return void
     */
    public function setTitleInOtherLanguages($titleInOtherLanguages)
    {
        $this->titleInOtherLanguages = $titleInOtherLanguages;
    }

    /**
     * Returns the titleSupplement
     *
     * @return string $titleSupplement
     */
    public function getTitleSupplement()
    {
        return $this->titleSupplement;
    }

    /**
     * Sets the titleSupplement
     *
     * @param string $titleSupplement
     * @return void
     */
    public function setTitleSupplement($titleSupplement)
    {
        $this->titleSupplement = $titleSupplement;
    }

    /**
     * Returns the translatedTitle
     *
     * @return string $translatedTitle
     */
    public function getTranslatedTitle()
    {
        return $this->translatedTitle;
    }

    /**
     * Sets the translatedTitle
     *
     * @param string $translatedTitle
     * @return void
     */
    public function setTranslatedTitle($translatedTitle)
    {
        $this->translatedTitle = $translatedTitle;
    }

    /**
     * Returns the uniformTitle
     *
     * @return string $uniformTitle
     */
    public function getUniformTitle()
    {
        return $this->uniformTitle;
    }

    /**
     * Sets the uniformTitle
     *
     * @param string $uniformTitle
     * @return void
     */
    public function setUniformTitle($uniformTitle)
    {
        $this->uniformTitle = $uniformTitle;
    }

    /**
     * Returns the bookVolume
     *
     * @return string $bookVolume
     */
    public function getBookVolume()
    {
        return $this->bookVolume;
    }

    /**
     * Sets the bookVolume
     *
     * @param string $bookVolume
     * @return void
     */
    public function setBookVolume($bookVolume)
    {
        $this->bookVolume = $bookVolume;
    }

    /**
     * Returns the bookYear
     *
     * @return string $bookYear
     */
    public function getBookYear()
    {
        return $this->bookYear;
    }

    /**
     * Sets the bookYear
     *
     * @param string $bookYear
     * @return void
     */
    public function setBookYear($bookYear)
    {
        $this->bookYear = $bookYear;
    }

    /**
     * Returns the pageRangeStart
     *
     * @return string $pageRangeStart
     */
    public function getPageRangeStart()
    {
        return $this->pageRangeStart;
    }

    /**
     * Sets the pageRangeStart
     *
     * @param string $pageRangeStart
     * @return void
     */
    public function setPageRangeStart($pageRangeStart)
    {
        $this->pageRangeStart = $pageRangeStart;
    }

    /**
     * Returns the pageRangeEnd
     *
     * @return string $pageRangeEnd
     */
    public function getPageRangeEnd()
    {
        return $this->pageRangeEnd;
    }

    /**
     * Sets the pageRangeEnd
     *
     * @param string $pageRangeEnd
     * @return void
     */
    public function setPageRangeEnd($pageRangeEnd)
    {
        $this->pageRangeEnd = $pageRangeEnd;
    }

    /**
     * Returns the doi
     *
     * @return string $doi
     */
    public function getDoi()
    {
        return $this->doi;
    }

    /**
     * Sets the doi
     *
     * @param string $doi
     * @return void
     */
    public function setDoi($doi)
    {
        $this->doi = $doi;
    }

    /**
     * Returns the sortDate
     *
     * @return string $sortDate
     */
    public function getSortDate()
    {
        return $this->sortDate;
    }

    /**
     * Sets the sortDate
     *
     * @param string $sortDate
     * @return void
     */
    public function setSortDate($sortDate)
    {
        $this->sortDate = $sortDate;
    }
    
    /**
     * Returns the txExtbaseType
     *
     * @return string $txExtbaseType
     */
    public function getTxExtbaseType()
    {
        return $this->txExtbaseType;
    }

    /**
     * Sets the txExtbaseType
     *
     * @param string $txExtbaseType
     * @return void
     */
    public function setTxExtbaseType($txExtbaseType)
    {
        $this->txExtbaseType = $txExtbaseType;
    }

    /**
     * Returns the literaturlistId
     *
     * @return string $literaturlistId
     */
    public function getLiteraturlistId()
    {
        return $this->literaturlistId;
    }

    /**
     * Sets the literaturlistId
     *
     * @param string $literaturlistId
     * @return void
     */
    public function setLiteraturlistId($literaturlistId)
    {
        $this->literaturlistId = $literaturlistId;
    }

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->editors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->authors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->othersInvolved = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->collaborators = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->organizations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->publishers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->periodicals = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->seriestitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->knowledgeItems = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->locations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->parentReferences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->childReferences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a Category
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\Netzweber\NwCitavi\Domain\Model\Category $category)
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\Netzweber\NwCitavi\Domain\Model\Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Adds a Keyword
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Keyword $keyword
     * @return void
     */
    public function addKeyword(\Netzweber\NwCitavi\Domain\Model\Keyword $keyword)
    {
        $this->keywords->attach($keyword);
    }

    /**
     * Removes a Keyword
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Keyword $keywordToRemove The Keyword to be removed
     * @return void
     */
    public function removeKeyword(\Netzweber\NwCitavi\Domain\Model\Keyword $keywordToRemove)
    {
        $this->keywords->detach($keywordToRemove);
    }

    /**
     * Returns the keywords
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Keyword> $keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Keyword> $keywords
     * @return void
     */
    public function setKeywords(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Adds a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $editor
     * @return void
     */
    public function addEditor(\Netzweber\NwCitavi\Domain\Model\Person $editor)
    {
        $this->editors->attach($editor);
    }

    /**
     * Removes a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $editorToRemove The Person to be removed
     * @return void
     */
    public function removeEditor(\Netzweber\NwCitavi\Domain\Model\Person $editorToRemove)
    {
        $this->editors->detach($editorToRemove);
    }

    /**
     * Returns the editors
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $editors
     */
    public function getEditors()
    {
        return $this->editors;
    }

    /**
     * Sets the editors
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $editors
     * @return void
     */
    public function setEditors(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $editors)
    {
        $this->editors = $editors;
    }

    /**
     * Adds a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $author
     * @return void
     */
    public function addAuthor(\Netzweber\NwCitavi\Domain\Model\Person $author)
    {
        $this->authors->attach($author);
    }

    /**
     * Removes a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $authorToRemove The Person to be removed
     * @return void
     */
    public function removeAuthor(\Netzweber\NwCitavi\Domain\Model\Person $authorToRemove)
    {
        $this->authors->detach($authorToRemove);
    }

    /**
     * Returns the authors
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $authors
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Sets the authors
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $authors
     * @return void
     */
    public function setAuthors(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $authors)
    {
        $this->authors = $authors;
    }

    /**
     * Adds a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $othersInvolved
     * @return void
     */
    public function addOthersInvolved(\Netzweber\NwCitavi\Domain\Model\Person $othersInvolved)
    {
        $this->othersInvolved->attach($othersInvolved);
    }

    /**
     * Removes a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $othersInvolvedToRemove The Person to be removed
     * @return void
     */
    public function removeOthersInvolved(\Netzweber\NwCitavi\Domain\Model\Person $othersInvolvedToRemove)
    {
        $this->othersInvolved->detach($othersInvolvedToRemove);
    }

    /**
     * Returns the othersInvolved
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $othersInvolved
     */
    public function getOthersInvolved()
    {
        return $this->othersInvolved;
    }

    /**
     * Sets the othersInvolved
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $othersInvolved
     * @return void
     */
    public function setOthersInvolved(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $othersInvolved)
    {
        $this->othersInvolved = $othersInvolved;
    }

    /**
     * Adds a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $collaborator
     * @return void
     */
    public function addCollaborator(\Netzweber\NwCitavi\Domain\Model\Person $collaborator)
    {
        $this->collaborators->attach($collaborator);
    }

    /**
     * Removes a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $collaboratorToRemove The Person to be removed
     * @return void
     */
    public function removeCollaborator(\Netzweber\NwCitavi\Domain\Model\Person $collaboratorToRemove)
    {
        $this->collaborators->detach($collaboratorToRemove);
    }

    /**
     * Returns the collaborators
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $collaborators
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Sets the collaborators
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $collaborators
     * @return void
     */
    public function setCollaborators(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $collaborators)
    {
        $this->collaborators = $collaborators;
    }

    /**
     * Adds a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $organization
     * @return void
     */
    public function addOrganization(\Netzweber\NwCitavi\Domain\Model\Person $organization)
    {
        $this->organizations->attach($organization);
    }

    /**
     * Removes a Person
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Person $organizationToRemove The Person to be removed
     * @return void
     */
    public function removeOrganization(\Netzweber\NwCitavi\Domain\Model\Person $organizationToRemove)
    {
        $this->organizations->detach($organizationToRemove);
    }

    /**
     * Returns the organizations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $organizations
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * Sets the organizations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Person> $organizations
     * @return void
     */
    public function setOrganizations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $organizations)
    {
        $this->organizations = $organizations;
    }

    /**
     * Adds a Publisher
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Publisher $publisher
     * @return void
     */
    public function addPublisher(\Netzweber\NwCitavi\Domain\Model\Publisher $publisher)
    {
        $this->publishers->attach($publisher);
    }

    /**
     * Removes a Publisher
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Publisher $publisherToRemove The Publisher to be removed
     * @return void
     */
    public function removePublisher(\Netzweber\NwCitavi\Domain\Model\Publisher $publisherToRemove)
    {
        $this->publishers->detach($publisherToRemove);
    }

    /**
     * Returns the publishers
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Publisher> $publishers
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * Sets the publishers
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Publisher> $publishers
     * @return void
     */
    public function setPublishers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $publishers)
    {
        $this->publishers = $publishers;
    }

    /**
     * Adds a Periodical
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Periodical $periodical
     * @return void
     */
    public function addPeriodical(\Netzweber\NwCitavi\Domain\Model\Periodical $periodical)
    {
        $this->periodicals->attach($periodical);
    }

    /**
     * Removes a Periodical
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Periodical $periodicalToRemove The Periodical to be removed
     * @return void
     */
    public function removePeriodical(\Netzweber\NwCitavi\Domain\Model\Periodical $periodicalToRemove)
    {
        $this->periodicals->detach($periodicalToRemove);
    }

    /**
     * Returns the periodicals
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Periodical> $periodicals
     */
    public function getPeriodicals()
    {
        return $this->periodicals;
    }

    /**
     * Sets the periodicals
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Periodical> $periodicals
     * @return void
     */
    public function setPeriodicals(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $periodicals)
    {
        $this->periodicals = $periodicals;
    }

    /**
     * Adds a Seriestitle
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Seriestitle $seriestitle
     * @return void
     */
    public function addSeriestitle(\Netzweber\NwCitavi\Domain\Model\Seriestitle $seriestitle)
    {
        $this->seriestitles->attach($seriestitle);
    }

    /**
     * Removes a Seriestitle
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Seriestitle $seriestitleToRemove The Seriestitle to be removed
     * @return void
     */
    public function removeSeriestitle(\Netzweber\NwCitavi\Domain\Model\Seriestitle $seriestitleToRemove)
    {
        $this->seriestitles->detach($seriestitleToRemove);
    }

    /**
     * Returns the seriestitles
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Seriestitle> $seriestitles
     */
    public function getSeriestitles()
    {
        return $this->seriestitles;
    }

    /**
     * Sets the seriestitles
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Seriestitle> $seriestitles
     * @return void
     */
    public function setSeriestitles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $seriestitles)
    {
        $this->seriestitles = $seriestitles;
    }

    /**
     * Adds a KnowledgeItem
     *
     * @param \Netzweber\NwCitavi\Domain\Model\KnowledgeItem $knowledgeItem
     * @return void
     */
    public function addKnowledgeItem(\Netzweber\NwCitavi\Domain\Model\KnowledgeItem $knowledgeItem)
    {
        $this->knowledgeItems->attach($knowledgeItem);
    }

    /**
     * Removes a KnowledgeItem
     *
     * @param \Netzweber\NwCitavi\Domain\Model\KnowledgeItem $knowledgeItemToRemove The KnowledgeItem to be removed
     * @return void
     */
    public function removeKnowledgeItem(\Netzweber\NwCitavi\Domain\Model\KnowledgeItem $knowledgeItemToRemove)
    {
        $this->knowledgeItems->detach($knowledgeItemToRemove);
    }

    /**
     * Returns the knowledgeItems
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\KnowledgeItem> $knowledgeItems
     */
    public function getKnowledgeItems()
    {
        return $this->knowledgeItems;
    }

    /**
     * Sets the knowledgeItems
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\KnowledgeItem> $knowledgeItems
     * @return void
     */
    public function setKnowledgeItems(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $knowledgeItems)
    {
        $this->knowledgeItems = $knowledgeItems;
    }

    /**
     * Adds a Location
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Location $location
     * @return void
     */
    public function addLocation(\Netzweber\NwCitavi\Domain\Model\Location $location)
    {
        $this->locations->attach($location);
    }

    /**
     * Removes a Location
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Location $locationToRemove The Location to be removed
     * @return void
     */
    public function removeLocation(\Netzweber\NwCitavi\Domain\Model\Location $locationToRemove)
    {
        $this->locations->detach($locationToRemove);
    }

    /**
     * Returns the locations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Location> $locations
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Sets the locations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Location> $locations
     * @return void
     */
    public function setLocations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $locations)
    {
        $this->locations = $locations;
    }

    /**
     * Adds a Reference
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Reference $parentReference
     * @return void
     */
    public function addParentReference(\Netzweber\NwCitavi\Domain\Model\Reference $parentReference)
    {
        $this->parentReferences->attach($parentReference);
    }

    /**
     * Removes a Reference
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Reference $parentReferenceToRemove The Reference to be removed
     * @return void
     */
    public function removeParentReference(\Netzweber\NwCitavi\Domain\Model\Reference $parentReferenceToRemove)
    {
        $this->parentReferences->detach($parentReferenceToRemove);
    }

    /**
     * Returns the parentReferences
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference> $parentReferences
     */
    public function getParentReferences()
    {
        return $this->parentReferences;
    }

    /**
     * Sets the parentReferences
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference> $parentReferences
     * @return void
     */
    public function setParentReferences(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $parentReferences)
    {
        $this->parentReferences = $parentReferences;
    }

    /**
     * Adds a Reference
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Reference $childReference
     * @return void
     */
    public function addChildReference(\Netzweber\NwCitavi\Domain\Model\Reference $childReference)
    {
        $this->childReferences->attach($childReference);
    }

    /**
     * Removes a Reference
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Reference $childReferenceToRemove The Reference to be removed
     * @return void
     */
    public function removeChildReference(\Netzweber\NwCitavi\Domain\Model\Reference $childReferenceToRemove)
    {
        $this->childReferences->detach($childReferenceToRemove);
    }

    /**
     * Returns the childReferences
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference> $childReferences
     */
    public function getChildReferences()
    {
        return $this->childReferences;
    }

    /**
     * Sets the childReferences
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Reference> $childReferences
     * @return void
     */
    public function setChildReferences(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $childReferences)
    {
        $this->childReferences = $childReferences;
    }

    /**
     * Returns the attachment
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Sets the attachment
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $attachment
     * @return void
     */
    public function setAttachment(\TYPO3\CMS\Extbase\Domain\Model\FileReference $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Returns the cover
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $cover
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Sets the cover
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $cover
     * @return void
     */
    public function setCover(\TYPO3\CMS\Extbase\Domain\Model\FileReference $cover)
    {
        $this->cover = $cover;
    }
}
