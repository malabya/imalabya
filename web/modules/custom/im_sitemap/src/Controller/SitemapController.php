<?php

namespace Drupal\im_sitemap\Controller;

use Drupal\Core\Database\Connection;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SitemapController.
 */
class SitemapController extends ControllerBase {

  /**
   * A database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Create a sitemap.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->database = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * Sitemap.
   *
   * @return string
   *   Return sitemap xml string.
   */
  public function sitemap() {
    $data = $this->database->query('SELECT loc, lastmod, priority FROM {sitemap}')->fetchAll();
    $xml = $this->generateXml($data);
    return new Response($xml, Response::HTTP_OK, [
      'Content-type' => 'application/xml; charset=utf-8',
      'X-Robots-Tag' => 'noindex, follow',
    ]);
  }

  /**
   * Generate the sitemap XML.
   *
   * @param array $data
   *   The data array.
   *
   * @return string
   *   The sitemap XML string.
   */
  private function generateXml(array $data) {
    $doc = new \DOMDocument();
    $urlset = $doc->createElement('urlset');
    $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    foreach ($data as $datum) {
      $url = $doc->createElement('url');
      foreach ($datum as $key => $value) {
        $elem = $doc->createElement($key, $value);
        $url->appendChild($elem);
      }
      $urlset->appendChild($url);
    }
    $doc->appendChild($urlset);
    return $doc->saveXML();
  }

}
