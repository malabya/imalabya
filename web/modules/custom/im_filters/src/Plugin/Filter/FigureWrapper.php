<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Add code tag structure for syntax highlighting.
 *
 * @Filter(
 *   id = "im_figure_wrapper",
 *   title = @Translation("Add a <code>figure</code> wrapper to inline images."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE
 * )
 */
class FigureWrapper extends FilterBase {

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
      // Skip if image already have a figure wrapper.
      if ($element->parentNode->tagName === 'figure') {
        continue;
      }
      $class = $element->getAttribute('class');
      $src = $element->getAttribute('src');
      $alt = $element->getAttribute('alt');

      // Create the elements.
      $image = $dom->createElement('img');
      $image->setAttribute('src', $src);
      $image->setAttribute('alt', $alt);
      $figure = $dom->createElement('figure');

      // Set attributes.
      $figure->setAttribute('class', $class);

      // Append & replace the new element.
      $figure->appendChild($image);
      $element->parentNode->replaceChild($figure, $element);
    }

    $result = new FilterProcessResult(Html::serialize($dom));

    return $result;
  }

}
