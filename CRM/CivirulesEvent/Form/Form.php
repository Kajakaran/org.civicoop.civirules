<?php

class CRM_CivirulesEvent_Form_Form extends CRM_Core_Form
{

  protected $ruleId = false;

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
  public function preProcess()
  {
    $this->ruleId = CRM_Utils_Request::retrieve('rule_id', 'Integer');

    $this->rule = new CRM_Civirules_BAO_Rule();
    $this->event = new CRM_Civirules_BAO_Event();

    $this->rule->id = $this->ruleId;
    if (!$this->rule->find(true)) {
      throw new Exception('Civirules could not find rule');
    }

    $this->event->id = $this->rule->event_id;
    if (!$this->event->find(true)) {
      throw new Exception('Civirules could not find event');
    }

    $this->eventClass = CRM_Civirules_BAO_Event::getEventObjectByEventId($this->event->id, true);
    $this->eventClass->setEventId($this->event->id);
    $this->eventClass->setRuleId($this->rule->id);
    $this->eventClass->setEventParams($this->rule->event_params);

    parent::preProcess();

    $this->setFormTitle();
  }

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $defaultValues = array();
    $defaultValues['rule_id'] = $this->ruleId;
    return $defaultValues;
  }

  public function postProcess() {
    $session = CRM_Core_Session::singleton();
    $session->setStatus('Rule '.$this->rule->label.' parameters updated', 'Rule parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$this->rule->id, TRUE);
    CRM_Utils_System::redirect($redirectUrl);
  }

  /**
   * Method to set the form title
   *
   * @access protected
   */
  protected function setFormTitle() {
    $title = 'CiviRules Edit event parameters';
    $this->assign('ruleEventHeader', 'Edit rule '.$this->rule->label);
    CRM_Utils_System::setTitle($title);
  }

}