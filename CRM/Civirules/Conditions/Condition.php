<?php

abstract class CRM_Civirules_Conditions_Condition {

  /**
   * This function returns true or false when an condition is valid or not
   *
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return bool
   */
  public abstract function isConditionValid(CRM_Civirules_EventData_EventData $eventData);

}