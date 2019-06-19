<?php
/*
Plugin Name: ACF Block Loader
Plugin URI: https://wp63.co/plugins/acf-block-loader/
Description: Wrapper class for easy ACF Gutenberg Block registration
Version: 1.0.0
Author: WP63
Author URI: https://wp63.co
License: GPLv2 or later
Text Domain: acfbl
*/

if( function_exists('add_action') ) {
  add_action('acf/init', function() {
    $namespace = apply_filters('wp63/acf_block_namespace', 'WP63\Blocks\\');
    $directory = get_template_directory() . '/' . apply_filters('wp63/acf_block_directory', 'Blocks');

    if( !is_dir( $directory ) ) {
      return;
    }

    if( $handle = opendir( $directory ) ) {
      while( false !== ( $filename = readdir( $handle ) )) {
        if( $filename === '.' || $filename === '..' ) {
          continue;
        }

        $parts = pathinfo( $filename );

        if( class_exists( $namespace . $parts['filename'] ) ) {
          $classname = $namespace . $parts['filename'];

          call_user_func( new $classname );
        }
      }
    }
  });
}
