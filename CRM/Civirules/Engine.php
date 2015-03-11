<?php

class CRM_Civirules_Engine {

  /**
   * Trigger a rule.
   *
   * The trigger will check the conditions and if conditions are valid then the actions are executed
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @param $rule_id
   */
  public static function triggerRule(CRM_Civirules_EventData_EventData $eventData, $rule_id, $event_id) {
    $eventData->setEventId($event_id);
    $eventData->setRuleId($rule_id);

    $isRuleValid = self::areConditionsValid($eventData, $rule_id);

    if ($isRuleValid) {
      self::executeActions($eventData, $rule_id);
    }
  }

  protected static function executeActions(CRM_Civirules_EventData_EventData $eventData, $rule_id) {
    $ruleActions = CRM_Civirules_BAO_RuleAction::getRuleActions($rule_id);
    foreach($ruleActions as $ruleAction) {
      self::executeAction($eventData, $ruleAction);
    }
  }

  protected static function executeAction(CRM_Civirules_EventData_EventData $eventData, $ruleAction) {
    $className = $ruleAction['class_name'];
    if (!class_exists($className)) {
      return;
    }

    $object = new $className();
    if (!$object instanceof CRM_Civirules_Action_Action) {
      return;
    }

    $rule_action_id = $ruleAction['rule_action_id'];
    $entity = $ruleAction['api_entity'];
    $action = $ruleAction['api_action'];
    $parameters = $ruleAction['api_parameters'];
    $object->processAction($rule_action_id, $entity, $action, $parameters, $eventData);
  }

  protected static function areConditionsValid(CRM_Civirules_EventData_EventData $eventData, $rule_id) {
    $isValid = true;
    $firstCondition = true;

    $conditionParams = array(
      'rule_id' => $rule_id
    );
    $ruleConditions = CRM_Civirules_BAO_RuleCondition::getValues($conditionParams);
    foreach ($ruleConditions as $ruleConditionId => $ruleCondition) {
      $isConditionValid = self::checkCondition($ruleCondition, $eventData);
      if ($firstCondition) {
        $isValid = $isConditionValid ? true : false;
        $firstCondition = false;
      } elseif ($ruleCondition['condition_link'] == 'AND') {
        if ($isConditionValid && $isValid) {
          $isValid = true;
        } else {
          $isValid = false;
        }
      } elseif ($ruleCondition['condition_link'] == 'OR'){
        if ($isConditionValid || $isValid) {
          $isValid = true;
        } else {
          $isValid = false;
        }
      } else {
        $isValid = false; //we should never reach this statement
      }
    }
    return $isValid;
  }

  protected static function checkCondition($ruleCondition, CRM_Civirules_EventData_EventData $eventData) {
    $condition = CRM_Civirules_BAO_Condition::getConditionObjectById($ruleCondition['condition_id'], false);
    if (!$condition) {
      return false;
    }
    $condition->setRuleConditionData($ruleCondition);
    $isValid = $condition->isConditionValid($eventData);
    return $isValid ? true : false;
  }

}