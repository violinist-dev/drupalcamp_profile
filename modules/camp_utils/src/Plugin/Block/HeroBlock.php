<?php

namespace Drupal\camp_utils\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\session_control\SessionControlManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'HeroBlock' block.
 *
 * @Block(
 *  id = "hero_block",
 *  admin_label = @Translation("Hero block"),
 * )
 */
class HeroBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * This manager service right here.
   *
   * @var \Drupal\session_control\SessionControlManager
   */
  protected $sessionControlManager;

  /**
   * HeroBlock constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SessionControlManager $session_control) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->sessionControlManager = $session_control;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('session_control.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'hero_title' => $this->t('Site name'),
      'hero_subtitle' => $this->t('xx. November'),
      'photo_credit' => $this->t('Photo by Trav Williams, Broken Banjo Photography'),
      'photo_url' => drupal_get_path('module', 'camp_utils') . '/images/background.jpg',
    ] + parent::defaultConfiguration();

 }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['hero_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hero title'),
      '#description' => $this->t('Enter a title for the hero component'),
      '#default_value' => $this->configuration['hero_title'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['hero_subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hero subtitle'),
      '#description' => $this->t('Enter a subtitle, like the date for example.'),
      '#default_value' => $this->configuration['hero_subtitle'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['photo_credit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Photo credit'),
      '#description' => $this->t('Enter a photo credit line, that will be displayed in the corner with the photo.'),
      '#default_value' => $this->configuration['photo_credit'],
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['photo_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Photo URL'),
      '#description' => $this->t('Enter a URL where the background photo is placed. It could be relative or absolute'),
      '#default_value' => $this->configuration['photo_url'],
      '#maxlength' => 64,
      '#size' => 64,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['hero_title'] = $form_state->getValue('hero_title');
    $this->configuration['hero_subtitle'] = $form_state->getValue('hero_subtitle');
    $this->configuration['photo_credit'] = $form_state->getValue('photo_credit');
    $this->configuration['photo_url'] = $form_state->getValue('photo_url');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $year = _camp_utils_get_year_filter();
    $sessions_enabled = $this->sessionControlManager->areSessionsEnabled($year);
    $build = [
      '#type' => 'inline_template',
      '#attached' => [
        'library' => [
          'camp_utils/hero_block',
        ],
      ],
      '#template' => '<div id="home" class="bg-image" style="background-image: url({{url}})">
        <div class="photo-credit">
          {{ photo_credit }}
        </div>
        <div class="bg-overlay"></div>
        <div class="slideshow-home">
          <div class="wrapper bg-image-wrapper">
            <h4>{{ title }}</h4>
            <hr>
            <p>{{ subtitle }}</p>
            <a class="btn btn-lg btn-primary" href="#signup">
              {{ sign_up }}
            </a><br><br>
            {%if sessions_enabled %}
            <a class="btn btn-lg btn-primary" href="/user/login?destination=/node/add/session">
              {{ submit_session }}
            </a>
            {%endif%}
          </div>
        </div>
      </div>',
      '#context' => [
        'url' => $this->configuration['photo_url'],
        'sessions_enabled' => $sessions_enabled,
        'submit_session' => $this->t('Submit session'),
        'sign_up' => $this->t('Sign up'),
        'title' => $this->configuration['hero_title'],
        'subtitle' => $this->configuration['hero_subtitle'],
        'photo_credit' => $this->configuration['photo_credit'],
      ],
    ];

    return $build;
  }

}
