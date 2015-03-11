<?php

class CRM_Civirules_Conditions_Form_ValueComparison extends CRM_Civirules_Conditions_Form_Form {

  /**
   * You can use this function to add elements to a form
   *
   * @param CRM_Core_Form $form
   * @return void
   */
  public function buildForm(CRM_Core_Form &$form) {
    $form->addSelect('operator', ts('Operator'), array(
      '=' => ts('equals'),
      '!=' => ts('not equals'),
      '>' => ts('greater than'),
      '<' => ts('less than'),
      '>=' => ts('greater than or equals'),
      '<=' => ts('less than or equals'),
    ), true);
    $this->add('text', 'value', ts('Compare value'), true);
  }

  /**
   * You can use this function to set default values
   *
   * @param CRM_Core_Form $form
   * @return array
   */
  public function defaultValues(CRM_Core_Form &$form, $defaultValues) {
    $data = $this->condition->getConditionData();
    if (!empty($data['operator'])) {
      $defaultValues['operator'] = $data['operator'];
    }
    if (!empty($data['value'])) {
      $defaultValues['value'] = $data['value'];
    }
    return $defaultValues;
  }

  /**
   * You can use this to post process the form
   *
   * This function should return a string with the options ready for saving into the database
   *
   * @param CRM_Core_Form $form
   * @param array $submittedValues
   * @return string
   */
  public function postProcess(CRM_Core_Form &$form, $submittedValues) {
    $data['operator'] = $submittedValues['operator'];
    $data['value'] = $submittedValues['values'];
    return $this->condition->transformConditionData($data);
  }

}