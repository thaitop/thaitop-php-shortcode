<?php
if (!defined('WP_UNINSTALL_PLUGIN') || !WP_UNINSTALL_PLUGIN) {
    die;
}

if (!current_user_can('activate_plugins')) {
    return;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-database.php';

$db = ThaiTop_PHP_Shortcode_DB::get_instance();
if ($db->table_exists()) {
    $db->drop_table();
    wp_cache_delete('thaitop_table_exists_' . $wpdb->prefix . 'thaitop_php_codes');
}

delete_option('thaitop_php_shortcode_version');
wp_cache_flush();
