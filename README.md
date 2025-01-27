# ThaiTop Custom PHP Shortcode Plugin

## Overview
This WordPress plugin allows you to:
- Execute PHP code snippets using shortcodes
- Manage PHP code snippets through an admin interface
- Reuse code across posts and pages
- Store code snippets in the database securely
- Preview code before execution
- Copy shortcodes with one click

Key Features:
- Simple shortcode system: [php_code id="your_id"]
- Built-in code editor in admin panel
- Database-backed code storage
- Secure code execution
- Error handling and reporting
- Easy to manage interface

## Installation
- Download the plugin
- Go to WordPress Admin > Plugins > Add New > Upload Plugin 
- Select the plugin zip file and click "Install Now"
- Activate the plugin

## Creating PHP Shortcode
- Navigate to "PHP Shortcodes" in WordPress admin menu
- Enter a "Shortcode ID" (use English characters, no spaces, e.g., "hello_world")
- Input your PHP code in the "PHP Code" field (without <?php ?> tags)
- Click "Save Code"

## Using Shortcode
- Copy the shortcode from the table below (e.g., [php_code id="hello_world"])
- Paste it into any post or page
- The PHP code will execute when the page loads

## Managing Shortcodes
- Click "Edit" to modify the code
- Click "Delete" to remove the shortcode
- Deleted shortcodes will no longer function

## Examples

1. Display Text:
```php
echo "Hello World!";
```

2. Display Date:
```php
echo date("d/m/Y");
```

3. WordPress Integration:
```php
$posts = get_posts(['numberposts' => 5]);
foreach ($posts as $post) {
    echo '<li>' . esc_html($post->post_title) . '</li>';
}
```

## Cautions
- Use `esc_html()` or `esc_attr()` functions for security when displaying data
- Avoid using code that may affect website security
- Always test code in a development environment first
