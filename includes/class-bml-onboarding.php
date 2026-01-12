<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Onboarding {

    protected $detector;

    public function __construct( Detector $detector ) {
        $this->detector = $detector;

        add_shortcode( 'bml_access', [ $this, 'shortcode_access' ] );
        add_shortcode( 'bml_registration', [ $this, 'shortcode_registration' ] );

        add_action( 'template_redirect', [ $this, 'handle_magic_link' ] );
    }

    public function shortcode_access() {
        if ( isset( $_POST['bml_email'] ) && isset( $_POST['bml_nonce'] ) && check_admin_referer( 'bml_access', 'bml_nonce' ) ) {
            $this->handle_access_submit();
        }

        ob_start();
        include BML_DIR . 'templates/access-form.php';
        return ob_get_clean();
    }

    protected function handle_access_submit() {
        $email = isset( $_POST['bml_email'] ) ? sanitize_email( wp_unslash( $_POST['bml_email'] ) ) : '';

        if ( ! is_email( $email ) ) {
            add_filter( 'bml_access_message', function() {
                return __( 'Please enter a valid email address.', 'buddymagiclogin' );
            } );
            return;
        }

        $user = get_user_by( 'email', $email );

        if ( ! $user ) {
            $user_id = wp_insert_user( [
                'user_login' => $email,
                'user_email' => $email,
                'user_pass'  => '',
            ] );

            if ( is_wp_error( $user_id ) ) {
                add_filter( 'bml_access_message', function() use ( $user_id ) {
                    return $user_id->get_error_message();
                } );
                return;
            }

            update_user_meta( $user_id, 'bml_status', 'pending_registration' );
            update_user_meta( $user_id, 'bml_created', time() );
            $user = get_user_by( 'id', $user_id );
        }

        $this->send_magic_link( $user->ID );

        add_filter( 'bml_access_message', function() {
            return __( 'Check your email for the magic login link.', 'buddymagiclogin' );
        } );
    }

    protected function send_magic_link( $user_id ) {
        $user = get_user_by( 'id', $user_id );
        if ( ! $user ) {
            return;
        }

        $token   = wp_generate_password( 32, false );
        $expires = time() + HOUR_IN_SECONDS;

        update_user_meta( $user_id, 'bml_token', $token );
        update_user_meta( $user_id, 'bml_expires', $expires );

        $access_page = (int) get_option( 'bml_access_page', 0 );
        $base_url    = $access_page ? get_permalink( $access_page ) : home_url( '/' );

        $magic_url = add_query_arg(
            [
                'bml_token' => $token,
                'bml_uid'   => $user_id,
            ],
            $base_url
        );

        $subject = __( 'Your magic login link', 'buddymagiclogin' );
        $message = sprintf(
            __( "Click this link to log in:\n\n%s\n\nThis link will expire in 60 minutes.", 'buddymagiclogin' ),
            esc_url( $magic_url )
        );

        wp_mail( $user->user_email, $subject, $message );
    }

    public function handle_magic_link() {
        if ( empty( $_GET['bml_token'] ) || empty( $_GET['bml_uid'] ) ) {
            return;
        }

        $user_id = absint( $_GET['bml_uid'] );
        $token   = sanitize_text_field( wp_unslash( $_GET['bml_token'] ) );

        $saved_token   = get_user_meta( $user_id, 'bml_token', true );
        $saved_expires = (int) get_user_meta( $user_id, 'bml_expires', true );

        if ( empty( $saved_token ) || ! hash_equals( $saved_token, $token ) || time() > $saved_expires ) {
            wp_die(
                esc_html__( 'Invalid or expired magic link.', 'buddymagiclogin' ),
                esc_html__( 'Magic link error', 'buddymagiclogin' ),
                [ 'back_link' => true ]
            );
        }

        $status = get_user_meta( $user_id, 'bml_status', true );
        if ( 'pending_payment' === $status ) {
            $started = (int) get_user_meta( $user_id, 'bml_payment_started', true );
            $timeout = (int) get_option( 'bml_payment_timeout', 60 );
            $expires = $started + ( $timeout * MINUTE_IN_SECONDS );

            if ( $started && time() > $expires ) {
                wp_delete_user( $user_id );

                wp_die(
                    esc_html__( 'Tempo scaduto per il pagamento. Ricomincia.', 'buddymagiclogin' ),
                    esc_html__( 'Pagamento scaduto', 'buddymagiclogin' ),
                    [ 'back_link' => true ]
                );
            }
        }

        wp_set_auth_cookie( $user_id, true );
        wp_set_current_user( $user_id );

        if ( 'pending_registration' === $status ) {
            $reg_page = (int) get_option( 'bml_registration_page', 0 );
            if ( $reg_page ) {
                wp_safe_redirect( get_permalink( $reg_page ) );
                exit;
            }
        }

        Integration::redirect( $user_id );
        exit;
    }

    public function shortcode_registration() {
        if ( ! is_user_logged_in() ) {
            return '<p>' . esc_html__( 'Use the magic login link from your email to access this page.', 'buddymagiclogin' ) . '</p>';
        }

        $user_id = get_current_user_id();

        if ( isset( $_POST['bml_registration_submit'] ) && isset( $_POST['bml_nonce'] ) && check_admin_referer( 'bml_registration', 'bml_nonce' ) ) {
            $this->handle_registration_submit( $user_id );
        }

        ob_start();
        include BML_DIR . 'templates/registration-form.php';
        return ob_get_clean();
    }

    protected function handle_registration_submit( $user_id ) {
        $enable_payment = (int) get_option( 'bml_payment_enable', 0 );

        if ( $enable_payment ) {
            update_user_meta( $user_id, 'bml_status', 'pending_payment' );
            update_user_meta( $user_id, 'bml_payment_started', time() );

            $payment_page = (int) get_option( 'bml_payment_page', 0 );
            if ( $payment_page ) {
                wp_safe_redirect( get_permalink( $payment_page ) );
                exit;
            }

            update_user_meta( $user_id, 'bml_status', 'completed' );
            Integration::redirect( $user_id );
            exit;
        } else {
            update_user_meta( $user_id, 'bml_status', 'completed' );
            Integration::redirect( $user_id );
            exit;
        }
    }
}
