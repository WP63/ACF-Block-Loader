<?php
namespace WP63;

abstract class Block {
  /**
   * Abstract method for block initialization
   * @return  array     method MUST return an array contains key `name` as block unique name, and `title` as block actual name
   */
  abstract protected function register();

  /**
   * Abstract method for rendering
   */
  abstract protected function render();

  public function __invoke() {
    $settings = $this->register();

    echo "<h1>Hello</h1>";

    /**
     * Immediate return false if name and title aren't specified
     */
    if( !isset( $settings['name'] ) || !isset( $settings['title'] ) ) {
      return false;
    }

    if( !isset( $settings['category'] ) ) {
      $settings['category'] = 'common';
    }

    /**
     * Register block
     */
    if( function_exists('acf_register_block_type') ) {
      acf_register_block_type([
        'name'              => $settings['name'],
        'title'             => $settings['title'],
        'category'          => $settings['category'],
        'render_callback'   => [$this, 'render'],
      ]);
    }
  }
}
