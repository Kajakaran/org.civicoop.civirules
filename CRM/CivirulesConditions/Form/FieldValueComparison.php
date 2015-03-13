<?php
/**
 * Class for CiviRules ValueComparison Form
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesConditions_Form_FieldValueComparison extends CRM_Core_Form {

  protected $ruleConditionId = false;

  protected $ruleCondition;

  protected $condition;

  protected $rule;

  protected $event;

  /**
   * @var CRM_Civirules_Event
   */
  protected $eventClass;

  /**
   * Overridden parent method to perform processing before form is build
   *
   * @access public
   */
  public function preProcess() {
    $this->ruleConditionId = CRM_Utils_Request::retrieve('rule_condition_id', 'Integer');

    $this->ruleCondition = new CRM_Civirules_BAO_RuleCondition();
    $this->ruleCondition->id = $this->ruleConditionId;

    $this->condition = new CRM_Civirules_BAO_Condition();
    $this->rule = new CRM_Civirules_BAO_Rule();
    $this->event = new CRM_Civirules_BAO_Event();

    if (!$this->ruleCondition->find(true)) {
      throw new Exception('Civirules could not find ruleCondition');
    }

    $this->condition->id = $this->ruleCondition->condition_id;
    if (!$this->condition->find(true)) {
      throw new Exception('Civirules could not find condition');
    }

    $this->rule->id = $this->ruleCondition->rule_id;
    if (!$this->rule->find(true)) {
      throw new Exception('Civirules could not find rule');
    }

    $this->event->id = $this->rule->event_id;
    if (!$this->event->find(true)) {
      throw new Exception('Civirules could not find event');
    }

    $this->eventClass = CRM_Civirules_BAO_Event::getPostEventObjectByClassName($this->event->class_name);

    parent::preProcess();
  }

  protected function getEntities() {
    $return = array();
    foreach($this->eventClass->getProvidedEntities() as $entityDef) {
      if (!empty($entityDef->daoClass) && class_exists($entityDef->daoClass)) {
        $return[$entityDef->entity] = $entityDef->label;
      }
    }
    return $return;
  }

  protected function getFields() {
    $return = array();
    foreach($this->eventClass->getProvidedEntities() as $entityDef) {
      if (!empty($entityDef->daoClass) && class_exists($entityDef->daoClass)) {
        $key = $entityDef->entity . '_';
        if (!is_callable(array($entityDef->daoClass, 'fields'))) {
          continue;
        }
        $fields = call_user_func(array($entityDef->daoClass, 'fields'));
        foreach ($fields as $field) {
          $fieldKey = $key . $field['name'];
          $rteurn[$fieldKey] = $field['title'];
        }
      }
    }
    return $return;
  }

  /**
   * Overridden parent method to build form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->setFormTitle();

    $this->add('hidden', 'rule_condition_id');

    $this->add('select', 'entity', ts('Entity'), $this->getEntities(), true);

    $this->add('select', 'field', ts('Field'), $this->getFields(), true);

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

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $data = array();
    if (!empty($this->ruleCondition->condition_params)) {
      $data = unserialize($this->ruleCondition->condition_params);
    }
    if (!empty($data['operator'])) {
      $defaultValues['operator'] = $data['operator'];
    }
    if (!empty($data['value'])) {
      $defaultValues['value'] = $data['value'];
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
    $data['operator'] = $this->_submitValues['operator'];
    $data['value'] = $this->_submitValues['value'];
    $this->ruleCondition->condition_params = serialize($data);
    $this->ruleCondition->save();

    $session = CRM_Core_Session::singleton();
    $session->setStatus('Condition '.$this->condition->label .'Parameters updated to CiviRule '
      .$this->rule->label,
      'Condition parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$this->rule->id, TRUE);
    CRM_Utils_System::redirect($redirectUrl);  }

  /**
   * Method to set the form title
   *
   * @access protected
   */
  protected function setFormTitle() {
    $title = 'CiviRules Edit Condition parameters';
    $this->assign('ruleConditionHeader', 'Edit Condition '.$this->condition->label.' of CiviRule '.$this->rule->label);
    CRM_Utils_System::setTitle($title);
  }
}