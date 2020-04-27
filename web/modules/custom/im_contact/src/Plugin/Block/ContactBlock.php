<?php

namespace Drupal\im_contact\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a Contact block.
 *
 * @Block(
 *   id = "im_contact_block",
 *   admin_label = @Translation("Contact"),
 *   category = @Translation("iMalabya")
 * )
 */
class ContactBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * Create a ContactForm block.
   *
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   *   The form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilder $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = $this->formBuilder->getForm('Drupal\im_contact\Form\ContactForm');
    return $form;
  }

}
