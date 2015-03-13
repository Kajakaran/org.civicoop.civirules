<?php
/**
 * Class for CiviRule Condition FirstContribution
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_CivirulesConditions_ActivityType extends CRM_Civirules_Condition {

  private $conditionParams = array();

  public function getExtraDataInputUrl($ruleConditionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/condition/activity_type/',
      'rule_condition_id='.$ruleConditionId);
  }

  /**
   * Method to set the Rule Condition data
   *
   * @param array $ruleCondition
   * @access public
   */
  public function setRuleConditionData($ruleCondition) {
    parent::setRuleConditionData($ruleCondition);
    $this->conditionParams = array();
    if (!empty($this->ruleCondition['condition_params'])) {
      $this->conditionParams = unserialize($this->ruleCondition['condition_params']);
    }
  }

  /**
   * Method to check if the condition is valid, will check if the contact
   * has an activity of the selected type
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return bool
   * @access public
   */
  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData) {
    $contactId = $eventData->getContactId();
    $activities = civicrm_api3('Activity', 'Get', array('contact_id' => $contactId));
    if ($activities['count'] > 0) {
      foreach ($activities['values'] as $activityId => $activity) {
        if ($activity['activity_type_id'] == $this->conditionParams['activity_type_id']) {
          return true;
        }
      }
    }
    return false;
  }
}