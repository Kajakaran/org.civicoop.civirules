<?php

abstract class CRM_Civirules_Event_Cron {

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
   * This function returns a CRM_Civirules_EventData_EventData this entity is used for triggering the rule
   *
   * Return false when no next entity is available
   *
   * @return CRM_Civirules_EventData_EventData|false
   */
  abstract protected function getNextEntityEventData();

  /**
   * @return int
   */
  public function process() {
    $count = 0;
    while($eventData = $this->getNextEntityEventData()) {
      CRM_Civirules_Engine::triggerRule($eventData, $this->ruleId, $this->eventId);
      $count ++;
    }
    return $count;
  }


}