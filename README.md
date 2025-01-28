# ThaiTop Custom PHP Shortcode Plugin

## Overview
This WordPress plugin allows you to:
- Execute PHP code snippets using shortcodes
- Manage PHP code snippets through an admin interface
- Reuse code across posts and pages
- Store code snippets in the database securely
- Preview code before execution
- Copy shortcodes with one click

## Key Features
- Simple shortcode system: [php_code id="your_id"]
- Support for dynamic attributes in shortcodes
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

## Using Shortcodes

### Basic Usage
```php
[php_code id="hello_world"]
```

### Using with Attributes
You can pass custom attributes to your PHP code:
```php
[php_code id="greeting" name="John" age="25" role="admin"]
```

### Accessing Attributes in Code
There are two ways to access attributes in your PHP code:

1. Direct Variable Access:
```php
echo "Hello $name, you are $age years old";
```

2. Using Attributes Array:
```php
echo "Role: " . $attributes['role'];
```

### Example with Attributes
1. Create a price calculator:
```php
[php_code id="calculate_price" price="10.99" quantity="3"]
```

PHP Code:
```php
$total = floatval($price) * intval($quantity);
echo "Total Price: $" . number_format($total, 2);
```

2. Conditional content:
```php
[php_code id="member_content" role="premium" user="John"]
```

PHP Code:
```php
if ($role === 'premium') {
    echo "Welcome premium member $user!";
    // Display premium content
}
```

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

## Security Notes
- Always sanitize attribute values before using them
- Use WordPress security functions (esc_html, esc_attr) when outputting data
- Validate numeric values using is_numeric()
- Cast values to appropriate types (intval, floatval)

## Cautions
- Use `esc_html()` or `esc_attr()` functions for security when displaying data
- Avoid using code that may affect website security
- Always test code in a development environment first

## Changelog

### Version 1.0.2 (Current)
- Added support for dynamic attributes in shortcodes
- Added comprehensive attribute handling documentation
- Updated admin interface with attribute usage examples
- Added icon for admin menu
- Improved code security for attribute handling

### Version 1.0.1
- Initial public release
- Basic shortcode functionality
- Admin interface for managing code snippets
- Database storage system
- Basic error handling

### Version 1.0.0
- Beta release for testing
- Core functionality development
- Basic admin interface
