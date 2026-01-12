<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_menu() {
        add_options_page(
            __( 'Buddy Magic Login', 'buddymagiclogin' ),
            __( 'Buddy Magic Login', 'buddymagiclogin' ),
            'manage_options',
            'buddymagiclogin',
            [ $this, 'render_page' ]
        );
    }

    public function register_settings() {
        $options = [
            'bml_access_page',
            'bml_registration_page',
            'bml_payment_enable',
            'bml_payment_page',
            'bml_payment_timeout',
            'bml_redirect_type',
            'bml_redirect_custom',
            'bml_disable_wp_login',
            'bml_disable_bp_login',
            'bml_disable_password_reset',
            'bml_disable_password_change',
            'bml_cleanup_enable',
            'bml_cleanup_timeout',
        ];

        foreach ( $options as $opt ) {
            register_setting( 'bml_settings', $opt );
        }
    }

    public function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        include BML_DIR . 'admin/settings-page.php';
    }
}
