<?php

namespace Drupal\camp_utils\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ContactBlock' block.
 *
 * @Block(
 *  id = "contact_block",
 *  admin_label = @Translation("contact_block"),
 * )
 */
class ContactBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $values = $this->getConfiguration();
    $style = '';
    if (!empty($values['bg_image'])) {
      $style = 'style="background-image: url(' . $values['bg_image'] . ');"';
    }

    return [
      '#type' => 'inline_template',
      '#template' => '
      <div ' . $style . '>
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h5>{% trans %} Location {% endtrans %}</h5>
              <h3>{{place}}</h3>
              <a class="fancybox fancybox.iframe btn btn-default" href="{{google_url}}" class="loc fancybox">{% trans %}View location{% endtrans %}</a>
            </div>
            <div class="col-md-6">
              <h5>{% trans %} Contact us at {% endtrans %}</h5>
              <h3><a href="mailto:{{email}}">{{email}}</a></h3>
              <h3><a href="tel:{{phone}}">{{phone}}</a></h3>
              <ul>
                <li><a href="{{twitter}}"><i class="fa fa-twitter"></i></a></li>
                <li><a href="{{facebook}}"><i class="fa fa-facebook"></i></a></li>
              </ul>
            </div>
          </div>
          </div>
        </div>
       </div>',
      '#context' => $values,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['google_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google maps URL'),
      '#default_value' => $this->getOption('google_url'),
      '#maxlength' => 512,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $this->getOption('email'),
    ];
    $form['facebook'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook'),
      '#default_value' => $this->getOption('facebook'),
    ];
    $form['twitter'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter'),
      '#default_value' => $this->getOption('twitter'),
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $this->getOption('phone'),
    ];
    $form['place'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Place'),
      '#default_value' => $this->getOption('place'),
    ];
    $form['bg_image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Background image'),
      '#default_value' => $this->getOption('bg_image'),
    ];
    return $form;
  }

  /**
   * @param $key
   *
   * @return string
   */
  private function getOption($key) {
    $conf = $this->getConfiguration();
    return !empty($conf[$key]) ? $conf[$key] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'google_url' => '',
      'email' => '',
      'phone' => '',
      'twitter' => '',
      'facebook' => '',
      'place' => '',
      'bg_image' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfiguration($form_state->getValues());
    parent::blockSubmit($form, $form_state);
  }

}
