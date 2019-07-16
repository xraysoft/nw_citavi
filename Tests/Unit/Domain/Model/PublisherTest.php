<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class PublisherTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\Publisher
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\Publisher();
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
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );

    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
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
