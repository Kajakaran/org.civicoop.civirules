<?php

/**
 * The post event handler
 */
class CRM_Civirules_Event_EditEntity {

  /**
   * Data set in pre and used for compare which field is changed
   *
   * @var array
   */
  protected static $preData = array();


  public static function pre($op, $objectName, $objectId, $params) {
    if ($op != 'edit') {
      return;
    }

    //retrieve data as it is currently in the database
    $entity = self::convertObjectNameToEntity($objectName);
    $data = civicrm_api3($entity, 'getsingle', array('id' => $objectId));
    self::setPreData($entity, $objectId, $data);
  }

  public static function post( $op, $objectName, $objectId, &$objectRef ) {
    $extensionConfig = CRM_Civirules_Config::singleton();
    if (!in_array($op,$extensionConfig->getValidEventOperations())) {
      return;
    }

    $entity = self::convertObjectNameToEntity($objectName);

    //set data
    $data = array();
    if (is_object($objectRef)) {
      CRM_Core_DAO::storeValues($objectRef, $data);
    } elseif (is_array($objectRef)) {
      $data = $objectRef;
    }

    if ($op == 'edit') {
      //set also original data with an edit event
      $oldData = self::getPreData($entity, $objectId);
      $eventData = new CRM_Civirules_EventData_Edit($entity, $objectId, $data, $oldData);
    } else {
      $eventData = new CRM_Civirules_EventData_Post($entity, $objectId, $data);
    }

    //find matching rules for this objectName and op
    $rules = CRM_Civirules_BAO_Rule::findRulesByObjectNameAndOp($objectName, $op);
    foreach($rules as $rule) {
      CRM_Civirules_Engine::triggerRule(clone $eventData, $rule['rule_id'], $rule['event_id']);
    }
  }

  protected static function setPreData($entity, $entity_id, $data) {
    self::$preData[$entity][$entity_id] = $data;
  }

  protected static function getPreData($entity, $entity_id) {
    if (isset(self::$preData[$entity][$entity_id])) {
      return self::$preData[$entity][$entity_id];
    }
    return array();
  }

  public static function convertObjectNameToEntity($objectName) {
    $entity = $objectName;
    switch($objectName) {
      case 'Individual':
      case 'Household':
      case 'Organization':
        $entity = 'contact';
        break;
    }
    return $entity;
  }

}