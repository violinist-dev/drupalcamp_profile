<?php

namespace Drupal\camp_utils\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a 'SignupBlock' block.
 *
 * @Block(
 *  id = "signup_block",
 *  admin_label = @Translation("Signup block"),
 * )
 */
class SignupBlock extends BlockBase {

  
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
       'eventbrite_url' => '',
      ] + parent::defaultConfiguration();
  
 }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['eventbrite_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Eventbrite URL'),
      '#description' => $this->t('Enter the URL of the eventbrite event'),
      '#default_value' => $this->configuration['eventbrite_url'],
      '#maxlength' => 512,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['eventbrite_url'] = $form_state->getValue('eventbrite_url');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_year = date('Y');
    if ($current_year != _camp_utils_get_year_filter()) {
      return [];
    }
    $build = [];
    $build['signup_block_eventbrite_url'] = [
      '#template' => '<div id="signup">
        <h2>{% trans %}Sign up!{% endtrans %}</h2>
        <a href="{{ eventbrite_url }}" class="btn btn-large btn-success">
          {% trans %}Find tickets! {% endtrans %}
        </a>
        <a href="{{ submit_url }}" class="btn btn-large btn-success">
          {% trans %}Submit session{% endtrans %}
        </a>
      </div>',
      '#context' => [
        'eventbrite_url' => $this->configuration['eventbrite_url'],
        'submit_url' => Url::fromUserInput('/user/login?destination=/node/add/session'),
      ],
      '#type' => 'inline_template',
    ];

    return $build;
  }

}
