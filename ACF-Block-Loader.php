<?php
namespace WP63;

class BlockLoader {
  private $required_version = '5.8.0';

  public function __construct() {
    $version = get_option('acf_version');
    $compare = version_compare( $version, $this->required_version );

    add_action('admin_notices', function() {
      if( !class_exists('ACF') ) {
        $this->VersionNotice();
      }
    });

    add_action('acf/init', function() use ( $compare ) {
      $namespace = apply_filters('wp63/acf_block_namespace', 'App\Blocks\\');
      $directory = get_template_directory() . '/' . apply_filters('wp63/acf_block_directory', 'Blocks');

      if( !class_exists('ACF') || $compare === -1 ) {
        add_action('admin_notices', function() use ( $compare ) {
          $this->VersionNotice();
        });

        return;
      }

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

  private function VersionNotice() {
    echo "
    <div class=\"notice notice-warning\">
      <p><code>wp63/acf-block-loader</code> requires Advanced Custom Field PRO {$this->required_version} or newer.</p>
    </div>";
  }
}

if( function_exists('add_action') ) {
  ( new BlockLoader() );
}
