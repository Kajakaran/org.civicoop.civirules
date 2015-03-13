<?php
/**
 * Class for CiviRules ValueComparison Form
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesConditions_Form_Contribution_FinancialType extends CRM_CivirulesConditions_Form_Form {

  protected function getFinancialTypes() {
    $return = array('' => ts('-- please select --'));
    $dao = CRM_Core_DAO::executeQuery("SELECT * FROM `civicrm_financial_type` where `is_active` = 1");
    while($dao->fetch()) {
      $return[$dao->id] = $dao->name;
    }
    return $return;
  }

  /**
   * Overridden parent method to build form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_condition_id');

    $this->add('select', 'financial_type_id', ts('Financial type'), $this->getFinancialTypes(), true);

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
    $data = unserialize($this->ruleCondition->condition_params);
    if (!empty($data['financial_type_id'])) {
      $defaultValues['financial_type_id'] = $data['financial_type_id'];
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
    $data['financial_type_id'] = $this->_submitValues['financial_type_id'];
    $this->ruleCondition->condition_params = serialize($data);
    $this->ruleCondition->save();

    parent::postProcess();
  }
}