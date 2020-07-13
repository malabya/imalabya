<?php

namespace Drupal\im_comment\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Disqus comment' block.
 *
 * @Block(
 *  id = "disqus_comment",
 *  admin_label = @Translation("Disqus comment"),
 *  category = @Translation("iMalabya")
 * )
 */
class DisqusCommentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'im_comment',
      '#disqus_shortname' => 'malabya',
      '#attached' => [
        'library' => [
          'im_comment/disqus',
        ],
      ],
    ];
  }

}
