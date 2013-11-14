<?php

/**
 * @file
 * Contains \Drupal\default_content\Tests\DefaultContentTest.
 */
namespace Drupal\default_content\Tests;

use Drupal\simpletest\WebTestBase;

class DefaultContentTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('rest', 'taxonomy', 'hal', 'default_content');

  public static function getInfo() {
    return array(
      'name' => 'Default content test',
      'description' => 'Test import of default content.',
      'group' => 'Default Content',
    );
  }

  protected function setUp() {
    parent::setUp();
    // Login as admin.
    $this->drupalLogin($this->drupalCreateUser(array_keys(\Drupal::moduleHandler()->invokeAll(('permission')))));
    $this->drupalCreateContentType(array('type' => 'page'));
  }

  /**
   * Test importing default content.
   */
  public function testImport() {
    // Enable the module and import the content.
    \Drupal::moduleHandler()->install(array('default_content_test'), TRUE);
    $this->rebuildContainer();
    $node = $this->drupalGetNodeByTitle('Imported node');
    $this->assertEqual($node->body->value, 'Crikey it works!');
    // Content is always imported as anonymous.
    $this->assertEqual($node->uid->target_id, 0);
    $this->drupalGet('node/' . $node->nid->value);
    $term_id = $node->field_tags->target_id;
    $this->assertTrue(!empty($term_id), 'Term reference populated');
  }

}
