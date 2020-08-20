=== Apocalypse Meow ===
Contributors: blobfolio
Donate link: https://blobfolio.com/plugin/apocalypse-meow/
Tags: login security, wordpress security, security plugin, brute-force, security, opsec, passwords, sessions, secure, malware, antivirus, block hackers, exploit, infection, protection, spam
Requires at least: 4.4
Tested up to: 5.2
Requires PHP: 7.1
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net

A simple, light-weight collection of tools to harden WordPress security and help mitigate common types of attacks.

== Description ==

Apocalypse Meow's main focus is addressing WordPress security issues related to user accounts and logins. This includes things like:

 * Brute-force login-in protection;
 * Customizable password strength requirements;
 * XML-RPC access controls;
 * Account access alerts;
 * Searchable access logs (including failed login attempts and temporary bans);
 * User enumeration prevention;
 * Registration SPAM protection;
 * Miscellaneous Core and template options to make targetted hacks more difficult;

Security is an admittedly technical subject, but Apocalypse Meow strives to help educate "normal" users about the nature of common web attacks, mitigation techniques, etc. Every option contains detailed explanations and links to external resources with additional information.

Knowledge is power!

For the *less* normal among us — system administrators, developers, and other IT professionals — there is also a [Premium Version](https://blobfolio.com/plugin/apocalypse-meow/), packed with administrative tools, data visualizations and export functionality, and complete [WP-CLI](https://wp-cli.org/) integration for those with nerdier workflows.

== Requirements ==

Due to the advanced nature of some of the plugin features, there are a few additional server requirements beyond what WordPress itself requires:

 * WordPress 4.4+.
 * PHP 7.1 or later.
 * PHP extensions: (bcmath or gmp), date, filter, json, pcre.
 * `CREATE` and `DROP` MySQL grants.
 * Single-site Installs (i.e. Multi-Site is not supported).

Please note: it is **not safe** to run WordPress atop a version of PHP that has reached its [End of Life](http://php.net/supported-versions.php). Future releases of this plugin might, out of necessity, drop support for old, unmaintained versions of PHP. To ensure you continue to receive plugin updates, bug fixes, and new features, just make sure PHP is kept up-to-date. :)

== Premium Version ==

Apocalypse Meow's proactive security hardening and attack mitigation features are completely **free**, and always will be.

The [Premium Version](https://blobfolio.com/plugin/apocalypse-meow/) is intended for IT professionals like system administrators and developers, who require more control over the data and workflow.

This version comes with a bunch of advanced tools, offering the ability to:

 * Reset passwords site-wide (with or without email notifications);
 * Detect and revoke old passwords hashed with MD5;
 * Rename the dangerous default "admin" and "administrator" usernames;
 * View and revoke individual user sessions;
 * Export login data in CSV format;
 * Backup and restore plugin settings;
 * Access to hooks and filters to interact with the brute-force login operations;
 * Run operations and view data through [WP-CLI](https://wp-cli.org/);

To learn more, visit [blobfolio.com](https://blobfolio.com/plugin/apocalypse-meow/).

== Frequently Asked Questions ==

= Is this plugin compatible with WPMU? =

No, sorry. This plugin may only be installed on single-site WordPress instances.

= How does the Community Pool Blocklist Work? =

The Community Pool is a new opt-in feature that combines attack data from your site with other sites running in pool mode to produce a global blocklist.

In other words, an attack against one becomes an attack against all!

The blocklist data is conservatively filtered using a tiered and weighted ranking system based on activity shared within the past 24 hours. For an IP address to be eligible for community banning, it must be independently reported from multiple sources and have a significant amount of total failures.

Your site's whitelist is always respected. Failures from whitelisted IPs will never be sent to the pool, and if the pool declares a ban for an IP you have whitelisted, your site will not ban it.

For more information, check out the Community Pool settings page.

= How do I unban a user? =

The Login Activity page will show any active bans in the top/right corner. You can click the button corresponding to the victim to remove the ban.

If you accidentally banned yourself and cannot access the backend, you have a few options:

 * Wait until the defined time has elapsed;
 * Login from a different IP address (tip: use your cellphone (via data, not Wifi));
 * Ask a friend to login and pardon you;
 * Temporarily de-activate the plugin by renaming the `apocalypse-meow` plugin folder via FTP;

Remember: you can (and should) whitelist any IP addresses that you commonly log in from. This is done through the Settings pgae.

= Can I see the passwords people tried when logging in? =

Of course not!  Haha.  Apocalypse Meow is here to solve security problems, not create them.  Only usernames and IP addresses are stored.

= Will the brute-force log-in prevention work if my server is behind a proxy? =

As of version 1.5.0, it is now possible to specify an alternative `$_SERVER` variable Apocalypse Meow should use to determine the visitor's "true" IP.  It is important to note, however, that depending on how that environmental variable is populated, the value might be forgeable.  Nonetheless, this should be better than nothing!

= I am seeing "You are running Vue in development mode." in the console? =

This informational message appears on Apocalypse Meow admin pages if your site is running in [WP_DEBUG](https://codex.wordpress.org/WP_DEBUG) mode. This version of [Vue.js](https://vuejs.org/) can provide more useful information for debugging Javascript-related issues.

When `WP_DEBUG` is set to `FALSE` (which should be the case for any production sites), the leaner production version of Vue.js is loaded instead. :)

= Multi-Server Setup =

Apocalypse Meow tracks login history in the database. If your WordPress site is spread across multiple load-balanced servers, they must share access to a master database, or else tracking will only occur on a per-node basis.

== Log Monitoring ==

Some robots are so dumb they'll continue trying to submit credentials even after the login form is replaced, wasting system resources and clogging up the log-in history table.  One way to mitigate this is to use a server-side log-monitoring program like [Fail2Ban](http://www.fail2ban.org/) or [OSSEC](https://ossec.github.io/) to ban users via the firewall.

Apocalypse Meow produces a 403 error when a banned user requests the login form. Your log-monitoring rule should therefore look for repeated 403 responses to `wp-login.php`.  Additionally, some robots are unable to follow redirects; if your login form requires SSL, you should also ban repeated 301/302 responses to catch those fools.

If you have enabled user enumeration protection with the `die()` option, requests for `?author=X` will produce a 400 response code which can be similarly tracked.

== Installation ==

Nothing fancy!  You can use the built-in installer on the Plugins page or extract and upload the `apocalypse-meow` folder to your plugins directory via FTP.

To install this plugin as [Must-Use](https://codex.wordpress.org/Must_Use_Plugins), download, extract, and upload the `apocalypse-meow` folder to your mu-plugins directory via FTP. See the [MU Caveats](https://codex.wordpress.org/Must_Use_Plugins#Caveats) for more information about getting WordPress to load an MU plugin that is in a subfolder.

Please note: MU Plugins are removed from the usual update-checking process, so you will need to handle future updates manually.

== Screenshots ==

1. View and search the login history and manage banned users.
2. All settings include detailed explanations, suggestions, and links to additional resources. Not only will your site be vastly more secure, you'll learn a lot!
3. The Community Pool: the login blocklist can ultimately be extended to include community-reported attack data, vastly increasing the effectiveness of the brute-force login mitigation.
4. Pro: simple but sexy statistics.
5. Pro: a ton of additional security and management tools for system administrators, including an ability to view and revoke individual user sessions.
6. Pro: a full suite of WP-CLI tools, hookable functions and filters to interact with or extend the login protection features, read-only configurations, and detailed documentation covering it all!

== Privacy Policy ==

When active, this plugin retains security logs of every sign-in attempt made to the CMS backend. This information — including the end user's public IP address, username, and the status of his or her attempt — is used to help prevent unauthorized system access and maintain Quality of Service for all site visitors.

This information resides fully on the hosting web site and is not shared with any third parties *unless* the Community Pool feature is enabled, in which case any IP addresses responsible for *attacks against your web site* are periodically shared with [Blobfolio, LLC](https://blobfolio.com/privacy-policy/), the maintainer of the centralized database. If any of those IP addresses are subsequently identified by multiple, independent sources, they will be published to a public blocklist (hosted by Blobfolio).

Data retention is entirely up to the site operator, but by default old records are automatically removed after 90 days.

Please note: Apocalypse Meow **DOES NOT** integrate with any WordPress GDPR "Personal Data" features. (Selective erasure of audit logs would undermine the security mechanisms provided by this plugin. Haha.)

== Changelog ==

= 21.6.2 =
* [Fix] Update handling of `plugins_api()` response to match formatting change.

= 21.6.1 =
* [Change] Disable "Upgrade [Bad Password] at Login" option by default for new installs.

= 21.6.0 =
* [Change] The ability to restrict WP-REST access has been removed from the plugin as thanks to Gutenberg WordPress won't work without it. Haha.
* [Misc] Minor code cleanup and performance improvements.

= 21.5.1 =
* [New] Add privacy policy hook for GDPR compliance.

= 21.5.0 =
* [Fix] Add missing translation string.
* [New] When retroactive password upgrades are enabled, users are now redirected to a streamlined password reset page rather than the general profile landing.
* [Misc] Compatibility notices are now only displayed on the main dashboard and plugins pages.
* [Misc] Future releases of this plugin will require PHP 7.0 or newer.
* [Misc] Various performance optimizations.

== Upgrade Notice ==

= 21.6.2 =
This release includes a small patch for WordPress `5.1` compatibility.

= 21.6.1 =
The "Upgrade [Bad Password] at Login" option now defaults to `FALSE` for new installations.

= 21.6.0 =
This release *removes* the ability to restrict access to the WP-REST API (except for the user enumeration neutering) as thanks to Gutenberg, WordPress will start to break without it. :( Other than that, this release just contains a few minor code cleanups and performance improvements.

= 21.5.1 =
Add privacy policy hook for GDPR compliance.

= 21.5.0 =
This release improves the retroactive password update feature and provides a handful of minor performance improvements.
