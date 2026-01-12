<?php
/**
 * Plugin Name:       Buddy Magic Login
 * Description:       Passwordless login, registration and optional payment for Buddypress and BuddyBoss.
 * Version:           1.0.0
 * Author:            Socialforger
 * Text Domain:       buddymagiclogin
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'BML_VERSION', '1.0.0' );
define( 'BML_FILE', __FILE__ );
define( 'BML_DIR', plugin_dir_path( __FILE__ ) );
define( 'BML_URL', plugin_dir_url( __FILE__ ) );

require_once BML_DIR . 'includes/class-bml-plugin.php';

add_action( 'plugins_loaded', function() {
    \BML\Plugin::instance();
} );

register_activation_hook( __FILE__, [ '\BML\Plugin', 'activate' ] );
register_deactivation_hook( __FILE__, [ '\BML\Plugin', 'deactivate' ] );
