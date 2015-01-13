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
    $this->executeSqlFile('sql/create_civirule_comparison.sql');
    $this->executeSqlFile('sql/create_civirule_condition.sql');
    $this->executeSqlFile('sql/create_civirule_data_selector.sql');
    $this->executeSqlFile('sql/create_civirule_event.sql');
    $this->executeSqlFile('sql/create_civirule_rule.sql');
    $this->executeSqlFile('sql/create_civirule_action.sql');
    $this->executeSqlFile('sql/create_civirule_rule_action.sql');
    $this->executeSqlFile('sql/create_civirule_rule_condition.sql');
    $this->executeSqlFile('sql/create_civirule_rule_event.sql');
  }
}