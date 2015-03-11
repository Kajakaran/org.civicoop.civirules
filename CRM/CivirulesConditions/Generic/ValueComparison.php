<?php

abstract class CRM_CivirulesConditions_Generic_ValueComparison extends CRM_Civirules_Condition {

  private $condition_params = array();

  public function setRuleConditionData($ruleCondition) {
    parent::setRuleConditionData($ruleCondition);
    $this->condition_params = array();
    if (!empty($this->ruleCondition['condition_params'])) {
      $this->condition_params = unserialize($this->ruleCondition['condition_params']);
    }
  }

  /**
   * Returns value of the field
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return mixed
   */
  abstract protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData);

  /**
   * Returns the value for the data comparison
   * @return mixed
   */
  protected function getComparisonValue() {
    return (!empty($this->condition_params['value']) ? $this->condition_params['value'] : '');
  }

  /**
   * Returns an operator for comparison
   *
   * Valid operators are:
   * - equal: =
   * - not equal: !=
   * - greater than: >
   * - lesser than: <
   * - greater than or equal: >=
   * - lesser than or equal: <=
   *
   * @return an operator for comparison
   */
  protected function getOperator() {
    return (!empty($this->condition_params['operator']) ? $this->condition_params['operator'] : '');
  }

  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    $value = $this->getFieldValue($eventData);
    $compareValue = $this->getComparisonValue();

    return $this->compare($value, $compareValue, $this->getOperator());
  }

  protected function compare($leftValue, $rightValue, $operator) {
    switch ($operator) {
      case '=':
        return ($leftValue == $rightValue) ? true : false;
        break;
      case '>':
        return ($leftValue > $rightValue) ? true : false;
        break;
      case '<':
        return ($leftValue < $rightValue) ? true : false;
        break;
      case '>=':
        return ($leftValue >= $rightValue) ? true : false;
        break;
      case '<=':
        return ($leftValue <= $rightValue) ? true : false;
        break;
      case '!=':
        return ($leftValue != $rightValue) ? true : false;
        break;
      default:
        return false;
        break;
    }
    return false;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a condition
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleConditionId
   * @return bool|string
   */
  public function getExtraDataInputUrl($ruleConditionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/condition/datacomparison/', 'rule_condition_id='.$ruleConditionId);
  }

  /**
   * Retruns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   */
  public function userFriendlyConditionParams() {
    return htmlentities(($this->getOperator())).' '.htmlentities($this->getComparisonValue());
  }

}