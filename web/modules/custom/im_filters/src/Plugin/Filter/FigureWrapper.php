<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\image\Entity\ImageStyle;

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

    $text = preg_replace("#<p>(\s*(?:<a.*>)?\s*<img .*>\s*(?:</a.*>)?\s*)</p>#Uim", "$1", $text);
    $dom = Html::load($text);
    $elements = $dom->getElementsByTagName('img');
    if ($elements->length === 0) {
      return new FilterProcessResult(Html::serialize($dom));
    }

    foreach ($elements as $element) {
      // Skip if image already have a figure wrapper.
      if ($element->parentNode->tagName === 'figure') {
        // Add the zoom class early for `medium-zoom` library.
        $element->setAttribute('class', 'zoomable');
        continue;
      }
      $class = $element->getAttribute('class');
      $src = $element->getAttribute('src');
      $alt = $element->getAttribute('alt');

      // Convert image to WebP and create the element.
      $filename = pathinfo(urldecode($src), PATHINFO_BASENAME);
      $style = ImageStyle::load('webp');
      $uri = $style->buildUrl('public://inline-images/' . $filename);
      $srcset = file_url_transform_relative($uri);
      $webp = $dom->createElement('img');
      $webp->setAttribute('src', $srcset);
      $webp->setAttribute('srcset', $srcset);
      $webp->setAttribute('type', 'image/webp');

      // Create the elements.
      $image = $dom->createElement('img');
      $image->setAttribute('src', $src);
      $image->setAttribute('alt', $alt);
      $figure = $dom->createElement('figure');

      // Set attributes.
      $figure->setAttribute('class', $class);

      // Now add the zoom class for img `medium-zoom` library.
      $z_class = 'zoomable';
      $image->setAttribute('class', $z_class);
      $webp->setAttribute('class', $z_class);

      // Append & replace the new element.
      $figure->appendChild($webp);
      // $figure->appendChild($image);
      $element->parentNode->replaceChild($figure, $element);
    }

    $result = new FilterProcessResult(Html::serialize($dom));
    $result->setAttachments([
      'library' => 'imalabya/zoom',
    ]);

    return $result;
  }

}
