<?php

namespace Drupal\im_contact\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * The contact form.
 */
class ContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'im_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#action'] = 'https://formspree.io/mzbjpbdk';

    $form['status'] = [
      '#markup' => "<div data-drupal-messages id='status' role='contentinfo'></div>",
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    $form['mail'] = [
      '#type' => 'email',
      '#title' => $this->t('E-Mail'),
      '#required' => TRUE,
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
      '#rows' => 8,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send Message'),
    ];

    $form['#attached']['library'][] = 'im_contact/formspree';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('Your phone number is number'));
  }

}
