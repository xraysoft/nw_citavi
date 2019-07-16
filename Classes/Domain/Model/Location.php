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
 * Location
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * address
     *
     * @var string
     */
    protected $address = '';

    /**
     * notes
     *
     * @var string
     */
    protected $notes = '';

    /**
     * locationType
     *
     * @var string
     */
    protected $locationType = '';

    /**
     * mirrorsReferencePropertyId
     *
     * @var string
     */
    protected $mirrorsReferencePropertyId = '';

    /**
     * addressUri
     *
     * @var string
     */
    protected $addressUri = '';

    /**
     * callNumber
     *
     * @var string
     */
    protected $callNumber = '';

    /**
     * literaturlistId
     *
     * @var string
     */
    protected $literaturlistId = '';

    /**
     * librarys
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Library>
     */
    protected $librarys = null;

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
        $this->librarys = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

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
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the notes
     *
     * @return string $notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets the notes
     *
     * @param string $notes
     * @return void
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Returns the locationType
     *
     * @return string $locationType
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * Sets the locationType
     *
     * @param string $locationType
     * @return void
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * Returns the mirrorsReferencePropertyId
     *
     * @return string $mirrorsReferencePropertyId
     */
    public function getMirrorsReferencePropertyId()
    {
        return $this->mirrorsReferencePropertyId;
    }

    /**
     * Sets the mirrorsReferencePropertyId
     *
     * @param string $mirrorsReferencePropertyId
     * @return void
     */
    public function setMirrorsReferencePropertyId($mirrorsReferencePropertyId)
    {
        $this->mirrorsReferencePropertyId = $mirrorsReferencePropertyId;
    }

    /**
     * Returns the addressUri
     *
     * @return string $addressUri
     */
    public function getAddressUri()
    {
        return $this->addressUri;
    }

    /**
     * Sets the addressUri
     *
     * @param string $addressUri
     * @return void
     */
    public function setAddressUri($addressUri)
    {
        $this->addressUri = $addressUri;
    }

    /**
     * Returns the callNumber
     *
     * @return string $callNumber
     */
    public function getCallNumber()
    {
        return $this->callNumber;
    }

    /**
     * Sets the callNumber
     *
     * @param string $callNumber
     * @return void
     */
    public function setCallNumber($callNumber)
    {
        $this->callNumber = $callNumber;
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
     * Adds a Library
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Library $library
     * @return void
     */
    public function addLibrary(\Netzweber\NwCitavi\Domain\Model\Library $library)
    {
        $this->librarys->attach($library);
    }

    /**
     * Removes a Library
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Library $libraryToRemove The Library to be removed
     * @return void
     */
    public function removeLibrary(\Netzweber\NwCitavi\Domain\Model\Library $libraryToRemove)
    {
        $this->librarys->detach($libraryToRemove);
    }

    /**
     * Returns the librarys
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Library> $librarys
     */
    public function getLibrarys()
    {
        return $this->librarys;
    }

    /**
     * Sets the librarys
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Library> $librarys
     * @return void
     */
    public function setLibrarys(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $librarys)
    {
        $this->librarys = $librarys;
    }
}
