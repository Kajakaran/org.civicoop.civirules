<?php
/**
 * Form controller class to manage CiviRule/RuleCondition
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
require_once 'CRM/Core/Form.php';

class CRM_Civirules_Form_RuleCondition extends CRM_Core_Form {

  protected $ruleId = NULL;
  protected $redirectUrl = NULL;

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
      'condition_id' => $this->_submitValues['rule_condition_select']
    );
    if (isset($this->_submitValues['rule_condition_link_select'])) {
      $saveParams['condition_link'] = $this->_submitValues['rule_condition_link_select'];
    }
    CRM_Civirules_BAO_RuleCondition::add($saveParams);
    $this->redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$this->_submitValues['rule_id'], TRUE);
    $session->setStatus('Condition added to CiviRule', 'Condition added', 'success');
    CRM_Utils_System::redirect($this->redirectUrl);
  }

  /**
   * Function to add the form elements
   *
   * @access protected
   */
  protected function createFormElements() {
    $this->add('hidden', 'rule_id');
    /*
     * add select list only if it is not the first condition
     */
    $linkList = array('AND' => 'AND', 'OR' =>'OR');
    $this->add('select', 'rule_condition_link_select', ts('Select Link Operator'), $linkList, TRUE);
    $conditionList = array_merge(array(' - select - '), CRM_Civirules_Utils::buildConditionList());
    asort($conditionList);
    $this->add('select', 'rule_condition_select', ts('Select Condition'), $conditionList, TRUE);

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
    $title = 'CiviRules Add Condition';
    $this->assign('ruleConditionHeader', 'Add Condition to CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($this->ruleId));
    CRM_Utils_System::setTitle($title);
  }
}
