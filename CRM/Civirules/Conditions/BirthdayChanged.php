<?php

class CRM_Civirules_Conditions_BirthdayChanged extends CRM_Civirules_Conditions_FieldChanged {

  protected function getEntity() {
    return 'contact';
  }

  protected function getField() {
    return 'birth_date';
  }

  protected function transformFieldData($fieldData) {
    return new DateTime($fieldData);
  }

}