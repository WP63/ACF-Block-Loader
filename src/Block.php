<?php
namespace WP63;

abstract class Block {
  protected $block;
  protected $post;
  protected $is_preview;
  protected $post_id;

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
        'render_callback'   => [$this, 'PrepareRender'],
      ]);
    }
  }

  /**
   * PrepareRender method.
   * Run before actual rendering method. Use for manipulating all repetitive data from ACF.
   */
  public function PrepareRender( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    $this->$block = $block;
    $this->$post = $post;
    $this->$is_preview = $is_preview;
    $this->$post_id = $post_id;

    $this->render();
  }
}
