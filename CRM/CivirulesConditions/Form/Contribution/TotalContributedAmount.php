<?php

class CRM_CivirulesConditions_Form_Contribution_TotalContributedAmount extends CRM_CivirulesConditions_Form_ValueComparison {

  /**
   * Overridden parent method to build form
   *
   * @access public
   */
  public function buildQuickForm() {
    parent::buildQuickForm();

    $this->add('select', 'period', ts('Period'), array('' => ts('All time')) + CRM_CivirulesConditions_Utils_Period::Options());
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
    if (!empty($data['period'])) {
      $defaultValues['period'] = $data['period'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to process form data after submission
   *
   * @throws Exception when rule condition not found
   * @access public
   */
  public function postProcess()
  {
    $data = unserialize($this->ruleCondition->condition_params);
    $data['period'] = $this->_submitValues['period'];
    $this->ruleCondition->condition_params = serialize($data);
    $this->ruleCondition->save();

    parent::postProcess();
  }

}