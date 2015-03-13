<?php

abstract class CRM_CivirulesActions_Generic_Api extends CRM_Civirules_Action {

  /**
   * Method to get the api entity to process in this CiviRule action
   *
   * @access protected
   * @abstract
   */
  protected abstract function getApiEntity();

  /**
   * Method to get the api action to process in this CiviRule action
   *
   * @access protected
   * @abstract
   */
  protected abstract function getApiAction();

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return array
   * @access protected
   */
  protected function alterApiParameters($parameters, CRM_Civirules_EventData_EventData $eventData) {
    //this method could be overridden in subclasses to alter parameters to meet certain criteria
    return $parameters;
  }

  /**
   * Process the action
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @access public
   */
  public function processAction(CRM_Civirules_EventData_EventData $eventData) {
    $entity = $this->getApiEntity();
    $action = $this->getApiAction();

    $params = $this->getActionParameters();

    //alter parameters by subclass
    $params = $this->alterApiParameters($params, $eventData);

    //execute the action
    $this->executeApiAction($entity, $action, $params);
  }

  /**
   * Executes the action
   *
   * This method could be overridden if needed
   *
   * @param $entity
   * @param $action
   * @param $parameters
   * @access protected
   */
  protected function executeApiAction($entity, $action, $parameters) {
    civicrm_api3($entity, $action, $parameters);
  }

}