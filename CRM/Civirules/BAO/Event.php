<?php
/**
 * BAO Event for CiviRule Event
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Event extends CRM_Civirules_DAO_Event {

  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $event = new CRM_Civirules_BAO_Event();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $event->$key = $value;
        }
      }
    }
    $event->find();
    while ($event->fetch()) {
      $row = array();
      self::storeValues($event, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update event
   * 
   * @param array $params 
   * @return array $result
   * @access public
   * @throws Exception when params is empty
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a civirule event');
    }
    $event = new CRM_Civirules_BAO_Event();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $event->$key = $value;
      }
    }
    if (!isset($event->name) || empty($event->name)) {
      $event->name = CRM_Civirules_Utils::buildNameFromLabel($event->label);
    }
    $event->save();
    self::storeValues($event, $result);
    return $result;
  }

  /**
   * Function to delete an event with id
   * 
   * @param int $eventId
   * @throws Exception when eventId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($eventId) {
    if (empty($eventId)) {
      throw new Exception('event id can not be empty when attempting to delete a civirule event');
    }
    $event = new CRM_Civirules_BAO_Event();
    $event->id = $eventId;
    $event->delete();
    return;
  }

  /**
   * Function to disable an event
   * 
   * @param int $eventId
   * @throws Exception when eventId is empty
   * @access public
   * @static
   */
  public static function disable($eventId) {
    if (empty($eventId)) {
      throw new Exception('event id can not be empty when attempting to disable a civirule event');
    }
    $event = new CRM_Civirules_BAO_Event();
    $event->id = $eventId;
    $event->find(true);
    self::add(array('id' => $event->id, 'is_active' => 0));
  }

  /**
   * Function to enable an event
   * 
   * @param int $eventId
   * @throws Exception when eventId is empty
   * @access public
   * @static
   */
  public static function enable($eventId) {
    if (empty($eventId)) {
      throw new Exception('event id can not be empty when attempting to enable a civirule event');
    }
    $event = new CRM_Civirules_BAO_Event();
    $event->id = $eventId;
    $event->find(true);
    self::add(array('id' => $event->id, 'is_active' => 1));
  }

  /**
   * Function to retrieve the label of an event with eventId
   * 
   * @param int $eventId
   * @return string $event->label
   * @access public
   * @static
   */
  public static function getEventLabelWithId($eventId) {
    if (empty($eventId)) {
      return '';
    }
    $event = new CRM_Civirules_BAO_Event();
    $event->id = $eventId;
    $event->find(true);
    return $event->label;
  }

  /**
   * Function to check if an event exists with class_name or object_name/op
   *
   * @param array $params
   * @return bool
   * @access public
   * @static
   */
  public static function eventExists($params) {
    if (isset($params['class_name']) && !empty($params['class_name'])) {
      $checkParams['class_name'] = $params['class_name'];
    } else {
      if (isset($params['object_name']) && isset($params['op']) && !empty($params['object_name']) && !empty($params['op'])) {
        $checkParams['object_name'] = $params['object_name'];
        $checkParams['op'] = $params['op'];
      }
    }
    if (!empty($checkParams)) {
      $foundEvents = self::getValues($checkParams);
      if (!empty($foundEvents)) {
        return TRUE;
      }
    }
    return FALSE;
  }
}