<?php
/**
 * BAO Condition for CiviRule Condition
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Condition extends CRM_Civirules_DAO_Condition {
  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function get_values($params) {
    $result = array();
    $condition = new CRM_Civirules_BAO_Condition();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $condition->$key = $value;
        }
      }
    }
    $condition->find();
    while ($condition->fetch()) {
      $row = array();
      self::storeValues($condition, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update condition
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
      throw new Exception('Params can not be empty when adding or updating a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $condition->$key = $value;
      }
    }
    $condition->save();
    self::storeValues($condition, $result);
    return $result;
  }
  /**
   * Function to delete a condition with id
   * 
   * @param int $condition_id
   * @throws Exception when condition_id is empty
   * @access public
   * @static
   */
  public static function delete_with_id($condition_id) {
    if (empty($condition_id)) {
      throw new Exception('condition_id can not be empty when attempting to delete a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $condition_id;
    $condition->delete();
    return;
  }
  /**
   * Function to disable a condition
   * 
   * @param int $condition_id
   * @throws Exception when condition_id is empty
   * @access public
   * @static
   */
  public static function disable($condition_id) {
    if (empty($condition_id)) {
      throw new Exception('condition_id can not be empty when attempting to disable a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $condition_id;
    $condition->find(true);
    self::add(array('id' => $condition->id, 'is_active' => 0));
  }
  /**
   * Function to enable a condition
   * 
   * @param int $condition_id
   * @throws Exception when condition_id is empty
   * @access public
   * @static
   */
  public static function enable($condition_id) {
    if (empty($condition_id)) {
      throw new Exception('condition_id can not be empty when attempting to enable a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $condition_id;
    $condition->find(true);
    self::add(array('id' => $condition->id, 'is_active' => 1));
  }
}