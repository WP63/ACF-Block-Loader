<?php
namespace WP63;

use function App\template;

abstract class Block {
  protected $block;
  protected $post;
  protected $is_preview;
  protected $post_id;
  protected $block_template;
  protected $block_name;

  /**
   * Abstract method for block initialization
   * @return  array     method MUST return an array contains key `name` as block unique name, and `title` as block actual name
   */
  abstract protected function register();

  /**
   * Abstract method for rendering
   */
  abstract protected function render();

  public function init() {
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

      $this->block_name = $settings['name'];

      if ( isset( $settings['template'] ) ) {
        $this->block_template = $settings['template'];
      }
    }
  }

  /**
   * PrepareRender method.
   * Run before actual rendering method. Use for manipulating all repetitive data from ACF.
   */
  public function PrepareRender( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    $this->$block = $block;
    $this->$content = $content;
    $this->$is_preview = $is_preview;
    $this->$post_id = $post_id;

    $is_sage = apply_filters( 'wp63/is_sage', false );

    if ( $is_sage ) {
      $data = $this->render();
      $template = $this->block_template;

      if ( !$template ) {
        $template = "blocks.{$this->block_name}";
      }

      echo template( $template, $data );
    } else {
      $this->render();
    }
  }
}
