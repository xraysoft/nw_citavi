<?php
namespace Netzweber\NwCitavi\Domain\Repository;

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
 * The repository for Logs
 */
class LogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );
    
    public function addLog($error, $errortext, $func, $logtype, $details, $importkey = 0) {
      $insertArray = array(
        'error' => $error,
        'errortext' => $errortext,
        'func' => $func,
        'logtype' => $logtype,
        'details' => $details,
        'importkey' => $importkey,
        'tstamp' => time(),
        'crdate' => time(),
        'cruser_id' => '1'        
      );
      
      $query  = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_nwcitavi_domain_model_log', $insertArray);  
    }
    
    public function findAll() {
      $query = $this->createQuery();
      $query->getQuerySettings()->setRespectSysLanguage(TRUE);
      $query->getQuerySettings()->setIgnoreEnableFields(FALSE);
      $query->getQuerySettings()->setIncludeDeleted(FALSE);
      $query->getQuerySettings()->setRespectStoragePage(FALSE);
      $query->setOrderings(
        array(
          'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
        )
      );
      return $query->execute();
    }
    
    public function clearAll() {
      $GLOBALS['TYPO3_DB']->exec_TRUNCATEquery('tx_nwcitavi_domain_model_log');
    }
}
