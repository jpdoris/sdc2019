<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Admin Modifications:
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Disable default dashboard widgets.
 */
add_action('wp_dashboard_setup', 'cu_disable_default_dashboard_widgets');
function cu_disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);       // Right Now Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);       // Quick Press Widget
	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
}

/**
 * Remove admin menu options.
 * FYI - the customizer menu option has been removed in
 * cu_remove_customizer_menu_item()
 */
add_action( 'admin_menu', 'cu_remove_menus' );
function cu_remove_menus(){
  // remove_menu_page( 'index.php' );                  //Dashboard
  // remove_menu_page( 'jetpack' );                    //Jetpack*
  // remove_menu_page( 'edit.php' );                   //Posts
  // remove_menu_page( 'upload.php' );                 //Media
  // remove_menu_page( 'edit.php?post_type=page' );    //Pages
  // remove_menu_page( 'edit-comments.php' );          //Comments
  // remove_menu_page( 'themes.php' );                 //Appearance
  // remove_menu_page( 'plugins.php' );                //Plugins
  // remove_menu_page( 'users.php' );                  //Users
  // remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings
}

/**
 * Custom dashboard widgets.
 * @link https://www.wpbeginner.com/wp-themes/how-to-add-custom-dashboard-widgets-in-wordpress/
 */
add_action( 'wp_dashboard_setup', 'cu_add_custom_dashboard_widgets' );
function cu_add_custom_dashboard_widgets() {
  //
}

/**
 * Enqueue block editor style. (updated for Gutenburg)
 */
// add_action( 'enqueue_block_editor_assets', 'cu_block_editor_styles' );
function cu_block_editor_styles() {
    wp_enqueue_style( 'cu-editor-styles', get_theme_file_uri( '/css/style-editor.css' ), false, '1.0', 'all' );
}

/**
 * Admin left footer text.
 */
add_filter( 'admin_footer_text', 'cu_admin_footer' );
function cu_admin_footer() {
	return '<span id="footer-thankyou">Custom Theme</span>';
}

/**
 * Remove Admin Menu Link to Theme Customizer
 */
add_action( 'admin_menu', 'cu_remove_customizer_menu_item');
function cu_remove_customizer_menu_item() {
  global $submenu;
  if ( isset( $submenu[ 'themes.php' ] ) ) {
    foreach ( $submenu[ 'themes.php' ] as $index => $menu_item ) {
      foreach ($menu_item as $value) {
        if (strpos($value,'customize') !== false) {
          unset( $submenu[ 'themes.php' ][ $index ] );
        }
      }
    }
  }
}
