<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class KnowledgeItemHashTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\KnowledgeItemHash
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\KnowledgeItemHash();
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
}
