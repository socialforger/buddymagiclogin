<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use function BML\get_access_message;

$message = get_access_message();
?>
<form method="post" class="bml-access-form">
    <?php if ( ! empty( $message ) ) : ?>
        <div class="bml-message"><?php echo esc_html( $message ); ?></div>
    <?php endif; ?>

    <p>
        <label for="bml_email"><?php esc_html_e( 'Email address', 'buddymagiclogin' ); ?></label><br />
        <input type="email" name="bml_email" id="bml_email" required />
    </p>

    <?php wp_nonce_field( 'bml_access', 'bml_nonce' ); ?>

    <p>
        <button type="submit" class="button button-primary">
            <?php esc_html_e( 'Send magic login link', 'buddymagiclogin' ); ?>
        </button>
    </p>
</form>
