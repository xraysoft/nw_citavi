<?php
namespace Netzweber\NwCitavi\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Lutz Eckelmann <lutz@eckelmann.biz>, Netzweber
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
 * The repository for Publishers
 */
class PublisherRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    public function initializeObject() {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find all publishers for the searchform
     *
     * @param null $settings
     * @return array
     */
    public function findAllOptions($settings = null): array
    {
        $filterCategories = null;
        $filterAuthors = null;
        $filterPublishers = null;
        $filterKeywords = null;
        $filterReferenceTypes = null;
        $filterSeriesTitles = null;
        $filterPeriodicals = null;
        $orXCategory = null;
        $orXAuthor = null;
        $orXPublisher = null;
        $orXKeyword = null;
        $orXReference = null;
        $orXSeriesTitle = null;
        $orXPeriodical = null;
        $res = array();
        if(!empty($settings['selectedcategory'])) {
            $filterCategories = explode(',', $settings['selectedcategory']);
        }
        if(!empty($settings['selectedauthor'])) {
            $filterAuthors = explode(',', $settings['selectedauthor']);
        }
        if(!empty($settings['selectedpublisher'])) {
            $filterPublishers = explode(',', $settings['selectedpublisher']);
        }
        if(!empty($settings['selectedkeyword'])) {
            $filterKeywords = explode(',', $settings['selectedkeyword']);
        }
        if(!empty($settings['selectedreferencetype'])) {
            $filterReferenceTypes = explode(',', $settings['selectedreferencetype']);
        }
        if(!empty($settings['selectedseriestitle'])) {
            $filterSeriesTitles = explode(',', $settings['selectedseriestitle']);
        }
        if(!empty($settings['selectedperiodical'])) {
            $filterPeriodicals = explode(',', $settings['selectedperiodical']);
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_nwcitavi_domain_model_publisher')->createQueryBuilder();
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
                $orXPublisher->add($queryBuilder->expr()->eq('mmpublisher.uid_foreign', $queryBuilder->createNamedParameter($filterPublisher, \PDO::PARAM_INT)));
            }
        }
        if(is_array($filterKeywords)) {
            $orXKeyword = $queryBuilder->expr()->orX();
            foreach($filterKeywords as $filterKeyword) {
                $orXKeyword->add($queryBuilder->expr()->eq('mmkeyword.uid_foreign', $queryBuilder->createNamedParameter($filterKeyword, \PDO::PARAM_INT)));
            }
        }
        if(is_array($filterReferenceTypes)) {
            $orXReference = $queryBuilder->expr()->orX();
            foreach($filterReferenceTypes as $filterReferencetype) {
                $orXReference->add($queryBuilder->expr()->like('references.reference_type', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($filterReferencetype) . '%')));
            }
        }
        if(is_array($filterSeriesTitles)) {
            $orXSeriesTitle = $queryBuilder->expr()->orX();
            foreach($filterSeriesTitles as $filterSeriesTitle) {
                $orXSeriesTitle->add($queryBuilder->expr()->eq('mmseriestitle.uid_foreign', $queryBuilder->createNamedParameter($filterSeriesTitle, \PDO::PARAM_INT)));
            }
        }
        if(is_array($filterPeriodicals)) {
            $orXPeriodical = $queryBuilder->expr()->orX();
            foreach($filterPeriodicals as $filterPeriodical) {
                $orXPeriodical->add($queryBuilder->expr()->eq('mmperiodical.uid_foreign', $queryBuilder->createNamedParameter($filterPeriodical, \PDO::PARAM_INT)));
            }
        }
        $queryBuilder
            ->select('publisher.uid','publisher.name')
            ->from('tx_nwcitavi_domain_model_publisher', 'publisher')
            ->join(
                'publisher',
                'tx_nwcitavi_reference_publisher_mm',
                'mmpublisher',
                $queryBuilder->expr()->eq('mmpublisher.uid_foreign', 'publisher.uid')
            );
        if(is_array($filterCategories)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_category_mm',
                    'mmcategory',
                    $queryBuilder->expr()->eq('mmcategory.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXCategory
                );
        }
        if(is_array($filterAuthors)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_authors_publisher_mm',
                    'mmauthor',
                    $queryBuilder->expr()->eq('mmauthor.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXAuthor
                );
        }
        if(is_array($filterPublishers)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_publisher_mm',
                    'mmpublisher2',
                    $queryBuilder->expr()->eq('mmpublisher2.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXPublisher
                );
        }
        if(is_array($filterKeywords)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_keyword_mm',
                    'mmkeyword',
                    $queryBuilder->expr()->eq('mmkeyword.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXPublisher
                );
        }
        if(is_array($filterReferenceTypes)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_domain_model_reference',
                    'references',
                    $queryBuilder->expr()->eq('references.uid', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXReference
                );
        }
        if(is_array($filterSeriesTitles)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_seriestitle_mm',
                    'mmseriestitle',
                    $queryBuilder->expr()->eq('mmseriestitle.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXSeriesTitle
                );
        }
        if(is_array($filterPeriodicals)) {
            $queryBuilder
                ->join(
                    'mmpublisher',
                    'tx_nwcitavi_reference_seriestitle_mm',
                    'mmperiodical',
                    $queryBuilder->expr()->eq('mmperiodical.uid_local', 'mmpublisher.uid_local')
                )
                ->where(
                    $orXSeriesTitle
                );
        }
        $queryBuilder
            ->groupBy('publisher.uid')
            ->orderBy('publisher.name');
        $statement = $queryBuilder->execute();
        $i = 0;
        while ($row = $statement->fetch()) {
            $res[$i]['uid'] = $row['uid'];
            $res[$i]['value'] = $row['name'];
            $i++;
        }
        return $res;
    }

    /**
     * Find publisher by citavi ID
     *
     * @param $citaviId
     * @return array|QueryResultInterface
     */
    public function findByCitaviId($citaviId) {
        $query = $this->createQuery();
        $where = 'citavi_id LIKE \''.$citaviId.'\'';
        $sql = 'SELECT * FROM tx_nwcitavi_domain_model_publisher WHERE '.$where;
        $query->statement($sql);
        return $query->execute();
    }

    /**
     * Find publisher by UID
     *
     * @param $uid
     * @return array|QueryResultInterface
     */
    public function findPublishersByUid($uid) {
        $query = $this->createQuery();
        $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
        $sql = 'SELECT * FROM tx_nwcitavi_domain_model_publisher p, tx_nwcitavi_reference_publisher_mm mm WHERE '.$where;
        $query->statement($sql);
        return $query->execute();
    }
}
