<?php
/**
 * Plugin Name: Buddy Magic Login
 * Description: Passwordless login, registration and optional payment for BuddyPress and BuddyBoss.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: buddymagiclogin
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ---------------------------------------------------------
 *  LOAD TEXT DOMAIN
 * ---------------------------------------------------------
 */
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'buddymagiclogin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});


/**
 * ---------------------------------------------------------
 *  DEFINE CONSTANTS
 * ---------------------------------------------------------
 */
define( 'BML_DIR', plugin_dir_path( __FILE__ ) );
define( 'BML_URL', plugin_dir_url( __FILE__ ) );


/**
 * ---------------------------------------------------------
 *  INCLUDE FILES
 * ---------------------------------------------------------
 */
require_once BML_DIR . 'includes/Detector.php';
require_once BML_DIR . 'includes/Integration.php';
require_once BML_DIR . 'includes/Onboarding.php';
require_once BML_DIR . 'includes/cleanup.php';
require_once BML_DIR . 'admin/settings.php';
require_once BML_DIR . 'admin/settings-page.php';


/**
 * ---------------------------------------------------------
 *  EMAIL SENDER OVERRIDE
 * ---------------------------------------------------------
 * All plugin emails will use:
 *   [Site Name] <noreply@sitedomain>
 */
add_filter( 'wp_mail_from', function( $email ) {

    $domain = wp_parse_url( home_url(), PHP_URL_HOST );

    if ( ! $domain ) {
        $domain = $_SERVER['SERVER_NAME'] ?? 'localhost';
    }

    return 'noreply@' . $domain;
});

add_filter( 'wp_mail_from_name', function( $name ) {
    return wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
});


/**
 * ---------------------------------------------------------
 *  BLOCK WORDPRESS LOGIN (WITHOUT BREAKING LOGOUT)
 * ---------------------------------------------------------
 */
function bml_block_wp_login() {

    if ( ! get_option( 'bml_disable_wp_login' ) ) {
        return;
    }

    $action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';

    // Allow logout
    if ( $action === 'logout' ) {
        return;
    }

    // Allow password reset flows
    if ( in_array( $action, [ 'lostpassword', 'rp', 'resetpass' ], true ) ) {
        return;
    }

    // Allow AJAX / REST
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return;
    }
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return;
    }

    $access_page = (int) get_option( 'bml_access_page', 0 );
    if ( ! $access_page ) {
        return;
    }

    $url = get_permalink( $access_page );
    if ( ! $url ) {
        return;
    }

    wp_safe_redirect( $url );
    exit;
}
add_action( 'login_init', 'bml_block_wp_login' );


/**
 * ---------------------------------------------------------
 *  BLOCK BUDDYBOSS / BUDDYPRESS LOGIN
 * ---------------------------------------------------------
 */
function bml_block_bp_login() {

    if ( ! get_option( 'bml_disable_bp_login' ) ) {
        return;
    }

    $access_page = (int) get_option( 'bml_access_page', 0 );
    if ( ! $access_page ) {
        return;
    }

    $url = get_permalink( $access_page );

    if ( function_exists( 'bp_is_register_page' ) && bp_is_register_page() ) {
        wp_safe_redirect( $url );
        exit;
    }

    if ( function_exists( 'bp_is_activation_page' ) && bp_is_activation_page() ) {
        wp_safe_redirect( $url );
        exit;
    }
}
add_action( 'template_redirect', 'bml_block_bp_login' );


/**
 * ---------------------------------------------------------
 *  TEMPLATE LOADER
 * ---------------------------------------------------------
 */
add_filter( 'template_include', function( $template ) {

    $access_page       = (int) get_option( 'bml_access_page', 0 );
    $registration_page = (int) get_option( 'bml_registration_page', 0 );

    if ( $access_page && is_page( $access_page ) ) {
        return BML_DIR . 'templates/access-form.php';
    }

    if ( $registration_page && is_page( $registration_page ) ) {
        return BML_DIR . 'templates/registration-form.php';
    }

    return $template;
});


/**
 * ---------------------------------------------------------
 *  SHORTCODES
 * ---------------------------------------------------------
 */
function bml_shortcode_access() {
    ob_start();
    include BML_DIR . 'templates/access-form.php';
    return ob_get_clean();
}

function bml_shortcode_registration() {
    ob_start();
    include BML_DIR . 'templates/registration-form.php';
    return ob_get_clean();
}

add_shortcode( 'bml_access', 'bml_shortcode_access' );
add_shortcode( 'bml_registration', 'bml_shortcode_registration' );


/**
 * ---------------------------------------------------------
 *  ACTIVATION HOOK
 * ---------------------------------------------------------
 */
register_activation_hook( __FILE__, function() {
    if ( ! get_option( 'bml_cleanup_timeout' ) ) {
        update_option( 'bml_cleanup_timeout', 60 );
    }
});


/**
 * ---------------------------------------------------------
 *  ADMIN MENU
 * ---------------------------------------------------------
 */
add_action( 'admin_menu', function() {
    add_options_page(
        __( 'Buddy Magic Login', 'buddymagiclogin' ),
        __( 'Buddy Magic Login', 'buddymagiclogin' ),
        'manage_options',
        'bml-settings',
        'bml_render_settings_page'
    );
});


/**
 * ---------------------------------------------------------
 *  INITIALIZE ONBOARDING
 * ---------------------------------------------------------
 */
add_action( 'plugins_loaded', function() {
    new \BML\Onboarding( new \BML\Detector() );
});
