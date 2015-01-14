<?php
/**
 * BAO RuleCondition for CiviRule Rule Condition
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_RuleCondition extends CRM_Civirules_DAO_RuleCondition {
  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function get_values($params) {
    $result = array();
    $rule_condition = new CRM_Civirules_BAO_RuleCondition();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $rule_condition->$key = $value;
        }
      }
    }
    $rule_condition->find();
    while ($rule_condition->fetch()) {
      $row = array();
      self::storeValues($rule_condition, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update rule condition
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
      throw new Exception('Params can not be empty when adding or updating a civirule rule condition');
    }
    $rule_condition = new CRM_Civirules_BAO_RuleCondition();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $rule_condition->$key = $value;
      }
    }
    $rule_condition->save();
    self::storeValues($rule_condition, $result);
    return $result;
  }
  /**
   * Function to delete a rule condition with id
   * 
   * @param int $rule_condition_id
   * @throws Exception when rule_condition_id is empty
   * @access public
   * @static
   */
  public static function delete_with_id($rule_condition_id) {
    if (empty($rule_condition_id)) {
      throw new Exception('rule_condition_id can not be empty when attempting to delete a civirule rule condition');
    }
    $rule_condition = new CRM_Civirules_BAO_RuleCondition();
    $rule_condition->id = $rule_condition_id;
    $rule_condition->delete();
    return;
  }
  /**
   * Function to disable a rule condition
   * 
   * @param int $rule_condition_id
   * @throws Exception when rule_condition_id is empty
   * @access public
   * @static
   */
  public static function disable($rule_condition_id) {
    if (empty($rule_condition_id)) {
      throw new Exception('rule_condition_id can not be empty when attempting to disable a civirule rule condition');
    }
    $rule_condition = new CRM_Civirules_BAO_RuleCondition();
    $rule_condition->id = $rule_condition_id;
    $rule_condition->find(true);
    self::add(array('id' => $rule_condition->id, 'is_active' => 0));
  }
  /**
   * Function to enable a rule condition
   * 
   * @param int $rule_condition_id
   * @throws Exception when rule_condition_id is empty
   * @access public
   * @static
   */
  public static function enable($rule_condition_id) {
    if (empty($rule_condition_id)) {
      throw new Exception('rule_condition_id can not be empty when attempting to enable a civirule rule condition');
    }
    $rule_condition = new CRM_Civirules_BAO_RuleCondition();
    $rule_condition->id = $rule_condition_id;
    $rule_condition->find(true);
    self::add(array('id' => $rule_condition->id, 'is_active' => 1));
  }
}