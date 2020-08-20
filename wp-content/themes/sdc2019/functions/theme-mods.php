<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Theme Modifications:
// Add/remove or modify front-end wordpress code by using
// action and filter hooks.
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Show all php errors.
 * Settings WP_DEBUG to true in config should do the trick. But if not try this.
 */
//add_action('init', 'show_php_errors');
function show_php_errors(){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

/**
 * If not using an image size plugin, you can add sizes manually using
 * add_image_size(). Then add the option to the 'Image Sizes' dropdown
 * in the Gutenburg editor when adding/editing and image by using the
 * image_size_names_choose hook.
 *
 * @link https://developer.wordpress.org/reference/functions/add_image_size/
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/image_size_names_choose
 */
// add_image_size( 'custom-100', 100, 0, false );

// add_filter( 'image_size_names_choose', 'cu_add_image_size_to_dropdown' );
function cu_add_image_size_to_dropdown( $sizes ) {
  return array_merge( $sizes, array(
    // '[image size name]' => '[Label]'
    'custom-100' => 'Custom 100',
  ));
}

/**
 * Allow upload of svgs.
 */
add_filter('upload_mimes', 'cu_add_mime_types');
function cu_add_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

/**
 * Loads the theme's translated strings.
 */
add_action( 'after_setup_theme', 'cu_load_translation');
function cu_load_translation() {
  load_theme_textdomain( 'cu_textdomain',  get_template_directory() . '/translation');
}

/**
 * Remove the p tags from around img tags.
 * http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
 */
add_filter( 'the_content', 'cu_remove_p_tags_from_img_tags');
function cu_remove_p_tags_from_img_tags($content) {
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

/**
 * Replace the default read more link that includes […].
 */
add_filter('excerpt_more', 'cu_excerpt_more');
function cu_excerpt_more($more) {
  global $post;
  return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'cu_textdomain' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'cu_textdomain' ) .'</a>';
}

/**
 * Redirect some urls.
 */
// add_action('wp', 'cu_redirect_urls');
function cu_redirect_urls() {
  if ( 'my_post_type' == get_post_type() ) {
    if (!is_admin()) {
      wp_safe_redirect( '/' );
      exit;
    }
  }
}

/**
 * Custom title text.
 * This filter is before WP adds titles so $title is just an empty string.
 * Will take precedence over Yoast so be careful.
 */
// add_filter('pre_get_document_title', 'cu_title_tag', 999, 1);
function cu_title_tag($title) {
  return $title;
}

/**
 * Filters the parts of the document title.
 * @param array $title
 *     The document title parts.
 *     @type string $title   Title of the viewed page.
 *     @type string $page    Optional. Page number if paginated.
 *     @type string $tagline Optional. Site description when on home page.
 *     @type string $site    Optional. Site title when not on home page.
 */
// add_filter('document_title_parts', 'filter_title_part');
function filter_title_part($title) {
  // Change title for singular blog post
  // if( is_singular( 'post' ) ){
  //   $title['title'] = 'EXAMPLE';
  //   $title['page'] = '2'; // optional
  //   $title['tagline'] = 'My tagline'; // optional
  //   $title['site'] = 'mysite.com'; //optional
  // }
  return $title;
}

/**
 * Filter the separator for the document title.
 * @param string $sep Document title separator. Default '-'.
 */
add_filter('document_title_separator', 'filter_title_sep');
function filter_title_sep($sep) {
  $sep = '|';
  return $sep;
}

/**
 * Add rewrite rules for nicer navigation.
 * @link https://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 * Do not forget to flush and regenerate the rewrite rules database after
 * modifying rules. From WordPress Administration Screens, Select
 * Settings -> Permalinks and just click Save Changes without any changes.
 */
//add_action('init', 'cu_rewrite_rules');
function cu_rewrite_rules() {
  // Page ID
  $page_id = 1;

  // Alternate regex ([a-z0-9]+(?:-[a-z0-9]+)*)
  add_rewrite_rule(
    '^my-page/([a-z0-9-]+)/([a-z0-9-]+)/?',
    'index.php?page_id='.$page_id.'&var1=$matches[1]&var2=$matches[2]',
    'top'
  );
}

/**
 * Add custom query variables.
 * Alternative method:
 * @link https://codex.wordpress.org/Rewrite_API/add_rewrite_tag
 */
//add_filter( 'query_vars', 'cu_add_query_var' );
function cu_add_query_var( $vars ) {
  $vars[] = 'var1';
  $vars[] = 'var2';
  return $vars;
}

/**
 * Theme customizer modifications.
 */
add_action( 'customize_register', 'cu_theme_customizer' );
function cu_theme_customizer($wp_customize) {
  // $wp_customize calls go here.

  // Uncomment the below lines to remove the default customize sections
  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');

  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

/**
 * ** Don't use this unless you understand the implications **
 *
 * Using this feature you can set the maximum allowed width for any content in the theme,
 * like oEmbeds and images added to posts.
 *
 * @link https://wycks.wordpress.com/2013/02/14/why-the-content_width-wordpress-global-kinda-sucks/
 */
// if ( ! isset( $content_width ) ) {
// 	$content_width = 680;
// }
//
//
//

function addRewriteRules()
{
  $query = new WP_Query();
  $query = $query->query([
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-schedule.php'
  ]);

  if(!$query) {
    return;
  }

  $schedulePage = $query[0];

  add_rewrite_tag('%session%', '([^&]+)');

  add_rewrite_rule('^'.$schedulePage->post_name.'/([^/]*)/?', 'index.php?page_id='.$schedulePage->ID.'&session=$matches[1]', 'top');
}
add_action('init', 'addRewriteRules');
