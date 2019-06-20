# ACF-Block-Loader
Wrapper class for easy ACF Gutenberg Block registration

## Install
```
composer require wp63/acf-block-loader
```

## Usage
By default, Class file should be stored in side `Blocks` directory (or `app/Blocks` in Sage 9) in your theme root directory. Filename should be the same as Classname as in PSR-4. We recommend you to map namespace `WP63\Blocks\` to `Blocks` directory in PSR-4 Autoload in `composer.json` file. Or you will need to include all Block files into your theme manually in `functions.php`


1. Create a class extending `WP63\Block`
2. Class default namespace is `WP63\Blocks\`
3. Class must have at least 2 methods: `register()` and `render()`
```php
<?php
namespace WP63\Blocks;

use WP63\Block;

class MyBlock extends Block {
  protected function register() {
    return [
      'name' => $name,
      'title' => $title,
      'category' => $category
    ];
  }

  public function render() {
    ...
    display block html
    ...
  }
}
```

`register()` method will return an array with 3 keys
* `$name` block-unique-name
* `$title` Block Title
* `$category` Block category. Predefined categories are [ common | formatting | layout | widgets | embed ] _(Optional)_

`render()` is the method for rendering actual block. Every output generated inside this method will be part of block html. Render method will have access to 4 callback arguments from ACF via `$this` variable
* array `$this->block` The block settings and attributes.
* string `$this->content` The block inner HTML (empty).
* boolean `$this->is_preview` True during AJAX preview.
* int|string `$this->post_id` The post ID this block is saved to.

## Filters
* `wp63/acf_block_namespace` Change block namespace. Default: `WP63\Blocks\`
* `wp63/acf_block_directory` Change directory name. Default: `./Blocks`

In case you use this library with Sage 9, You will need to hook into `wp63/acf_block_directory` and change directory name.
```php
add_filter('wp63/acf_block_directory', function( $directory ) {
    return '../app/Blocks';
});
```
