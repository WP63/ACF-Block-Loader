# ACF-Block-Loader
Wrapper class for easy ACF Gutenberg Block registration

## Install
```
composer require wp63/acf-block-loader
```

## Usage
1. Create a class extending `WP63\Block`
2. Class default namespace is `WP63\Blocks\`
3. Class must have at least 2 method: `register()` and `render()`
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
