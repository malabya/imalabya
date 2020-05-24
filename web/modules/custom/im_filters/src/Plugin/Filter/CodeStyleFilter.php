<?php

namespace Drupal\im_filters\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Add code tag structure for syntax highlighting.
 *
 * @Filter(
 *   id = "im_code_style",
 *   title = @Translation("Code tag for Syntax highlighting"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE
 * )
 */
class CodeStyleFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {

    $dom = Html::load($text);
    $elements = $dom->getElementsByTagName('pre');
    if ($elements->length === 0) {
      return new FilterProcessResult(Html::serialize($dom));
    }

    foreach ($elements as $element) {
      $content = $element->textContent;
      $class = $element->getAttribute('class');

      // Create the elements.
      $code = $dom->createElement('code', htmlentities($content));
      $pre = $dom->createElement('pre');

      // Set attributes.
      $code->setAttribute('class', $class);

      // Add the `line-numbers` class for prismjs.
      $class .= ' line-numbers';
      $pre->setAttribute('class', $class);

      // Append & replace the new element.
      $pre->appendChild($code);
      $element->parentNode->replaceChild($pre, $element);
    }

    $result = new FilterProcessResult(Html::serialize($dom));
    $result->setAttachments([
      'library' => 'imalabya/prism',
    ]);

    return $result;
  }

}
