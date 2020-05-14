<?php

namespace Drupal\im_sitemap\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the im_sitemap module.
 */
class SitemapControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "im_sitemap SitemapController's controller functionality",
      'description' => 'Test Unit for module im_sitemap and controller SitemapController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests im_sitemap functionality.
   */
  public function testSitemapController() {
    // Check that the basic functions of module im_sitemap.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
