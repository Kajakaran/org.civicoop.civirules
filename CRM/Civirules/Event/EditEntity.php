<?php
/**
 * Class for CiviRules post event handling
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Civirules_Event_EditEntity {

  /**
   * Data set in pre and used for compare which field is changed
   *
   * @var array $preData
   */
  protected static $preData = array();

  /**
   * Method pre to store the entity data before the data in the database is changed
   * for the edit operation
   *
   * @param string $op
   * @param string $objectName
   * @param int $objectId
   * @param array $params
   * @access public
   * @static
   *
   */
  public static function pre($op, $objectName, $objectId, $params) {
    if ($op != 'edit') {
      return;
    }

    //retrieve data as it is currently in the database
    $entity = self::convertObjectNameToEntity($objectName);
    $data = civicrm_api3($entity, 'getsingle', array('id' => $objectId));
    self::setPreData($entity, $objectId, $data);
  }

  /**
   * Method post
   *
   * @param string $op
   * @param string $objectName
   * @param int $objectId
   * @param object $objectRef
   * @access public
   * @static
   */
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

  /**
   * Method to set the pre operation data
   *
   * @param string $entity
   * @param int $entityId
   * @param array $data
   * @access protected
   * @static
   */
  protected static function setPreData($entity, $entityId, $data) {
    self::$preData[$entity][$entityId] = $data;
  }

  /**
   * Method to get the pre operation data
   *
   * @param string $entity
   * @param id $entityId
   * @return array
   * @access protected
   * @static
   */
  protected static function getPreData($entity, $entityId) {
    if (isset(self::$preData[$entity][$entityId])) {
      return self::$preData[$entity][$entityId];
    }
    return array();
  }

  /**
   * Method to convert the object name to the entity for contacts
   *
   * @param string $objectName
   * @return string $entity
   * @access public
   * @static
   */
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