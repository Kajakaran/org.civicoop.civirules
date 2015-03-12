<?php
/**
 * Class for CiviRules ValueComparison Form
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesConditions_Form_ContributionStatus extends CRM_Core_Form {

  protected $ruleConditionId = false;

  /**
   * Overridden parent method to perform processing before form is build
   *
   * @access public
   */
  public function preProcess() {
    $this->ruleConditionId = CRM_Utils_Request::retrieve('rule_condition_id', 'Integer');
    parent::preProcess();
  }

  protected function getContributionStatus() {
    return array('' => ts('-- please select --')) + CRM_Core_BAO_OptionValue::getOptionValuesAssocArrayFromName('contribution_status');
  }

  /**
   * Overridden parent method to build form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->setFormTitle();

    $this->add('hidden', 'rule_condition_id');

    $this->add('select', 'contribution_status_id', ts('Status'), $this->getContributionStatus(), true);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $data = array();
    $defaultValues = array();
    $defaultValues['rule_condition_id'] = $this->ruleConditionId;
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    if ($ruleCondition->find(true)) {
      $data = unserialize($ruleCondition->condition_params);
    }
    if (!empty($data['contribution_status_id'])) {
      $defaultValues['contribution_status_id'] = $data['contribution_status_id'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to process form data after submission
   *
   * @throws Exception when rule condition not found
   * @access public
   */
  public function postProcess() {
    $ruleId = 0;
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    $condition_label = '';
    if ($ruleCondition->find(true)) {
      $ruleId = $ruleCondition->rule_id;
      $condition = new CRM_Civirules_BAO_Condition();
      $condition->id = $ruleCondition->condition_id;
      if ($condition->find(true)) {
        $condition_label = $condition->label;
      }
    } else {
      throw new Exception('Could not find rule condition');
    }

    $data['contribution_status_id'] = $this->_submitValues['contribution_status_id'];
    $ruleCondition->condition_params = serialize($data);
    $ruleCondition->save();

    $session = CRM_Core_Session::singleton();
    $session->setStatus('Condition '.$condition_label.' parameters updated to CiviRule '
      .CRM_Civirules_BAO_Rule::getRuleLabelWithId($ruleId),
      'Condition parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$ruleId, TRUE);
    CRM_Utils_System::redirect($redirectUrl);  }

  /**
   * Method to set the form title
   *
   * @access protected
   */
  protected function setFormTitle() {
    $conditionLabel = '';
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    if ($ruleCondition->find(true)) {
      $condition = new CRM_Civirules_BAO_Condition();
      $condition->id = $ruleCondition->condition_id;
      if ($condition->find(true)) {
        $conditionLabel = $condition->label;
      }
    }

    $title = 'CiviRules Edit Condition parameters';
    $this->assign('ruleConditionHeader', 'Edit Condition '.$conditionLabel.' of CiviRule '
      .CRM_Civirules_BAO_Rule::getRuleLabelWithId($ruleCondition->rule_id));
    CRM_Utils_System::setTitle($title);
  }
}