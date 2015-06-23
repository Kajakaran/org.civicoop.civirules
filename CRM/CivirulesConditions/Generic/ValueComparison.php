<?php
/**
 * Abstract class for generic value comparison conditions
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

abstract class CRM_CivirulesConditions_Generic_ValueComparison extends CRM_Civirules_Condition {

  protected $conditionParams = array();

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
   * Returns the value of the field for the condition
   * For example: I want to check if age > 50, this function would return the 50
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return
   * @access protected
   * @abstract
   */
  abstract protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData);

  /**
   * Returns the value for the data comparison
   *
   * @return mixed
   * @access protected
   */
  protected function getComparisonValue() {
    if (!empty($this->conditionParams['value'])) {
      return $this->conditionParams['value'];
    } else {
      return '';
    }
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
   * @return string operator for comparison
   * @access protected
   */
  protected function getOperator() {
    if (!empty($this->conditionParams['operator'])) {
      return $this->conditionParams['operator'];
    } else {
      return '';
    }
  }

  /**
   * Mandatory method to return if the condition is valid
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return bool
   * @access public
   */

  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    $value = $this->getFieldValue($eventData);
    $compareValue = $this->getComparisonValue();
    return $this->compare($value, $compareValue, $this->getOperator());
  }

  /**
   * Method to compare data
   *
   * @param mixed $leftValue
   * @param mixed $rightValue
   * @param string $operator
   * @return bool
   * @access protected
   */
  protected function compare($leftValue, $rightValue, $operator) {
    switch ($operator) {
      case '=':
        if ($leftValue == $rightValue) {
          return true;
        } else {
          return false;
        }
        break;
      case '>':
        if ($leftValue > $rightValue) {
          return true;
        } else {
          return false;
        }
        break;
      case '<':
        if ($leftValue < $rightValue) {
          return true;
        } else {
          return false;
        }
        break;
      case '>=':
        if ($leftValue >= $rightValue) {
          return true;
        } else {
          return false;
        }
        break;
      case '<=':
        if ($leftValue <= $rightValue) {
          return true;
        } else {
          false;
        }
        break;
      case '!=':
        if ($leftValue != $rightValue) {
          return true;
        } else {
          return false;
        }
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
   * @access public
   */
  public function getExtraDataInputUrl($ruleConditionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/condition/datacomparison/', 'rule_condition_id='.$ruleConditionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    return htmlentities(($this->getOperator())).' '.htmlentities($this->getComparisonValue());
  }

  /**
   * Returns an array with possible operators
   *
   * @return array
   */
  public function getOperators() {
    return array(
      '=' => ts('Is equal to'),
      '!=' => ts('Is not equal to'),
      '>' => ts('Is greater than'),
      '<' => ts('Is less than'),
      '>=' => ts('Is greater than or equal to'),
      '<=' => ts('Is less than or equal to'),
    );
  }

}