<?php
/**
 * Abstract Class for CiviRules action
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

abstract class CRM_Civirules_Action {

  protected $ruleAction = array();

  protected $action = array();

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
   * Method to set RuleActionData
   *
   * @param $ruleAction
   * @access public
   */
  public function setRuleActionData($ruleAction) {
    $this->ruleAction = array();
    if (is_array($ruleAction)) {
      $this->ruleAction = $ruleAction;
    }
  }

  /**
   * Method to set actionData
   *
   * @param $action
   * @access public
   */
  public function setActionData($action) {
    $this->action = $action;
  }

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

  /**
   * Convert parameters to an array of parameters
   *
   * @return array
   * @access protected
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
   * $access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return false;
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    return '';
  }



}