<?php
/**
 * Class for CiviRules AgeComparison (extending generic ValueComparison)
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesConditions_ContributionAmount extends CRM_CivirulesConditions_Generic_ValueComparison {

  /**
   * Returns value of the field
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return mixed
   * @access protected
   */
  protected function getFieldValue(CRM_Civirules_EventData_EventData $eventData) {
    $contribution = $eventData->getEntityData('Contribution');
    if (isset($contribution['total_amount'])) {
      return (float) $contribution['total_amount'];
    }
    return (float) 0.00; //undefined birth date
  }

  /**
   * Returns the value for the data comparison
   *
   * @return mixed
   * @access protected
   */
  protected function getComparisonValue() {
    if (!empty($this->conditionParams['value'])) {
      return (float) $this->conditionParams['value'];
    } else {
      return (float) 0.00;
    }
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