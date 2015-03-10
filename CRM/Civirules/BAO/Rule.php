<?php
/**
 * BAO Rule for CiviRule Rule
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Rule extends CRM_Civirules_DAO_Rule {

  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $rule = new CRM_Civirules_BAO_Rule();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $rule->$key = $value;
        }
      }
    }
    $rule->find();
    while ($rule->fetch()) {
      $row = array();
      self::storeValues($rule, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update rule
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
      throw new Exception('Params can not be empty when adding or updating a civirule rule');
    }
    $rule = new CRM_Civirules_BAO_Rule();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $rule->$key = $value;
      }
    }
    $rule->save();
    self::storeValues($rule, $result);
    return $result;
  }

  /**
   * Function to delete a rule with id
   * 
   * @param int $ruleId
   * @throws Exception when ruleId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($ruleId) {
    if (empty($ruleId)) {
      throw new Exception('rule id can not be empty when attempting to delete a civirule rule');
    }
    CRM_Civirules_BAO_RuleAction::deleteWithRuleId($ruleId);
    CRM_Civirules_BAO_RuleCondition::deleteWithRuleId($ruleId);
    $rule = new CRM_Civirules_BAO_Rule();
    $rule->id = $ruleId;
    $rule->delete();
    return;
  }

  /**
   * Function to disable a rule
   * 
   * @param int $ruleId
   * @throws Exception when ruleId is empty
   * @access public
   * @static
   */
  public static function disable($ruleId) {
    if (empty($ruleId)) {
      throw new Exception('rule id can not be empty when attempting to disable a civirule rule');
    }
    $rule = new CRM_Civirules_BAO_Rule();
    $rule->id = $ruleId;
    $rule->find(true);
    self::add(array('id' => $rule->id, 'is_active' => 0));
  }

  /**
   * Function to enable an rule
   * 
   * @param int $ruleId
   * @throws Exception when ruleId is empty
   * @access public
   * @static
   */
  public static function enable($ruleId) {
    if (empty($ruleId)) {
      throw new Exception('rule id can not be empty when attempting to enable a civirule rule');
    }
    $rule = new CRM_Civirules_BAO_Rule();
    $rule->id = $ruleId;
    $rule->find(true);
    self::add(array('id' => $rule->id, 'is_active' => 1));
  }

  /**
   * Function to retrieve the label of a rule with ruleId
   * 
   * @param int $ruleId
   * @return string $rule->label
   * @access public
   * @static
   */
  public static function getRuleLabelWithId($ruleId) {
    if (empty($ruleId)) {
      return '';
    }
    $rule = new CRM_Civirules_BAO_Rule();
    $rule->id = $ruleId;
    $rule->find(true);
    return $rule->label;
  }

  /**
   * Function to check if a label already exists in the rule table
   *
   * @param $labelToBeChecked
   * @return bool
   * @access public
   * @static
   */
  public static function labelExists($labelToBeChecked) {
    $rule = new CRM_Civirules_BAO_Rule();
    $rule->label = $labelToBeChecked;
    if ($rule->count() > 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Returns an array with rules which should be triggered
   *
   * @param $entity ObjectName in the Post hook
   * @param $action op in the Post hook
   * @return array
   */
  public static function findRulesByObjectnameAndAction($entity, $action)
  {
    $rules = array();
    $sql = "SELECT r.id AS rule_id, e.id AS event_id
            FROM `civirule_rule` r
            INNER JOIN `civirule_event` e ON r.event_id = e.id AND e.is_active = 1
            WHERE r.`is_active` = 1 AND e.class_name IS NULL AND e.entity = %1 AND e.action = %2";
    $params[1] = array($entity, 'String');
    $params[2] = array($action, 'String');
    $dao = CRM_Core_DAO::executeQuery($sql, $params, TRUE, 'CRM_Civirules_BAO_Rule');
    while ($dao->fetch()) {
      $rule_data = array();
      CRM_Core_DAO::storeValues($dao, $rule_data);
      $rules[] = $rule_data;
    }
    return $rules;
  }

  /*
   * Function to get latest rule id
   *
   * @return int $ruleId
   * @access public
   * @static
   */
  public static function getLatestRuleId() {
    $rule = new CRM_Civirules_BAO_Rule();
    $query = 'SELECT MAX(id) AS maxId FROM '.$rule->tableName();
    $dao = CRM_Core_DAO::executeQuery($query);
    if ($dao->fetch()) {
      return $dao->maxId;
    }
  }
}