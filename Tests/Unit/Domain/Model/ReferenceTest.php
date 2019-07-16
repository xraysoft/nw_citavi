<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class ReferenceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\Reference
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\Reference();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getCitaviHashReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCitaviHash()
        );

    }

    /**
     * @test
     */
    public function setCitaviHashForStringSetsCitaviHash()
    {
        $this->subject->setCitaviHash('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'citaviHash',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCitaviIdReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCitaviId()
        );

    }

    /**
     * @test
     */
    public function setCitaviIdForStringSetsCitaviId()
    {
        $this->subject->setCitaviId('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'citaviId',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCreatedByReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCreatedBy()
        );

    }

    /**
     * @test
     */
    public function setCreatedByForStringSetsCreatedBy()
    {
        $this->subject->setCreatedBy('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'createdBy',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCreatedBySidReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCreatedBySid()
        );

    }

    /**
     * @test
     */
    public function setCreatedBySidForStringSetsCreatedBySid()
    {
        $this->subject->setCreatedBySid('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'createdBySid',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCreatedOnReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setCreatedOnForIntSetsCreatedOn()
    {
    }

    /**
     * @test
     */
    public function getModifiedByReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getModifiedBy()
        );

    }

    /**
     * @test
     */
    public function setModifiedByForStringSetsModifiedBy()
    {
        $this->subject->setModifiedBy('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'modifiedBy',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getModifiedBySidReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getModifiedBySid()
        );

    }

    /**
     * @test
     */
    public function setModifiedBySidForStringSetsModifiedBySid()
    {
        $this->subject->setModifiedBySid('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'modifiedBySid',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getModifiedOnReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setModifiedOnForIntSetsModifiedOn()
    {
    }

    /**
     * @test
     */
    public function getReferenceTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getReferenceType()
        );

    }

    /**
     * @test
     */
    public function setReferenceTypeForStringSetsReferenceType()
    {
        $this->subject->setReferenceType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'referenceType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getParentReferenceTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getParentReferenceType()
        );

    }

    /**
     * @test
     */
    public function setParentReferenceTypeForStringSetsParentReferenceType()
    {
        $this->subject->setParentReferenceType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'parentReferenceType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getISBNReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getISBN()
        );

    }

    /**
     * @test
     */
    public function setISBNForStringSetsISBN()
    {
        $this->subject->setISBN('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'iSBN',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSequenceNumberReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSequenceNumber()
        );

    }

    /**
     * @test
     */
    public function setSequenceNumberForStringSetsSequenceNumber()
    {
        $this->subject->setSequenceNumber('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sequenceNumber',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAbstractReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAbstract()
        );

    }

    /**
     * @test
     */
    public function setAbstractForStringSetsAbstract()
    {
        $this->subject->setAbstract('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'abstract',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAbstractRTFReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAbstractRTF()
        );

    }

    /**
     * @test
     */
    public function setAbstractRTFForStringSetsAbstractRTF()
    {
        $this->subject->setAbstractRTF('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'abstractRTF',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAccessDateReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAccessDate()
        );

    }

    /**
     * @test
     */
    public function setAccessDateForStringSetsAccessDate()
    {
        $this->subject->setAccessDate('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'accessDate',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAdditionsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAdditions()
        );

    }

    /**
     * @test
     */
    public function setAdditionsForStringSetsAdditions()
    {
        $this->subject->setAdditions('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'additions',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefAuthorsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefAuthors()
        );

    }

    /**
     * @test
     */
    public function setRefAuthorsForStringSetsRefAuthors()
    {
        $this->subject->setRefAuthors('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refAuthors',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefCategoriesReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefCategories()
        );

    }

    /**
     * @test
     */
    public function setRefCategoriesForStringSetsRefCategories()
    {
        $this->subject->setRefCategories('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refCategories',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCitationKeyUpdateTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCitationKeyUpdateType()
        );

    }

    /**
     * @test
     */
    public function setCitationKeyUpdateTypeForStringSetsCitationKeyUpdateType()
    {
        $this->subject->setCitationKeyUpdateType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'citationKeyUpdateType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefCollaboratorsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefCollaborators()
        );

    }

    /**
     * @test
     */
    public function setRefCollaboratorsForStringSetsRefCollaborators()
    {
        $this->subject->setRefCollaborators('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refCollaborators',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField1ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField1()
        );

    }

    /**
     * @test
     */
    public function setCustomField1ForStringSetsCustomField1()
    {
        $this->subject->setCustomField1('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField1',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField2ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField2()
        );

    }

    /**
     * @test
     */
    public function setCustomField2ForStringSetsCustomField2()
    {
        $this->subject->setCustomField2('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField2',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField3ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField3()
        );

    }

    /**
     * @test
     */
    public function setCustomField3ForStringSetsCustomField3()
    {
        $this->subject->setCustomField3('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField3',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField4ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField4()
        );

    }

    /**
     * @test
     */
    public function setCustomField4ForStringSetsCustomField4()
    {
        $this->subject->setCustomField4('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField4',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField5ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField5()
        );

    }

    /**
     * @test
     */
    public function setCustomField5ForStringSetsCustomField5()
    {
        $this->subject->setCustomField5('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField5',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField6ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField6()
        );

    }

    /**
     * @test
     */
    public function setCustomField6ForStringSetsCustomField6()
    {
        $this->subject->setCustomField6('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField6',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField7ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField7()
        );

    }

    /**
     * @test
     */
    public function setCustomField7ForStringSetsCustomField7()
    {
        $this->subject->setCustomField7('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField7',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField8ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField8()
        );

    }

    /**
     * @test
     */
    public function setCustomField8ForStringSetsCustomField8()
    {
        $this->subject->setCustomField8('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField8',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCustomField9ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCustomField9()
        );

    }

    /**
     * @test
     */
    public function setCustomField9ForStringSetsCustomField9()
    {
        $this->subject->setCustomField9('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'customField9',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBookDateReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBookDate()
        );

    }

    /**
     * @test
     */
    public function setBookDateForStringSetsBookDate()
    {
        $this->subject->setBookDate('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bookDate',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDefaultLocationIDReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDefaultLocationID()
        );

    }

    /**
     * @test
     */
    public function setDefaultLocationIDForStringSetsDefaultLocationID()
    {
        $this->subject->setDefaultLocationID('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'defaultLocationID',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEditionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEdition()
        );

    }

    /**
     * @test
     */
    public function setEditionForStringSetsEdition()
    {
        $this->subject->setEdition('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'edition',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefEditorsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefEditors()
        );

    }

    /**
     * @test
     */
    public function setRefEditorsForStringSetsRefEditors()
    {
        $this->subject->setRefEditors('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refEditors',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEvaluationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEvaluation()
        );

    }

    /**
     * @test
     */
    public function setEvaluationForStringSetsEvaluation()
    {
        $this->subject->setEvaluation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'evaluation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEvaluationRTFReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEvaluationRTF()
        );

    }

    /**
     * @test
     */
    public function setEvaluationRTFForStringSetsEvaluationRTF()
    {
        $this->subject->setEvaluationRTF('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'evaluationRTF',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefKeywordsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefKeywords()
        );

    }

    /**
     * @test
     */
    public function setRefKeywordsForStringSetsRefKeywords()
    {
        $this->subject->setRefKeywords('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refKeywords',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBookLanguageReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBookLanguage()
        );

    }

    /**
     * @test
     */
    public function setBookLanguageForStringSetsBookLanguage()
    {
        $this->subject->setBookLanguage('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bookLanguage',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBookNoteReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBookNote()
        );

    }

    /**
     * @test
     */
    public function setBookNoteForStringSetsBookNote()
    {
        $this->subject->setBookNote('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bookNote',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getNumberReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getNumber()
        );

    }

    /**
     * @test
     */
    public function setNumberForStringSetsNumber()
    {
        $this->subject->setNumber('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'number',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getNumberOfVolumesReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getNumberOfVolumes()
        );

    }

    /**
     * @test
     */
    public function setNumberOfVolumesForStringSetsNumberOfVolumes()
    {
        $this->subject->setNumberOfVolumes('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'numberOfVolumes',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getOnlineAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOnlineAddress()
        );

    }

    /**
     * @test
     */
    public function setOnlineAddressForStringSetsOnlineAddress()
    {
        $this->subject->setOnlineAddress('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'onlineAddress',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefOrganizationsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefOrganizations()
        );

    }

    /**
     * @test
     */
    public function setRefOrganizationsForStringSetsRefOrganizations()
    {
        $this->subject->setRefOrganizations('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refOrganizations',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getOriginalCheckedByReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOriginalCheckedBy()
        );

    }

    /**
     * @test
     */
    public function setOriginalCheckedByForStringSetsOriginalCheckedBy()
    {
        $this->subject->setOriginalCheckedBy('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'originalCheckedBy',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getOriginalPublicationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOriginalPublication()
        );

    }

    /**
     * @test
     */
    public function setOriginalPublicationForStringSetsOriginalPublication()
    {
        $this->subject->setOriginalPublication('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'originalPublication',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefOthersInvolvedReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefOthersInvolved()
        );

    }

    /**
     * @test
     */
    public function setRefOthersInvolvedForStringSetsRefOthersInvolved()
    {
        $this->subject->setRefOthersInvolved('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refOthersInvolved',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPageCountReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPageCount()
        );

    }

    /**
     * @test
     */
    public function setPageCountForStringSetsPageCount()
    {
        $this->subject->setPageCount('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'pageCount',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPageRangeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPageRange()
        );

    }

    /**
     * @test
     */
    public function setPageRangeForStringSetsPageRange()
    {
        $this->subject->setPageRange('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'pageRange',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getParallelTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getParallelTitle()
        );

    }

    /**
     * @test
     */
    public function setParallelTitleForStringSetsParallelTitle()
    {
        $this->subject->setParallelTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'parallelTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRefPeriodicalReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefPeriodical()
        );

    }

    /**
     * @test
     */
    public function setRefPeriodicalForStringSetsRefPeriodical()
    {
        $this->subject->setRefPeriodical('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refPeriodical',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPlaceOfPublicationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPlaceOfPublication()
        );

    }

    /**
     * @test
     */
    public function setPlaceOfPublicationForStringSetsPlaceOfPublication()
    {
        $this->subject->setPlaceOfPublication('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'placeOfPublication',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPriceReturnsInitialValueForFloat()
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );

    }

    /**
     * @test
     */
    public function setPriceForFloatSetsPrice()
    {
        $this->subject->setPrice(3.14159265);

        self::assertAttributeEquals(
            3.14159265,
            'price',
            $this->subject,
            '',
            0.000000001
        );

    }

    /**
     * @test
     */
    public function getRefPublishersReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefPublishers()
        );

    }

    /**
     * @test
     */
    public function setRefPublishersForStringSetsRefPublishers()
    {
        $this->subject->setRefPublishers('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refPublishers',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRatingReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setRatingForIntSetsRating()
    {
    }

    /**
     * @test
     */
    public function getRefSeriesTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRefSeriesTitle()
        );

    }

    /**
     * @test
     */
    public function setRefSeriesTitleForStringSetsRefSeriesTitle()
    {
        $this->subject->setRefSeriesTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'refSeriesTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getShortTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getShortTitle()
        );

    }

    /**
     * @test
     */
    public function setShortTitleForStringSetsShortTitle()
    {
        $this->subject->setShortTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'shortTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSourceOfBibliographicInformationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSourceOfBibliographicInformation()
        );

    }

    /**
     * @test
     */
    public function setSourceOfBibliographicInformationForStringSetsSourceOfBibliographicInformation()
    {
        $this->subject->setSourceOfBibliographicInformation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sourceOfBibliographicInformation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSpecificField1ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSpecificField1()
        );

    }

    /**
     * @test
     */
    public function setSpecificField1ForStringSetsSpecificField1()
    {
        $this->subject->setSpecificField1('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'specificField1',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSpecificField2ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSpecificField2()
        );

    }

    /**
     * @test
     */
    public function setSpecificField2ForStringSetsSpecificField2()
    {
        $this->subject->setSpecificField2('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'specificField2',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSpecificField4ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSpecificField4()
        );

    }

    /**
     * @test
     */
    public function setSpecificField4ForStringSetsSpecificField4()
    {
        $this->subject->setSpecificField4('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'specificField4',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSpecificField7ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSpecificField7()
        );

    }

    /**
     * @test
     */
    public function setSpecificField7ForStringSetsSpecificField7()
    {
        $this->subject->setSpecificField7('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'specificField7',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getStorageMediumReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getStorageMedium()
        );

    }

    /**
     * @test
     */
    public function setStorageMediumForStringSetsStorageMedium()
    {
        $this->subject->setStorageMedium('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'storageMedium',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSubtitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSubtitle()
        );

    }

    /**
     * @test
     */
    public function setSubtitleForStringSetsSubtitle()
    {
        $this->subject->setSubtitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'subtitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTableOfContentsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTableOfContents()
        );

    }

    /**
     * @test
     */
    public function setTableOfContentsForStringSetsTableOfContents()
    {
        $this->subject->setTableOfContents('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'tableOfContents',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTableOfContentsRTFReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTableOfContentsRTF()
        );

    }

    /**
     * @test
     */
    public function setTableOfContentsRTFForStringSetsTableOfContentsRTF()
    {
        $this->subject->setTableOfContentsRTF('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'tableOfContentsRTF',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );

    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTitleInOtherLanguagesReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitleInOtherLanguages()
        );

    }

    /**
     * @test
     */
    public function setTitleInOtherLanguagesForStringSetsTitleInOtherLanguages()
    {
        $this->subject->setTitleInOtherLanguages('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'titleInOtherLanguages',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTitleSupplementReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitleSupplement()
        );

    }

    /**
     * @test
     */
    public function setTitleSupplementForStringSetsTitleSupplement()
    {
        $this->subject->setTitleSupplement('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'titleSupplement',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTranslatedTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTranslatedTitle()
        );

    }

    /**
     * @test
     */
    public function setTranslatedTitleForStringSetsTranslatedTitle()
    {
        $this->subject->setTranslatedTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'translatedTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getUniformTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUniformTitle()
        );

    }

    /**
     * @test
     */
    public function setUniformTitleForStringSetsUniformTitle()
    {
        $this->subject->setUniformTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'uniformTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBookVolumeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBookVolume()
        );

    }

    /**
     * @test
     */
    public function setBookVolumeForStringSetsBookVolume()
    {
        $this->subject->setBookVolume('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bookVolume',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBookYearReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBookYear()
        );

    }

    /**
     * @test
     */
    public function setBookYearForStringSetsBookYear()
    {
        $this->subject->setBookYear('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bookYear',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPageRangeStartReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPageRangeStart()
        );

    }

    /**
     * @test
     */
    public function setPageRangeStartForStringSetsPageRangeStart()
    {
        $this->subject->setPageRangeStart('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'pageRangeStart',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPageRangeEndReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPageRangeEnd()
        );

    }

    /**
     * @test
     */
    public function setPageRangeEndForStringSetsPageRangeEnd()
    {
        $this->subject->setPageRangeEnd('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'pageRangeEnd',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDoiReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDoi()
        );

    }

    /**
     * @test
     */
    public function setDoiForStringSetsDoi()
    {
        $this->subject->setDoi('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'doi',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSortDateReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSortDate()
        );

    }

    /**
     * @test
     */
    public function setSortDateForStringSetsSortDate()
    {
        $this->subject->setSortDate('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sortDate',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLiteraturlistIdReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLiteraturlistId()
        );

    }

    /**
     * @test
     */
    public function setLiteraturlistIdForStringSetsLiteraturlistId()
    {
        $this->subject->setLiteraturlistId('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'literaturlistId',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAttachmentReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getAttachment()
        );

    }

    /**
     * @test
     */
    public function setAttachmentForFileReferenceSetsAttachment()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setAttachment($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'attachment',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCoverReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getCover()
        );

    }

    /**
     * @test
     */
    public function setCoverForFileReferenceSetsCover()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setCover($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'cover',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCategoriesReturnsInitialValueForCategory()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategories()
        );

    }

    /**
     * @test
     */
    public function setCategoriesForObjectStorageContainingCategorySetsCategories()
    {
        $category = new \Netzweber\NwCitavi\Domain\Model\Category();
        $objectStorageHoldingExactlyOneCategories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCategories->attach($category);
        $this->subject->setCategories($objectStorageHoldingExactlyOneCategories);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneCategories,
            'categories',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategories()
    {
        $category = new \Netzweber\NwCitavi\Domain\Model\Category();
        $categoriesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoriesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($category));
        $this->inject($this->subject, 'categories', $categoriesObjectStorageMock);

        $this->subject->addCategory($category);
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategories()
    {
        $category = new \Netzweber\NwCitavi\Domain\Model\Category();
        $categoriesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoriesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($category));
        $this->inject($this->subject, 'categories', $categoriesObjectStorageMock);

        $this->subject->removeCategory($category);

    }

    /**
     * @test
     */
    public function getKeywordsReturnsInitialValueForKeyword()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getKeywords()
        );

    }

    /**
     * @test
     */
    public function setKeywordsForObjectStorageContainingKeywordSetsKeywords()
    {
        $keyword = new \Netzweber\NwCitavi\Domain\Model\Keyword();
        $objectStorageHoldingExactlyOneKeywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneKeywords->attach($keyword);
        $this->subject->setKeywords($objectStorageHoldingExactlyOneKeywords);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneKeywords,
            'keywords',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addKeywordToObjectStorageHoldingKeywords()
    {
        $keyword = new \Netzweber\NwCitavi\Domain\Model\Keyword();
        $keywordsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $keywordsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($keyword));
        $this->inject($this->subject, 'keywords', $keywordsObjectStorageMock);

        $this->subject->addKeyword($keyword);
    }

    /**
     * @test
     */
    public function removeKeywordFromObjectStorageHoldingKeywords()
    {
        $keyword = new \Netzweber\NwCitavi\Domain\Model\Keyword();
        $keywordsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $keywordsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($keyword));
        $this->inject($this->subject, 'keywords', $keywordsObjectStorageMock);

        $this->subject->removeKeyword($keyword);

    }

    /**
     * @test
     */
    public function getEditorsReturnsInitialValueForPerson()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getEditors()
        );

    }

    /**
     * @test
     */
    public function setEditorsForObjectStorageContainingPersonSetsEditors()
    {
        $editor = new \Netzweber\NwCitavi\Domain\Model\Person();
        $objectStorageHoldingExactlyOneEditors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneEditors->attach($editor);
        $this->subject->setEditors($objectStorageHoldingExactlyOneEditors);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneEditors,
            'editors',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addEditorToObjectStorageHoldingEditors()
    {
        $editor = new \Netzweber\NwCitavi\Domain\Model\Person();
        $editorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $editorsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($editor));
        $this->inject($this->subject, 'editors', $editorsObjectStorageMock);

        $this->subject->addEditor($editor);
    }

    /**
     * @test
     */
    public function removeEditorFromObjectStorageHoldingEditors()
    {
        $editor = new \Netzweber\NwCitavi\Domain\Model\Person();
        $editorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $editorsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($editor));
        $this->inject($this->subject, 'editors', $editorsObjectStorageMock);

        $this->subject->removeEditor($editor);

    }

    /**
     * @test
     */
    public function getAuthorsReturnsInitialValueForPerson()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getAuthors()
        );

    }

    /**
     * @test
     */
    public function setAuthorsForObjectStorageContainingPersonSetsAuthors()
    {
        $author = new \Netzweber\NwCitavi\Domain\Model\Person();
        $objectStorageHoldingExactlyOneAuthors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneAuthors->attach($author);
        $this->subject->setAuthors($objectStorageHoldingExactlyOneAuthors);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneAuthors,
            'authors',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addAuthorToObjectStorageHoldingAuthors()
    {
        $author = new \Netzweber\NwCitavi\Domain\Model\Person();
        $authorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $authorsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($author));
        $this->inject($this->subject, 'authors', $authorsObjectStorageMock);

        $this->subject->addAuthor($author);
    }

    /**
     * @test
     */
    public function removeAuthorFromObjectStorageHoldingAuthors()
    {
        $author = new \Netzweber\NwCitavi\Domain\Model\Person();
        $authorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $authorsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($author));
        $this->inject($this->subject, 'authors', $authorsObjectStorageMock);

        $this->subject->removeAuthor($author);

    }

    /**
     * @test
     */
    public function getOthersInvolvedReturnsInitialValueForPerson()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getOthersInvolved()
        );

    }

    /**
     * @test
     */
    public function setOthersInvolvedForObjectStorageContainingPersonSetsOthersInvolved()
    {
        $othersInvolved = new \Netzweber\NwCitavi\Domain\Model\Person();
        $objectStorageHoldingExactlyOneOthersInvolved = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneOthersInvolved->attach($othersInvolved);
        $this->subject->setOthersInvolved($objectStorageHoldingExactlyOneOthersInvolved);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneOthersInvolved,
            'othersInvolved',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addOthersInvolvedToObjectStorageHoldingOthersInvolved()
    {
        $othersInvolved = new \Netzweber\NwCitavi\Domain\Model\Person();
        $othersInvolvedObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $othersInvolvedObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($othersInvolved));
        $this->inject($this->subject, 'othersInvolved', $othersInvolvedObjectStorageMock);

        $this->subject->addOthersInvolved($othersInvolved);
    }

    /**
     * @test
     */
    public function removeOthersInvolvedFromObjectStorageHoldingOthersInvolved()
    {
        $othersInvolved = new \Netzweber\NwCitavi\Domain\Model\Person();
        $othersInvolvedObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $othersInvolvedObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($othersInvolved));
        $this->inject($this->subject, 'othersInvolved', $othersInvolvedObjectStorageMock);

        $this->subject->removeOthersInvolved($othersInvolved);

    }

    /**
     * @test
     */
    public function getCollaboratorsReturnsInitialValueForPerson()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCollaborators()
        );

    }

    /**
     * @test
     */
    public function setCollaboratorsForObjectStorageContainingPersonSetsCollaborators()
    {
        $collaborator = new \Netzweber\NwCitavi\Domain\Model\Person();
        $objectStorageHoldingExactlyOneCollaborators = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCollaborators->attach($collaborator);
        $this->subject->setCollaborators($objectStorageHoldingExactlyOneCollaborators);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneCollaborators,
            'collaborators',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addCollaboratorToObjectStorageHoldingCollaborators()
    {
        $collaborator = new \Netzweber\NwCitavi\Domain\Model\Person();
        $collaboratorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $collaboratorsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($collaborator));
        $this->inject($this->subject, 'collaborators', $collaboratorsObjectStorageMock);

        $this->subject->addCollaborator($collaborator);
    }

    /**
     * @test
     */
    public function removeCollaboratorFromObjectStorageHoldingCollaborators()
    {
        $collaborator = new \Netzweber\NwCitavi\Domain\Model\Person();
        $collaboratorsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $collaboratorsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($collaborator));
        $this->inject($this->subject, 'collaborators', $collaboratorsObjectStorageMock);

        $this->subject->removeCollaborator($collaborator);

    }

    /**
     * @test
     */
    public function getOrganizationsReturnsInitialValueForPerson()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getOrganizations()
        );

    }

    /**
     * @test
     */
    public function setOrganizationsForObjectStorageContainingPersonSetsOrganizations()
    {
        $organization = new \Netzweber\NwCitavi\Domain\Model\Person();
        $objectStorageHoldingExactlyOneOrganizations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneOrganizations->attach($organization);
        $this->subject->setOrganizations($objectStorageHoldingExactlyOneOrganizations);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneOrganizations,
            'organizations',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addOrganizationToObjectStorageHoldingOrganizations()
    {
        $organization = new \Netzweber\NwCitavi\Domain\Model\Person();
        $organizationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $organizationsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($organization));
        $this->inject($this->subject, 'organizations', $organizationsObjectStorageMock);

        $this->subject->addOrganization($organization);
    }

    /**
     * @test
     */
    public function removeOrganizationFromObjectStorageHoldingOrganizations()
    {
        $organization = new \Netzweber\NwCitavi\Domain\Model\Person();
        $organizationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $organizationsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($organization));
        $this->inject($this->subject, 'organizations', $organizationsObjectStorageMock);

        $this->subject->removeOrganization($organization);

    }

    /**
     * @test
     */
    public function getPublishersReturnsInitialValueForPublisher()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getPublishers()
        );

    }

    /**
     * @test
     */
    public function setPublishersForObjectStorageContainingPublisherSetsPublishers()
    {
        $publisher = new \Netzweber\NwCitavi\Domain\Model\Publisher();
        $objectStorageHoldingExactlyOnePublishers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOnePublishers->attach($publisher);
        $this->subject->setPublishers($objectStorageHoldingExactlyOnePublishers);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOnePublishers,
            'publishers',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addPublisherToObjectStorageHoldingPublishers()
    {
        $publisher = new \Netzweber\NwCitavi\Domain\Model\Publisher();
        $publishersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $publishersObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($publisher));
        $this->inject($this->subject, 'publishers', $publishersObjectStorageMock);

        $this->subject->addPublisher($publisher);
    }

    /**
     * @test
     */
    public function removePublisherFromObjectStorageHoldingPublishers()
    {
        $publisher = new \Netzweber\NwCitavi\Domain\Model\Publisher();
        $publishersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $publishersObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($publisher));
        $this->inject($this->subject, 'publishers', $publishersObjectStorageMock);

        $this->subject->removePublisher($publisher);

    }

    /**
     * @test
     */
    public function getPeriodicalsReturnsInitialValueForPeriodical()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getPeriodicals()
        );

    }

    /**
     * @test
     */
    public function setPeriodicalsForObjectStorageContainingPeriodicalSetsPeriodicals()
    {
        $periodical = new \Netzweber\NwCitavi\Domain\Model\Periodical();
        $objectStorageHoldingExactlyOnePeriodicals = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOnePeriodicals->attach($periodical);
        $this->subject->setPeriodicals($objectStorageHoldingExactlyOnePeriodicals);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOnePeriodicals,
            'periodicals',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addPeriodicalToObjectStorageHoldingPeriodicals()
    {
        $periodical = new \Netzweber\NwCitavi\Domain\Model\Periodical();
        $periodicalsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $periodicalsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($periodical));
        $this->inject($this->subject, 'periodicals', $periodicalsObjectStorageMock);

        $this->subject->addPeriodical($periodical);
    }

    /**
     * @test
     */
    public function removePeriodicalFromObjectStorageHoldingPeriodicals()
    {
        $periodical = new \Netzweber\NwCitavi\Domain\Model\Periodical();
        $periodicalsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $periodicalsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($periodical));
        $this->inject($this->subject, 'periodicals', $periodicalsObjectStorageMock);

        $this->subject->removePeriodical($periodical);

    }

    /**
     * @test
     */
    public function getSeriestitlesReturnsInitialValueForSeriestitle()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getSeriestitles()
        );

    }

    /**
     * @test
     */
    public function setSeriestitlesForObjectStorageContainingSeriestitleSetsSeriestitles()
    {
        $seriestitle = new \Netzweber\NwCitavi\Domain\Model\Seriestitle();
        $objectStorageHoldingExactlyOneSeriestitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneSeriestitles->attach($seriestitle);
        $this->subject->setSeriestitles($objectStorageHoldingExactlyOneSeriestitles);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneSeriestitles,
            'seriestitles',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addSeriestitleToObjectStorageHoldingSeriestitles()
    {
        $seriestitle = new \Netzweber\NwCitavi\Domain\Model\Seriestitle();
        $seriestitlesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $seriestitlesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($seriestitle));
        $this->inject($this->subject, 'seriestitles', $seriestitlesObjectStorageMock);

        $this->subject->addSeriestitle($seriestitle);
    }

    /**
     * @test
     */
    public function removeSeriestitleFromObjectStorageHoldingSeriestitles()
    {
        $seriestitle = new \Netzweber\NwCitavi\Domain\Model\Seriestitle();
        $seriestitlesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $seriestitlesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($seriestitle));
        $this->inject($this->subject, 'seriestitles', $seriestitlesObjectStorageMock);

        $this->subject->removeSeriestitle($seriestitle);

    }

    /**
     * @test
     */
    public function getKnowledgeItemsReturnsInitialValueForKnowledgeItem()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getKnowledgeItems()
        );

    }

    /**
     * @test
     */
    public function setKnowledgeItemsForObjectStorageContainingKnowledgeItemSetsKnowledgeItems()
    {
        $knowledgeItem = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItem();
        $objectStorageHoldingExactlyOneKnowledgeItems = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneKnowledgeItems->attach($knowledgeItem);
        $this->subject->setKnowledgeItems($objectStorageHoldingExactlyOneKnowledgeItems);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneKnowledgeItems,
            'knowledgeItems',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addKnowledgeItemToObjectStorageHoldingKnowledgeItems()
    {
        $knowledgeItem = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItem();
        $knowledgeItemsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $knowledgeItemsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($knowledgeItem));
        $this->inject($this->subject, 'knowledgeItems', $knowledgeItemsObjectStorageMock);

        $this->subject->addKnowledgeItem($knowledgeItem);
    }

    /**
     * @test
     */
    public function removeKnowledgeItemFromObjectStorageHoldingKnowledgeItems()
    {
        $knowledgeItem = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItem();
        $knowledgeItemsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $knowledgeItemsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($knowledgeItem));
        $this->inject($this->subject, 'knowledgeItems', $knowledgeItemsObjectStorageMock);

        $this->subject->removeKnowledgeItem($knowledgeItem);

    }

    /**
     * @test
     */
    public function getLocationsReturnsInitialValueForLocation()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getLocations()
        );

    }

    /**
     * @test
     */
    public function setLocationsForObjectStorageContainingLocationSetsLocations()
    {
        $location = new \Netzweber\NwCitavi\Domain\Model\Location();
        $objectStorageHoldingExactlyOneLocations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneLocations->attach($location);
        $this->subject->setLocations($objectStorageHoldingExactlyOneLocations);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneLocations,
            'locations',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addLocationToObjectStorageHoldingLocations()
    {
        $location = new \Netzweber\NwCitavi\Domain\Model\Location();
        $locationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $locationsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($location));
        $this->inject($this->subject, 'locations', $locationsObjectStorageMock);

        $this->subject->addLocation($location);
    }

    /**
     * @test
     */
    public function removeLocationFromObjectStorageHoldingLocations()
    {
        $location = new \Netzweber\NwCitavi\Domain\Model\Location();
        $locationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $locationsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($location));
        $this->inject($this->subject, 'locations', $locationsObjectStorageMock);

        $this->subject->removeLocation($location);

    }

    /**
     * @test
     */
    public function getParentReferencesReturnsInitialValueForReference()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getParentReferences()
        );

    }

    /**
     * @test
     */
    public function setParentReferencesForObjectStorageContainingReferenceSetsParentReferences()
    {
        $parentReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $objectStorageHoldingExactlyOneParentReferences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneParentReferences->attach($parentReference);
        $this->subject->setParentReferences($objectStorageHoldingExactlyOneParentReferences);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneParentReferences,
            'parentReferences',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addParentReferenceToObjectStorageHoldingParentReferences()
    {
        $parentReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $parentReferencesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $parentReferencesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($parentReference));
        $this->inject($this->subject, 'parentReferences', $parentReferencesObjectStorageMock);

        $this->subject->addParentReference($parentReference);
    }

    /**
     * @test
     */
    public function removeParentReferenceFromObjectStorageHoldingParentReferences()
    {
        $parentReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $parentReferencesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $parentReferencesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($parentReference));
        $this->inject($this->subject, 'parentReferences', $parentReferencesObjectStorageMock);

        $this->subject->removeParentReference($parentReference);

    }

    /**
     * @test
     */
    public function getChildReferencesReturnsInitialValueForReference()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getChildReferences()
        );

    }

    /**
     * @test
     */
    public function setChildReferencesForObjectStorageContainingReferenceSetsChildReferences()
    {
        $childReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $objectStorageHoldingExactlyOneChildReferences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneChildReferences->attach($childReference);
        $this->subject->setChildReferences($objectStorageHoldingExactlyOneChildReferences);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneChildReferences,
            'childReferences',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addChildReferenceToObjectStorageHoldingChildReferences()
    {
        $childReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $childReferencesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $childReferencesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($childReference));
        $this->inject($this->subject, 'childReferences', $childReferencesObjectStorageMock);

        $this->subject->addChildReference($childReference);
    }

    /**
     * @test
     */
    public function removeChildReferenceFromObjectStorageHoldingChildReferences()
    {
        $childReference = new \Netzweber\NwCitavi\Domain\Model\Reference();
        $childReferencesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $childReferencesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($childReference));
        $this->inject($this->subject, 'childReferences', $childReferencesObjectStorageMock);

        $this->subject->removeChildReference($childReference);

    }
}
