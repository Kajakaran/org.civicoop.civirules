<?php
/**
 * Class for CiviRules soft deleting of contacts
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_CivirulesActions_Contact_SoftDelete extends CRM_Civirules_Action {

  /**
   * Method processAction to execute the action
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @access public
   *
   */
  public function processAction(CRM_Civirules_EventData_EventData $eventData) {
    $contactId = $eventData->getContactId();

    //we cannot delete domain contacts
    if (CRM_Contact_BAO_Contact::checkDomainContact($contactId)) {
      return;
    }

    CRM_Contact_BAO_Contact::deleteContact($contactId);
  }
  /**
   * Method to return the url for additional form processing for action
   * and return false if none is needed
   *
   * @param int $ruleActionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return FALSE;
  }


}