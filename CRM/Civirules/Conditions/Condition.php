<?php

abstract class CRM_Civirules_Conditions_Condition {

  private $ruleConditionId = false;

  private $ruleCondition = false;

  protected function __construct($ruleConditionId=false) {
    $this->ruleConditionId = $ruleConditionId;
    $this->ruleCondition = false;

    if ($this->getRuleConditionId()) {
      $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
      $ruleCondition->id = $this->getRuleConditionId();
      if ($ruleCondition->find(true)) {
        $ruleConditionData = array();
        CRM_Core_DAO::storeValues($ruleCondition, $ruleConditionData);
        $this->ruleCondition = $ruleConditionData;
      }
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
   * Returns wether this condition has an extra input form
   *
   * If your condition contains a form override this method and return a CRM_Civirules_Conditions_Form_Form
   * If your condition does not have/need an extra input form then return this method should return false
   *
   * @return bool|CRM_Civirules_Conditions_Form_Form
   */
  public function getForm() {
    return false;
  }

  /**
   * Returns the extra condition data
   *
   * @return array
   */
  public function getConditionData() {
    return array();
  }

  /*
   * Transforms condition data so that it could be stored in the database
   *
   * @param array $data
   * @return string
   */
  public function transformConditionData($data=array()) {
    return '';
  }

  /**
   * Returns the Id of the current condition, or false if not set
   *
   * @return int
   */
  public function getRuleConditionId() {
    return $this->ruleConditionId;
  }

  /**
   * Returns the rule condition or false when not set
   *
   * @return array|bool
   */
  public function getRuleCondition() {
    return $this->ruleCondition;
  }



}