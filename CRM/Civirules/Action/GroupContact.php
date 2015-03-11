<?php

class CRM_Civirules_Action_GroupContact extends CRM_Civirules_Action_Action {

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return array
   */
  protected function alterApiParameters($parameters, CRM_Civirules_EventData_EventData $eventData) {
    //this function could be overridden in subclasses to alter parameters to meet certain criteraia
    $parameters['contact_id'] = $eventData->getContactId();
    return $parameters;
  }

}