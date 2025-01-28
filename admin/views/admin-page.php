<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'thaitop_php_codes';

// Get edit code if in edit mode
$edit_code = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['code_id'])) {
    $edit_code = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE code_id = %s",
        $_GET['code_id']
    ));
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['code_id'])) {
    if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_code')) {
        $code_id = sanitize_text_field($_GET['code_id']);
        $wpdb->delete(
            $table_name,
            array('code_id' => $code_id),
            array('%s')
        );
        echo '<div class="notice notice-success"><p>Code deleted successfully!</p></div>';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'save_php_code')) {
        $code_id = sanitize_text_field($_POST['code_id']);
        $php_code = stripslashes($_POST['php_code']);
        
        if ($edit_code) {
            $wpdb->update(
                $table_name,
                array('php_code' => $php_code),
                array('code_id' => $code_id),
                array('%s'),
                array('%s')
            );
        } else {
            $existing_code = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table_name WHERE code_id = %s",
                $code_id
            ));

            if ($existing_code) {
                echo '<div class="notice notice-error"><p>Error: Shortcode ID already exists!</p></div>';
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'code_id' => $code_id,
                        'php_code' => $php_code
                    ),
                    array('%s', '%s')
                );
            }
        }
        
        if ($wpdb->last_error) {
            echo '<div class="notice notice-error"><p>Error: ' . esc_html($wpdb->last_error) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>' . 
                ($edit_code ? 'Code updated' : 'Code saved') . 
                ' successfully!</p></div>';
            
            if ($edit_code) {
                echo '<script>window.location.href = "' . remove_query_arg(['action', 'code_id']) . '";</script>';
            }
        }
    }
}
?>

<div class="wrap">
    <h1>PHP Shortcodes</h1>
    <form method="post">
        <?php wp_nonce_field('save_php_code'); ?>
        <table class="form-table">
            <tr>
                <th><label for="code_id">Shortcode ID</label></th>
                <td>
                    <input type="text" name="code_id" id="code_id" 
                        class="regular-text" required 
                        value="<?php echo $edit_code ? esc_attr($edit_code->code_id) : ''; ?>"
                        <?php echo $edit_code ? 'readonly' : ''; ?>>
                    <p class="description">
                        Basic usage: [php_code id="your-id"]<br>
                        With attributes: [php_code id="your-id" name="John" age="25" custom="value"]<br>
                        Access attributes in code: $name, $age, $custom<br>
                        Access all attributes as array: $attributes['name'], $attributes['custom']
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="php_code">PHP Code</label></th>
                <td>
                    <textarea name="php_code" id="php_code" rows="10" class="large-text code" required><?php 
                        echo $edit_code ? esc_textarea($edit_code->php_code) : ''; 
                    ?></textarea>
                    <div class="code-examples">
                        <h4>Examples of Using Attributes:</h4>
                        <pre>
// Example 1: Direct variable access
echo "Hello $name, you are $age years old";

// Example 2: Using attributes array
echo "Welcome {$attributes['name']}!";

// Example 3: Conditional check
if(isset($age) && is_numeric($age)) {
    echo "Age verified: " . intval($age);
}

// Example 4: Multiple attributes
$price = isset($price) ? floatval($price) : 0;
$quantity = isset($quantity) ? intval($quantity) : 1;
echo "Total: $" . ($price * $quantity);
                        </pre>
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" 
                value="<?php echo $edit_code ? 'Update Code' : 'Save Code'; ?>">
            <?php if ($edit_code): ?>
                <a href="<?php echo remove_query_arg(['action', 'code_id']); ?>" 
                    class="button">Cancel</a>
            <?php endif; ?>
        </p>
    </form>
    
    <h2>Saved Codes</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="column-id">ID</th>
                <th class="column-code">Code</th>
                <th class="column-shortcode">Shortcode</th>
                <th class="column-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $codes = $wpdb->get_results("SELECT * FROM $table_name");
            foreach ($codes as $code) {
                $delete_url = wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'delete',
                            'code_id' => $code->code_id
                        )
                    ),
                    'delete_code'
                );
                
                $edit_url = add_query_arg(
                    array(
                        'action' => 'edit',
                        'code_id' => $code->code_id
                    )
                );
                
                // Get first 3 lines of code
                $code_preview = stripslashes($code->php_code);
                $lines = explode("\n", $code_preview);
                $preview_lines = array_slice($lines, 0, 3);
                $has_more = count($lines) > 3;
                
                echo '<tr>';
                echo '<td class="column-id">' . esc_html($code->code_id) . '</td>';
                echo '<td class="column-code"><pre style="max-width: 100%; overflow-x: auto;">' . esc_html(implode("\n", $preview_lines));
                if ($has_more) {
                    echo "\n...";
                }
                echo '</pre></td>';
                echo '<td class="column-shortcode">';
                echo '<div class="shortcode-wrapper">';
                echo '<code>[php_code id="' . esc_html($code->code_id) . '"]</code>';
                echo '<button class="copy-button button button-small" onclick="copyShortcode(this)" data-shortcode="[php_code id=&quot;' . esc_html($code->code_id) . '&quot;]">Copy</button>';
                echo '</div>';
                echo '</td>';
                echo '<td class="column-actions">';
                echo '<a href="' . esc_url($edit_url) . '" class="button button-small">Edit</a> ';
                echo '<a href="' . esc_url($delete_url) . '" class="button button-small button-link-delete" onclick="return confirm(\'Are you sure you want to delete this code?\')">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<style>
.column-id { width: 15%; }
.column-code { width: 45%; }
.column-shortcode { width: 25%; }
.column-actions { width: 15%; }
.shortcode-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}
.copy-button {
    min-width: 60px;
}
.code-examples {
    margin-top: 10px;
    padding: 10px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.code-examples h4 {
    margin: 0 0 10px 0;
    color: #23282d;
}
.code-examples pre {
    background: #fff;
    padding: 10px;
    border: 1px solid #e5e5e5;
    overflow-x: auto;
}
</style>

<script>
function copyShortcode(button) {
    const shortcode = button.getAttribute('data-shortcode');
    navigator.clipboard.writeText(shortcode).then(function() {
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        setTimeout(function() {
            button.textContent = originalText;
        }, 1500);
    });
}
</script>
