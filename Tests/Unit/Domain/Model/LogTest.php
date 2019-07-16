<?php
namespace Netzweber\NwCitavi\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Lutz Eckelmann <lutz.eckelmann@netzweber.de>
 * @author Wolfgang Schröder <wolfgang.schroeder@netzweber.de>
 */
class LogTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Netzweber\NwCitavi\Domain\Model\Log
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Netzweber\NwCitavi\Domain\Model\Log();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getErrorReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setErrorForIntSetsError()
    {
    }

    /**
     * @test
     */
    public function getErrortextReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getErrortext()
        );

    }

    /**
     * @test
     */
    public function setErrortextForStringSetsErrortext()
    {
        $this->subject->setErrortext('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'errortext',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFuncReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFunc()
        );

    }

    /**
     * @test
     */
    public function setFuncForStringSetsFunc()
    {
        $this->subject->setFunc('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'func',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLogtypeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLogtype()
        );

    }

    /**
     * @test
     */
    public function setLogtypeForStringSetsLogtype()
    {
        $this->subject->setLogtype('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'logtype',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDetailsReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDetails()
        );

    }

    /**
     * @test
     */
    public function setDetailsForStringSetsDetails()
    {
        $this->subject->setDetails('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'details',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getImportkeyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getImportkey()
        );

    }

    /**
     * @test
     */
    public function setImportkeyForStringSetsImportkey()
    {
        $this->subject->setImportkey('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'importkey',
            $this->subject
        );

    }
}
