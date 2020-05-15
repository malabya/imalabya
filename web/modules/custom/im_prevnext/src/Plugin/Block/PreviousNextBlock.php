<?php

namespace Drupal\im_prevnext\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\path_alias\AliasManager;

/**
 * Provides a 'Previous Next feed' block for blogs.
 *
 * @Block(
 *   id = "im_prev_next",
 *   admin_label = @Translation("Previous Next blog"),
 *   category = @Translation("iMalabya"),
 *   context_definitions = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Node"))
 *   }
 * )
 */
class PreviousNextBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The path alias manager.
   *
   * @var \Drupal\path_alias\AliasManager
   */
  protected $pathAliasManager;

  /**
   * Create a previous next block.
   */
  public function __construct(array $configuration,
  $plugin_id,
  $plugin_definition,
  EntityTypeManagerInterface $entity_type_manager,
  AliasManager $alias_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->pathAliasManager = $alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('path_alias.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->getContextValue('node');

    $previous_node_id = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('status', 1)->condition('type', 'blog')
      ->condition('created', $node->created->value, "<")
      ->sort('created', 'DESC')
      ->range(0, 1)
      ->execute();

    $next_node_id = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('status', 1)->condition('type', 'blog')
      ->condition('created', $node->created->value, ">")
      ->sort('created', 'ASC')
      ->range(0, 1)
      ->execute();

    $previous_node = !empty($previous_node_id) ? $this->entityTypeManager->getStorage('node')->load(reset($previous_node_id)) : NULL;
    $next_node = !empty($next_node_id) ? $this->entityTypeManager->getStorage('node')->load(reset($next_node_id)) : NULL;

    $build = [
      '#theme' => 'im_previous_next',
      '#previous' => $previous_node != NULL ? Link::fromTextAndUrl($previous_node->label(), $previous_node->toUrl('canonical'))->toString() : NULL,
      '#next' => $next_node != NULL ? Link::fromTextAndUrl($next_node->label(), $next_node->toUrl('canonical'))->toString() : NULL,
    ];

    return $build;

  }

}
