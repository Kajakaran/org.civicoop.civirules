<?php

class CRM_Civirules_EventData_Cron extends CRM_Civirules_EventData_EventData {

  protected $entity;

  public function __construct($contactId, $entity, $data) {
    parent::__construct();

    $this->entity = $entity;
    $this->contact_id = $contactId;

    $this->setEntityData($entity, $data);
  }

  public function getEntity() {
    return $this->entity;
  }

}