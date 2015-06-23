<?php
/**
 * Created by PhpStorm.
 * User: jaap
 * Date: 6/23/15
 * Time: 10:26 AM
 */

class CRM_CivirulesCronEvent_GroupMembership extends CRM_Civirules_Event_Cron {

  private $dao = false;

  /**
   * This function returns a CRM_Civirules_EventData_EventData this entity is used for triggering the rule
   *
   * Return false when no next entity is available
   *
   * @return CRM_Civirules_EventData_EventData|false
   */
  protected function getNextEntityEventData() {
    if (!$this->dao) {
      if (!$this->queryForEventEntities()) {
        return false;
      }
    }
    if ($this->dao->fetch()) {
      $data = array();
      CRM_Core_DAO::storeValues($this->dao, $data);
      $eventData = new CRM_Civirules_EventData_Cron($this->dao->contact_id, 'GroupContact', $data);
      return $eventData;
    }
    return false;
  }

  /**
   * Returns an array of entities on which the event reacts
   *
   * @return CRM_Civirules_EventData_EntityDefinition
   */
  protected function reactOnEntity() {
    return new CRM_Civirules_EventData_EntityDefinition('GroupContact', 'GroupContact', 'CRM_Contact_DAO_GroupContact', 'GroupContact');
  }

  /**
   * Method to query event entities
   *
   * @access private
   */
  private function queryForEventEntities() {

    if (empty($this->eventParams['group_id'])) {
      return false;
    }

    $sql = "SELECT c.*
            FROM `civicrm_group_contact` `c`
            WHERE `c`.`group_id` = %1 AND c.status = 'Added'
            AND `c`.`contact_id` NOT IN (
              SELECT `rule_log`.`contact_id`
              FROM `civirule_rule_log` `rule_log`
              WHERE `rule_log`.`rule_id` = %2 AND DATE(`rule_log`.`log_date`) = DATE(NOW())
            )";
    $params[1] = array($this->eventParams['group_id'], 'Integer');
    $params[2] = array($this->ruleId, 'Integer');
    $this->dao = CRM_Core_DAO::executeQuery($sql, $params, true, 'CRM_Contact_DAO_GroupContact');

    return true;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a condition
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleId
   * @return bool|string
   * @access public
   * @abstract
   */
  public function getExtraDataInputUrl($ruleId) {
    return CRM_Utils_System::url('civicrm/civirule/form/event/groupmembership/', 'rule_id='.$ruleId);
  }

  public function setEventParams($eventParams) {
    $this->eventParams = unserialize($eventParams);
  }

  /**
   * Returns a description of this event
   *
   * @return string
   * @access public
   * @abstract
   */
  public function getEventDescription() {
    $groupName = ts('Unknown');
    try {
      $groupName = civicrm_api3('Group', 'getvalue', array(
        'return' => 'title',
        'id' => $this->eventParams['group_id']
      ));
    } catch (Exception $e) {
      //do nothing
    }
    return ts('Daily trigger for all members of group %1', array(
      1 => $groupName
    ));
  }

}