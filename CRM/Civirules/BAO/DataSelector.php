<?php
/**
 * BAO Condition for CiviRule Data Selector
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_DataSelector extends CRM_Civirules_DAO_DataSelector {

  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $dataSelector = new CRM_Civirules_BAO_DataSelector();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $dataSelector->$key = $value;
        }
      }
    }
    $dataSelector->find();
    while ($dataSelector->fetch()) {
      $row = array();
      self::storeValues($dataSelector, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update data selector
   * 
   * @param array $params 
   * @return array $result
   * @access public
   * @throws Exception when params is empty
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a civirule data selector');
    }
    $dataSelector = new CRM_Civirules_BAO_DataSelector();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $dataSelector->$key = $value;
      }
    }
    $dataSelector->save();
    self::storeValues($dataSelector, $result);
    return $result;
  }

  /**
   * Function to delete a data selector with id
   * 
   * @param int $dataSelectorId
   * @throws Exception when dataSelectorId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($dataSelectorId) {
    if (empty($dataSelectorId)) {
      throw new Exception('data selector id can not be empty when attempting to delete a civirule data selector');
    }
    $dataSelector = new CRM_Civirules_BAO_DataSelector();
    $dataSelector->id = $dataSelectorId;
    $dataSelector->delete();
    return;
  }

  /**
   * Function to disable a data selector
   * 
   * @param int $dataSelectorId
   * @throws Exception when dataSelectorId is empty
   * @access public
   * @static
   */
  public static function disable($dataSelectorId) {
    if (empty($dataSelectorId)) {
      throw new Exception('data selector id can not be empty when attempting to disable a civirule data selector');
    }
    $dataSelector = new CRM_Civirules_BAO_DataSelector();
    $dataSelector->id = $dataSelectorId;
    $dataSelector->find(true);
    self::add(array('id' => $dataSelector->id, 'is_active' => 0));
  }

  /**
   * Function to enable a data selector
   * 
   * @param int $dataSelectorId
   * @throws Exception when data_selector_id is empty
   * @access public
   * @static
   */
  public static function enable($dataSelectorId) {
    if (empty($dataSelectorId)) {
      throw new Exception('data_selector_id can not be empty when attempting to enable a civirule data selector');
    }
    $dataSelector = new CRM_Civirules_BAO_DataSelector();
    $dataSelector->id = $dataSelectorId;
    $dataSelector->find(true);
    self::add(array('id' => $dataSelector->id, 'is_active' => 1));
  }
}