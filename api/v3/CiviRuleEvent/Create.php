<?php
/**
 * CiviRuleEvent.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_civi_rule_event_create_spec(&$spec) {
  $spec['label']['api_required'] = 1;
}

/**
 * CiviRuleEvent.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
function civicrm_api3_civi_rule_event_create($params) {
  if (!isset($params['id']) && empty($params['label'])) {
    return civicrm_api3_create_error('Label can not be empty when adding a new CiviRule Event');
  }
  /*
   * either class_name or combination of entity/action is mandatory
   */
  if (_checkClassNameEntityAction($params) == FALSE) {
    return civicrm_api3_create_error('Either Class Name or a combination of Entity/Action is mandatory');
  }
  /*
   * set created or modified date and user_id
   */
  $session = CRM_Core_Session::singleton();
  $userId = $session->get('userID');
  if (isset($params['id'])) {
    $params['modified_date'] = date('Ymd');
    $params['modified_user_id'] = $userId;
  } else {
    $params['created_date'] = date('Ymd');
    $params['created_user_id'] = $userId;
  }
  $returnValues = CRM_Civirules_BAO_Event::add($params);
  return civicrm_api3_create_success($returnValues, $params, 'CiviRuleEvent', 'Create');
}

/**
 * Function to check if className or Action/Entity are passed
 *
 * @param array $params
 * @return bool
 */
function _checkClassNameEntityAction($params) {
  if (isset($params['class_name']) && !empty($params['class_name'])) {
    if (!isset($params['entity']) && !isset($params['action'])) {
      return TRUE;
    } else {
      if (empty($params['entity']) && empty($params['action'])) {
        return TRUE;
      }
    }
  }
  if (isset($params['entity']) && isset($params['action']) && !empty($params['entity']) && !empty($params['action'])) {
    if (!isset($params['class_name']) || empty($params['class_name'])) {
      return TRUE;
    }
  }
  return FALSE;
}

