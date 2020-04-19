<?php

namespace Drupal\im_prevnext;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;

/**
 * The Previous Next pager service.
 */
class PreviousNext {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Create a Previous Next pager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Get previous node.
   *
   * @param mixed $node
   *   The current node object.
   */
  public function getPreviousNode($node) {
    $previous_node_id = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('status', 1)->condition('type', 'blog')
      ->condition('created', $node->created->value, "<")
      ->sort('created', 'DESC')
      ->range(0, 1)
      ->execute();

    $previous_node = !empty($previous_node_id) ? $this->entityTypeManager->getStorage('node')->load(reset($previous_node_id)) : NULL;

    return $previous_node != NULL ? Link::fromTextAndUrl($previous_node->label(), $previous_node->toUrl('canonical'))->toString() : NULL;
  }

  /**
   * Get next node.
   *
   * @param mixed $node
   *   The current node object.
   */
  public function getNextNode($node) {
    $next_node_id = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('status', 1)->condition('type', 'blog')
      ->condition('created', $node->created->value, ">")
      ->sort('created', 'ASC')
      ->range(0, 1)
      ->execute();

    $next_node = !empty($next_node_id) ? $this->entityTypeManager->getStorage('node')->load(reset($next_node_id)) : NULL;

    return $next_node != NULL ? Link::fromTextAndUrl($next_node->label(), $next_node->toUrl('canonical'))->toString() : NULL;
  }

}
