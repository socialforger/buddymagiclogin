<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bml_render_settings_page() {

    // Pages
    $access_page       = (int) get_option( 'bml_access_page', 0 );
    $registration_page = (int) get_option( 'bml_registration_page', 0 );

    // Payment
    $payment_enable  = (int) get_option( 'bml_payment_enable', 0 );
    $payment_page    = (int) get_option( 'bml_payment_page', 0 );
    $payment_timeout = (int) get_option( 'bml_payment_timeout', 60 );

    // Redirect
    $redirect_target = get_option( 'bml_redirect_target', 'profile' );
    $redirect_custom = get_option( 'bml_redirect_custom', '' );

    // Security
    $disable_wp_login = (int) get_option( 'bml_disable_wp_login', 0 );
    $disable_bp_login = (int) get_option( 'bml_disable_bp_login', 0 );
    $disable_reset    = (int) get_option( 'bml_disable_reset', 0 );
    $disable_change   = (int) get_option( 'bml_disable_change', 0 );

    // Cleanup
    $cleanup_timeout = (int) get_option( 'bml_cleanup_timeout', 60 );

    ?>

    <div class="wrap">
        <h1><?php esc_html_e( 'Buddy Magic Login', 'buddymagiclogin' ); ?></h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'bml_settings' ); ?>

            <h2><?php esc_html_e( 'Core pages', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Access Page', 'buddymagiclogin' ); ?></th>
                    <td>
                        <?php
                        wp_dropdown_pages( [
                            'name'              => 'bml_access_page',
                            'selected'          => $access_page,
                            'show_option_none'  => __( '-- Select a page --', 'buddymagiclogin' ),
                            'option_none_value' => 0,
                        ] );
                        ?>
                        <p class="description">
                            <?php esc_html_e( 'Users enter their email here to receive the magic login link.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Registration Page', 'buddymagiclogin' ); ?></th>
                    <td>
                        <?php
                        wp_dropdown_pages( [
                            'name'              => 'bml_registration_page',
                            'selected'          => $registration_page,
                            'show_option_none'  => __( '-- Select a page --', 'buddymagiclogin' ),
                            'option_none_value' => 0,
                        ] );
                        ?>
                        <p class="description">
                            <?php esc_html_e( 'New users complete their profile here after magic login.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

            </table>

            <h2><?php esc_html_e( 'Shortcode reminder', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Required shortcodes', 'buddymagiclogin' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'Insert the following shortcodes into the selected pages:', 'buddymagiclogin' ); ?></p>

                        <p><strong><?php esc_html_e( 'Place this in the Access Page', 'buddymagiclogin' ); ?>:</strong><br>
                        <code>[bml_access]</code></p>

                        <p><strong><?php esc_html_e( 'Place this in the Registration Page', 'buddymagiclogin' ); ?>:</strong><br>
                        <code>[bml_registration]</code></p>

                        <p class="description">
                            <?php esc_html_e( 'Without these shortcodes, the login and registration flow will not work.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

            </table>

            <h2><?php esc_html_e( 'Payment', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Enable payment step', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="bml_payment_enable" value="1" <?php checked( $payment_enable ); ?>>
                            <?php esc_html_e( 'Redirect users to a payment page after registration.', 'buddymagiclogin' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Payment Page', 'buddymagiclogin' ); ?></th>
                    <td>
                        <?php
                        wp_dropdown_pages( [
                            'name'              => 'bml_payment_page',
                            'selected'          => $payment_page,
                            'show_option_none'  => __( '-- Select a page --', 'buddymagiclogin' ),
                            'option_none_value' => 0,
                        ] );
                        ?>
                        <p class="description">
                            <?php esc_html_e( 'Place your checkout (WooCommerce, PMPro, etc.) on this page.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Payment timeout (minutes)', 'buddymagiclogin' ); ?></th>
                    <td>
                        <input type="number" name="bml_payment_timeout" value="<?php echo esc_attr( $payment_timeout ); ?>" min="1" step="1">
                        <p class="description">
                            <?php esc_html_e( 'After this time, incomplete payment accounts are invalidated.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

            </table>

            <h2><?php esc_html_e( 'Final redirect', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Redirect target', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="radio" name="bml_redirect_target" value="profile" <?php checked( $redirect_target, 'profile' ); ?>>
                            <?php esc_html_e( 'User profile (BuddyBoss/BuddyPress)', 'buddymagiclogin' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="bml_redirect_target" value="custom" <?php checked( $redirect_target, 'custom' ); ?>>
                            <?php esc_html_e( 'Custom URL', 'buddymagiclogin' ); ?>
                        </label>

                        <p>
                            <input type="text" name="bml_redirect_custom" value="<?php echo esc_attr( $redirect_custom ); ?>" class="regular-text">
                        </p>
                    </td>
                </tr>

            </table>

            <h2><?php esc_html_e( 'Security', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable WordPress login', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="bml_disable_wp_login" value="1" <?php checked( $disable_wp_login ); ?>>
                            <?php esc_html_e( 'Redirect wp-login.php attempts to the Access Page.', 'buddymagiclogin' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable BuddyBoss/BuddyPress login', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="bml_disable_bp_login" value="1" <?php checked( $disable_bp_login ); ?>>
                            <?php esc_html_e( 'Redirect native login/register pages to the Access Page.', 'buddymagiclogin' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable password reset', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="bml_disable_reset" value="1" <?php checked( $disable_reset ); ?>>
                            <?php esc_html_e( 'Block the lost password flow.', 'buddymagiclogin' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable change password', 'buddymagiclogin' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="bml_disable_change" value="1" <?php checked( $disable_change ); ?>>
                            <?php esc_html_e( 'Hide password fields in user profile.', 'buddymagiclogin' ); ?>
                        </label>
                    </td>
                </tr>

            </table>

            <h2><?php esc_html_e( 'Cleanup', 'buddymagiclogin' ); ?></h2>
            <table class="form-table">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Delete incomplete registrations', 'buddymagiclogin' ); ?></th>
                    <td>
                        <input type="number" name="bml_cleanup_timeout" value="<?php echo esc_attr( $cleanup_timeout ); ?>" min="1" step="1">
                        <p class="description">
                            <?php esc_html_e( 'Automatically delete pending accounts after a timeout.', 'buddymagiclogin' ); ?>
                        </p>
                        <p class="description">
                            <?php esc_html_e( 'Applies to users with pending_registration or pending_payment status.', 'buddymagiclogin' ); ?>
                        </p>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>

<?php
}
