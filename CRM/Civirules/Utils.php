<?php
/**
 * Utils - class with generic functions CiviRules
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Civirules_Utils {

  /**
   * Function return display name of contact retrieved with contact_id
   * 
   * @param int $contactId
   * @return string $contactName
   * @access public
   * @static
   */
  public static function getContactName($contactId) {
    if (empty($contactId)) {
      return '';
    }
    $params = array(
      'id' => $contactId,
      'return' => 'display_name');
    try {
      $contactName = civicrm_api3('Contact', 'Getvalue', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      $contactName = '';
    }
    return $contactName;
  }

  /**
   * Function to format is_active to yes/no
   * 
   * @param int $isActive
   * @return string
   * @access public
   * @static
   */
  public static function formatIsActive($isActive) {
    if ($isActive == 1) {
      return ts('Yes');
    } else {
      return ts('No');
    }
  }

  /**
   * Public function to generate name from label
   *
   * @param $label
   * @return string
   * @access public
   * @static
   */
  public static function buildNameFromLabel($label) {
    $labelParts = explode(' ', strtolower($label));
    $nameString = implode('_', $labelParts);
    return substr($nameString, 0, 80);
  }

  /**
   * Function to build the event list
   *
   * @return array $eventList
   * @access public
   * @static
   */
  public static function buildEventList() {
    $eventList = array();
    $events = CRM_Civirules_BAO_Event::getValues(array());
    foreach ($events as $eventId => $event) {
      $eventList[$eventId] = $event['label'];
    }
    return $eventList;
  }
}

