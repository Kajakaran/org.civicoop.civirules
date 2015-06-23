<?php

/**
 * Copyright (C) 2015 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to CiviCRM under the AGPL-3.0
 */
class CRM_Civirules_Upgrader extends CRM_Civirules_Upgrader_Base {

  /**
   * Create CiviRules tables on extension install. Do not change the
   * sequence as there will be dependencies in the foreign keys
   */
  public function install() {
    $this->executeSqlFile('sql/createCiviruleAction.sql');
    $this->executeSqlFile('sql/createCiviruleCondition.sql');
    $this->executeSqlFile('sql/createCiviruleEvent.sql');
    $this->executeSqlFile('sql/insertCiviruleEvent.sql');
    $this->executeSqlFile('sql/createCiviruleRule.sql');
    $this->executeSqlFile('sql/createCiviruleRuleAction.sql');
    $this->executeSqlFile('sql/createCiviruleRuleCondition.sql');
    $this->executeSqlFile('sql/createCiviruleRuleLog.sql');
  }

  public function uninstall() {
    $this->executeSqlFile('sql/uninstall.sql');
  }

  public function upgrade_1001() {
    CRM_Core_DAO::executeQuery("ALTER TABLE `civirule_rule` ADD event_params TEXT NULL AFTER event_id");

    CRM_Core_DAO::executeQuery("
      INSERT INTO civirule_event (name, label, object_name, op, cron, class_name, created_date, created_user_id)
      VALUES
        ('groupmembership', 'Daily trigger for group members', null, null, 1, 'CRM_CivirulesCronEvent_GroupMembership',  CURDATE(), 1);
      ");
    return true;
  }
}