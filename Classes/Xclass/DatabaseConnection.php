<?php
namespace Netzweber\NwCitavi\Xclass;

class DatabaseConnection extends \TYPO3\CMS\Core\Database\DatabaseConnection {
    /**
     * Creates and executes a SELECT SQL-statement
     * Using this function specifically allow us to handle the LIMIT feature independently of DB.
     *
     * @param string $select_fields List of fields to select from the table. This is what comes right after "SELECT ...". Required value.
     * @param string $from_table Table(s) from which to select. This is what comes right after "FROM ...". Required value.
     * @param string $where_clause Additional WHERE clauses put in the end of the query. NOTICE: You must escape values in this argument with $this->fullQuoteStr() yourself! DO NOT PUT IN GROUP BY, ORDER BY or LIMIT!
     * @param string $groupBy Optional GROUP BY field(s), if none, supply blank string.
     * @param string $orderBy Optional ORDER BY field(s), if none, supply blank string.
     * @param string $limit Optional LIMIT value ([begin,]max), if none, supply blank string.
     * @return bool|\mysqli_result|object MySQLi result object / DBAL object
     */
    public function exec_SELECTquery($select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '')
    {
        $fieldExists = explode(";", $orderBy);
        $numFieldExists = count($fieldExists);
        if($numFieldExists > 1) {
          $newOrderBy = '';
          $sortings = explode(",", $orderBy);
          $numSortings = count($sortings);
          for($i = 0; $i < $numSortings; $i++) {
            $subSortings = explode(";", $sortings[$i]);
            $numSubSortings = count($subSortings);
            if($numSubSortings > 1) {
              if($subSortings[1] == 'field') {
                for($j = 2; $j < $numSubSortings; $j++) {
                  if($j == ($numSubSortings-1)) {
                    $ascdesc = explode(" ", $subSortings[$j]);
                    switch($ascdesc[0]) {
                      case '\'journal_article\'':
                        $field = '\'JournalArticle\'';
                        break;
                      case '\'book\'':
                        $field = '\'Book\'';
                        break;
                      case '\'contribution\'':
                        $field = '\'Contribution\'';
                        break;
                      case '\'unknown\'':
                        $field = '\'Unknown\'';
                        break;
                      case '\'special_issue\'':
                        $field = '\'SpecialIssue\'';
                        break;
                      case '\'unpublished_work\'':
                        $field = '\'UnpublishedWork\'';
                        break;
                      default:
                        $field = $ascdesc[0];
                    }
                    $fields .= ', '.$field.') '.$ascdesc[1];
                  } else {
                    switch($subSortings[$j]) {
                      case '\'journal_article\'':
                        $field = '\'JournalArticle\'';
                        break;
                      case '\'book\'':
                        $field = '\'Book\'';
                        break;
                      case '\'contribution\'':
                        $field = '\'Contribution\'';
                        break;
                      case '\'unknown\'':
                        $field = '\'Unknown\'';
                        break;
                      case '\'special_issue\'':
                        $field = '\'SpecialIssue\'';
                        break;
                      case '\'unpublished_work\'':
                        $field = '\'UnpublishedWork\'';
                        break;
                      default:
                        $field = $subSortings[$j];
                    }
                    $fields .= ', '.$field;
                  }
                }
                if($i > 0) {
                  $newOrderBy .= 'FIELD('.$subSortings[0].$fields;
                } else {
                  $newOrderBy .= 'FIELD('.$subSortings[0].$fields.', ';
                }
                $fields = '';
              }  
            } else {
              if($i == 0) {
                $newOrderBy .= $sortings[$i];
              } else {
                $newOrderBy .= ', '.$sortings[$i];
              }
            }
          }
          $orderBy = $newOrderBy;
        }
        $query = $this->SELECTquery($select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit);
        $res = $this->query($query);
        if ($this->debugOutput) {
            $this->debug('exec_SELECTquery');
        }
        if ($this->explainOutput) {
            $this->explain($query, $from_table, $res->num_rows);
        }
        foreach ($this->postProcessHookObjects as $hookObject) {
            $hookObject->exec_SELECTquery_postProcessAction($select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '', $this);
        }
        return $res;
    }
}