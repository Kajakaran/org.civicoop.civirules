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
  public static function getValues($params) {
    $result = array();
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $ruleCondition->$key = $value;
        }
      }
    }
    $ruleCondition->find();
    while ($ruleCondition->fetch()) {
      $row = array();
      self::storeValues($ruleCondition, $row);
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
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $ruleCondition->$key = $value;
      }
    }
    $ruleCondition->save();
    self::storeValues($ruleCondition, $result);
    return $result;
  }

  /**
   * Function to delete a rule condition with id
   * 
   * @param int $ruleConditionId
   * @throws Exception when ruleConditionId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($ruleConditionId) {
    if (empty($ruleConditionId)) {
      throw new Exception('rule condition id can not be empty when attempting to delete a civirule rule condition');
    }
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $ruleConditionId;
    $ruleCondition->delete();
    return;
  }

  /**
   * Function to disable a rule condition
   * 
   * @param int $ruleConditionId
   * @throws Exception when ruleConditionId is empty
   * @access public
   * @static
   */
  public static function disable($ruleConditionId) {
    if (empty($ruleConditionId)) {
      throw new Exception('rule condition id can not be empty when attempting to disable a civirule rule condition');
    }
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $ruleConditionId;
    $ruleCondition->find(true);
    self::add(array('id' => $ruleCondition->id, 'is_active' => 0));
  }

  /**
   * Function to enable a rule condition
   * 
   * @param int $ruleConditionId
   * @throws Exception when ruleConditionId is empty
   * @access public
   * @static
   */
  public static function enable($ruleConditionId) {
    if (empty($ruleConditionId)) {
      throw new Exception('rule condition id can not be empty when attempting to enable a civirule rule condition');
    }
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $ruleConditionId;
    $ruleCondition->find(true);
    self::add(array('id' => $ruleCondition->id, 'is_active' => 1));
  }
}