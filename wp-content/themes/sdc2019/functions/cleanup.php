<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Clean Up Mods:
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Don't show admin bar on the front end.
 */
show_admin_bar(false);

/**
 * Remove unnecessary scripts/styles/other stuff from the head tag.
 * https://www.isitwp.com/remove-code-wordpress-header/
 */
add_action( 'init', 'cu_head_cleanup' );
function cu_head_cleanup() {
  // EditURI link
  remove_action('wp_head', 'rsd_link');
  // Windows live writer.
  remove_action('wp_head', 'wlwmanifest_link');
  // Previous link.
  remove_action('wp_head', 'parent_post_rel_link', 10, 0);
  // Start link.
  remove_action('wp_head', 'start_post_rel_link', 10, 0);
  // Links for adjacent posts.
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  // E.g. <link rel='index' title='Main Page' href='http://www.mysite.com' />
  remove_action('wp_head', 'index_rel_link');
  // WP version.
  remove_action('wp_head', 'wp_generator');
	// Category feeds.
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// Post and comment feeds.
	// remove_action( 'wp_head', 'feed_links', 2 );
	// Emoji scripts / styles.
  // remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  // remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  // remove_action( 'wp_print_styles', 'print_emoji_styles' );
  // remove_action( 'admin_print_styles', 'print_emoji_styles' );
  // Remove WP version from css.
  // add_filter('style_loader_src', 'cu_remove_wp_ver_css_js', 9999);
  // Remove Wp version from scripts.
  // add_filter('script_loader_src', 'cu_remove_wp_ver_css_js', 9999);
}

/**
 * Hide WordPress version number from the RSS feeds.
 */
add_filter( 'the_generator', function(){return '';} );

/**
 * Remove default styles for the Recent Comments widget.
 * Otherwise outputs <style> tag with !important css.
 */
add_filter( 'show_recent_comments_widget_style', '__return_false', 99 );

/**
 * Remove injected CSS from recent comments widget.
 */
add_filter( 'wp_head', function() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
  }
}, 1 );

/**
 * Remove injected CSS from gallery.
 */
add_filter( 'gallery_style', function($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}, 1 );

/**
 * Remove WP version from scripts.
 */
function cu_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
