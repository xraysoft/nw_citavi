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
 * Category
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * literaturlistId
     *
     * @var string
     */
    protected $literaturlistId = '';

    /**
     * parent
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category>
     * * @cascade remove
     */
    protected $parent = null;

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
        $this->parent = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * Adds a Category
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Category $parent
     * @return void
     */
    public function addParent(\Netzweber\NwCitavi\Domain\Model\Category $parent)
    {
        $this->parent->attach($parent);
    }

    /**
     * Removes a Category
     *
     * @param \Netzweber\NwCitavi\Domain\Model\Category $parentToRemove The Category to be removed
     * @return void
     */
    public function removeParent(\Netzweber\NwCitavi\Domain\Model\Category $parentToRemove)
    {
        $this->parent->detach($parentToRemove);
    }

    /**
     * Returns the parent
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category> $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets the parent
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netzweber\NwCitavi\Domain\Model\Category> $parent
     * @return void
     */
    public function setParent(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $parent)
    {
        $this->parent = $parent;
    }
}
