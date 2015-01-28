<?php
/**
 * BAO Action for CiviRule Action
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Action extends CRM_Civirules_DAO_Action {

  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $action = new CRM_Civirules_BAO_Action();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $action->$key = $value;
        }
      }
    }
    $action->find();
    while ($action->fetch()) {
      $row = array();
      self::storeValues($action, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update action
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
      throw new Exception('Params can not be empty when adding or updating a civirule action');
    }
    $action = new CRM_Civirules_BAO_Action();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $action->$key = $value;
      }
    }
    $action->save();
    self::storeValues($action, $result);
    return $result;
  }

  /**
   * Function to delete an action with id
   * 
   * @param int $actionId
   * @throws Exception when actionId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($actionId) {
    if (empty($actionId)) {
      throw new Exception('action id can not be empty when attempting to delete a civirule action');
    }
    $action = new CRM_Civirules_BAO_Action();
    $action->id = $actionId;
    $action->delete();
    return;
  }

  /**
   * Function to disable an action
   * 
   * @param int $actionId
   * @throws Exception when actionId is empty
   * @access public
   * @static
   */
  public static function disable($actionId) {
    if (empty($actionId)) {
      throw new Exception('action id can not be empty when attempting to disable a civirule action');
    }
    $action = new CRM_Civirules_BAO_Action();
    $action->id = $actionId;
    $action->find(true);
    self::add(array('id' => $action->id, 'is_active' => 0));
  }

  /**
   * Function to enable an action
   * 
   * @param int $actionId
   * @throws Exception when actionId is empty
   * @access public
   * @static
   */
  public static function enable($actionId) {
    if (empty($actionId)) {
      throw new Exception('action id can not be empty when attempting to enable a civirule action');
    }
    $action = new CRM_Civirules_BAO_Action();
    $action->id = $actionId;
    $action->find(true);
    self::add(array('id' => $action->id, 'is_active' => 1));
  }
}
