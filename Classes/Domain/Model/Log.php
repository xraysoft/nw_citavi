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
 * Log
 */
class Log extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * error
     *
     * @var int
     */
    protected $error = 0;

    /**
     * errortext
     *
     * @var string
     */
    protected $errortext = '';

    /**
     * func
     *
     * @var string
     */
    protected $func = '';

    /**
     * logtype
     *
     * @var string
     */
    protected $logtype = '';

    /**
     * details
     *
     * @var string
     */
    protected $details = '';

    /**
     * importkey
     *
     * @var string
     */
    protected $importkey = '';

    /**
     * @var \DateTime
     */
    protected $crdate = null;


    /**
     * Returns the creation date
     *
     * @return \DateTime $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Returns the error
     *
     * @return int $error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the error
     *
     * @param int $error
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Returns the errortext
     *
     * @return string $errortext
     */
    public function getErrortext()
    {
        return $this->errortext;
    }

    /**
     * Sets the errortext
     *
     * @param string $errortext
     * @return void
     */
    public function setErrortext($errortext)
    {
        $this->errortext = $errortext;
    }

    /**
     * Returns the func
     *
     * @return string $func
     */
    public function getFunc()
    {
        return $this->func;
    }

    /**
     * Sets the func
     *
     * @param string $func
     * @return void
     */
    public function setFunc($func)
    {
        $this->func = $func;
    }

    /**
     * Returns the logtype
     *
     * @return string $logtype
     */
    public function getLogtype()
    {
        return $this->logtype;
    }

    /**
     * Sets the logtype
     *
     * @param string $logtype
     * @return void
     */
    public function setLogtype($logtype)
    {
        $this->logtype = $logtype;
    }

    /**
     * Returns the details
     *
     * @return string $details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Sets the details
     *
     * @param string $details
     * @return void
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * Returns the importkey
     *
     * @return string $importkey
     */
    public function getImportkey()
    {
        return $this->importkey;
    }

    /**
     * Sets the importkey
     *
     * @param string $importkey
     * @return void
     */
    public function setImportkey($importkey)
    {
        $this->importkey = $importkey;
    }
}
