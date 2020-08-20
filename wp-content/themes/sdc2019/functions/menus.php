<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Register Menus:
// These registered menus will be available in Appearance > Menus
// where the admin can add items such as posts and pages.
// They can be output in templates using wp_nav_menu. Examples
// can be found in the header and footer by default.
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

add_action( 'after_setup_theme', function() {
  register_nav_menus(
    array(
      'main-menu' => __( 'The Main Menu', 'cu_textdomain' ),
      'footer-menu' => __( 'Footer Menu', 'cu_textdomain' ),
    )
  );
});
