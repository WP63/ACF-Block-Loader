<?php
namespace WP63;

use function App\template;

abstract class Block {
  /**
   * Abstract method for block initialization
   * @return  array     method MUST return an array contains key `name` as block unique name, and `title` as block actual name
   */
  abstract protected function register();

  /**
   * Abstract method for rendering
   */
  abstract protected static function render( $options );

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
        'render_callback'   => get_called_class() . '::PrepareRender',
      ]);
    }
  }

  /**
   * PrepareRender method.
   * Run before actual rendering method. Use for manipulating all repetitive data from ACF.
   */
  public static function PrepareRender( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    $is_sage = apply_filters( 'wp63/is_sage', false );
    $options = (object) [
      'block' => $block,
      'content' => $content,
      'is_preview' => $is_preview,
      'post_id' => $post_id,
    ];

    $block_name = explode( '/', $block['name'] )[1];

    do_action( 'wp63/before_block_render', $options );
    do_action( "wp63/before_block_render/{$block_name}", $options );

    if ( $is_sage ) {
      $data = static::render( $options );
      $template = "blocks.{$block_name}";

      echo template( $template, $data );
    } else {
      static::render( $options );
    }

    do_action( 'wp63/after_block_render', $options );
    do_action( "wp63/after_block_render/{$block_name}", $options );
  }
}
