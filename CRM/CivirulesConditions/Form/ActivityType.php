<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_CivirulesConditions_Form_ActivityType extends CRM_Core_Form {

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


  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_condition_id');

    $activityTypeList = array('- select -') + CRM_Civirules_Utils::getActivityTypeList();
    asort($activityTypeList);
    $this->add('select', 'activity_type_id', 'Activity Type', $activityTypeList, true);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));

    parent::buildQuickForm();
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
    if (!empty($data['activity_type_id'])) {
      $defaultValues['activity_type_id'] = $data['activity_type_id'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to perform data processing once form is submitted
   *
   * @access public
   */
  public function postProcess() {
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    $conditionLabel = '';
    if ($ruleCondition->find(true)) {
      $ruleId = $ruleCondition->rule_id;
      $condition = new CRM_Civirules_BAO_Condition();
      $condition->id = $ruleCondition->condition_id;
      if ($condition->find(true)) {
        $conditionLabel = $condition->label;
      }
    } else {
      throw new Exception('Could not find rule condition');
    }

    $data['activity_type_id'] = $this->_submitValues['activity_type_id'];
    $ruleCondition->condition_params = serialize($data);
    $ruleCondition->save();

    $session = CRM_Core_Session::singleton();
    $session->setStatus('Condition '.$conditionLabel.' parameters updated to CiviRule '
      .CRM_Civirules_BAO_Rule::getRuleLabelWithId($ruleId),
      'Condition parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$ruleId, TRUE);
    CRM_Utils_System::redirect($redirectUrl);
  }

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