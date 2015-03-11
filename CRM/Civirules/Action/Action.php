<?php

abstract class CRM_Civirules_Action_Action {

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return array
   */
  protected function alterApiParameters($parameters, CRM_Civirules_EventData_EventData $eventData) {
    //this function could be overridden in subclasses to alter parameters to meet certain criteraia
    return $parameters;
  }

  /**
   * Process the action
   *
   * @param $rule_action_id
   * @param $entity
   * @param $action
   * @param $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   */
  public function processAction($rule_action_id, $entity, $action, $parameters, CRM_Civirules_EventData_EventData $eventData) {
    $params = $this->convertParameters($parameters);

    //alter parameters by subclass
    $params = $this->alterApiParameters($params, $eventData);

    //execute the action
    $this->executeAction($entity, $action, $params);
  }

  /**
   * Executes the action
   *
   * This function could be overriden if needed
   *
   * @param $entity
   * @param $action
   * @param $parameters
   */
  protected function executeAction($entity, $action, $parameters) {
    civicrm_api3($entity, $action, $parameters);
  }

  /**
   * Convert parameters to an array of parameters
   *
   * @param $action_parameters
   * @return array
   */
  protected function convertParameters($action_parameters) {
    return CRM_Civirules_Utils_Parameters::convertFromMultiline($action_parameters);
  }



}