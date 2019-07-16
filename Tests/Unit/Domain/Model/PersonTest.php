<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class PersonTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\Person
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\Person();
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
    public function getFirstNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFirstName()
        );

    }

    /**
     * @test
     */
    public function setFirstNameForStringSetsFirstName()
    {
        $this->subject->setFirstName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'firstName',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLastNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLastName()
        );

    }

    /**
     * @test
     */
    public function setLastNameForStringSetsLastName()
    {
        $this->subject->setLastName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'lastName',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getMiddleNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMiddleName()
        );

    }

    /**
     * @test
     */
    public function setMiddleNameForStringSetsMiddleName()
    {
        $this->subject->setMiddleName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'middleName',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAbbreviationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAbbreviation()
        );

    }

    /**
     * @test
     */
    public function setAbbreviationForStringSetsAbbreviation()
    {
        $this->subject->setAbbreviation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'abbreviation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPrefReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPref()
        );

    }

    /**
     * @test
     */
    public function setPrefForStringSetsPref()
    {
        $this->subject->setPref('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'pref',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSuffReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSuff()
        );

    }

    /**
     * @test
     */
    public function setSuffForStringSetsSuff()
    {
        $this->subject->setSuff('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'suff',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSexReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSex()
        );

    }

    /**
     * @test
     */
    public function setSexForStringSetsSex()
    {
        $this->subject->setSex('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sex',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPersonTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPersonType()
        );

    }

    /**
     * @test
     */
    public function setPersonTypeForStringSetsPersonType()
    {
        $this->subject->setPersonType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'personType',
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
