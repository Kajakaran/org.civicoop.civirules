<?php

class CRM_CivirulesConditions_PrimaryEmailChanged extends CRM_CivirulesConditions_Generic_FieldChanged {

  /**
   * Returns name of entity
   *
   * @return string
   */
  protected function getEntity() {
    return 'Email';
  }

  /**
   * Returns name of the field
   * @return string
   */
  protected function getField() {
    return 'email';
  }

  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    $isValid = parent::isConditionValid($eventData);
    if ($isValid) {
      $data = $eventData->getEntityData($this->getEntity());
      if (!empty($data['is_primary'])) {
        $isValid = true;
      } else {
        $isValid = false;
      }
    }
    return $isValid;
  }

}