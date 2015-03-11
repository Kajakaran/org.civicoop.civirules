<?php

class CRM_CivirulesConditions_AgeComparison extends CRM_CivirulesConditions_Generic_ValueComparison {

  /**
   * Returns value of the field
   *
   * @param CRM_Civirules_EventData_EventData $eventData
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
   * Retruns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   */
  public function userFriendlyConditionParams() {
    switch ($this->getOperator()) {
      case '=':
        $label =  'Age is %1';
        break;
      case '>':
        $label =  'Age is older than %1';
        break;
      case '<':
        $label =  'Age is younger than %1';
        break;
      case '>=':
        $label =  'Age is %1 or older than %1';
        break;
      case '<=':
        $label =  'Age is %1 or younger than %1';
        break;
      case '!=':
        $label =  'Age is not %1';
        break;
      default:
        return '';
        break;
    }
    return ts($label, array(1 => $this->getComparisonValue()));
  }

}