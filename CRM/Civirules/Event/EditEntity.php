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
    if ($op != 'edit') {
      return;
    }

    //set data
    $data = array();
    CRM_Core_DAO::storeValues($objectRef, $data);

    $entity = self::convertObjectNameToEntity($objectName);
    $oldData = self::getPreData($entity, $objectId);

    $rules = CRM_Civirules_BAO_Rule::findRulesByObjectnameAndAction($objectName, $op);
    foreach($rules as $rule) {
      $eventData = new CRM_Civirules_EventData_Edit($entity, $objectId, $data, $oldData);
      CRM_Civirules_Engine::triggerRule($eventData, $rule['rule_id'], $rule['event_id']);
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

  protected static function convertObjectNameToEntity($objectName) {
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