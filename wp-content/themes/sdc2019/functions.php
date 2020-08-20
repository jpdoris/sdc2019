<?php
// Enqueue scripts and styles.
require_once( 'functions/enqueue-scripts.php' );
// Login screen modifications.
require_once( 'functions/login.php' );
// Register theme support for title tag, etc.
require_once( 'functions/theme-support.php' );
// Register menu locations to be used by Appearance > Menus.
require_once( 'functions/menus.php' );
// Clean up some WP stuff we don't want or need.
require_once( 'functions/cleanup.php' );
// Load helper functions.
require_once( 'functions/helper-functions.php' );
// Theme modifications and text domain translation registration.
require_once( 'functions/theme-mods.php' );
// Comments callback function.
require_once( 'functions/comments.php' );
// Admin functions & modifications.
require_once( 'functions/admin.php' );
// Advanced Custom Field plugin.
require_once( 'functions/acf.php' );
// Register a 'sidebar' or rather, a widgetized area configureable in the admin.
// require_once( 'functions/sidebar.php' );
