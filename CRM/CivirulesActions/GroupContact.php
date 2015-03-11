<?php

class CRM_CivirulesActions_GroupContact extends CRM_Civirules_Action {

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return array
   */
  protected function alterApiParameters($parameters, CRM_Civirules_EventData_EventData $eventData) {
    //this function could be overridden in subclasses to alter parameters to meet certain criteraia
    $parameters['contact_id'] = $eventData->getContactId();
    return $parameters;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/action/groupcontact', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Retruns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   */
  public function userFriendlyConditionParams() {
    $params = $this->getActionParameters();
    if (!empty($params['group_id'])) {
      $group = civicrm_api3('Group', 'getvalue', array('return' => 'title', 'id' => $params['group_id']));
      return ts('Add contact to group %1', array(1 => $group));
    }
    return '';
  }

}