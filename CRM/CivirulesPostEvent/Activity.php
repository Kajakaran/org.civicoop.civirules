<?php

class CRM_CivirulesPostEvent_Activity extends CRM_Civirules_Event_Post {

  /**
   * Returns an array of entities on which the event reacts
   *
   * @return CRM_Civirules_EventData_EntityDefinition
   */
  protected function reactOnEntity() {
    return new CRM_Civirules_EventData_EntityDefinition($this->objectName, $this->objectName, $this->getDaoClassName(), 'Activity');
  }

  /**
   * Return the name of the DAO Class. If a dao class does not exist return an empty value
   *
   * @return string
   */
  protected function getDaoClassName() {
    return 'CRM_Activity_DAO_Activity';
  }

  /**
   * Trigger a rule for this event
   *
   * @param $op
   * @param $objectName
   * @param $objectId
   * @param $objectRef
   */
  public function triggerEvent($op, $objectName, $objectId, $objectRef) {
    $eventData = $this->getEventDataFromPost($op, $objectName, $objectId, $objectRef);

    //trigger for activity event for every source_contact_id, target_contact_id and assignee_contact_id
    $activityContact = new CRM_Activity_BAO_ActivityContact();
    $activityContact->activity_id = $objectId;
    $activityContact->find();
    while($activityContact->fetch()) {
      $data = array();
      CRM_Core_DAO::storeValues($activityContact, $data);
      $eventData->setEntityData('ActivityContact', $data);

      CRM_Civirules_Engine::triggerRule($this, clone $eventData);
    }
  }

}