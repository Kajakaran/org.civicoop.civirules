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

  protected $contact_id;

  /**
   * @var CRM_Civirules_Event
   */
  protected $event;

  public function __construct() {

  }

  /**
   * Set the event
   *
   * @param CRM_Civirules_Event $event
   */
  public function setEvent(CRM_Civirules_Event $event) {
    $this->event = $event;
  }

  /**
   * @return CRM_Civirules_Event
   */
  public function getEvent() {
    return $this->event;
  }

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



}