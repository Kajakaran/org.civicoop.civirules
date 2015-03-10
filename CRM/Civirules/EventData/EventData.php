<?php

/**
 * Event data
 * If you have custom events you can create a subclass of this class
 * and change where needed
 *
 */
abstract class CRM_Civirules_EventData_EventData {

  /**
   * Contains data for entities available in the event
   *
   * @var array
   */
  private $entity_data = array();

  protected $event_id;

  protected $rule_id;

  protected $contact_id;


  /**
   * Returns the ID of the contact used in the event
   *
   * @return int
   */
  public function getContactId() {
    return $this->contact_id;
  }

  /**
   * Returns an array with data for an entity
   *
   * If entity is not available then an empty array is returned
   *
   * @param string $entity
   * @return array
   */
  public function getEntityData($entity) {
    if (isset($this->entity_data[$entity]) && is_array($this->entity_data[$entity])) {
      return $this->entity_data[$entity];
    }
    return array();
  }

  /**
   * Sets data for an entity
   *
   * @param string $entity
   * @param array $data
   * @return CRM_CiviRules_Engine_EventData
   */
  protected function setEntityData($entity, $data) {
    if (is_array($data)) {
      $this->entity_data[$entity] = $data;
    }
    return $this;
  }

  public function setEventId($event_id) {
    $this->event_id = $event_id;
    return $this;
  }

  public function setRuleId($rule_id) {
    $this->rule_id = $rule_id;
    return $this;
  }

  public function getEventId() {
    return $this->event_id;
  }

  public function getRuleId() {
    return $this->rule_id;
  }



}