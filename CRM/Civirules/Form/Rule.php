<?php
/**
 * Form controller class to manage CiviRule/Rule
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
require_once 'CRM/Core/Form.php';

class CRM_Civirules_Form_Rule extends CRM_Core_Form {
  
  protected $ruleId = NULL;

  /**
   * Function to buildQuickForm (extends parent function)
   * 
   * @access public
   */
  function buildQuickForm() {
    $this->setPageTitle();
    $this->createFormElements();
    parent::buildQuickForm();
  }

  /**
   * Function to perform processing before displaying form (overrides parent function)
   * 
   * @access public
   */
  function preProcess() {
    if ($this->_action != CRM_Core_Action::ADD) {
      $this->ruleId = CRM_Utils_Request::retrieve('id', 'Positive');
    }
    switch($this->_action) {
      case CRM_Core_Action::DELETE:
        CRM_Civirules_BAO_Rule::deleteWithId($this->ruleId);
        $session = CRM_Core_Session::singleton();
        $session->setStatus('CiviRule deleted', 'Delete', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::DISABLE:
        CRM_Civirules_BAO_Rule::disable($this->ruleId);
        $session = CRM_Core_Session::singleton();
        $session->setStatus('CiviRule disabled', 'Disable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::ENABLE:
        CRM_Civirules_BAO_Rule::enable($this->ruleId);
        $session = CRM_Core_Session::singleton();
        $session->setStatus('CiviRule enabled', 'Enable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
    }
  }

  /**
   * Function to perform post save processing (extends parent function)
   * 
   * @access public
   */
  function postProcess() {
    $values = $this->getVar('_submitValues');
    parent::postProcess();
  }

  /**
   * Function to set default values (overrides parent function)
   * 
   * @return array $defaults
   * @access public
   */
  function setDefaultValues() {
    $defaults = array();
    $defaults['id'] = $this->ruleId;
    switch ($this->_action) {
      case CRM_Core_Action::ADD:
        $this->setAddDefaults($defaults);
        break;
      case CRM_Core_Action::UPDATE:
        $this->setUpdateDefaults($defaults);
        break;
    }
    return $defaults;
  }

  /**
   * Function to add validation rules (overrides parent function)
   * 
   * @access public
   */
  function addRules() {
    $this->addFormRule(array('CRM_Civirules_Form_Rule', 'validateRuleLabelExists'));
  }

  /**
   * Function to validate if rule label already exists
   *
   * @param type $fields
   * @return type
   */
  static function validateRuleLabelExists($fields) {
    /*
     * if id not empty, edit mode. Check if changed before check if exists
     */
    if (!empty($fields['id'])) {
      /*
       * check if values have changed against database label
       */
      $currentLabel = CRM_Civirules_BAO_Rule::getRuleLabelWithId($fields['id']);
      if ($fields['rule_label'] != $currentLabel &&
        CRM_Civirules_BAO_Rule::labelExists($fields['rule_label']) == TRUE) {
        $errors['rule_label'] = 'There is already a rule with this name';
        return $errors;
      }
    } else {
      if (CRM_Civirules_BAO_Rule::labelExists($fields['rule_label']) == TRUE) {
        $errors['rule_label'] = 'There is already a rule with this name';
        return $errors;
      }
    }
    return TRUE;
  }

  /**
   * Function to add the form elements
   * 
   * @access protected
   */
  protected function createFormElements() {
    $this->add('hidden', 'id');
    $this->add('text', 'rule_label', ts('Name'), array('size' => CRM_Utils_Type::HUGE), TRUE);
    $this->add('checkbox', 'rule_is_active', ts('Enabled'));
    $this->add('text', 'rule_created_date', ts('Created Date'));
    $this->add('text', 'rule_created_contact', ts('Created By'));
    if ($this->_action == CRM_Core_Action::UPDATE) {
      $this->createUpdateFormElements();
    }
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Function to add the form elements specific for the update action
   */
  protected function createUpdateFormElements() {
    $this->add('text', 'rule_event_label', '', array('size' => CRM_Utils_Type::HUGE));
    $this->assign('ruleConditions', $this->getRuleConditions());
    $this->assign('ruleActions', $this->getRuleActions());
  }

  /**
   * Function to set the page title based on action and data coming in
   * 
   * @access protected
   */
  protected function setPageTitle() {
    $title = 'CiviRules '.  ucfirst(CRM_Core_Action::description($this->_action)).' Rule';
    CRM_Utils_System::setTitle($title);
  }

  /**
   * Function to set default values if action is add
   * 
   * @param array $defaults
   * @access protected
   */
  protected function setAddDefaults(&$defaults) {
    $defaults['rule_is_active'] = 1;
    $defaults['rule_created_date'] = date('d-m-Y');
    $session = CRM_Core_Session::singleton();
    $defaults['rule_created_contact'] = CRM_Civirules_Utils::getContactName($session->get('userID'));
  }

  /**
   * Function to set default values if action is update
   * 
   * @param array $defaults
   * @access protected
   */
  protected function setUpdateDefaults(&$defaults) {
    $ruleData = CRM_Civirules_BAO_Rule::getValues(array('id' => $this->ruleId));
    if (!empty($ruleData)) {
      $defaults['rule_label'] = $ruleData[$this->ruleId]['label'];
      $defaults['rule_is_active'] = $ruleData[$this->ruleId]['is_active'];
      $defaults['rule_created_date'] = date('d-m-Y', 
        strtotime($ruleData[$this->ruleId]['created_date']));
      $defaults['rule_created_contact'] = CRM_Civirules_Utils::
        getContactName($ruleData[$this->ruleId]['created_contact_id']);
      if (!empty($ruleData[$this->ruleId]['event_id'])) {
        $this->setEventDefaults($ruleData[$this->ruleId]['event_id'], $defaults);
      }
    }
  }

  /**
   * Function to get event defaults
   * 
   * @param int $eventId
   * @param array $defaults
   * @access protected
   */
  protected function setEventDefaults($eventId, &$defaults) {
    if (!empty($eventId)) {
      $defaults['rule_event_label'] = CRM_Civirules_BAO_Event::getEventLabelWithId($eventId);
      $this->assign('deleteEventUrl', $this->setEventDeleteAction($eventId));
    }
  }

  /**
   * Function to get the rule conditions for the rule
   *
   * @return array $ruleConditions
   * @access protected
   */
  protected function getRuleConditions() {
    $conditionParams = array(
      'is_active' => 1,
      'rule_id' => $this->ruleId);
    $ruleConditions = CRM_Civirules_BAO_RuleCondition::getValues($conditionParams);
    foreach ($ruleConditions as $ruleConditionId => $ruleCondition) {
      $ruleConditions[$ruleConditionId]['name'] =
        CRM_Civirules_BAO_Condition::getConditionLabelWithId($ruleCondition['condition_id']);
      $ruleConditions[$ruleConditionId]['comparison'] =
        CRM_Civirules_BAO_Comparison::getComparisonLabelWithId($ruleCondition['comparison_id']);
      $ruleConditions[$ruleConditionId]['actions'] = $this->setRuleConditionActions($ruleConditionId);
    }
    return $ruleConditions;
  }

  /**
   * Function to get the rule actions for the rule
   *
   * @return array $ruleActions
   * @access protected
   */
  protected function getRuleActions() {
    $actionParams = array(
      'is_active' => 1,
      'rule_id' => $this->ruleId);
    $ruleActions = CRM_Civirules_BAO_RuleAction::getValues($actionParams);
    foreach ($ruleActions as $ruleActionId => $ruleAction) {
      $ruleActions[$ruleActionId]['label'] =
        CRM_Civirules_BAO_Action::getActionLabelWithId($ruleAction['action_id']);
      $ruleActions[$ruleActionId]['actions'] = $this->setRuleActionActions($ruleActionId);
    }
    return $ruleActions;
  }

  /**
   * Function to set the actions for each rule condition
   *
   * @param int $ruleConditionId
   * @return array
   * @access protected
   */
  protected function setRuleConditionActions($ruleConditionId) {
    $conditionActions = array();
    $updateUrl = CRM_Utils_System::url('civicrm/civirule/form/rulecondition', 'action=update&id='.
      $ruleConditionId);
    $deleteUrl = CRM_Utils_System::url('civicrm/civirule/form/rulecondition', 'action=delete&id='.
      $ruleConditionId);
    $conditionActions[] = '<a class="action-item" title="Update" href="'.$updateUrl.'">Edit</a>';
    $conditionActions[] = '<a class="action-item" title="Delete" href="'.$deleteUrl.'">Delete</a>';
    return $conditionActions;
  }

  /**
   * Function to set the html for the delete event action
   *
   * @param int $eventId
   * @return string $deleteHtml
   * @access protected
   */
  protected function setEventDeleteAction($eventId) {
    $deleteUrl = CRM_Utils_System::url('civicrm/civirule/form/event', 'action=delete&id='.
      $eventId);
    $deleteHtml = '<a class="action-item" title="Delete" href="'.$deleteUrl.'">Delete</a>';
    return $deleteHtml;
  }

  /**
   * Function to set the actions for each rule action
   *
   * @param int $ruleActionId
   * @return array
   * @access protected
   */
  protected function setRuleActionActions($ruleActionId) {
    $actionActions = array();
    $updateUrl = CRM_Utils_System::url('civicrm/civirule/form/ruleaction', 'action=update&id='.
      $ruleActionId);
    $deleteUrl = CRM_Utils_System::url('civicrm/civirule/form/ruleaction', 'action=delete&id='.
      $ruleActionId);
    $actionActions[] = '<a class="action-item" title="Update" href="'.$updateUrl.'">Edit</a>';
    $actionActions[] = '<a class="action-item" title="Delete" href="'.$deleteUrl.'">Delete</a>';
    return $actionActions;
  }
}
