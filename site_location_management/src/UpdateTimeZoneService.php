<?php

namespace Drupal\site_location_management;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\site_location_management\UpdateTimeZoneServiceInterface;
/**
 * UpdateTimeZoneService service.
 */
class UpdateTimeZoneService implements UpdateTimeZoneServiceInterface {
  
  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;
  
  /**
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(DateFormatterInterface $date_formatter) {
    $this->dateFormatter = $date_formatter;
   }

  /**
   * Updates Current Time According to the selected Time Zone
   */
  public function getCurrentTimezone($timezone) {
    try {
      $customFormat = 'jS M Y - h:i:s';
      //$month = str_replace('Sep', 'Sept', date('M'));
      $currentTimeFormat = $this->dateFormatter->format(time(), 'custom', $customFormat, $timezone );
      return $currentTimeFormat;
    } catch (\Exception $e) {
      \Drupal::logger('site_location_management')->error($e->getMessage());
      return [
        "exception" => 'Error Occured during processing ' . $e->getMessage()
      ];
    }
  }
}
