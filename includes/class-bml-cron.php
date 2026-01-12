<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Cron {

    public function __construct() {
        add_action( 'bml_cleanup_cron', [ $this, 'cleanup' ] );
    }

    public function cleanup() {
        $enable  = (int) get_option( 'bml_cleanup_enable', 1 );
        $timeout = (int) get_option( 'bml_cleanup_timeout', 60 );

        if ( ! $enable || $timeout <= 0 ) {
            return;
        }

        $cutoff = time() - ( $timeout * MINUTE_IN_SECONDS );

        $users = get_users( [
            'fields'     => 'ID',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key'     => 'bml_status',
                    'value'   => [ 'pending_registration', 'pending_payment' ],
                    'compare' => 'IN',
                ],
                [
                    'key'     => 'bml_created',
                    'value'   => $cutoff,
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ],
            ],
        ] );

        if ( empty( $users ) ) {
            return;
        }

        foreach ( $users as $user_id ) {
            wp_delete_user( $user_id );
        }
    }
}
