<?php

class CRM_CivirulesConditions_Contribution_FinancialType extends CRM_Civirules_Condition {

  private $conditionParams = array();

  /**
   * Method to set the Rule Condition data
   *
   * @param array $ruleCondition
   * @access public
   */
  public function setRuleConditionData($ruleCondition) {
    parent::setRuleConditionData($ruleCondition);
    $this->conditionParams = array();
    if (!empty($this->ruleCondition['condition_params'])) {
      $this->conditionParams = unserialize($this->ruleCondition['condition_params']);
    }
  }

  /**
   * Method to determine if the condition is valid
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return bool
   */

  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    $isConditionValid = FALSE;
    $contribution = $eventData->getEntityData('Contribution');
    switch ($this->conditionParams['operator']) {
      case 0:
        if ($contribution['financial_type_id'] == $this->conditionParams['financial_type_id']) {
          $isConditionValid = TRUE;
        }
      break;
      case 1:
        if ($contribution['financial_type_id'] != $this->conditionParams['financial_type_id']) {
          $isConditionValid = TRUE;
        }
      break;
    }
    return $isConditionValid;
  }

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
  public function getExtraDataInputUrl($ruleConditionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/condition/contribution_financialtype/', 'rule_condition_id='.$ruleConditionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $financialType = new CRM_Financial_BAO_FinancialType();
    $financialType->id = $this->conditionParams['financial_type_id'];
    $operator = null;
    if ($this->conditionParams['operator'] == 0) {
      $operator = 'equals';
    }
    if ($this->conditionParams['operator'] == 1) {
      $operator = 'is not equal to';
    }
    if ($financialType->find(true)) {
      return 'Financial type '.$operator.' '.$financialType->name;
    }
    return '';
  }

  /**
   * Returns an array with required entity names
   *
   * @return array
   * @access public
   */
  public function requiredEntities() {
    return array('Contribution');
  }

}