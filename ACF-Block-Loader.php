<?php
if( function_exists( 'add_action' ) ) {
  add_action( 'admin_notices', function () {
    $version = get_option('acf_version');
    $required_version = '5.8.0';
    $compare = version_compare( $version, $required_version );

    if( !class_exists('ACF') || $compare === -1 ) {
      echo "
      <div class=\"notice notice-warning\">
        <p><code>wp63/acf-block-loader</code> requires Advanced Custom Field PRO {$required_version} or newer.</p>
      </div>";
    }
  } );

  add_action( 'acf/init', function () {
    $namespace = apply_filters('wp63/acf_block_namespace', 'App\Blocks\\');
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

          (new $classname)->init();
        }
      }
    }
  } );
}
