<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$access_page       = (int) get_option( 'bml_access_page', 0 );
$registration_page = (int) get_option( 'bml_registration_page', 0 );
$payment_enable    = (int) get_option( 'bml_payment_enable', 0 );
$payment_page      = (int) get_option( 'bml_payment_page', 0 );
$payment_timeout   = (int) get_option( 'bml_payment_timeout', 60 );
$redirect_type     = get_option( 'bml_redirect_type', 'default' );
$redirect_custom   = get_option( 'bml_redirect_custom', '' );
$disable_wp_login  = (int) get_option( 'bml_disable_wp_login', 1 );
$disable_bp_login  = (int) get_option( 'bml_disable_bp_login', 1 );
$disable_pwd_reset = (int) get_option( 'bml_disable_password_reset', 1 );
$disable_pwd_change= (int) get_option( 'bml_disable_password_change', 1 );
$cleanup_enable    = (int) get_option( 'bml_cleanup_enable', 1 );
$cleanup_timeout   = (int) get_option( 'bml_cleanup_timeout', 60 );
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Buddy Magic Login', 'buddymagiclogin' ); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'bml_settings' ); ?>

        <h2><?php esc_html_e( 'Core pages', 'buddymagiclogin' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="bml_access_page"><?php esc_html_e( 'Access Page', 'buddymagiclogin' ); ?></label></th>
                <td>
                    <?php
                    wp_dropdown_pages( [
                        'name'             => 'bml_access_page',
                        'id'               => 'bml_access_page',
                        'selected'         => $access_page,
                        'show_option_none' => __( '-- Select a page --', 'buddymagiclogin' ),
                    ] );
                    ?>
                    <p class="description">
                        <?php esc_html_e( 'Page where users enter their email to receive the magic login link.', 'buddymagiclogin' ); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th><label for="bml_registration_page"><?php esc_html_e( 'Registration Page', 'buddymagiclogin' ); ?></label></th>
                <td>
                    <?php
                    wp_dropdown_pages( [
                        'name'             => 'bml_registration_page',
                        'id'               => 'bml_registration_page',
                        'selected'         => $registration_page,
                        'show_option_none' => __( '-- Select a page --', 'buddymagiclogin' ),
                    ] );
                    ?>
                    <p class="description">
                        <?php esc_html_e( 'Page where users complete their profile after magic login.', 'buddymagiclogin' ); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2><?php esc_html_e( 'Payment', 'buddymagiclogin' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Enable payment step', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_payment_enable" value="1" <?php checked( $payment_enable, 1 ); ?> />
                        <?php esc_html_e( 'Redirect to a payment page after registration.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th><label for="bml_payment_page"><?php esc_html_e( 'Payment Page', 'buddymagiclogin' ); ?></label></th>
                <td>
                    <?php
                    wp_dropdown_pages( [
                        'name'             => 'bml_payment_page',
                        'id'               => 'bml_payment_page',
                        'selected'         => $payment_page,
                        'show_option_none' => __( '-- Select a page --', 'buddymagiclogin' ),
                    ] );
                    ?>
                    <p class="description">
                        <?php esc_html_e( 'Page where your payment plugin checkout lives.', 'buddymagiclogin' ); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th><label for="bml_payment_timeout"><?php esc_html_e( 'Payment timeout (minutes)', 'buddymagiclogin' ); ?></label></th>
                <td>
                    <input type="number" name="bml_payment_timeout" id="bml_payment_timeout"
                           value="<?php echo esc_attr( $payment_timeout ); ?>" min="1" />
                    <p class="description">
                        <?php esc_html_e( 'Time allowed to complete payment before the account is invalidated.', 'buddymagiclogin' ); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2><?php esc_html_e( 'Final redirect', 'buddymagiclogin' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Redirect target', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="radio" name="bml_redirect_type" value="default" <?php checked( $redirect_type, 'default' ); ?> />
                        <?php esc_html_e( 'User profile (BuddyBoss/BuddyPress)', 'buddymagiclogin' ); ?>
                    </label>
                    <br />
                    <label>
                        <input type="radio" name="bml_redirect_type" value="custom" <?php checked( $redirect_type, 'custom' ); ?> />
                        <?php esc_html_e( 'Custom URL', 'buddymagiclogin' ); ?>
                    </label>
                    <br />
                    <input type="text" name="bml_redirect_custom" id="bml_redirect_custom"
                           class="regular-text" value="<?php echo esc_attr( $redirect_custom ); ?>" />
                </td>
            </tr>
        </table>

        <h2><?php esc_html_e( 'Security', 'buddymagiclogin' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Disable WordPress login', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_disable_wp_login" value="1" <?php checked( $disable_wp_login, 1 ); ?> />
                        <?php esc_html_e( 'Redirect wp-login.php attempts to the Access Page.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable BuddyBoss/BuddyPress login', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_disable_bp_login" value="1" <?php checked( $disable_bp_login, 1 ); ?> />
                        <?php esc_html_e( 'Redirect native login/register pages to the Access Page.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable password reset', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_disable_password_reset" value="1" <?php checked( $disable_pwd_reset, 1 ); ?> />
                        <?php esc_html_e( 'Prevent lost/reset password flow.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable change password', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_disable_password_change" value="1" <?php checked( $disable_pwd_change, 1 ); ?> />
                        <?php esc_html_e( 'Hide password fields and block profile password changes.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>
        </table>

        <h2><?php esc_html_e( 'Cleanup', 'buddymagiclogin' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Delete incomplete registrations', 'buddymagiclogin' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="bml_cleanup_enable" value="1" <?php checked( $cleanup_enable, 1 ); ?> />
                        <?php esc_html_e( 'Automatically delete pending accounts after a timeout.', 'buddymagiclogin' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="bml_cleanup_timeout"><?php esc_html_e( 'Cleanup timeout (minutes)', 'buddymagiclogin' ); ?></label></th>
                <td>
                    <input type="number" name="bml_cleanup_timeout" id="bml_cleanup_timeout"
                           value="<?php echo esc_attr( $cleanup_timeout ); ?>" min="1" />
                    <p class="description">
                        <?php esc_html_e( 'Applies to users with pending_registration or pending_payment status.', 'buddymagiclogin' ); ?>
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
