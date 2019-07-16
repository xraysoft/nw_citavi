<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class LocationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\Location
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\Location();
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
    public function getAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );

    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getNotesReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getNotes()
        );

    }

    /**
     * @test
     */
    public function setNotesForStringSetsNotes()
    {
        $this->subject->setNotes('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'notes',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLocationTypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLocationType()
        );

    }

    /**
     * @test
     */
    public function setLocationTypeForStringSetsLocationType()
    {
        $this->subject->setLocationType('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'locationType',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getMirrorsReferencePropertyIdReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMirrorsReferencePropertyId()
        );

    }

    /**
     * @test
     */
    public function setMirrorsReferencePropertyIdForStringSetsMirrorsReferencePropertyId()
    {
        $this->subject->setMirrorsReferencePropertyId('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'mirrorsReferencePropertyId',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAddressUriReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddressUri()
        );

    }

    /**
     * @test
     */
    public function setAddressUriForStringSetsAddressUri()
    {
        $this->subject->setAddressUri('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'addressUri',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCallNumberReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCallNumber()
        );

    }

    /**
     * @test
     */
    public function setCallNumberForStringSetsCallNumber()
    {
        $this->subject->setCallNumber('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'callNumber',
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
    public function getLibrarysReturnsInitialValueForLibrary()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getLibrarys()
        );

    }

    /**
     * @test
     */
    public function setLibrarysForObjectStorageContainingLibrarySetsLibrarys()
    {
        $library = new \Netzweber\NwCitavi\Domain\Model\Library();
        $objectStorageHoldingExactlyOneLibrarys = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneLibrarys->attach($library);
        $this->subject->setLibrarys($objectStorageHoldingExactlyOneLibrarys);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneLibrarys,
            'librarys',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addLibraryToObjectStorageHoldingLibrarys()
    {
        $library = new \Netzweber\NwCitavi\Domain\Model\Library();
        $librarysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $librarysObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($library));
        $this->inject($this->subject, 'librarys', $librarysObjectStorageMock);

        $this->subject->addLibrary($library);
    }

    /**
     * @test
     */
    public function removeLibraryFromObjectStorageHoldingLibrarys()
    {
        $library = new \Netzweber\NwCitavi\Domain\Model\Library();
        $librarysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $librarysObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($library));
        $this->inject($this->subject, 'librarys', $librarysObjectStorageMock);

        $this->subject->removeLibrary($library);

    }
}
