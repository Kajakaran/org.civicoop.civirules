<?php

/**
 * This class handles period conditions
 * It provides a list with options for period
 * and it converts it to a start and end date of the period
 *
 */
class CRM_CivirulesConditions_Utils_Period {

  public static function Options() {
    return array(
      'this month' => ts('This month'),
      'previous month' => ts('Previous month'),
      'last 30 days' => ts('Last 30 days'),
      'this year' => ts('This year'),
      'previous year' => ts('Previous year'),
    );
  }

  public static function convertPeriodToStartDate($period) {
    $date = new DateTime();
    switch ($period) {
      case 'this month':
        $date->modify('first day of this month');
        return $date;
        break;
      case 'previous month':
        $date->modify('first day of previous month');
        return $date;
        break;
      case 'last 30 days':
        $date->modify('-30 days');
        return $date;
        break;
      case 'this year':
        $date->modify('first day of January this year');
        return $date;
        break;
      case 'previous year':
        $date->modify('first day of January previous year');
        return $date;
        break;
    }

    return false;
  }

  public static function convertPeriodToEndDate($period) {
    $date = new DateTime();
    switch ($period) {
      case 'this month':
        $date->modify('last day of this month');
        return $date;
        break;
      case 'previous month':
        $date->modify('last day of previous month');
        return $date;
        break;
      case 'last 30 days':
        return $date;
        break;
      case 'this year':
        $date->modify('last day of December this year');
        return $date;
        break;
      case 'previous year':
        $date->modify('last day of December previous year');
        return $date;
        break;
    }

    return false;
  }

}
