<?php

namespace Drupal\session_control;

use Drupal\Core\Config\ConfigFactory;

/**
 * SessionControlManager service.
 */
class SessionControlManager {

  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $config;

  /**
   * Constructs a SessionControlManager object.
   */
  public function __construct(ConfigFactory $config) {
    $this->config = $config;
  }

  /**
   * Are sessions enabled?
   */
  public function areSessionsEnabled($year) {
    if (date('Y') != $year) {
      return FALSE;
    }
    $config = $this->config->get('session_control.settings');
    if (!$config->get('sessions_enabled')) {
      return FALSE;
    }
    if ($config->get('session_deadline') < time()) {
      return FALSE;
    }
    return TRUE;
  }

}
