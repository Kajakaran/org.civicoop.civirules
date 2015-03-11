<?php

abstract class CRM_Civirules_Conditions_DataComparison extends CRM_Civirules_Conditions_Condition {

  /**
   * Returns value of the field
   *
   * @parameter CRM_Civirules_EventData_EventData $eventData
   * @return mixed
   */
  abstract protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData);

  /**
   * Returns the value for the data comparison
   * @return mixed
   */
  abstract protected function getComparisonValue();

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
  abstract protected function getOperator();

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
   * Returns wether this condition has an extra input form
   *
   * If your condition contains a form override this method and return a CRM_Civirules_Conditions_Form_Form
   * If your condition does not have/need an extra input form then return this method should return false
   *
   * @return bool|CRM_Civirules_Conditions_Form_Form
   */
  public function getForm() {
    return new CRM_Civirules_Conditions_Form_ValueComparison($this);
  }

  /**
   * Returns the extra condition data
   *
   * @return array
   */
  public function getConditionData() {
    $return = array(
      'operator' => '=',
      'value' => '',
    );
    $ruleCondition = $this->getRuleCondition();
    if (is_array($ruleCondition)) {
      $data = CRM_Civirules_Utils_Parameters::convertFromMultiline($ruleCondition['data']);
      if (!empty($data['operator'])) {
        $return['operator'] = $data['operator'];
      }
      if (!empty($data['value'])) {
        $return['value'] = $data['value'];
      }
    }
    return $return;
  }

  /*
   * Transforms condition data so that it could be stored in the database
   *
   * @param array $data
   * @return string
   */
  public function transformConditionData($data=array()) {
    return CRM_Civirules_Utils_Parameters::convertToMultiline($data);
  }

}