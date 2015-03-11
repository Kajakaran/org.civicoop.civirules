<?php

abstract class CRM_CivirulesConditions_Generic_FieldChanged extends CRM_Civirules_Condition {

  /**
   * Returns name of entity
   *
   * @return string
   */
  abstract protected function getEntity();

  /**
   * Returns name of the field
   * @return string
   */
  abstract protected function getField();

  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    //not the right event. The event data should contain also
    if (!$eventData instanceof CRM_Civirules_EventData_Interface_OriginalData) {
      return false;
    }

    $entity = $this->getEntity();
    if ($entity != $eventData->getOriginalEntity()) {
      return false;
    }

    $fieldData = $this->getFieldData($eventData);
    $originalData = $this->getOriginalFieldData($eventData);

    if (empty($fieldData) && empty($originalData)) {
      return false; //both original and new data are null so assume not changed
    } elseif ($fieldData == $originalData) {
      return false; //both data are equal so assume not changed
    }

    return true;
  }

  /**
   * This function could be overridden in subclasses to
   * transform field data to a certain type
   *
   * E.g. a date field could be transformed to a DataTime object so that
   * the comparison is easier
   *
   * @param $fieldData
   * @return mixed
   */
  protected function transformFieldData($fieldData) {
    return $fieldData;
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
    return false;
  }

  protected function getFieldData(CRM_Civirules_EventData_EventData $eventData) {
    $entity = $this->getEntity();
    $data = $eventData->getEntityData($entity);
    $field = $this->getField();
    if (isset($data[$field])) {
      return $this->transformFieldData($data[$field]);
    }
    return null;
  }

  protected function getOriginalFieldData(CRM_Civirules_EventData_Interface_OriginalData $eventData) {
    $entity = $this->getEntity();
    if ($eventData->getOriginalEntity() != $entity) {
      return null;
    }

    $data = $eventData->getOriginalData();
    $field = $this->getField();
    if (isset($data[$field])) {
      return $this->transformFieldData($data[$field]);
    }
    return null;
  }
}