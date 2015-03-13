<?php

class CRM_CivirulesConditions_FieldValueComparison extends CRM_CivirulesConditions_Generic_ValueComparison {

  /**
   * Returns the value of the field for the condition
   * For example: I want to check if age > 50, this function would return the 50
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return
   * @access protected
   * @abstract
   */
  protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData) {
    $entity = $this->conditionParams['entity'];
    $field = $this->conditionParams['field'];

    $data = $eventData->getEntityData($entity);
    if (isset($data[$field])) {
      return $this->normalizeValue($data[$field]);
    }
    return null;
  }

  /**
   * Returns the value for the data comparison
   *
   * @return mixed
   * @access protected
   */
  protected function getComparisonValue() {
    if (!empty($this->conditionParams['value'])) {
      return $this->normalizeValue($this->conditionParams['value']);
    } else {
      return null;
    }
  }

  protected function normalizeValue($value) {
    if (value === null) {
      return null;
    }

    //@todo normalize value based on the field
    return $value;
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
    return CRM_Utils_System::url('civicrm/civirule/form/condition/fieldvaluecomparison/', 'rule_condition_id='.$ruleConditionId);
  }

}