<?php
/**
 * Form controller class to manage CiviRule/RuleAction
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
require_once 'CRM/Core/Form.php';

class CRM_Civirules_Form_RuleAction extends CRM_Core_Form {

  protected $ruleId = NULL;

  /**
   * Function to buildQuickForm (extends parent function)
   *
   * @access public
   */
  function buildQuickForm() {
    $this->setFormTitle();
    $this->createFormElements();
    parent::buildQuickForm();
  }

  /**
   * Function to perform processing before displaying form (overrides parent function)
   *
   * @access public
   */
  function preProcess() {
    $this->ruleId = CRM_Utils_Request::retrieve('rid', 'Integer');
  }

  /**
   * Function to perform post save processing (extends parent function)
   *
   * @access public
   */
  function postProcess() {
    $session = CRM_Core_Session::singleton();
    $saveParams = array(
      'rule_id' => $this->_submitValues['rule_id'],
      'action_id' => $this->_submitValues['rule_action_select']
    );
    CRM_Civirules_BAO_RuleAction::add($saveParams);
    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$this->_submitValues['rule_id'], TRUE);
    $session->setStatus('Action added to CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($this->_submitValues['rule_id']),
      'Action added', 'success');
    CRM_Utils_System::redirect($redirectUrl);
  }

  /**
   * Function to add the form elements
   *
   * @access protected
   */
  protected function createFormElements() {
    $this->add('hidden', 'rule_id');
    $actionList = array_merge(array(' - select - '), CRM_Civirules_Utils::buildActionList());
    asort($actionList);
    $this->add('select', 'rule_action_select', ts('Select Action'), $actionList, TRUE);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  public function setDefaultValues() {
    $defaults['rule_id'] = $this->ruleId;
    return $defaults;
  }

  /**
   * Function to set the form title based on action and data coming in
   *
   * @access protected
   */
  protected function setFormTitle() {
    $title = 'CiviRules Add Action';
    $this->assign('ruleActionHeader', 'Add Action to CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($this->ruleId));
    CRM_Utils_System::setTitle($title);
  }
}
