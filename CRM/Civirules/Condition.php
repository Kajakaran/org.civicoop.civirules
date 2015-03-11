<?php

abstract class CRM_Civirules_Condition {

  protected $ruleCondition = array();

  public function setRuleConditionData($ruleCondition) {
    $this->ruleCondition = array();
    if (is_array($ruleCondition)) {
      $this->ruleCondition = $ruleCondition;
    }
  }

  /**
   * This function returns true or false when an condition is valid or not
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return bool
   */
  public abstract function isConditionValid(CRM_Civirules_EventData_EventData $eventData);

  /**
   * Returns a redirect url to extra data input from the user after adding a condition
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleConditionId
   * @return bool|string
   */
  abstract public function getExtraDataInputUrl($ruleConditionId);

  /**
   * Retruns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   */
  public function userFriendlyConditionParams() {
    return '';
  }

  /**
   * Returns an array with required entity names
   *
   * @return array
   */
  public function requiredEntities() {
    return array();
  }



}