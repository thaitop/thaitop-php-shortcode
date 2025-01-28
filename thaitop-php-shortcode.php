<?php
/*
Plugin Name: ThaiTop Custom PHP Shortcode
Description: Execute custom PHP code via shortcodes in WordPress
Version: 1.0.2
Author: ThaiTop
*/

// Security check
if (!defined('ABSPATH')) {
    die('Direct access not permitted');
}

// Plugin path and URL constants
define('TPHP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TPHP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load required files
require_once TPHP_PLUGIN_PATH . 'includes/class-activator.php';
require_once TPHP_PLUGIN_PATH . 'includes/class-shortcode.php';
require_once TPHP_PLUGIN_PATH . 'admin/class-admin.php';

// Initialize plugin
function thaitop_php_shortcode_init() {
    // Initialize classes
    new ThaiTop_PHP_Shortcode_Admin();
    new ThaiTop_PHP_Shortcode_Handler();
}
add_action('plugins_loaded', 'thaitop_php_shortcode_init');

// Register activation hook
register_activation_hook(__FILE__, array('ThaiTop_PHP_Shortcode_Activator', 'activate'));
