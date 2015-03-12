<?php
/**
 * Class for CiviRules CronEvent Birthday
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_CivirulesCronEvent_Birthday extends CRM_Civirules_Event_Cron {

  private $dao = false;

  /**
   * This method returns a CRM_Civirules_EventData_EventData this entity is used for triggering the rule
   *
   * Return false when no next entity is available
   *
   * @return object|bool CRM_Civirules_EventData_EventData|false
   * @access protected
   */
  protected function getNextEntityEventData() {
    if (!$this->dao) {
      $this->queryForEventEntities();
    }
    if ($this->dao->fetch()) {
      $data = array();
      CRM_Core_DAO::storeValues($this->dao, $data);
      $eventData = new CRM_Civirules_EventData_Cron($this->dao->id, 'contact', $data);
      return $eventData;
    }
    return false;
  }

  /**
   * Method to query event entities
   *
   * @access private
   */
  private function queryForEventEntities() {
    $sql = "SELECT c.*
            FROM `civicrm_contact` `c`
            WHERE `c`.`birth_date` IS NOT NULL
            AND DAY(`c`.`birth_date`) = DAY(NOW())
            AND MONTH(`c`.`birth_date`) = MONTH(NOW())
            AND c.is_deceased = 0 and c.is_deleted = 0
            AND `c`.`id` NOT IN (
              SELECT `rule_log`.`contact_id`
              FROM `civirule_rule_log` `rule_log`
              WHERE `rule_log`.`rule_id` = %1 AND DATE(`rule_log`.`log_date`) = DATE(NOW())
            )";
    $params[1] = array($this->ruleId, 'Integer');
    $this->dao = CRM_Core_DAO::executeQuery($sql, $params, 'CRM_Contact_BAO_Contact');
  }

}