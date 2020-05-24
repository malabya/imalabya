<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
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

      // Get the file ID.
      $fid = $this->getFileId($src);

      // Get the placeholder image src.
      $placeholder = $this->getPlaceholder($fid);

      $classes = explode(" ", $element->getAttribute('class'));

      // Add the lazy class for lazy loading image.
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
   * Get the file ID from file path.
   */
  protected function getFileId($src) {
    $filename = pathinfo($src, PATHINFO_BASENAME);
    $fid = \Drupal::entityQuery('file')->condition('filename', $filename)->execute();

    return reset($fid);
  }

  /**
   * Get the placeholder image.
   */
  protected function getPlaceholder($fid) {
    $style = ImageStyle::load('placeholder');
    $file = File::load($fid);
    $uri = $style->buildUrl($file->getFileUri());

    return file_url_transform_relative($uri);
  }

}
