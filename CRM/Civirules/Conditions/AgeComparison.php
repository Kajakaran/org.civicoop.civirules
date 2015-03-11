<?php

class CRM_Civirules_Conditions_AgeComparison extends CRM_Civirules_Conditions_DataComparison {

  /**
   * Returns value of the field
   *
   * @parameter CRM_Civirules_EventData_EventData $eventData
   * @return mixed
   */
  protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData) {
    $birth_date = civicrm_api3('Contact', 'getvalue', array('id' => $eventData->getContactId(), 'return' => 'birth_date'));
    if ($birth_date) {
      return newDateTime($birth_date)->diff(new DateTime('now'))->y;
    }
    return false; //undefined birth date
  }

  /**
   * Returns the value for the data comparison
   * @return mixed
   */
  protected function getComparisonValue() {
    throw new exception('return age value');
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
    throw new exception('return operator');
  }
}