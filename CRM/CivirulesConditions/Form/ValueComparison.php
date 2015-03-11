<?php

class CRM_CivirulesConditions_Form_ValueComparison extends CRM_Core_Form {

  protected $ruleConditionId = false;

  function preProcess() {
    $this->ruleConditionId = CRM_Utils_Request::retrieve('rule_condition_id', 'Integer');
    parent::preProcess();
  }

  function buildQuickForm() {
    $this->setFormTitle();

    $this->add('hidden', 'rule_condition_id');

    $this->add('select', 'operator', ts('Operator'), array(
      '=' => ts('Is equal to'),
      '!=' => ts('Is not equal to'),
      '>' => ts('Is greater than'),
      '<' => ts('Is less than'),
      '>=' => ts('Is greater than or equal to'),
      '<=' => ts('Is less than or equal to'),
    ), true);
    $this->add('text', 'value', ts('Compare value'), true);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  public function setDefaultValues() {
    $data = array();
    $defaultValues = array();
    $defaultValues['rule_condition_id'] = $this->ruleConditionId;
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    if ($ruleCondition->find(true)) {
      $data = unserialize($ruleCondition->condition_params);
    }
    if (!empty($data['operator'])) {
      $defaultValues['operator'] = $data['operator'];
    }
    if (!empty($data['value'])) {
      $defaultValues['value'] = $data['value'];
    }
    return $defaultValues;
  }

  public function postProcess() {
    $rule_id = 0;
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    $condition_label = '';
    if ($ruleCondition->find(true)) {
      $rule_id = $ruleCondition->rule_id;
      $condition = new CRM_Civirules_BAO_Condition();
      $condition->id = $ruleCondition->condition_id;
      if ($condition->find(true)) {
        $condition_label = $condition->label;
      }
    } else {
      throw new Exception('Could not find rule condition');
    }

    $data['operator'] = $this->_submitValues['operator'];
    $data['value'] = $this->_submitValues['value'];
    $ruleCondition->condition_params = serialize($data);
    $ruleCondition->save();

    $session = CRM_Core_Session::singleton();
    $session->setStatus('Condition '.$condition_label.' parameters updated to CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($rule_id),
      'Condition parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$rule_id, TRUE);
    CRM_Utils_System::redirect($redirectUrl);  }

  protected function setFormTitle() {
    $condition_label = '';
    $ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $ruleCondition->id = $this->ruleConditionId;
    if ($ruleCondition->find(true)) {
      $condition = new CRM_Civirules_BAO_Condition();
      $condition->id = $ruleCondition->condition_id;
      if ($condition->find(true)) {
        $condition_label = $condition->label;
      }
    }

    $title = 'CiviRules Edit Condition parameters';
    $this->assign('ruleConditionHeader', 'Edit Condition '.$condition_label.' of CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($ruleCondition->rule_id));
    CRM_Utils_System::setTitle($title);
  }

}