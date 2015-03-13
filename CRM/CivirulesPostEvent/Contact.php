<?php

class CRM_CivirulesPostEvent_Contact extends CRM_Civirules_Event_Post {

  /**
   * Returns an array of entities on which the event reacts
   *
   * @return CRM_Civirules_EventData_EntityDefinition
   */
  protected function reactOnEntity() {
    return new CRM_Civirules_EventData_EntityDefinition($this->objectName, $this->objectName, $this->getDaoClassName(), 'contact');
  }

  /**
   * Return the name of the DAO Class. If a dao class does not exist return an empty value
   *
   * @return string
   */
  protected function getDaoClassName() {
    return 'CRM_Contact_DAO_Contact';
  }

}