<?php
/**
 * Plugin Name: MetroFibre Package Manager
 * Plugin URI: 
 * Description: Manages and displays a list of packages on a dedicated page.
 * Version: 1.0.0
 * Author: Nazrul Kabir
 * Author URI: https://nazrulkabir.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: package-manager
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('PACKAGE_MANAGER_VERSION', '1.0.0');
define('PACKAGE_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PACKAGE_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once PACKAGE_MANAGER_PLUGIN_DIR . 'includes/class-package-post-type.php';
require_once PACKAGE_MANAGER_PLUGIN_DIR . 'includes/class-package-meta-box.php';
require_once PACKAGE_MANAGER_PLUGIN_DIR . 'includes/class-package-shortcode.php';
require_once PACKAGE_MANAGER_PLUGIN_DIR . 'includes/class-package-template.php';

// Initialize the plugin
function package_manager_init() {
    $post_type = new Package_Post_Type();
    $post_type->init();

    $meta_box = new Package_Meta_Box();
    $meta_box->init();

    $shortcode = new Package_Shortcode();
    $shortcode->init();

    $template = new Package_Template();
    $template->init();
}
add_action('plugins_loaded', 'package_manager_init');

// Activation hook
function package_manager_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'package_postcode_ranges';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        package_id bigint(20) NOT NULL,
        postcode_range varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'package_manager_activate');


// Deactivation hook
function package_manager_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'package_manager_deactivate');
