<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Login Screen Modifications:
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Enqueue login style sheet.
 */
// add_action( 'login_enqueue_scripts', 'cu_login_css', 10 );
function cu_login_css() {
	wp_enqueue_style( 'cu-login-css', get_template_directory_uri() . '/css/login.css', false );
}

/**
 * Changing the logo link from wordpress.org to your site
 */
add_filter( 'login_headerurl', 'cu_login_url' );
function cu_login_url() { return home_url(); }

/**
 * Changing the alt text on the logo to show your site name
 */
add_filter( 'login_headertext', 'cu_login_title' );
function cu_login_title() { return get_option( 'blogname' ); }
