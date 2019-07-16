<?php
namespace Netzweber\NwCitavi\Domain\Repository;


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
 * The repository for Persons
 */
class PersonRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
  public function initializeObject() {
      /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
      $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
      
      $querySettings->setRespectStoragePage(FALSE);
      
      $this->setDefaultQuerySettings($querySettings);
  }

  public function findAllOptions($group = null, $category = null) {
    if($group) {
      $query = $this->createQuery();
      if($category) {
        $sql = 'SELECT person.uid, person.full_name FROM tx_nwcitavi_domain_model_person person, tx_nwcitavi_reference_'.$group.'_person_mm mmperson, tx_nwcitavi_reference_category_mm mmcategory WHERE person.deleted = 0 AND person.hidden = 0 AND mmperson.uid_foreign = person.uid AND mmcategory.uid_local = mmperson.uid_local AND mmcategory.uid_foreign = '.$category;
      } else {
        $sql = 'SELECT person.uid, person.full_name FROM tx_nwcitavi_domain_model_person person, tx_nwcitavi_reference_'.$group.'_person_mm mmperson, tx_nwcitavi_reference_category_mm mmcategory WHERE person.deleted = 0 AND person.hidden = 0 AND mmperson.uid_foreign = person.uid';
      }
      $query->statement($sql);
      $res = $query->execute();
    } else {
      $query = $this->createQuery();
      $sql = 'SELECT uid, full_name FROM tx_nwcitavi_domain_model_person WHERE deleted = 0 AND hidden = 0';
      $query->statement($sql);
      $res = $query->execute();
    }
    
    return $res;
  }
  
  public function findByCitaviId($citaviId) {
    $query = $this->createQuery();
    $where = 'citavi_id LIKE \''.$citaviId.'\'';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findAuthorsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person p, tx_nwcitavi_reference_authors_person_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findEditorsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person p, tx_nwcitavi_reference_editors_person_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findCollaboratorsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person p, tx_nwcitavi_reference_collaborators_person_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findOrganizationsByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person p, tx_nwcitavi_reference_organizations_person_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
  
  public function findOthersInvolvedByUid($uid) {
    $query = $this->createQuery();
    $where = 'mm.uid_local = '.$uid.' AND p.uid = mm.uid_foreign';
    $sql = 'SELECT * FROM tx_nwcitavi_domain_model_person p, tx_nwcitavi_reference_othersinvolved_person_mm mm WHERE '.$where;
    $query->statement($sql);
    $res = $query->execute();
    
    return $res;
  }
}