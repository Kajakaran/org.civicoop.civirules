<?php

abstract class CRM_Civirules_Conditions_Form_Form {

  protected $condition;

  public function __construct(CRM_Civirules_Conditions_Condition $condition) {
    $this->condition = $condition;
  }

  /**
   * You can use this function to add elements to a form
   *
   * @param CRM_Core_Form $form
   * @return void
   */
  abstract public function buildForm(CRM_Core_Form &$form);

  /**
   * You can use this function to set default values
   *
   * @param CRM_Core_Form $form
   * @param array $defaultValues
   * @return array
   */
  abstract public function defaultValues(CRM_Core_Form &$form, $defaultValues);

  /**
   * You can use this to post process the form
   *
   * This function should return a string with the options ready for saving into the database
   *
   * @param CRM_Core_Form $form
   * @param array $submittedValues
   * @return string
   */
  abstract public function postProcess(CRM_Core_Form &$form, $submittedValues);

}