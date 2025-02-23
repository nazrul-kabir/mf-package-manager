<?php

class Package_Meta_Box {
    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_meta_box() {
        add_meta_box(
            'package_details',
            __('Package Details', 'package-manager'),
            array($this, 'render_meta_box'),
            'package',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('package_details', 'package_details_nonce');

        $fields = array(
            'name' => __('Name:', 'package-manager'),
            'description' => __('Description:', 'package-manager'),
            'download_speed' => __('Download Speed:', 'package-manager'),
            'upload_speed' => __('Upload Speed:', 'package-manager'),
            'extra_free' => __('Extra Free:', 'package-manager'),
            'provider' => __('Provider:', 'package-manager'),
            'type' => __('Type:', 'package-manager'),
            'price' => __('Price:', 'package-manager'),
        );

        foreach ($fields as $field => $label) {
            $value = get_post_meta($post->ID, '_package_' . $field, true);
            ?>
            <p>
                <label for="package_<?php echo $field; ?>"><?php echo $label; ?></label>
                <?php if ($field === 'description' || $field === 'extra_free'): ?>
                    <textarea id="package_<?php echo $field; ?>" name="package_<?php echo $field; ?>" rows="3"><?php echo esc_textarea($value); ?></textarea>
                <?php elseif ($field === 'type'): ?>
                    <select id="package_<?php echo $field; ?>" name="package_<?php echo $field; ?>">
                        <option value="residential" <?php selected($value, 'residential'); ?>><?php _e('Residential', 'package-manager'); ?></option>
                        <option value="business" <?php selected($value, 'business'); ?>><?php _e('Business', 'package-manager'); ?></option>
                    </select>
                <?php else: ?>
                    <input type="text" id="package_<?php echo $field; ?>" name="package_<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>">
                <?php endif; ?>
            </p>
            <?php
        }

        // Postcode selection
        $selected_postcodes = get_post_meta($post->ID, '_package_postcodes', true) ?: array();
        if (!is_array($selected_postcodes)) {
            $selected_postcodes = array($selected_postcodes);
        }
        $all_postcodes = $this->get_all_postcodes();
        ?>
        <p>
            <label for="package_postcodes"><?php _e('Postcodes:', 'package-manager'); ?></label>
            <select id="package_postcodes" name="package_postcodes[]" multiple style="width: 100%; max-width: 400px;">
            <option value="all" <?php selected(in_array('all', $selected_postcodes), true); ?>><?php _e('All', 'package-manager'); ?></option>
                <?php foreach ($all_postcodes as $postcode): ?>
                    <option value="<?php echo esc_attr($postcode); ?>" <?php selected(in_array($postcode, $selected_postcodes), true); ?>>
                        <?php echo esc_html($postcode); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['package_details_nonce']) || !wp_verify_nonce($_POST['package_details_nonce'], 'package_details')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'name' => 'sanitize_text_field',
            'description' => 'sanitize_textarea_field',
            'download_speed' => 'sanitize_text_field',
            'upload_speed' => 'sanitize_text_field',
            'extra_free' => 'sanitize_textarea_field',
            'provider' => 'sanitize_text_field',
            'type' => 'sanitize_text_field',
            'price' => 'sanitize_text_field',
        );

        foreach ($fields as $field => $sanitize_callback) {
            if (isset($_POST['package_' . $field])) {
                update_post_meta($post_id, '_package_' . $field, $sanitize_callback($_POST['package_' . $field]));
            }
        }

        // Save postcodes
        if (isset($_POST['package_postcodes'])) {
            $postcodes = array_map('sanitize_text_field', $_POST['package_postcodes']);
            update_post_meta($post_id, '_package_postcodes', $postcodes);
        }
    }

    private function get_all_postcodes() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'address';
        return $wpdb->get_col("SELECT DISTINCT postcode FROM $table_name ORDER BY postcode ASC");
    }

    public function enqueue_admin_scripts($hook) {
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        }

        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);

        wp_enqueue_script('package-manager-admin', PACKAGE_MANAGER_PLUGIN_URL . 'assets/js/package-manager-admin.js', array('jquery', 'select2'), PACKAGE_MANAGER_VERSION, true);
    }
}
