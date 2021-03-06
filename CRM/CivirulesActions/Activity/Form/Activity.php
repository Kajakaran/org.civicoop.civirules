<?php
/**
 * Class for CiviRules Group Contact Action Form
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesActions_Activity_Form_Activity extends CRM_CivirulesActions_Form_Form {


  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_action_id');

    $this->add('select', 'activity_type_id', ts('Activity type'), array('' => ts('-- please select --')) + CRM_Core_OptionGroup::values('activity_type'), true);

    $this->add('select', 'status_id', ts('Status'), array('' => ts('-- please select --')) + CRM_Core_OptionGroup::values('activity_status'), true);

    $this->add('text', 'subject', ts('Subject'));

    $data = unserialize($this->ruleAction->action_params);
    $assignees = array();
    if (!empty($data['assignee_contact_id'])) {
      $assignees[] = $data['assignee_contact_id'];
    }
    $this->assign('selectedContacts', implode(",", $assignees));
    CRM_Contact_Form_NewContact::buildQuickForm($this);


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
    $defaultValues = parent::setDefaultValues();
    $data = unserialize($this->ruleAction->action_params);
    if (!empty($data['activity_type_id'])) {
      $defaultValues['activity_type_id'] = $data['activity_type_id'];
    }
    if (!empty($data['status_id'])) {
      $defaultValues['status_id'] = $data['status_id'];
    }
    if (!empty($data['subject'])) {
      $defaultValues['subject'] = $data['subject'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to process form data after submitting
   *
   * @access public
   */
  public function postProcess() {
    $data['activity_type_id'] = $this->_submitValues['activity_type_id'];
    $data['status_id'] = $this->_submitValues['status_id'];
    $data['subject'] = $this->_submitValues['subject'];
    $data['assignee_contact_id'] = false;

    $values = $this->controller->exportValues();
    if (!empty($values['contact_select_id']) && count($values['contact_select_id']) > 0) {
      $data['assignee_contact_id'] = reset($values['contact_select_id']);
    }

    $this->ruleAction->action_params = serialize($data);
    $this->ruleAction->save();
    parent::postProcess();
  }

}