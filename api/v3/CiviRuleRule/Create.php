<?php
/**
 * CiviRuleRule.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception when label is empty and id is not set (label is required for create)
 *
 */
function civicrm_api3_civi_rule_rule_create($params) {
  if (!isset($params['id']) && empty($params['label'])) {
    throw new API_Exception('Label can not be empty when adding a new CiviRule');
  }
  /*
   * replace event_id when value is 0 to prevent restraint conflict
   */
  if (isset($params['event_id']) && $params['event_id'] == 0) {
    $params['event_id'] = null;
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
  $returnValues = CRM_Civirules_BAO_Rule::add($params);
  return civicrm_api3_create_success($returnValues, $params, 'CiviRuleRule', 'Create');
}

