<?php
/**
 * Class for CiviRule Condition FirstContribution
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_CivirulesConditions_Contribution_CustomQueryContact extends CRM_Civirules_Condition {

  private $conditionParams = array();
  
  public function getExtraDataInputUrl($ruleConditionId) {
    return CRM_Utils_System::url('civicrm/civirule/form/condition/addQuery/',
      'rule_condition_id='.$ruleConditionId);
  }
  
  public function setRuleConditionData($ruleCondition) {
    parent::setRuleConditionData($ruleCondition);
    $this->conditionParams = array();
    if (!empty($this->ruleCondition['condition_params'])) {
      $this->conditionParams = unserialize($this->ruleCondition['condition_params']);
    }
  }
  
  public function userFriendlyConditionParams() {
    $query = $this->conditionParams['smart_group_query'];
    if (!empty($query)) {
      return 'Condition Query is '.$query;
    }
    return '';
  }
  
  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData)
  {
    $contactId  = $eventData->getContactId();
    $entityData = $eventData->getEntityData('ContributionSoft');
    $contacts   = array();
    
    $query = $this->conditionParams['smart_group_query'];
    if (!empty($query)) {
      $subQueries = explode(';', $query);
      // To run only first and select statement in query string
      if (!empty($subQueries) && !preg_match('/^(insert|update|delete|create|drop|replace)/i', $subQueries[0])) {
        CRM_Core_Error::debug_var('CiviRules::Custom Query Contact Condition Query', $subQueries[0]);
        CRM_Core_Error::debug_var('CiviRules::Custom Query Contact Condition Param Contribution', $entityData['contribution_id']);
        $dao = CRM_Core_DAO::executeQuery('SELECT '.$subQueries[0], array(1=> array($entityData['contribution_id'], 'Int')));
        while ($dao->fetch()) {
          $contacts[] = $dao->contact_id;
        } 
      }
    }
    CRM_Core_Error::debug_var('CiviRules::Custom Query Contact Condition Contacts', $contacts);
    if (!empty($contacts)) {
      $eventData->setConditionOutputData('ContributionSoft', $contacts);
      return TRUE;
    } else {
      return FALSE;
    }
  }

   /**
   * Returns an array with required entity names
   *
   * @return array
   * @access public
   */
  public function requiredEntities() {
    return array('ContributionSoft');
  }
  

}