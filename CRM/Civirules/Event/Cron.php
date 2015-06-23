<?php

abstract class CRM_Civirules_Event_Cron extends CRM_Civirules_Event {

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
    $isValidCount = 0;
    while($eventData = $this->getNextEntityEventData()) {
      $isValid = CRM_Civirules_Engine::triggerRule($this, $eventData);
      if ($isValid) {
        $isValidCount++;
      }
      $count ++;
    }
    return array(
      'count' => $count,
      'is_valid_count' => $isValidCount,
    );
  }


}