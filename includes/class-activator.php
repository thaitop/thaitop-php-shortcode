<?php
class ThaiTop_PHP_Shortcode_Activator {
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'thaitop_php_codes';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            code_id varchar(50) NOT NULL,
            php_code text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
