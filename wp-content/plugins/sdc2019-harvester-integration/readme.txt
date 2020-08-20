=== Sdc2019 Harvester Integration ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://example.com/
Tags: comments, spam
Requires at least: 4.5
Tested up to: 5.2.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

About:

This custom plugin adds a menu item to the Wordpress admin which allows the user to refresh session/speaker data stored
to disk in a flat file.


Developer Notes:

- sdc2019-harvester-integration.php loads and instantiates inc\main.php.
- inc/main.php registers the menu item and renders the cache refresh page.
- The cache refresh page has a button that triggers an ajax call, via inc/harvester.js, to the HarvesterClient class,
	which handles the API calls and the data storage.

** Tested on a local wordpress install but in a production environment, be aware that it's possible there may be file
	permission issues trying to create the cache file (inc/presentationcache).


Setup:
- Run composer install
- Activate plugin in Wordpress admin
- For temporary debugging purposes, the contents of the data/cache file can be viewed by pointing a browser to:
	/wp-content/plugins/sdc2019-harvester-integration/inc/dump.php
