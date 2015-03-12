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

    $this->addCondition('CRM_CivirulesConditions_PrimaryEmailChanged', 'primary_email_changed', ts('Primary e-mail addres is changed'));
    $this->addCondition('CRM_CivirulesConditions_AgeComparison', 'age_comparison', ts('Age comparison'));
    $this->addCondition('CRM_CivirulesConditions_BirthdayChanged', 'birthday_changed', ts('Birthday changed'));
    $this->addCondition('CRM_CivirulesConditions_FirstContribution', 'first_contribution_of_contact', ts('First Contribution of a Contact'));
    $this->addCondition('CRM_CivirulesConditions_ContributionStatus', 'contribution_status', ts('Contribution status'));
    $this->addCondition('CRM_CivirulesConditions_ContributionFinancialType', 'contribution_financial_type', ts('Contribution financial type'));
    $this->addCondition('CRM_CivirulesConditions_ContributionAmount', 'contribution_total_amount', ts('Contribution total amount'));

    $this->addAction('CRM_CivirulesActions_GroupContact', 'GroupContact', 'Add contact to group', 'GroupContact', 'create');
  }

  protected function addCondition($class_name, $name, $label) {
    $session = CRM_Core_Session::singleton();
    $userId = $session->get('userID');

    $params['class_name'] = $class_name;
    $params['name'] = $name;
    $params['label'] = $label;
    $params['is_active'] = 1;
    $params['created_user_id'] = $userId;
    $params['created_date'] = date('Ymd');
    CRM_Civirules_BAO_Condition::add($params);
  }

  protected function addAction($class_name, $name, $label, $api_entity, $api_action) {
    $session = CRM_Core_Session::singleton();
    $userId = $session->get('userID');

    $params['class_name'] = $class_name;
    $params['name'] = $name;
    $params['label'] = $label;
    $params['api_entity'] = $api_entity;
    $params['api_action'] = $api_action;
    $params['is_active'] = 1;
    $params['created_user_id'] = $userId;
    $params['created_date'] = date('Ymd');
    CRM_Civirules_BAO_Action::add($params);
  }
}