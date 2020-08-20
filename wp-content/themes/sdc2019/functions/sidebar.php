<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Sidebar:
// A 'sidebar' is a widgetized area. Admins can add widgets to
// sidebars in Appearance > Widgets.
// So technically, a sidebar doesn't need to act like a sidebar.
// There are a bunch of default WP widgets.
// Custom widgets can be created if needed.
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Register a sidebar.
 * To add the sidebar directly in code, use dynamic_sidebar('my-sidebar')
 * or call get_sidebar() to include the sidebar.php template.
 * If multiple sidebars are registered, each can have a specific sidebar
 * template e.g. get_sidebar('secondary') will map to sidebar-secondary.php.
 */
add_action( 'widgets_init', 'cu_register_sidebars' );
function cu_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'cu_textdomain' ),
		'description' => __( 'The first (primary) sidebar.', 'cu_textdomain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
}
