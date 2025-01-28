<?php
class ThaiTop_PHP_Shortcode_Handler {
    public function __construct() {
        add_shortcode('php_code', array($this, 'handle_shortcode'));
    }
    
    public function handle_shortcode($atts) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'thaitop_php_codes';
        
        // แยก id ออกมาจาก attributes อื่นๆ
        $id = isset($atts['id']) ? $atts['id'] : '';
        
        // ลบ id ออกจาก attributes เพื่อให้เหลือแต่ custom attributes
        unset($atts['id']);
        
        if (empty($id)) {
            return 'Error: ID is required';
        }
        
        $code = $wpdb->get_var($wpdb->prepare(
            "SELECT php_code FROM $table_name WHERE code_id = %s",
            $id
        ));
        
        if (!$code) {
            return 'Error: Code not found';
        }

        // Clean and prepare code for execution
        $code = stripslashes($code);
        $code = trim($code);
        
        ob_start();
        try {
            // ทำให้ attributes ทั้งหมดเป็นตัวแปรที่ใช้ได้ในโค้ด PHP
            extract($this->sanitize_attributes($atts));
            
            // ส่ง attributes ทั้งหมดเป็นตัวแปร $attributes ให้ใช้ในโค้ด
            $attributes = $this->sanitize_attributes($atts);
            
            eval($code);
        } catch (Error $e) {
            ob_end_clean();
            return "Error: " . $e->getMessage();
        }
        
        return ob_get_clean();
    }

    private function sanitize_attributes($atts) {
        $clean_atts = array();
        foreach ($atts as $key => $value) {
            // ทำความสะอาดชื่อตัวแปร
            $key = sanitize_key($key);
            // ทำความสะอาดค่า
            $value = sanitize_text_field($value);
            $clean_atts[$key] = $value;
        }
        return $clean_atts;
    }
}
