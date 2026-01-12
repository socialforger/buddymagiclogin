<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Security {

    protected $detector;

    public function __construct( Detector $detector ) {
        $this->detector = $detector;

        add_action( 'login_init', [ $this, 'maybe_block_wp_login' ], 1 );
        add_action( 'bp_init', [ $this, 'maybe_block_bp_login' ], 1 );

        add_filter( 'show_password_fields', [ $this, 'maybe_hide_password_fields' ] );
        add_filter( 'allow_password_reset', [ $this, 'maybe_block_password_reset' ], 10, 2 );
    }

    protected function get_access_url() {
        $page_id = (int) get_option( 'bml_access_page', 0 );
        if ( $page_id ) {
            return get_permalink( $page_id );
        }
        return home_url( '/' );
    }

    public function maybe_block_wp_login() {
        $disable = (int) get_option( 'bml_disable_wp_login', 1 );
        if ( ! $disable ) {
            return;
        }

        if ( ! empty( $_GET['bml_token'] ) ) {
            return;
        }

        wp_safe_redirect( $this->get_access_url() );
        exit;
    }

    public function maybe_block_bp_login() {
        $disable = (int) get_option( 'bml_disable_bp_login', 1 );
        if ( ! $disable ) {
            return;
        }

        if ( function_exists( 'bp_is_register_page' ) && bp_is_register_page() ) {
            wp_safe_redirect( $this->get_access_url() );
            exit;
        }

        if ( function_exists( 'bp_is_activation_page' ) && bp_is_activation_page() ) {
            wp_safe_redirect( $this->get_access_url() );
            exit;
        }
    }

    public function maybe_hide_password_fields( $show ) {
        $disable = (int) get_option( 'bml_disable_password_change', 1 );
        return $disable ? false : $show;
    }

    public function maybe_block_password_reset( $allow, $user ) {
        $disable = (int) get_option( 'bml_disable_password_reset', 1 );
        return $disable ? false : $allow;
    }
}
