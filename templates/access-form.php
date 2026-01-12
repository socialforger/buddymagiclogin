<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Messaggio dinamico (successo o errore)
$message = apply_filters( 'bml_access_message', '' );
?>

<div class="bml-access-wrapper">

    <?php if ( ! empty( $message ) ) : ?>
        <p class="bml-message"><?php echo esc_html( $message ); ?></p>
    <?php endif; ?>

    <form method="post" class="bml-access-form">

        <label for="bml_email">
            <?php esc_html_e( 'Email address', 'buddymagiclogin' ); ?>
        </label>

        <input
            type="email"
            id="bml_email"
            name="bml_email"
            required
            autocomplete="email"
        />

        <?php wp_nonce_field( 'bml_access', 'bml_nonce' ); ?>

        <button type="submit" class="bml-submit">
            <?php esc_html_e( 'Send', 'buddymagiclogin' ); ?>
        </button>

    </form>

</div>
