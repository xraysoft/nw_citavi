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
 * Person
 */
class Person extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * fullName
     *
     * @var string
     */
    protected $fullName = '';
    
    /**
     * firstName
     *
     * @var string
     */
    protected $firstName = '';

    /**
     * lastName
     *
     * @var string
     */
    protected $lastName = '';

    /**
     * middleName
     *
     * @var string
     */
    protected $middleName = '';

    /**
     * abbreviation
     *
     * @var string
     */
    protected $abbreviation = '';

    /**
     * pref
     *
     * @var string
     */
    protected $pref = '';

    /**
     * suff
     *
     * @var string
     */
    protected $suff = '';

    /**
     * sex
     *
     * @var string
     */
    protected $sex = '';

    /**
     * personType
     *
     * @var string
     */
    protected $personType = '';

    /**
     * literaturlistId
     *
     * @var string
     */
    protected $literaturlistId = '';

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
     * Returns the fullName
     *
     * @return string $fullName
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Sets the fullName
     *
     * @param string $fullName
     * @return void
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * Returns the firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the middleName
     *
     * @return string $middleName
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Sets the middleName
     *
     * @param string $middleName
     * @return void
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * Returns the abbreviation
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Sets the abbreviation
     *
     * @param string $abbreviation
     * @return void
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

    /**
     * Returns the pref
     *
     * @return string $pref
     */
    public function getPref()
    {
        return $this->pref;
    }

    /**
     * Sets the pref
     *
     * @param string $pref
     * @return void
     */
    public function setPref($pref)
    {
        $this->pref = $pref;
    }

    /**
     * Returns the suff
     *
     * @return string $suff
     */
    public function getSuff()
    {
        return $this->suff;
    }

    /**
     * Sets the suff
     *
     * @param string $suff
     * @return void
     */
    public function setSuff($suff)
    {
        $this->suff = $suff;
    }

    /**
     * Returns the sex
     *
     * @return string $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets the sex
     *
     * @param string $sex
     * @return void
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * Returns the personType
     *
     * @return string $personType
     */
    public function getPersonType()
    {
        return $this->personType;
    }

    /**
     * Sets the personType
     *
     * @param string $personType
     * @return void
     */
    public function setPersonType($personType)
    {
        $this->personType = $personType;
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
}
