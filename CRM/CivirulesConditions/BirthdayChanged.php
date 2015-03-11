<?php

class CRM_CivirulesConditions_BirthdayChanged extends CRM_CivirulesConditions_Generic_FieldChanged {

  /**
   * Returns name of entity
   *
   * @return string
   */
  protected function getEntity() {
    return 'contact';
  }

  /**
   * Returns name of the field
   * @return string
   */
  protected function getField() {
    return 'birth_date';
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
    return new DateTime($fieldData);
  }

}