<?php
/**
 * Class for CiviRules adding an activity to the system
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesActions_Activity_Add extends CRM_CivirulesActions_Generic_Api {

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $params
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return array $params
   * @access protected
   */
  protected function alterApiParameters($params, CRM_Civirules_EventData_EventData $eventData) {
    $action_params = $this->getActionParameters();
    //this function could be overridden in subclasses to alter parameters to meet certain criteraia
    $params['target_contact_id'] = $eventData->getContactId();
    $params['activity_type_id'] = $action_params['activity_type_id'];
    $params['status_id'] = $action_params['status_id'];
    $params['subject'] = $action_params['subject'];
    if (!empty($action_params['assignee_contact_id'])) {
      $params['assignee_contact_id'] = $action_params['assignee_contact_id'];
    }
    return $params;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/action/activity', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $return = '';
    $params = $this->getActionParameters();
    $type = CRM_Core_OptionGroup::getLabel('activity_type', $params['activity_type_id']);
    $return .= ts("Type: %1", array(1 => $type));
    $status = CRM_Core_OptionGroup::getLabel('activity_status', $params['status_id']);
    $return .= "<br>";
    $return .= ts("Status: %1", array(1 => $status));
    $subject = $params['subject'];
    if (!empty($subject)) {
      $return .= "<br>";
      $return .= ts("Subject: %1", array(1 => $subject));
    }
    if (!empty($params['assignee_contact_id'])) {
      $assignee = civicrm_api3('Contact', 'getvalue', array('return' => 'display_name', 'id' => $params['assignee_contact_id']));
      $return .= '<br>';
      $return .= ts("Assignee: %1", array(1 => $assignee));
    }
    return $return;
  }

  /**
   * Method to set the api entity
   *
   * @return string
   * @access protected
   */
  protected function getApiEntity() {
    return 'Activity';
  }

  /**
   * Method to set the api action
   *
   * @return string
   * @access protected
   */
  protected function getApiAction() {
    return 'create';
  }

}