<?php
/**
 * Class for CiviRules engine
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Civirules_Engine {

  /**
   * Trigger a rule.
   *
   * The trigger will check the conditions and if conditions are valid then the actions are executed
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @param int $ruleId
   * @param int $eventId
   * @access public
   * @static
   */
  public static function triggerRule(CRM_Civirules_EventData_EventData $eventData, $ruleId, $eventId) {
    $eventData->setEventId($eventId);
    $eventData->setRuleId($ruleId);

    $isRuleValid = self::areConditionsValid($eventData, $ruleId);

    if ($isRuleValid) {
      self::logRule($eventData, $ruleId);
      self::executeActions($eventData, $ruleId);

    }
  }

  /**
   * Method to execute the actions
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @param int $ruleId
   * @access protected
   * @static
   */
  protected static function executeActions(CRM_Civirules_EventData_EventData $eventData, $ruleId) {
    $actionParams = array(
      'rule_id' => $ruleId
    );
    $ruleActions = CRM_Civirules_BAO_RuleAction::getValues($actionParams);
    foreach ($ruleActions as $ruleAction) {
      self::executeAction($eventData, $ruleAction);
    }
  }

  /**
   * Method to execute a single action
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @param array $ruleAction
   * @access protected
   * @static
   */
  protected static function executeAction(CRM_Civirules_EventData_EventData $eventData, $ruleAction) {
    $object = CRM_Civirules_BAO_Action::getActionObjectById($ruleAction['action_id'], true);
    if (!$object) {
      return;
    }

    $object->setRuleActionData($ruleAction);
    $object->processAction($eventData);
  }

  /**
   * Method to check if all conditions are valid
   *
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @param int $ruleId
   * @return bool
   * @access protected
   * @static
   */
  protected static function areConditionsValid(CRM_Civirules_EventData_EventData $eventData, $ruleId) {
    $isValid = true;
    $firstCondition = true;

    $conditionParams = array(
      'rule_id' => $ruleId
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

  /**
   * Method to check condition
   *
   * @param array $ruleCondition
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return bool
   * @access protected
   * @static
   */
  protected static function checkCondition($ruleCondition, CRM_Civirules_EventData_EventData $eventData) {
    $condition = CRM_Civirules_BAO_Condition::getConditionObjectById($ruleCondition['condition_id'], false);
    if (!$condition) {
      return false;
    }
    $condition->setRuleConditionData($ruleCondition);
    $isValid = $condition->isConditionValid($eventData);
    return $isValid;
  }

  /**
   * This function writes a record to the log table to indicate that this rule for this event is triggered
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @param $ruleId
   */
  protected static function logRule(CRM_Civirules_EventData_EventData $eventData, $ruleId) {
    $sql = "INSERT INTO `civirule_rule_log` (`rule_id`, `contact_id`, `log_date`) VALUES (%1, %2, NOW())";
    $params[1] = array($ruleId, 'Integer');
    $params[2] = array($eventData->getContactId(), 'Integer');
    CRM_Core_DAO::executeQuery($sql, $params);
  }
}