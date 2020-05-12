<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Add code tag structure for syntax highlighting.
 *
 * @Filter(
 *   id = "im_external_links",
 *   title = @Translation("Open external links in a new tab and add rel='nofollow'"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE
 * )
 */
class ExternalLinks extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {

    $dom = Html::load($text);
    $elements = $dom->getElementsByTagName('a');
    if ($elements->length === 0) {
      return new FilterProcessResult(Html::serialize($dom));
    }

    foreach ($elements as $element) {
      $url = $element->getAttribute('href');
      if (UrlHelper::isExternal($url)) {
        $element->setAttribute('target', '_blank');
        $element->setAttribute('rel', 'nofollow noopener');
      }
    }

    $result = new FilterProcessResult(Html::serialize($dom));

    return $result;
  }

}
