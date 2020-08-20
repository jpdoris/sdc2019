=== Rublon Two-Factor Authentication (2FA) ===
Contributors: rublon
Tags: two-factor authentication, login, password, two-factor, authentication, security, login verification, two-step verification, 2-step verification, two-step authentication, 2-step authentication, 2-factor authentication, 2-factor, 2FA, wordpress security, mobile, mobile phone, cell phone, smartphone, login protection, qr code, admin, javascript, plugin, multi-factor authentication, MFA, login approval, two-factor verification, Zwei-Faktor-Authentifizierung, dwuskładnikowe uwierzytelnianie, dwuskładnikowe logowanie, logowanie, uwierzytelnianie
Requires at least: 5.0
Tested up to: 5.2.1
Stable tag: 4.0
Requires PHP: 5.5.1
License: GNU Public License, version 2
License URI: http://opensource.org/licenses/gpl-license.php

Instant account security with effortless, email-based two-factor authentication; optional mobile app for more security; no tokens.

== Description ==

= REGAIN CONTROL OF YOUR COMPANY! =
- Account security for all employees
- No configuration or training needed

> #### Recommended by Security Experts and Industry Professionals
> *"I'm impressed!" &mdash; Tony Perez, [Sucuri](http://sucuri.net)* <br>
> *"Thanks to Rublon, two-factor authentication is now easy and we have no problem convincing our customers to use two-factor authentication." &mdash; Robert Abela, [WP White Security](https://www.wpwhitesecurity.com/)* <br>
> *"The easiest and most secure 2-factor auth" &mdash; Max Monty* <br>
> *"Absolutely essential for all WordPress installs" &mdash; Chuck Lasker* <br>
> *"A little marvel of a plugin and authentication system" &mdash; Álvaro Degives Más* <br>
> *"The best 2-factor authentication solution for WordPress" &mdash; rain3r.walt3r* <br>
> *"Two-factor authentication for our thousands of customers" &mdash; Steve Truman, [a3rev](http://a3rev.com)* <br>
> [Read more](https://rublon.com/)

= In What Languages Is Rublon Available? =
- English
- German
- Japanese (translated by [Digital Cube](https://en.digitalcube.jp))
- Turkish (translated by Mehmet Emre Baş, proofread by Tarık Çayır)
- Polish

> #### Follow Us
> [Facebook](https://www.facebook.com/RublonApp) | [LinkedIn](https://www.linkedin.com/company/2772205) | [Twitter](https://twitter.com/rublon)

== Installation ==

1. Log in to your WordPress administration panel using an administrator account.
2. Go to "Plugins" -> "Add New" and search for "Rublon" using the plugins search box.
3. Click the "Install Now" button inside the Rublon plugin box in the search results and confirm the installation.
4. Click on "Activate Plugin".
5. Go to Rublon plugin settings and enter System Token and Secret Key of your application from [Admin Console](https://admin.rublon.net/) website.
6. During your next login, confirm your identity via an email link Rublon sends you.
7. Optional: For more security and control, install the Rublon mobile app onto your phone (available for [Android](https://play.google.com/store/apps/details?id=com.rublon.android), [iOS](https://itunes.apple.com/us/app/rublon-authenticator/id1434412791)).

= Server requirements =
- PHP version 5.5.1 or greater
- cURL PHP extension enabled

== Frequently Asked Questions ==

= Why Do I Need Two-Factor Authentication? =
Botnets carry out brute force attacks against thousands of WordPress sites and blogs every day, regardless of size. Once inside, botnets infect your visitors with malware. A compromised website leads to delisting by search engines or blocking by your hosting provider. Rublon Account Security prevents such attacks.

= Why are Passwords Not Enough? =
Many people use a simple, easy-to-guess password. It can be easily stolen when they use multiple devices; the same password across multiple services; or on unsecured connections, such as public Wi-Fi networks. Botnets hammer at your WordPress site trying to compromise it using millions of common passwords and character combinations.

= How Does Rublon Work? =
During the first login, confirm your identity by clicking on the link you’ll receive via email. Your next login from the same device will only require your WordPress password. For additional security, you can install the Rublon mobile app, which allows to use few others authentication methods, e.g. scans a Rublon Code to confirm your identity.

= Why Should I Use Rublon? =
Rublon is simple and easy. Activate the plugin and you're done. Your users don't need to install or configure anything and don’t need training or one-time codes. Once they confirm their identity on a device, they can log in to all web services by only entering their WordPress password.

= How is Rublon Different? =
Traditional two-factor authentication solutions demand users enter a one-time password each time they want to login. That’s why people don’t like them. Rublon is different. With Rublon, you confirm your identity by simply clicking on a link or using one of selected of authentication methods via Rublon mobile app.

= What Does Rublon Cost? =
Rublon for WordPress gives you access to the free Rublon Personal Edition, which lets you protect up to 1 account. For more accounts, sign up to [Admin Console](https://admin.rublon.net/) and select a Paid Plan.

= How can I protect my account with Rublon? =
Simply install the Rublon for WordPress plugin and activate it. After activation, your administrator account will be instantly protected with email-based two-factor authentication. In order to protect more accounts, please switch Rublon to paid subscription by contacting <hello@rublon.com>.

= I want more than email-based, two-factor authentication. Does Rublon support phone-based, out-of-band two-factor authentication? =
Yes! Just install the Rublon mobile app onto your phone (available for Android and iOS). After entering your WordPress login credentials, you will be prompted to verify your account in one of the following ways:<br>
* enter the TOTP code (*Time-Based One Time Password*)
* scan a QR code
* confirm transaction by using push notification
* copy the verification code from SMS sent to your mobile number

= Do all my users have to be protected by Rublon? =
Plugin activation instantly protects your administrator account. Users who install the Rublon mobile app will have additional protection regardless of the setting you’ve selected. Please keep in mind that you need have paid subscription in order to protect more than 1 account per website.

= Will my login credentials be known to Rublon? =
No. Rublon never knows your credentials or those of your users.  They are never transmitted to our servers. Rublon does its work in the background only after WordPress verifies your password. It's an independent security layer that sits beneath the login form.

= Why is using the Rublon mobile app more secure than email-based authentication? =
The Rublon mobile app holds your digital identity with your private encryption key, which never leaves your phone. With any action requiring the mobile app, such as confirming your identity, the Rublon app generates an unique encrypted digital signature. Gaining access to an email account without two-factor authentication is easier than stealing your private key from your phone and reusing it.

== Screenshots ==

1. Rublon Two-Factor Authentication in progress
2. Select authentication method
3. Identity confirmation via email
4. Identity confirmation via Mobile App and QR code
5. Identity confirmation via Mobile App and push notification
6. Identity confirmation via Mobile App and TOTP
7. Identity confirmation via Mobile App and SMS verification code
8. Decide whether this is your trusted device or not
9. Identity has been successfully confirmed
10. Connect Rublon Plugin with your application in the Admin Console using System Token and Secret Key

== Upgrade notice ==

After successful plugin installation, Rublon can be activated with your application in the Rublon tab of the Administration Panel with your Admin Console panel using System Token and Secret Key.

== Legal notice ==

I have read and agree to the [Terms of use](https://core.rublon.net/terms_of_use) and [Privacy Policy](https://core.rublon.net/privacy_policy) before installing the Rublon WordPress Plugin.

== Changelog ==

= 4.0.0 =
* Rublon Core Systems update
* New plugin activation process

= 3.2.12 =
* Added message regarding upcoming changes in Rublon plugin and Rublon API service

= 3.2.11 =
* Fixed authentication process for multisite configuration

= 3.2.10 =
* Fixed the return URL which is sent during the authentication process on multisite installation

= 3.2.9 =
* Removed deprecated method
* Rublon core libraries update

= 3.2.8 =
* Added compatibility with Peter's Login Redirect plugin
* Fixed issue with missing method wp_destroy_current_session for WordPress version < 4.0
* Added monochromatic Rublon icon

= 3.2.7 =
* Improved error handling
* Rublon core libraries update

= 3.2.6 =
* Optimized temporary data cleaning
* Rublon core libraries update

= 3.2.5 =
* Fixed issues with coexistence with a membership plugin
* Rublon core libraries update

= 3.2.4 =
* Fixed disabling/enabling XML-RPC which caused problems with using WordPress mobile app
* Rublon Badge updated
* Plugin name changed to "Rublon Two-Factor Authentication"
* Rublon core libraries update

= 3.2.3 =
* Translations updated
* Rublon core libraries update

== Upgrade notice ==

After a successful installation, the plugin can be updated automatically in the "Plugins" section of the Administation Panel.