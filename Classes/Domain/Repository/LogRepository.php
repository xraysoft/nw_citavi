<?php
namespace Netzweber\NwCitavi\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
        'sorting' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param $error
     * @param $errorText
     * @param $func
     * @param $logType
     * @param $details
     * @param int $importKey
     */
    public function addLog($error, $errorText, $func, $logType, $details, $importKey = 0): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nwcitavi_domain_model_log');
        $queryBuilder
            ->insert('tx_nwcitavi_domain_model_log')
            ->values([
                'error' => $error,
                'errortext' => $errorText,
                'func' => $func,
                'logtype' => $logType,
                'details' => $details,
                'importkey' => $importKey,
                'tstamp' => time(),
                'crdate' => time(),
                'cruser_id' => '1',
            ])
            ->execute();
    }

    /**
     * @return array|QueryResultInterface
     */
    public function findAll() {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(TRUE);
        $query->getQuerySettings()->setIgnoreEnableFields(FALSE);
        $query->getQuerySettings()->setIncludeDeleted(FALSE);
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->setOrderings(
            array(
                'crdate' => QueryInterface::ORDER_DESCENDING
            )
        );
        return $query->execute();
    }

    /**
     *
     */
    public function clearAll(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_nwcitavi_domain_model_log');
        $queryBuilder->truncate('tx_nwcitavi_domain_model_log');
    }
}
