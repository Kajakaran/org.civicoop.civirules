<?php
/**
 * BAO Comparison for CiviRule Comparison
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_BAO_Comparison extends CRM_Civirules_DAO_Comparison {
  /**
   * Function to get values
   * 
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $comparison = new CRM_Civirules_BAO_Comparison();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $comparison->$key = $value;
        }
      }
    }
    $comparison->find();
    while ($comparison->fetch()) {
      $row = array();
      self::storeValues($comparison, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update comparison
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
      throw new Exception('Params can not be empty when adding or updating a civirule comparison');
    }
    $comparison = new CRM_Civirules_BAO_Comparison();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $comparison->$key = $value;
      }
    }
    $comparison->save();
    self::storeValues($comparison, $result);
    return $result;
  }
  /**
   * Function to delete a comparison with id
   * 
   * @param int $comparisonId
   * @throws Exception when comparison_id is empty
   * @access public
   * @static
   */
  public static function deleteWithId($comparisonId) {
    if (empty($comparisonId)) {
      throw new Exception('comparison id can not be empty when attempting to delete a civirule comparison');
    }
    $comparison = new CRM_Civirules_BAO_Comparison();
    $comparison->id = $comparisonId;
    $comparison->delete();
    return;
  }
  /**
   * Function to disable a comparison
   * 
   * @param int $comparisonId
   * @throws Exception when comparisonId is empty
   * @access public
   * @static
   */
  public static function disable($comparisonId) {
    if (empty($comparisonId)) {
      throw new Exception('comparison id can not be empty when attempting to disable a civirule comparison');
    }
    $comparison = new CRM_Civirules_BAO_Comparison();
    $comparison->id = $comparisonId;
    $comparison->find(true);
    self::add(array('id' => $comparison->id, 'is_active' => 0));
  }
  /**
   * Function to enable a comparison
   * 
   * @param int $comparisonId
   * @throws Exception when comparisonId is empty
   * @access public
   * @static
   */
  public static function enable($comparisonId) {
    if (empty($comparisonId)) {
      throw new Exception('comparison id can not be empty when attempting to enable a civirule comparison');
    }
    $comparison = new CRM_Civirules_BAO_Comparison();
    $comparison->id = $comparisonId;
    $comparison->find(true);
    self::add(array('id' => $comparison->id, 'is_active' => 1));
  }
}