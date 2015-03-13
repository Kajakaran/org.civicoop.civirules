<?php

abstract class CRM_Civirules_Event {

  protected $ruleId;

  protected $eventId;

  public function setRuleId($ruleId) {
    $this->ruleId = $ruleId;
  }

  public function getRuleId() {
    return $this->ruleId;
  }

  public function setEventId($eventId) {
    $this->eventId = $eventId;
  }

  public function getEventId() {
    return $this->eventId;
  }

  /**
   * Returns an array of entities on which the event reacts
   *
   * @return CRM_Civirules_EventData_EntityDefinition
   */
  abstract protected function reactOnEntity();


  public function getProvidedEntities() {
    $additionalEntities = $this->getAdditionalEntities();
    foreach($additionalEntities as $entity) {
      $entities[$entity->key] = $entity;
    }

    $entity = $this->reactOnEntity();
    $entities[$entity->key] = $entity;

    return $entities;
  }

  /**
   * Returns an array of additional entities provided in this event
   *
   * @return array of CRM_Civirules_EventData_EntityDefinition
   */
  protected function getAdditionalEntities() {
    return array();
  }

}