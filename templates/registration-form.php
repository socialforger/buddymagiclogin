<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$enable_payment = (int) get_option( 'bml_payment_enable', 0 );
$timeout        = (int) get_option( 'bml_payment_timeout', 60 );

$button_label = $enable_payment
    ? __( 'Vai al pagamento', 'buddymagiclogin' )
    : __( 'Completa registrazione', 'buddymagiclogin' );
?>
<form method="post" class="bml-registration-form">
    <?php wp_nonce_field( 'bml_registration', 'bml_nonce' ); ?>

    <p>
        <?php esc_html_e( 'Complete your profile and continue.', 'buddymagiclogin' ); ?>
    </p>

    <?php if ( $enable_payment ) : ?>
        <p class="bml-payment-info">
            <?php
            printf(
                esc_html__( 'Hai tempo per pagare: %d minuti.', 'buddymagiclogin' ),
                $timeout
            );
            ?>
        </p>
    <?php endif; ?>

    <p>
        <button type="submit" name="bml_registration_submit" class="button button-primary">
            <?php echo esc_html( $button_label ); ?>
        </button>
    </p>
</form>
