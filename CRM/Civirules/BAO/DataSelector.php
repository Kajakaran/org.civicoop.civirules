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
  public static function get_values($params) {
    $result = array();
    $data_selector = new CRM_Civirules_BAO_DataSelector();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $data_selector->$key = $value;
        }
      }
    }
    $data_selector->find();
    while ($data_selector->fetch()) {
      $row = array();
      self::storeValues($data_selector, $row);
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
    $data_selector = new CRM_Civirules_BAO_DataSelector();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $data_selector->$key = $value;
      }
    }
    $data_selector->save();
    self::storeValues($data_selector, $result);
    return $result;
  }
  /**
   * Function to delete a data selector with id
   * 
   * @param int $data_selector_id
   * @throws Exception when data_selector_id is empty
   * @access public
   * @static
   */
  public static function delete_with_id($data_selector_id) {
    if (empty($data_selector_id)) {
      throw new Exception('data_selector_id can not be empty when attempting to delete a civirule data selector');
    }
    $data_selector = new CRM_Civirules_BAO_DataSelector();
    $data_selector->id = $data_selector_id;
    $data_selector->delete();
    return;
  }
  /**
   * Function to disable a data selector
   * 
   * @param int $data_selector_id
   * @throws Exception when data_selector_id is empty
   * @access public
   * @static
   */
  public static function disable($data_selector_id) {
    if (empty($data_selector_id)) {
      throw new Exception('data_selector_id can not be empty when attempting to disable a civirule data selector');
    }
    $data_selector = new CRM_Civirules_BAO_DataSelector();
    $data_selector->id = $data_selector_id;
    $data_selector->find(true);
    self::add(array('id' => $data_selector->id, 'is_active' => 0));
  }
  /**
   * Function to enable a data selector
   * 
   * @param int $data_selector_id
   * @throws Exception when data_selector_id is empty
   * @access public
   * @static
   */
  public static function enable($data_selector_id) {
    if (empty($data_selector_id)) {
      throw new Exception('data_selector_id can not be empty when attempting to enable a civirule data selector');
    }
    $data_selector = new CRM_Civirules_BAO_DataSelector();
    $data_selector->id = $data_selector_id;
    $data_selector->find(true);
    self::add(array('id' => $data_selector->id, 'is_active' => 1));
  }
}