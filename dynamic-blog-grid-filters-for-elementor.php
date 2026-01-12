<?php
/**
 * Plugin Name: Dynamic Blog Grid & Filters for Elementor
 * Description: An Elementor addon to create dynamic, filterable blog grids with category & tag filters, pagination, and mobile off-canvas UI.
 * Plugin URI:  https://example.com/dynamic-blog-grid-filters
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * Text Domain: dynamic-blog-grid-filters
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'DBGFE_VERSION', '1.0.0' );
define( 'DBGFE_PATH', plugin_dir_path( __FILE__ ) );
define( 'DBGFE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if Elementor is active
 */
function dbgfe_is_elementor_active() {
    return did_action( 'elementor/loaded' );
}

/**
 * Admin notice if Elementor is missing
 */
function dbgfe_admin_notice_missing_elementor() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    echo '<div class="notice notice-error"><p>';
    echo esc_html__( 'Dynamic Blog Grid & Filters requires Elementor to be installed and activated.', 'dynamic-blog-grid-filters' );
    echo '</p></div>';
}

/**
 * Init plugin
 */
function dbgfe_init_plugin() {

    if ( ! dbgfe_is_elementor_active() ) {
        add_action( 'admin_notices', 'dbgfe_admin_notice_missing_elementor' );
        return;
    }

    // Load text domain
    load_plugin_textdomain( 'dynamic-blog-grid-filters', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    // Register assets
    add_action( 'wp_enqueue_scripts', 'dbgfe_register_assets' );

    // Register Elementor widget
    add_action( 'elementor/widgets/register', 'dbgfe_register_widgets' );
}
add_action( 'plugins_loaded', 'dbgfe_init_plugin' );

/**
 * Register CSS & JS
 */
function dbgfe_register_assets() {
    wp_register_style(
        'dbgfe-style',
        DBGFE_URL . 'assets/css/blog-grid.css',
        [],
        DBGFE_VERSION
    );

    wp_register_script(
        'dbgfe-script',
        DBGFE_URL . 'assets/js/blog-grid.js',
        [ 'jquery' ],
        DBGFE_VERSION,
        true
    );
}

/**
 * Register Elementor widgets
 */
function dbgfe_register_widgets( $widgets_manager ) {
    require_once DBGFE_PATH . 'widgets/class-dynamic-blog-grid.php';

    $widgets_manager->register( new \DBGFE_Dynamic_Blog_Grid() );
}





