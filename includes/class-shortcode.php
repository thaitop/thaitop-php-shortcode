<?php
class ThaiTop_PHP_Shortcode_Handler {
    public function __construct() {
        add_shortcode('php_code', array($this, 'handle_shortcode'));
    }
    
    public function handle_shortcode($atts) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'thaitop_php_codes';
        
        $atts = shortcode_atts(array(
            'id' => ''
        ), $atts);
        
        if (empty($atts['id'])) {
            return 'Error: ID is required';
        }
        
        $code = $wpdb->get_var($wpdb->prepare(
            "SELECT php_code FROM $table_name WHERE code_id = %s",
            $atts['id']
        ));
        
        if (!$code) {
            return 'Error: Code not found';
        }

        // Clean and prepare code for execution
        $code = stripslashes($code);
        $code = trim($code);
        
        ob_start();
        try {
            eval($code);
        } catch (Error $e) {
            ob_end_clean();
            return "Error: " . $e->getMessage();
        }
        
        return ob_get_clean();
    }
}
