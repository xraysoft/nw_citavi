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
 * KnowledgeItem
 */
class KnowledgeItem extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * knowledgeItemType
     *
     * @var string
     */
    protected $knowledgeItemType = '';

    /**
     * quotationType
     *
     * @var string
     */
    protected $quotationType = '';

    /**
     * coreStatement
     *
     * @var string
     */
    protected $coreStatement = '';

    /**
     * coreStatementUpdateType
     *
     * @var string
     */
    protected $coreStatementUpdateType = '';

    /**
     * text
     *
     * @var string
     */
    protected $text = '';

    /**
     * textRTF
     *
     * @var string
     */
    protected $textRTF = '';

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
     * Returns the knowledgeItemType
     *
     * @return string $knowledgeItemType
     */
    public function getKnowledgeItemType()
    {
        return $this->knowledgeItemType;
    }

    /**
     * Sets the knowledgeItemType
     *
     * @param string $knowledgeItemType
     * @return void
     */
    public function setKnowledgeItemType($knowledgeItemType)
    {
        $this->knowledgeItemType = $knowledgeItemType;
    }

    /**
     * Returns the quotationType
     *
     * @return string $quotationType
     */
    public function getQuotationType()
    {
        return $this->quotationType;
    }

    /**
     * Sets the quotationType
     *
     * @param string $quotationType
     * @return void
     */
    public function setQuotationType($quotationType)
    {
        $this->quotationType = $quotationType;
    }

    /**
     * Returns the coreStatement
     *
     * @return string $coreStatement
     */
    public function getCoreStatement()
    {
        return $this->coreStatement;
    }

    /**
     * Sets the coreStatement
     *
     * @param string $coreStatement
     * @return void
     */
    public function setCoreStatement($coreStatement)
    {
        $this->coreStatement = $coreStatement;
    }

    /**
     * Returns the coreStatementUpdateType
     *
     * @return string $coreStatementUpdateType
     */
    public function getCoreStatementUpdateType()
    {
        return $this->coreStatementUpdateType;
    }

    /**
     * Sets the coreStatementUpdateType
     *
     * @param string $coreStatementUpdateType
     * @return void
     */
    public function setCoreStatementUpdateType($coreStatementUpdateType)
    {
        $this->coreStatementUpdateType = $coreStatementUpdateType;
    }

    /**
     * Returns the text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text
     *
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Returns the textRTF
     *
     * @return string $textRTF
     */
    public function getTextRTF()
    {
        return $this->textRTF;
    }

    /**
     * Sets the textRTF
     *
     * @param string $textRTF
     * @return void
     */
    public function setTextRTF($textRTF)
    {
        $this->textRTF = $textRTF;
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
