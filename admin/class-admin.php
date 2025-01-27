<?php
class ThaiTop_PHP_Shortcode_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_thaitop-php-shortcode' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'thaitop-php-shortcode-admin',
            TPHP_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            '1.0.0'
        );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'PHP Shortcodes',
            'PHP Shortcodes',
            'manage_options',
            'thaitop-php-shortcode',
            array($this, 'render_admin_page')
        );
    }
    
    public function render_admin_page() {
        include TPHP_PLUGIN_PATH . 'admin/views/admin-page.php';
    }
}
