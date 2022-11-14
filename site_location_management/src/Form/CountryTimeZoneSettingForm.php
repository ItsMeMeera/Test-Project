<?php

namespace Drupal\site_location_management\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings Form for Updating Timezone for User
 */
class CountryTimeZoneSettingForm extends ConfigFormBase {

  /**
   * Drupal\site_location_management\UpdateTimeZoneServiceInterface definition.
   *
   * @var \Drupal\site_location_management\UpdateTimeZoneServiceInterface
   */
  protected $updateTimeZone;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->updateTimeZone = $container->get('site_location_management.update_timezone_service');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_location_timezone_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['site_location_management.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('site_location_management.settings');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Name'),
      '#description' => $this->t('Enter Country Name'),
      '#required' => TRUE,
      '#default_value' => !empty($config->get('country')) ? $config->get('country') : '',
    ];
    $form["city"] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#description' => $this->t('Enter City Name'),
      '#required' => TRUE,
      '#default_value' => !empty($config->get('city')) ? $config->get('city') : '',
    ];
    $form["timezone"] = [
      '#type' => 'select',
      '#title' => $this->t('Time Zone'),
      '#options' => [
        ""=> $this->t('-Please select a option-'),
        "America/Chicago" => "America/Chicago",
        "America/New_York" => "America/New_York", 
        "Asia/Tokyo" => "Asia/Tokyo",
        "Asia/Dubai"=> "Asia/Dubai",
        "Asia/Kolkata" => "Asia/Kolkata",
        "Europe/Amsterdam" => "Europe/Amsterdam",
        "Europe/Oslo" => "Europe/Oslo",
        "Europe/London" => "Europe/London"
      ],
      '#required' => TRUE,
      '#default_value' => !empty($config->get('timezone')) ? $config->get('timezone') : '',
    ];
    $timezone = !empty($config->get('timezone')) ? $config->get('timezone') : '';
    $currentTime = $this->updateTimeZone->getCurrentTimezone($timezone);
    if(!empty($timezone)){
      $form["time"] = [
        "#type" => 'textfield',
        '#title' => $this->t('Time'),
        '#attributes' => array('readonly' => 'readonly'),
        '#default_value' => $currentTime,
      ];
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('site_location_management.settings'); 
    $time = $form_state->getValue('time');
    \Drupal::logger('time_value_submit')->notice('<pre><code>' . print_r($time, TRUE) . '<cpde><pre>');
    $config
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->set('time',  $form_state->getValue('time'))
      ->save();
  }
}