<?php
/**
 * BAO RuleEvent for CiviRule Rule Condition
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_RuleEvent extends CRM_Civirules_DAO_RuleEvent {
  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function get_values($params) {
    $result = array();
    $rule_event = new CRM_Civirules_BAO_RuleEvent();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $rule_event->$key = $value;
        }
      }
    }
    $rule_event->find();
    while ($rule_event->fetch()) {
      $row = array();
      self::storeValues($rule_event, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update rule event
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
      throw new Exception('Params can not be empty when adding or updating a civirule rule event');
    }
    $rule_event = new CRM_Civirules_BAO_RuleEvent();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $rule_event->$key = $value;
      }
    }
    $rule_event->save();
    self::storeValues($rule_event, $result);
    return $result;
  }
  /**
   * Function to delete a rule event with id
   * 
   * @param int $rule_event_id
   * @throws Exception when rule_event_id is empty
   * @access public
   * @static
   */
  public static function delete_with_id($rule_event_id) {
    if (empty($rule_event_id)) {
      throw new Exception('rule_event_id can not be empty when attempting to delete a civirule rule event');
    }
    $rule_event = new CRM_Civirules_BAO_RuleEvent();
    $rule_event->id = $rule_event_id;
    $rule_event->delete();
    return;
  }
  /**
   * Function to disable a rule event
   * 
   * @param int $rule_event_id
   * @throws Exception when rule_event_id is empty
   * @access public
   * @static
   */
  public static function disable($rule_event_id) {
    if (empty($rule_event_id)) {
      throw new Exception('rule_event_id can not be empty when attempting to disable a civirule rule event');
    }
    $rule_event = new CRM_Civirules_BAO_RuleEvent();
    $rule_event->id = $rule_event_id;
    $rule_event->find(true);
    self::add(array('id' => $rule_event->id, 'is_active' => 0));
  }
  /**
   * Function to enable a rule event
   * 
   * @param int $rule_event_id
   * @throws Exception when rule_event_id is empty
   * @access public
   * @static
   */
  public static function enable($rule_event_id) {
    if (empty($rule_event_id)) {
      throw new Exception('rule_event_id can not be empty when attempting to enable a civirule rule event');
    }
    $rule_event = new CRM_Civirules_BAO_RuleEvent();
    $rule_event->id = $rule_event_id;
    $rule_event->find(true);
    self::add(array('id' => $rule_event->id, 'is_active' => 1));
  }
}