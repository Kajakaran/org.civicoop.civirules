<?php
/**
 * Class for CiviRules post event handling
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Civirules_Event_Post extends CRM_Civirules_Event {

  protected $objectName;

  protected $op;

  public function setEventId($eventId) {
    parent::setEventId($eventId);

    $event = new CRM_Civirules_BAO_Event();
    $event->id = $this->eventId;
    if (!$event->find(true)) {
      throw new Exception('Civirules: could not find event with ID: '.$this->eventId);
    }
    $this->objectName = $event->object_name;
    $this->op = $event->op;
  }

  /**
   * Returns an array of entities on which the event reacts
   *
   * @return CRM_Civirules_EventData_EntityDefinition
   */
  protected function reactOnEntity() {
    $entity = CRM_Civirules_Utils_ObjectName::convertToEntity($this->objectName);
    return new CRM_Civirules_EventData_EntityDefinition($this->objectName, $entity, $this->getDaoClassName(), $entity);
  }

  /**
   * Return the name of the DAO Class. If a dao class does not exist return an empty value
   *
   * @return string
   */
  protected function getDaoClassName() {
    $daoClassName = CRM_Core_DAO_AllCoreTables::getFullName($this->objectName);
    return $daoClassName;
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

    //find matching rules for this objectName and op
    $events = CRM_Civirules_BAO_Rule::findRulesByObjectNameAndOp($objectName, $op);
    foreach($events as $event) {
      if ($event instanceof CRM_Civirules_Event_Post) {
        $event->triggerEvent($op, $objectName, $objectId, $objectRef);
      }
    }
  }

  /**
   * Trigger a rule for this event
   *
   * @param $op
   * @param $objectName
   * @param $objectId
   * @param $objectRef
   */
  public function triggerEvent($op, $objectName, $objectId, $objectRef) {
    $eventData = $this->getEventDataFromPost($op, $objectName, $objectId, $objectRef);
    CRM_Civirules_Engine::triggerRule($this, clone $eventData);
  }

  /**
   * Get event data belonging to this specific post event
   *
   * Sub classes could override this method. E.g. a post on GroupContact doesn't give on object of GroupContact
   * it rather gives an array with contact Id's
   *
   * @param $op
   * @param $objectName
   * @param $objectId
   * @param $objectRef
   * @return CRM_Civirules_EventData_Edit|CRM_Civirules_EventData_Post
   */
  protected function getEventDataFromPost($op, $objectName, $objectId, $objectRef) {
    $entity = CRM_Civirules_Utils_ObjectName::convertToEntity($objectName);

    //set data
    $data = array();
    if (is_object($objectRef)) {
      CRM_Core_DAO::storeValues($objectRef, $data);
    } elseif (is_array($objectRef)) {
      $data = $objectRef;
    }

    if ($op == 'edit') {
      //set also original data with an edit event
      $oldData = CRM_Civirules_Utils_PreData::getPreData($entity, $objectId);
      $eventData = new CRM_Civirules_EventData_Edit($entity, $objectId, $data, $oldData);
    } else {
      $eventData = new CRM_Civirules_EventData_Post($entity, $objectId, $data);
    }
    return $eventData;
  }


}