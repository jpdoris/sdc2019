<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Theme Support:
// Add or remove theme support for certain functionality specified
// by WP.
// Check here for more info:
// https://developer.wordpress.org/reference/functions/add_theme_support/
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

add_action( 'after_setup_theme', 'cu_add_theme_support' );
function cu_add_theme_support() {

	// Add WP Thumbnail Support
	// add_theme_support( 'post-thumbnails', array( 'post' ) ); // Posts only

	// Default thumbnail size
	// set_post_thumbnail_size(125, 125, true);

	// Add RSS Support
	add_theme_support( 'automatic-feed-links' );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   *
   * For implementation, use the following filters (check in theme-mods.php):
   * pre_get_document_title
   * document_title_parts
   * document_title_separator
   */
	add_theme_support( 'title-tag' );

	// Add HTML5 Support
	add_theme_support( 'html5',
    array(
      'comment-list',
      'comment-form',
      'search-form',
      'gallery',
      'caption',
    )
	);

	// add_theme_support( 'custom-logo', array(
	// 	'height'      => 100,
	// 	'width'       => 400,
	// 	'flex-height' => true,
	// 	'flex-width'  => true,
	// 	'header-text' => array( 'site-title', 'site-description' ),
	// ) );

  // $custom_header_defaults = array(
  // 	'default-image'          => '',
  // 	'width'                  => 0,
  // 	'height'                 => 0,
  // 	'flex-height'            => false,
  // 	'flex-width'             => false,
  // 	'uploads'                => true,
  // 	'random-default'         => false,
  // 	'header-text'            => true,
  // 	'default-text-color'     => '',
  // 	'wp-head-callback'       => '',
  // 	'admin-head-callback'    => '',
  // 	'admin-preview-callback' => '',
  // );
  // add_theme_support( 'custom-header', $custom_header_defaults );

  // wp custom background
	// add_theme_support( 'custom-background',
	//     array(
	//     'default-image' => '',    // background image default
	//     'default-color' => '',    // background color default (dont add the #)
	//     'wp-head-callback' => '_custom_background_cb',
	//     'admin-head-callback' => '',
	//     'admin-preview-callback' => ''
	//     )
	// );

	// Adding post format support
	// https://codex.wordpress.org/Post_Formats
	// add_theme_support( 'post-formats',
	// 	array(
	// 		'aside',             // title less blurb
	// 		'gallery',           // gallery of images
	// 		'link',              // quick link to other site
	// 		'image',             // an image
	// 		'quote',             // a quick quote
	// 		'status',            // a Facebook like status update
	// 		'video',             // video
	// 		'audio',             // audio
	// 		'chat'               // chat transcript
	// 	)
	// );

  // https://make.wordpress.org/core/2016/03/22/implementing-selective-refresh-support-for-widgets/
  // add_theme_support( 'customize-selective-refresh-widgets' );
}
