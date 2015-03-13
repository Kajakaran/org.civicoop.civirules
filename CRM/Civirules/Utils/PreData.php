<?php

class CRM_Civirules_Utils_PreData {

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
    $entity = CRM_Civirules_Utils_ObjectName::convertToEntity($objectName);
    $data = civicrm_api3($entity, 'getsingle', array('id' => $objectId));
    self::setPreData($entity, $objectId, $data);
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
   * @param int $entityId
   * @return array
   * @access protected
   * @static
   */
  public static function getPreData($entity, $entityId) {
    if (isset(self::$preData[$entity][$entityId])) {
      return self::$preData[$entity][$entityId];
    }
    return array();
  }

}