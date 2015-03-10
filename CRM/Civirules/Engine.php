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
      CRM_Core_Session::setStatus('Trigger '.$rule_id.' is valid now executing actions', '', 'success');
    } else {
      CRM_Core_Session::setStatus('Trigger '.$rule_id.' is not valid', '', 'error');
    }
  }

  protected static function areConditionsValid(CRM_Civirules_EventData_EventData $eventData, $rule_id) {
    $isValid = true;
    $firstCondition = true;

    $conditionParams = array(
      'rule_id' => $rule_id
    );
    $ruleConditions = CRM_Civirules_BAO_RuleCondition::getValues($conditionParams);
    foreach ($ruleConditions as $ruleConditionId => $ruleCondition) {
      $isConditionValid = self::checkCondition($conditionParams['condition_id'], $eventData);
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

  protected static function checkCondition($condition_id, CRM_Civirules_EventData_EventData $eventData) {
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $condition_id;
    if (!$condition->find(true)) {
      return false;
    }

    $class_name = $condition->class_name;
    if (!class_exists($class_name)) {
      return false;
    }

    $object = new $class_name();
    if (!$object instanceof CRM_Civirules_Conditions_Condition) {
      return false;
    }

    $isValid = $object->isConditionValid($eventData);
    var_dump($isValid);
    return $isValid ? true : false;
  }

}