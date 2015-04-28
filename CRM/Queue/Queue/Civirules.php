<?php

class CRM_Queue_Queue_Civirules extends CRM_Queue_Queue_Sql {

  /**
   * Determine number of items remaining in the queue
   *
   * @return int
   */
  function numberOfItems() {
    return CRM_Core_DAO::singleValueQuery("
      SELECT count(*)
      FROM civicrm_queue_item
      WHERE queue_name = %1
      and (release_time is null OR release_time <= curdate())
    ", array(
      1 => array($this->getName(), 'String'),
    ));
  }

}