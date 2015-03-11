<?php

class CRM_Civirules_EventData_Edit extends CRM_Civirules_EventData_Post implements CRM_Civirules_EventData_Interface_OriginalData {

  protected $originalData = array();

  public function __construct($entity, $objectId, $data, $originalData) {
    parent::__construct($entity, $objectId, $data);

    if (!is_array($originalData)) {
      throw new Exception('Original data is not set or is not an array in EditEventData for CiviRules');
    }
    $this->originalData = $originalData;
  }

  public function getOriginalData() {
    return $this->originalData;
  }

  public function getOriginalEntity() {
    return $this->entity;
  }

}