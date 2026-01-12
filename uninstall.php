<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$option_keys = [
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

foreach ( $option_keys as $key ) {
    delete_option( $key );
}

$users = get_users( [ 'fields' => 'ID' ] );

foreach ( $users as $user_id ) {
    delete_user_meta( $user_id, 'bml_status' );
    delete_user_meta( $user_id, 'bml_created' );
    delete_user_meta( $user_id, 'bml_token' );
    delete_user_meta( $user_id, 'bml_expires' );
    delete_user_meta( $user_id, 'bml_payment_started' );
}
