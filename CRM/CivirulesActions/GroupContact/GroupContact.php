<?php
/**
 * Class for CiviRules Group Contact Action
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

abstract class CRM_CivirulesActions_GroupContact_GroupContact extends CRM_CivirulesActions_Generic_Api {

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $params
   * @param object CRM_Civirules_EventData_EventData $eventData
   * @return array $params
   * @access protected
   */
  protected function alterApiParameters($params, CRM_Civirules_EventData_EventData $eventData) {
    //this function could be overridden in subclasses to alter parameters to meet certain criteraia
    $params['contact_id'] = $eventData->getContactId();
    $entityData           = $eventData->getEntityData('ContributionSoft');
    // Alter Params if contribution soft event is happening
    if (!empty($entityData)) {
      $params['contact_id']   = $eventData->getConditionOutputData('ContributionSoft');
    }
    return $params;
  }

  /**
   * Process the action
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @access public
   */
  public function processAction(CRM_Civirules_EventData_EventData $eventData) {
    $entity = $this->getApiEntity();
    $action = $this->getApiAction();

    $action_params = $this->getActionParameters();
    $group_ids = array();
    if (!empty($action_params['group_id'])) {
      $group_ids = array($action_params['group_id']);
    } elseif (!empty($action_params['group_ids']) && is_array($action_params['group_ids'])) {
      $group_ids = $action_params['group_ids'];
    }
    foreach($group_ids as $group_id) {
      $params = array();
      $params['group_id'] = $group_id;

      //alter parameters by subclass
      $params = $this->alterApiParameters($params, $eventData);
      if (is_array($params['contact_id'])) {
        $newParams = array();
        $newParams['group_id']    = $group_id;
        foreach ($params['contact_id'] as $key => $value) {
          if($key ==  0) {
            $newParams['contact_id'] = $value;
          } else {
            $newParams['contact_id_'.$key] = $value;
          }
        }
        $this->executeApiAction($entity, $action, $newParams);
      } else {
        //execute the action
        $this->executeApiAction($entity, $action, $params);
      }
    }
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/action/groupcontact', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $params = $this->getActionParameters();
    if (!empty($params['group_id'])) {
      $group = civicrm_api3('Group', 'getvalue', array('return' => 'title', 'id' => $params['group_id']));
      return $this->getActionLabel($group);
    } elseif (!empty($params['group_ids']) && is_array($params['group_ids'])) {
      $groups = '';
      foreach($params['group_ids'] as $group_id) {
        $group = civicrm_api3('Group', 'getvalue', array('return' => 'title', 'id' => $group_id));
        if (strlen($groups)) {
          $groups .= ', ';
        }
        $groups .= $group;
      }
      return $this->getActionLabel($groups);
    }
    return '';
  }

  /**
   * Method to set the api entity
   *
   * @return string
   * @access protected
   */
  protected function getApiEntity() {
    return 'GroupContact';
  }

  protected function getActionLabel($group) {
    switch ($this->getApiAction()) {
      case 'create':
        return ts('Add contact to group(s): %1', array(
          1 => $group
        ));
        break;
      case 'delete':
        return ts('Remove contact from group(s): %1', array(
          1 => $group
        ));
        break;
    }
    return '';
  }

}