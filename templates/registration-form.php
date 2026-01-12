<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_user_logged_in() ) : ?>

    <p><?php esc_html_e( 'Use the magic login link from your email to access this page.', 'buddymagiclogin' ); ?></p>

<?php
    return;
endif;

$user_id = get_current_user_id();
?>

<div class="bml-registration-wrapper">

    <form method="post" class="bml-registration-form">

        <h2><?php esc_html_e( 'Complete registration', 'buddymagiclogin' ); ?></h2>

        <?php
        /**
         * Qui puoi aggiungere campi personalizzati in futuro.
         * Per ora lasciamo la registrazione "vuota" perché la logica
         * è gestita da Onboarding::handle_registration_submit().
         */
        ?>

        <?php wp_nonce_field( 'bml_registration', 'bml_nonce' ); ?>

        <button type="submit" name="bml_registration_submit" class="bml-submit">
            <?php esc_html_e( 'Complete registration', 'buddymagiclogin' ); ?>
        </button>

    </form>

</div>
