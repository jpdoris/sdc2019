<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Advanced Custom Field Plugin:
// - Add option pages for general/header/footer/404
// - Setup components (flexible content field items)
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Create default option pages.
 * Use ACF Pro to add fields.
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
  acf_add_options_sub_page(array(
    'page_title' 	=> '404 Settings',
    'menu_title'	=> '404',
    'parent_slug'	=> 'theme-general-settings',
  ));
}

/* E.g. Page template using components:
<?php get_header(); ?>
<main class="content-generic<?php add_page_id_class() ?>">
  <?php
  if( have_rows('components') ):
    while ( have_rows('components') ) : the_row();
      get_components();
    endwhile;
  endif;
  ?>
</main>
<?php get_footer(); ?>
*/

/**
 * Get all the components in the [theme]/components/ directory.
 * Then require the file.
 *
 * Components are created using ACF flexible content fields.
 * Create the following folders:
 * [theme]/components
 * [theme]/js/classes/components
 * [theme]/scss/components
 */
function get_components() {
  $dir = get_template_directory() . '/components';
  foreach( array_filter( glob( $dir . '/*.*' ), 'is_file' ) as $file ) {
    require $file;
  }
}

/**
 * Add a class to the main content container
 * that specifies the page using the page id.
 * The slug cannot be used as it changes when the user updates the url.
 *
 * This can be used to add custom styles to a component on a specific page.
 */
function add_page_id_class() {
  global $post;
  echo ' content-page-' . $post->ID;
}

// function test_value_textarea( $value, $post_id, $field )
// {
//   error_log( $value );
//   return $value;
// }
// add_filter('acf/update_value/key=field_5cffd0723c292', 'test_value_textarea', 10, 3);
//

// function my_acf_save_post( $post_id ) {
//     if( $post_id != '1292' || empty($_POST['acf']) ){
//       return;
//     }

//     // Do something with all values.
//     $values = $_POST['acf'];
//     error_log(json_encode($values));
// }

// add_action('acf/save_post', 'my_acf_save_post', 5);


function my_acf_init() {

  acf_update_setting('google_api_key', 'AIzaSyA2ZRvHVXSnt0eD07GS2jJGol7fQDKtTt4');
}

add_action('acf/init', 'my_acf_init');
