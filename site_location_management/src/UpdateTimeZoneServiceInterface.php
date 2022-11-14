<?php

namespace Drupal\site_location_management;


/**
 * Interface UpdateTimeZoneServiceInterface.
 */
interface UpdateTimeZoneServiceInterface {

  /**
   * @param string $text
   *   The plain text to encrypt.
   */
  public function getCurrentTimezone($timezone);
}



