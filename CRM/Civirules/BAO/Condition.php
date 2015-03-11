<?php
/**
 * BAO Condition for CiviRule Condition
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Condition extends CRM_Civirules_DAO_Condition {

  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $condition = new CRM_Civirules_BAO_Condition();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $condition->$key = $value;
        }
      }
    }
    $condition->find();
    while ($condition->fetch()) {
      $row = array();
      self::storeValues($condition, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update condition
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
      throw new Exception('Params can not be empty when adding or updating a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $condition->$key = $value;
      }
    }
    $condition->save();
    self::storeValues($condition, $result);
    return $result;
  }

  /**
   * Function to delete a condition with id
   * 
   * @param int $conditionId
   * @throws Exception when conditionId is empty
   * @access public
   * @static
   */
  public static function deleteWithId($conditionId) {
    if (empty($conditionId)) {
      throw new Exception('condition id can not be empty when attempting to delete a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $conditionId;
    $condition->delete();
    return;
  }

  /**
   * Function to disable a condition
   * 
   * @param int $conditionId
   * @throws Exception when conditionId is empty
   * @access public
   * @static
   */
  public static function disable($conditionId) {
    if (empty($conditionId)) {
      throw new Exception('condition id can not be empty when attempting to disable a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $conditionId;
    $condition->find(true);
    self::add(array('id' => $condition->id, 'is_active' => 0));
  }

  /**
   * Function to enable a condition
   * 
   * @param int $conditionId
   * @throws Exception when conditionId is empty
   * @access public
   * @static
   */
  public static function enable($conditionId) {
    if (empty($conditionId)) {
      throw new Exception('condition id can not be empty when attempting to enable a civirule condition');
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $conditionId;
    $condition->find(true);
    self::add(array('id' => $condition->id, 'is_active' => 1));
  }

  /**
   * Function to retrieve the label of a condition with conditionId
   *
   * @param int $conditionId
   * @return string $condition->label
   * @access public
   * @static
   */
  public static function getConditionLabelWithId($conditionId) {
    if (empty($conditionId)) {
      return '';
    }
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $conditionId;
    $condition->find(true);
    return $condition->label;
  }

  /**
   * Get the condition class for this condition
   *
   * @param $condition_id
   * @param bool $abort if true this function will throw an exception if class could not be instanciated
   * @return CRM_Civirules_Condition
   * @throws Exception if abort is set to true and class does not exist or is not valid
   */
  public static function getConditionObjectById($condition_id, $abort=true) {
    $condition = new CRM_Civirules_BAO_Condition();
    $condition->id = $condition_id;
    if (!$condition->find(true)) {
      if ($abort) {
        throw new Exception('Could not find condition');
      }
      return false;
    }

    $class_name = $condition->class_name;
    if (!class_exists($class_name)) {
      if ($abort) {
        throw new Exception('Condition class ' . $class_name . ' does not exist');
      }
      return false;
    }

    $object = new $class_name();
    if (!$object instanceof CRM_Civirules_Condition) {
      if ($abort) {
        throw new Exception('Condition class ' . $class_name . ' is not a subclass of CRM_Civirules_Condition');
      }
      return false;
    }
    return $object;
  }
}