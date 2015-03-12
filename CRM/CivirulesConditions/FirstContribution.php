<?php
/**
 * Class for CiviRule Condition FirstContribution
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_CivirulesConditions_FirstContribution extends CRM_Civirules_Condition {

  /**
   * Method is mandatory and checks if the condition is met
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return bool
   * @access public
   */
  public function isConditionValid(CRM_Civirules_EventData_EventData $eventData)
  {
    $contactId = $eventData->getContactId();
    $contributionParams = array('contact_id' => $contactId);
    $countContributions = civicrm_api3('Contribution', 'getcount', $contributionParams);
    switch ($countContributions) {
      case 0:
        return TRUE;
        break;
      case 1:
        $existingContribution = civicrm_api3('Contribution', 'Getsingle', array('contact_id' => $contactId));
        $eventContribution = $eventData->getEntityData('Contribution');
        if ($eventContribution['contribution_id'] == $existingContribution['contribution_id']) {
          return TRUE;
        }
      break;
      default:
        return FALSE;
      break;
    }
  }

  /**
   * Method is mandatory, in this case no additional data input is required
   * so it returns FALSE
   *
   * @param int $ruleConditionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleConditionId) {
    return FALSE;
  }

  /**
   * Returns an array with required entity names
   *
   * @return array
   * @access public
   */
  public function requiredEntities() {
    return array('Contribution');
  }
}