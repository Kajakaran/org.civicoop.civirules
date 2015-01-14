<?php
/**
 * BAO RuleAction for CiviRule Rule Action
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_RuleAction extends CRM_Civirules_DAO_RuleAction {
  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function get_values($params) {
    $result = array();
    $rule_action = new CRM_Civirules_BAO_RuleAction();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $rule_action->$key = $value;
        }
      }
    }
    $rule_action->find();
    while ($rule_action->fetch()) {
      $row = array();
      self::storeValues($rule_action, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update rule action
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
      throw new Exception('Params can not be empty when adding or updating a civirule rule action');
    }
    $rule_action = new CRM_Civirules_BAO_RuleAction();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $rule_action->$key = $value;
      }
    }
    $rule_action->save();
    self::storeValues($rule_action, $result);
    return $result;
  }
  /**
   * Function to delete a rule action with id
   * 
   * @param int $rule_action_id
   * @throws Exception when rule_action_id is empty
   * @access public
   * @static
   */
  public static function delete_with_id($rule_action_id) {
    if (empty($rule_action_id)) {
      throw new Exception('rule_action_id can not be empty when attempting to delete a civirule rule action');
    }
    $rule_action = new CRM_Civirules_BAO_RuleAction();
    $rule_action->id = $rule_action_id;
    $rule_action->delete();
    return;
  }
  /**
   * Function to disable a rule action
   * 
   * @param int $rule_action_id
   * @throws Exception when rule_action_id is empty
   * @access public
   * @static
   */
  public static function disable($rule_action_id) {
    if (empty($rule_action_id)) {
      throw new Exception('rule_action_id can not be empty when attempting to disable a civirule rule action');
    }
    $rule_action = new CRM_Civirules_BAO_RuleAction();
    $rule_action->id = $rule_action_id;
    $rule_action->find(true);
    self::add(array('id' => $rule_action->id, 'is_active' => 0));
  }
  /**
   * Function to enable a rule action
   * 
   * @param int $rule_action_id
   * @throws Exception when rule_action_id is empty
   * @access public
   * @static
   */
  public static function enable($rule_action_id) {
    if (empty($rule_action_id)) {
      throw new Exception('rule_action_id can not be empty when attempting to enable a civirule rule action');
    }
    $rule_action = new CRM_Civirules_BAO_RuleAction();
    $rule_action->id = $rule_action_id;
    $rule_action->find(true);
    self::add(array('id' => $rule_action->id, 'is_active' => 1));
  }
}