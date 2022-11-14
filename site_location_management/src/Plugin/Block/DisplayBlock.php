<?php

namespace Drupal\site_location_management\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\site_location_management\UpdateTimeZoneServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'displayBlock' block.
 *
 * @Block(
 *  id = "site_location_management_display",
 *  admin_label = @Translation("Display TimeZone block"),
 * )
 */
class DisplayBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a TimeZone Update Block object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $configFactory,
    UpdateTimeZoneServiceInterface $UpdateTimeZone
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
    $this->UpdateTimeZone = $UpdateTimeZone;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('site_location_management.update_timezone_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('site_location_management.settings');
    $timezone = $config->get('timezone');
    $time = $this->UpdateTimeZone->getCurrentTimezone($timezone);
    $build['content'] = [
        '#theme' => 'time_zone_block_display',
        '#location' => $config->get('country'),
        '#time' => $time,        
    ];
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}