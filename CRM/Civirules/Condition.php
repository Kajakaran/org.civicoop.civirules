<?php
/**
 * Abstract Class for CiviRules condition
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

abstract class CRM_Civirules_Condition {

  protected $ruleCondition = array();

  /**
   * Method to set RuleConditionData
   *
   * @param $ruleCondition
   * @access public
   */
  public function setRuleConditionData($ruleCondition) {
    $this->ruleCondition = array();
    if (is_array($ruleCondition)) {
      $this->ruleCondition = $ruleCondition;
    }
  }

  /**
   * This method returns true or false when an condition is valid or not
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return bool
   * @access public
   * @abstract
   */
  public abstract function isConditionValid(CRM_Civirules_EventData_EventData $eventData);

  /**
   * Returns a redirect url to extra data input from the user after adding a condition
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleConditionId
   * @return bool|string
   * @access public
   * @abstract
   */
  abstract public function getExtraDataInputUrl($ruleConditionId);

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    return '';
  }

  /**
   * Returns an array with required entity names
   *
   * @return array
   * @access public
   */
  public function requiredEntities() {
    return array();
  }



}