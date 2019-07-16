<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class KnowledgeItemTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\KnowledgeItem
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItem();
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
    public function getKnowledgeItemTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getKnowledgeItemType()
        );

    }

    /**
     * @test
     */
    public function setKnowledgeItemTypeForStringSetsKnowledgeItemType()
    {
        $this->subject->setKnowledgeItemType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'knowledgeItemType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getQuotationTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getQuotationType()
        );

    }

    /**
     * @test
     */
    public function setQuotationTypeForStringSetsQuotationType()
    {
        $this->subject->setQuotationType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'quotationType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCoreStatementReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCoreStatement()
        );

    }

    /**
     * @test
     */
    public function setCoreStatementForStringSetsCoreStatement()
    {
        $this->subject->setCoreStatement('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'coreStatement',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCoreStatementUpdateTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCoreStatementUpdateType()
        );

    }

    /**
     * @test
     */
    public function setCoreStatementUpdateTypeForStringSetsCoreStatementUpdateType()
    {
        $this->subject->setCoreStatementUpdateType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'coreStatementUpdateType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTextReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getText()
        );

    }

    /**
     * @test
     */
    public function setTextForStringSetsText()
    {
        $this->subject->setText('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'text',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTextRTFReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTextRTF()
        );

    }

    /**
     * @test
     */
    public function setTextRTFForStringSetsTextRTF()
    {
        $this->subject->setTextRTF('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'textRTF',
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
}
