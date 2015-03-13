<?php
/**
 * Utils - class with generic functions CiviRules
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_Utils {

  /**
   * Function return display name of contact retrieved with contact_id
   * 
   * @param int $contactId
   * @return string $contactName
   * @access public
   * @static
   */
  public static function getContactName($contactId) {
    if (empty($contactId)) {
      return '';
    }
    $params = array(
      'id' => $contactId,
      'return' => 'display_name');
    try {
      $contactName = civicrm_api3('Contact', 'Getvalue', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      $contactName = '';
    }
    return $contactName;
  }

  /**
   * Function to format is_active to yes/no
   * 
   * @param int $isActive
   * @return string
   * @access public
   * @static
   */
  public static function formatIsActive($isActive) {
    if ($isActive == 1) {
      return ts('Yes');
    } else {
      return ts('No');
    }
  }

  /**
   * Public function to generate name from label
   *
   * @param $label
   * @return string
   * @access public
   * @static
   */
  public static function buildNameFromLabel($label) {
    $labelParts = explode(' ', strtolower($label));
    $nameString = implode('_', $labelParts);
    return substr($nameString, 0, 80);
  }

  /**
   * Function to build the event list
   *
   * @return array $eventList
   * @access public
   * @static
   */
  public static function buildEventList() {
    $eventList = array();
    $events = CRM_Civirules_BAO_Event::getValues(array());
    foreach ($events as $eventId => $event) {
      $eventList[$eventId] = $event['label'];
    }
    return $eventList;
  }

  /**
   * Function to build the conditions list
   *
   * @return array $conditionList
   * @access public
   * @static
   */
  public static function buildConditionList() {
    $conditionList = array();
    $conditions = CRM_Civirules_BAO_Condition::getValues(array());
    foreach ($conditions as $conditionId => $condition) {
      $conditionList[$conditionId] = $condition['label'];
    }
    return $conditionList;
  }

  /**
   * Function to build the action list
   *
   * @return array $actionList
   * @access public
   * @static
   */
  public static function buildActionList() {
    $actionList = array();
    $actions = CRM_Civirules_BAO_Action::getValues(array());
    foreach ($actions as $actionId => $action) {
      $actionList[$actionId] = $action['label'];
    }
    return $actionList;
  }

  /**
   * Function to return activity type list
   *
   * @return array $activityTypeList
   * @access public
   */
  public static function getActivityTypeList() {
    $activityTypeList = array();
    $activityTypeOptionGroupId = self::getOptionGroupIdWithName('activity_type');
    $params = array(
      'option_group_id' => $activityTypeOptionGroupId,
      'is_active' => 1);
    $activityTypes = civicrm_api3('OptionValue', 'Get', $params);
    foreach ($activityTypes['values'] as $optionValue) {
      $activityTypeList[$optionValue['value']] = $optionValue['label'];
    }
    return $activityTypeList;
  }

  /**
   * Function to get the option group id of an option group with name
   *
   * @param string $optionGroupName
   * @return int $optionGroupId
   * @throws Exception when no option group activity_type is found
   */
  public static function getOptionGroupIdWithName($optionGroupName) {
    $params = array(
      'name' => $optionGroupName,
      'return' => 'id');
    try {
      $optionGroupId = civicrm_api3('OptionGroup', 'Getvalue', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find an option group with the name '.$optionGroupName.
        ', error from API OptionGroup Getvalue: '.$ex->getMessage());
    }
    return $optionGroupId;
  }
}

