<?php

abstract class CRM_Civirules_Action {

  protected $ruleAction = array();

  protected $action = array();

  public function setRuleActionData($ruleAction) {
    $this->ruleAction = array();
    if (is_array($ruleAction)) {
      $this->ruleAction = $ruleAction;
    }
  }

  public function setActionData($action) {
    $this->action = $action;
  }

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
   * @param CRM_Civirules_EventData_EventData $eventData
   */
  public function processAction(CRM_Civirules_EventData_EventData $eventData) {
    $entity = $this->action['api_entity'];
    $action = $this->action['api_action'];

    $params = $this->getActionParameters();

    //alter parameters by subclass
    $params = $this->alterApiParameters($params, $eventData);

    //execute the action
    $this->executeApiAction($entity, $action, $params);
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
  protected function executeApiAction($entity, $action, $parameters) {
    civicrm_api3($entity, $action, $parameters);
  }

  /**
   * Convert parameters to an array of parameters
   *
   * @return array
   */
  protected function getActionParameters() {
    $params = array();
    if (!empty($this->ruleAction['action_params'])) {
      $params = unserialize($this->ruleAction['action_params']);
    }
    return $params;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return false;
  }

  /**
   * Retruns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   */
  public function userFriendlyConditionParams() {
    return '';
  }



}