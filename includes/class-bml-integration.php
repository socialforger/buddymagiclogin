<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Integration {

    protected static $detector;

    public function __construct( Detector $detector ) {
        self::$detector = $detector;
    }

    public static function redirect( $user_id ) {
        $type   = get_option( 'bml_redirect_type', 'default' );
        $custom = trim( get_option( 'bml_redirect_custom', '' ) );

        if ( 'custom' === $type && ! empty( $custom ) ) {
            wp_safe_redirect( esc_url( $custom ) );
            exit;
        }

        if ( function_exists( 'bp_core_get_user_domain' ) ) {
            $profile_url = bp_core_get_user_domain( $user_id );
            if ( $profile_url ) {
                wp_safe_redirect( $profile_url );
                exit;
            }
        }

        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}
