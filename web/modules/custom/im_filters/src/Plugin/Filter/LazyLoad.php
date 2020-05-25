<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\Entity\File;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\image\Entity\ImageStyle;

/**
 * Add code tag structure for syntax highlighting.
 *
 * @Filter(
 *   id = "im_lazy_load_image",
 *   title = @Translation("Lazy load inline images."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE
 * )
 */
class LazyLoad extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {

    $dom = Html::load($text);
    $elements = $dom->getElementsByTagName('img');
    if ($elements->length === 0) {
      return new FilterProcessResult(Html::serialize($dom));
    }

    foreach ($elements as $element) {
      $src = $element->getAttribute('src');

      // Get the placeholder image src.
      $placeholder = $this->getPlaceholder($src);

      // Get any existing classes and add the lazy class for lazy loading image.
      $classes = explode(" ", $element->getAttribute('class'));
      array_push($classes, 'lazy');

      // Set the attributes.
      $element->setAttribute('class', implode(" ", $classes));
      $element->setAttribute('src', $placeholder);
      $element->setAttribute('data-src', $src);
    }

    $result = new FilterProcessResult(Html::serialize($dom));
    $result->setAttachments([
      'library' => 'imalabya/lazy',
    ]);

    return $result;
  }

  /**
   * Get the placeholder image.
   */
  protected function getPlaceholder($src) {
    $filename = pathinfo(urldecode($src), PATHINFO_BASENAME);
    $style = ImageStyle::load('placeholder');
    $uri = $style->buildUrl('public://inline-images/' . $filename);

    return file_url_transform_relative($uri);
  }

}
