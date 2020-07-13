<?php

namespace Drupal\im_comment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Disqus settings.
 */
class DisqusConfigForm extends ConfigFormBase {

  /**
   * Disqus shortname.
   *
   * @var string.
   */
  const SETTINGS = 'im_comment.disqus';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'im_comment_disqus_shortname';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['shortname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Disqus shortname'),
      '#description' => $this->t('Enter the Disqus shortname from the Disqus account'),
      '#default_value' => $config->get('shortname'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory
      ->getEditable(static::SETTINGS)
      ->set('shortname', $form_state->getValue('shortname'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
