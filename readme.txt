=== Buddy Magic Login ===
Contributors: socialforger
Donate link: https://github.com/socialforger/
Tags: login, passwordless, magic link, registration, buddypress, buddyboss, onboarding, payment
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Passwordless login, registration and optional payment for Buddypress and Buddyboss.

== Description ==

Buddy Magic Login replaces traditional username/password authentication with a simple, secure, email‑based magic link flow.

It is designed for communities built with:

* **BuddyBoss Platform**
* **BuddyPress**

and works perfectly even if only WordPress is installed.

### 🔑 Key Features

* Passwordless login via **magic link**
* Single **Access Page** for all users
* **Registration Page** after magic login for new users
* Optional **Payment Page** with timeout and automatic cleanup
* Clear user messages
* Final redirect to:
  * BuddyBoss/BuddyPress profile, or
  * custom URL
* Disables:
  * wp-login.php
  * BuddyBoss/BuddyPress login & registration pages
  * password reset
  * password change
* Automatic cleanup of incomplete accounts:
  * `pending_registration`
  * `pending_payment`

### 🧩 Payment Plugins

Buddy Magic Login does **not** process payments.  
You can use any payment plugin:
* WooCommerce
* Paid Memberships Pro
* Restrict Content Pro
* Stripe plugins
* Any checkout page

Just place your checkout on the **Payment Page** and select it in the Settings.

### 🧭 How It Works

1. User enters email on the **Access Page**
2. Receives an email message with  **magic login link**
3. If new → redirected to **Registration Page**
4. If payment enabled → redirected to **Payment Page**
5. After completion → redirected to profile or custom URL

### 🧹 Automatic Cleanup

Users who do not complete registration or payment within the configured timeout are automatically deleted.

== Installation ==

1. Upload the `buddymagiclogin` folder to `/wp-content/plugins/`
2. Activate **Buddy Magic Login** from the Plugins screen
3. Create two pages:
   * **Access Page** → add shortcode: `[bml_access]`
   * **Registration Page** → add shortcode: `[bml_registration]`
4. (Optional) Create a **Payment Page** and place your checkout there
5. Go to **Settings → Buddy Magic Login** and configure:
   * Access Page
   * Registration Page
   * Payment Page (optional)
   * Payment timeout
   * Final redirect
   * Security options
   * Cleanup options

== Frequently Asked Questions ==

= Does this plugin handle payments? =

No. Buddy Magic Login does not process payments.  
Use any payment plugin and set its checkout page as the Payment Page.

= Can users set or reset a password? =

No. This plugin is fully passwordless.  
Password reset and password change are disabled for security and consistency.

= Does it work without BuddyBoss or BuddyPress? =

Yes.  
If no social platform is detected, the final redirect falls back to the homepage.

== Screenshots ==

1. Settings page
2. Access Page (magic link form)
3. Registration Page with payment timeout notice

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release of Buddy Magic Login.
