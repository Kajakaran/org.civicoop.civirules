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
    $this->executeSqlFile('sql/createCiviruleComparison.sql');
    $this->executeSqlFile('sql/createCiviruleCondition.sql');
    $this->executeSqlFile('sql/createCiviruleDataSelector.sql');
    $this->executeSqlFile('sql/createCiviruleEvent.sql');
    $this->executeSqlFile('sql/createCiviruleRule.sql');
    $this->executeSqlFile('sql/createCiviruleAction.sql');
    $this->executeSqlFile('sql/createCiviruleRuleAction.sql');
    $this->executeSqlFile('sql/createCiviruleRuleCondition.sql');
  }

  /**
   * Upgrade 1001 - add columns api_action and api_entity to civirule_action
   */
  public function upgrade_1001() {
    $this->ctx->log->info('Applying update 1001 (add columns api_action and api_entity to civirule_action)');
    if (CRM_Core_DAO::checkTableExists('civirule_action')) {
      if (!CRM_Core_DAO::checkFieldExists('civirule_action', 'api_entity')) {
        CRM_Core_DAO::executeQuery('ALTER TABLE civirule_action ADD COLUMN api_entity VARCHAR(45) AFTER label');
      }
      if (!CRM_Core_DAO::checkFieldExists('civirule_action', 'api_action')) {
        CRM_Core_DAO::executeQuery('ALTER TABLE civirule_action ADD COLUMN api_action VARCHAR(45) AFTER api_entity');
      }
    }
    return TRUE;
  }

  /**
   * Upgrade 1002 - drop column comparison_id from civirule_rule_action
   */
  public function upgrade_1002() {
    $this->ctx->log->info('Applying update 1002 (drop column comparison_id from civirule_rule_action)');
    if (CRM_Core_DAO::checkTableExists('civirule_rule_action')) {
      if (CRM_Core_DAO::checkFieldExists('civirule_rule_action', 'comparison_id')) {
        CRM_Core_DAO::executeQuery('ALTER TABLE civirule_rule_action DROP FOREIGN KEY fk_ra_comparison');
        CRM_Core_DAO::executeQuery('ALTER TABLE civirule_rule_action DROP INDEX fk_ra_comparison_idx');
        CRM_Core_DAO::executeQuery('ALTER TABLE civirule_rule_action DROP COLUMN comparison_id');
      }
    }
    return TRUE;
  }
}