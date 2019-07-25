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
 * The repository for Locations
 */
class LocationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
  public function initializeObject() {
      /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
      $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
      
      $querySettings->setRespectStoragePage(FALSE);
      
      $this->setDefaultQuerySettings($querySettings);
  }

  public function findAllOptions() {
    if(!empty($settings['selectedcategory'])) {
      $filterCategories = explode(",", $settings['selectedcategory']);
    }
    if(!empty($settings['selectedauthor'])) {
      $filterAuthors = explode(",", $settings['selectedauthor']);
    }
    if(!empty($settings['selectedlocation'])) {
      $filterPublishers = explode(",", $settings['selectedlocation']);
    }
    if(!empty($settings['selectedkeyword'])) {
      $filterKeywords = explode(",", $settings['selectedkeyword']);
    }
    if(!empty($settings['selectedreferencetype'])) {
      $filterReferencetypes = explode(",", $settings['selectedreferencetype']);
    }
    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_nwcitavi_domain_model_location')->createQueryBuilder();
    if(is_array($filterCategories)) {
      $orXCategory = $queryBuilder->expr()->orX();
      foreach($filterCategories as $filterCategory) {
        $orXCategory->add($queryBuilder->expr()->eq('mmcategory.uid_foreign', $queryBuilder->createNamedParameter($filterCategory, \PDO::PARAM_INT)));
      }
    }
    if(is_array($filterAuthors)) {
      $orXAuthor = $queryBuilder->expr()->orX();
      foreach($filterAuthors as $filterAuthor) {
        $orXAuthor->add($queryBuilder->expr()->eq('mmauthor.uid_foreign', $queryBuilder->createNamedParameter($filterAuthor, \PDO::PARAM_INT)));
      }
    }
    if(is_array($filterPublishers)) {
      $orXPublisher = $queryBuilder->expr()->orX();
      foreach($filterPublishers as $filterPublisher) {
        $orXPublisher->add($queryBuilder->expr()->eq('mmlocation.uid_foreign', $queryBuilder->createNamedParameter($filterPublisher, \PDO::PARAM_INT)));
      }
    }
    if(is_array($filterKeywords)) {
      $orXKeyword = $queryBuilder->expr()->orX();
      foreach($filterKeywords as $filterKeyword) {
        $orXKeyword->add($queryBuilder->expr()->eq('mmkeyword.uid_foreign', $queryBuilder->createNamedParameter($filterKeyword, \PDO::PARAM_INT)));
      }
    }
    if(is_array($filterReferencetypes)) {
      $orXReference = $queryBuilder->expr()->orX();
      foreach($filterReferencetypes as $filterReferencetype) {
        $orXReference->add($queryBuilder->expr()->like('references.reference_type', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($filterReferencetype) . '%')));
      }
    }
    $queryBuilder
      ->select('*')
      ->from('tx_nwcitavi_domain_model_location')
      ->join(
        'tx_nwcitavi_domain_model_location',
        'tx_nwcitavi_reference_location_mm',
        'mmlocation',
        $queryBuilder->expr()->eq('mmlocation.uid_foreign', 'tx_nwcitavi_domain_model_location.uid')
      );
    if(is_array($filterCategories)) {
      $queryBuilder
        ->join(
          'mmlocation',
          'tx_nwcitavi_reference_category_mm',
          'mmcategory',
          $queryBuilder->expr()->eq('mmcategory.uid_local', 'mmlocation.uid_local')
        )
        ->where(
          $orXCategory
        );
    }
    if(is_array($filterAuthors)) {
      $queryBuilder
        ->join(
          'mmlocation',
          'tx_nwcitavi_reference_authors_location_mm',
          'mmauthor',
          $queryBuilder->expr()->eq('mmauthor.uid_local', 'mmlocation.uid_local')
        )
        ->where(
          $orXAuthor
        );
    }          
    if(is_array($filterPublishers)) {
      $queryBuilder
        ->join(
          'mmlocation',
          'tx_nwcitavi_reference_location_mm',
          'mmlocation',
          $queryBuilder->expr()->eq('mmlocation.uid_local', 'mmlocation.uid_local')
        )
        ->where(
          $orXPublisher
        );
    }
    if(is_array($filterKeywords)) {
      $queryBuilder
        ->join(
          'mmlocation',
          'tx_nwcitavi_reference_keyword_mm',
          'mmkeyword',
          $queryBuilder->expr()->eq('mmkeyword.uid_local', 'mmlocation.uid_local')
        )
        ->where(
          $orXPublisher
        );
    }
    if(is_array($filterReferencetypes)) {
      $queryBuilder
        ->join(
          'mmlocation',
          'tx_nwcitavi_domain_model_reference',
          'references',
          $queryBuilder->expr()->eq('references.uid', 'mmlocation.uid_local')
        )
        ->where(
          $orXReference
        );
    }
    $queryBuilder
      ->groupBy('tx_nwcitavi_domain_model_location.uid')
      ->orderBy('tx_nwcitavi_domain_model_location.address');
      
    $statement = $queryBuilder->execute();
    $i = 0;
    while ($row = $statement->fetch()) {
      $res[$i]['uid'] = $row['uid'];
      $res[$i]['value'] = $row['address'];
      $i++;
    }
    
    return $res;
  }
  
  public function findByCitaviId($citaviId) {
    $query = $this->createQuery();
    $where = 'citavi_id LIKE \''.$citaviId.'\'';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_location WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findLocationsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND l.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_location l, tx_nwcitavi_reference_location_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }    
}