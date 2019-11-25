# ACF-Block-Loader
Wrapper class for easy ACF Gutenberg Block registration

## Install
```
composer require wp63/acf-block-loader
```

## Usage
By default, Class file should be stored in side `Blocks` directory (or `app/Blocks` in Sage 9) in your theme root directory. Filename should be the same as Classname as in PSR-4. We recommend you to map namespace `App\Blocks\` to `Blocks` directory in PSR-4 Autoload in `composer.json` file. Or you will need to include all Block files into your theme manually in `functions.php`


1. Create a class extending `WP63\Block`
2. Class default namespace is `WP63\Blocks\`
3. Class must have at least 2 methods: `register()` and static `render()`
```php
<?php
namespace App\Blocks;

use WP63\Block;

class MyBlock extends Block {
  protected function register() {
    return [
      'name' => $name,
      'title' => $title,
      'category' => $category
    ];
  }

  public static function render( $options ) {
    // You can directly render HTML within `render()` method
    ...
    display block html
    ...

    // Or alternatively, return an array for Blade template engine in Sage 9
    return [
      'name' => 'value', // $name in template file
      'foo' => 'bar', // $foo in template file
    ];
  }
}
```

`register()` method will return an array with 3 keys
* `$name` block-unique-name
* `$title` Block Title
* `$category` Block category. Predefined categories are [ common | formatting | layout | widgets | embed ] _(Optional)_

`render()` is the static method for rendering actual block. Every output generated inside this method will be part of block html. Render method will have access to 4 callback arguments from ACF via `$options` arguments
* array `$options->block` The block settings and attributes.
* string `$options->content` The block inner HTML (empty).
* boolean `$options->is_preview` True during AJAX preview.
* int|string `$options->post_id` The post ID this block is saved to.

## Filters
* `wp63/acf_block_namespace` Change block namespace. Default: `App\Blocks\`
* `wp63/acf_block_directory` Change directory name. Default: `./Blocks`
* `wp63/acf_block_template_directory` Change Blade template directory. relative to `/views` directory Default: `blocks`

## Actions
* `wp63/before_block_render` Before each block is rendered
* `wp63/before_block_render/{$block_name}` Before `$block_name` is rendered
* `wp63/after_block_render` After each block is rendered
* `wp63/after_block_render/{$block_name}` After `$block_name` is rendered

In case you use this library with Sage 9, You will need to hook into `wp63/acf_block_directory` and change directory name.
```php
add_filter('wp63/acf_block_directory', function( $directory ) {
    return '../app/Blocks';
});
```

## Use with Blade template engine in Sage 9
1. In class `render()` method, instead of echo output directly, return an array contains all data to be exposed to Blade template file.
2. Create template file in `views/blocks` the file name must be the same as block nam3 (`$name`). for example: `views/blocks/hero-banner.blade.php`
