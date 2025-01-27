<?php
class ThaiTop_PHP_Shortcode_DB {
    private static $instance = null;
    private $wpdb;
    private $table_name;

    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'thaitop_php_codes';
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function table_exists() {
        $cache_key = 'thaitop_table_exists_' . $this->table_name;
        $exists = wp_cache_get($cache_key);

        if (false === $exists) {
            $exists = $this->execute_prepared_query(
                "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s",
                [DB_NAME, $this->table_name],
                'get_var'
            );
            wp_cache_set($cache_key, $exists);
        }
        return ($exists === $this->table_name);
    }

    public function drop_table() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        if ($this->table_exists()) {
            return $this->execute_prepared_query(
                "DROP TABLE IF EXISTS %i",
                [$this->table_name],
                'query'
            );
        }
        return false;
    }

    protected function execute_prepared_query($query, $args = [], $method = 'query') {
        if (empty($query)) {
            return false;
        }

        // ตรวจสอบ method ที่ใช้ได้
        $allowed_methods = ['query', 'get_var', 'get_row', 'get_col', 'get_results'];
        if (!in_array($method, $allowed_methods)) {
            return false;
        }

        try {
            // เตรียม query ด้วย prepare
ß            $prepared_query = empty($args) ? $query : $this->wpdb->prepare($query, ...$args);
            
            // ตรวจสอบว่า query ถูกเตรียมสำเร็จ
            if ($prepared_query === false) {
                return false;
            }

            // ทำ query ด้วย method ที่กำหนด
            return $this->wpdb->$method($prepared_query);
        } catch (Exception $e) {
            error_log('Database query error: ' . $e->getMessage());
            return false;
        }
    }

    protected function prepare_query($query, ...$args) {
        if (empty($query)) {
            return '';
        }

        try {
            return empty($args) ? $query : $this->wpdb->prepare($query, ...$args);
        } catch (Exception $e) {
            error_log('Query preparation error: ' . $e->getMessage());
            return '';
        }
    }
}
