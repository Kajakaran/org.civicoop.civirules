<?php
/**
 * DAO Condition for table civirule_condition
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_DAO_Condition extends CRM_Core_DAO {
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  static $_export = null;
  /**
   * empty definition for virtual function
   */
  static function getTableName() {
    return 'civirule_condition';
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields() {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true
        ) ,
        'name' => array(
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 64,
        ),
        'label' => array(
          'name' => 'label',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 128,
        ),
        'function_name' => array(
          'name' => 'function_name',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 256,
        ),
        'civicrm_form_class' => array(
          'name' => 'civicrm_form_class',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 128,
        ),
        'is_active' => array(
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_INT,
        )
      );
    }
    return self::$_fields;
  }
  /**
   * Returns an array containing, for each field, the array key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  static function &fieldKeys() {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
        'id' => 'id',
        'name' => 'name',
        'label' => 'label',
        'function_name' => 'function_name',
        'civicrm_form_class' => 'civicrm_form_class',
        'is_active' => 'is_active'
      );
    }
    return self::$_fieldKeys;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  static function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['activity'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}