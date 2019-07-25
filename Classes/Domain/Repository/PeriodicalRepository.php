<?php
namespace Netzweber\NwCitavi\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Lutz Eckelmann <lutz.eckelmann@xraysoft.de>, Netzweber
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The repository for Periodicals
 */
class PeriodicalRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
  public function initializeObject() {
      /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
      $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
      
      $querySettings->setRespectStoragePage(FALSE);
      
      $this->setDefaultQuerySettings($querySettings);
  }

  public function findAllOptions() {
    $filterCategories = explode(",", $settings['selectedcategory']);
    if(is_array($filterCategories)) {
      if(count($filterCategories) > 1) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_nwcitavi_domain_model_periodical')->createQueryBuilder();
        $orX = $queryBuilder->expr()->orX();
        foreach($filterCategories as $filterCategory) {
          $orX->add($queryBuilder->expr()->eq('mmcategory.uid_foreign', $queryBuilder->createNamedParameter($filterCategory, \PDO::PARAM_INT)));
        }
        $queryBuilder
          ->select('*')
          ->from('tx_nwcitavi_domain_model_periodical')
          ->join(
            'tx_nwcitavi_domain_model_periodical',
            'tx_nwcitavi_reference_periodical_mm',
            'mmperiodical',
            $queryBuilder->expr()->eq('mmperiodical.uid_foreign', 'tx_nwcitavi_domain_model_periodical.uid')
          )
          ->join(
            'mmperiodical',
            'tx_nwcitavi_reference_category_mm',
            'mmcategory',
            $queryBuilder->expr()->eq('mmcategory.uid_local', 'mmperiodical.uid_local'),
            $filterConstraints
          )
          ->where(
            $orX
          )
          ->groupBy('tx_nwcitavi_domain_model_periodical.uid')
          ->orderBy('tx_nwcitavi_domain_model_periodical.name');
          
        $statement = $queryBuilder->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
          $res[$i]['uid'] = $row['uid'];
          $res[$i]['value'] = $row['name'];
          $i++;
        }
      } else {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_nwcitavi_domain_model_periodical')->createQueryBuilder();
        $queryBuilder
          ->select('*')
          ->from('tx_nwcitavi_domain_model_periodical')
          ->join(
            'tx_nwcitavi_domain_model_periodical',
            'tx_nwcitavi_reference_periodical_mm',
            'mmperiodical',
            $queryBuilder->expr()->eq('mmperiodical.uid_foreign', 'tx_nwcitavi_domain_model_periodical.uid')
          )
          ->join(
            'mmperiodical',
            'tx_nwcitavi_reference_category_mm',
            'mmcategory',
            $queryBuilder->expr()->eq('mmcategory.uid_local', 'mmperiodical.uid_local')
          );
        if($settings['selectedcategory']) {
          $queryBuilder
            ->where(
              $queryBuilder->expr()->eq('mmcategory.uid_foreign', $queryBuilder->createNamedParameter($filterCategories[0], \PDO::PARAM_INT))
            );
        }
        $queryBuilder
          ->groupBy('tx_nwcitavi_domain_model_periodical.uid')
          ->orderBy('tx_nwcitavi_domain_model_periodical.name');
          
        $statement = $queryBuilder->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
          $res[$i]['uid'] = $row['uid'];
          $res[$i]['value'] = $row['name'];
          $i++;
        }
      }
    }
    
    return $res;
  }
  
  public function findByCitaviId($citaviId) {
    $query = $this->createQuery();
    $where = 'citavi_id LIKE \''.$citaviId.'\'';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_periodical WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findPeriodicalsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_periodical p, tx_nwcitavi_reference_periodical_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }    
}