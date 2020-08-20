<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Helper Functions:
// - cu_pagination()
// - partial()
// - cu_wp_query_starter()
// - cu_get_social_links()
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/**
 * Pagination starter code.
 */
function cu_pagination() {
  global $wp_query;
  if ( $wp_query->max_num_pages <= 1 ) return;
  ?>
  <nav class="pagination-links">
    <?php
    $bignum = 999999999; // need an unlikely integer
    echo paginate_links( array(
      'base'       => str_replace( $bignum, '%#%', esc_url( get_pagenum_link( $bignum ) ) ),
      'format'     => '?paged=%#%', //or ''
      'current' => max( 1, get_query_var('paged') ),
      // 'current'    => max( 1, $paged ),
      'total'      => $wp_query->max_num_pages,
      'mid_size'   => 3,
      'end_size'   => 3,
      'type'       => 'plain', //or list
      'prev_text'  => '&larr;',
      'next_text'  => '&rarr;'
    ) );
    ?>
  </nav>
  <?php
}

/**
 * Include template partial and set up content data
 */
function partial($file, $c = null) {
  global $content_partial;
  $content_partial = $c;
  require get_template_directory() . '/partials/' . $file . '.php';
}

/**
 * WP_Query Starter Template.
 */
function cu_wp_query_starter() {
  // The Query
  $the_query = new WP_Query(array(
    'order' => 'DESC',
    'orderby' => 'post_date',
    'post_type' => 'post',
    'post_status' => 'publish',
    'nopaging' => true,
  ));

  // The Loop
  if ( $the_query->have_posts() ) {
  	echo '<ul>';
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();
  		echo '<li>' . get_the_title() . '</li>';
  	}
  	echo '</ul>';
  	/* Restore original Post Data */
  	wp_reset_postdata();
  } else {
  	// no posts found
  }
}

/**
 * Include template components and set up content data
 */
function component($file, $c = null) {
    global $content;
    $content = $c;
    require get_template_directory() . '/components/' . $file . '.php';
}

/**
 * Add custom body class to specific page(s)
 */
add_filter( 'body_class', 'custom_body_class');
function custom_body_class( $classes ) {
    if ( is_page('samsung-developer-conference-home-launch'))
        $classes[] = 'home-launch';

    return $classes;
}

// Register Custom Navigation Walker
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';


/**
 * **Example** Print social links from an options page.
 */
function cu_get_social_links() {
  $url_instagram = get_field('link_instagram', 'option');
  $url_twitter = get_field('link_twitter', 'option');
  $url_facebook = get_field('link_facebook', 'option');
  $url_pinterest = get_field('link_pinterest', 'option');
  $url_youtube = get_field('link_youtube', 'option');
  ?>
  <div class="social-links">
    <a target="_blank" href="<?php echo $url_instagram; ?>" class="social social-instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
    <a target="_blank" href="<?php echo $url_twitter; ?>" class="social social-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
    <a target="_blank" href="<?php echo $url_facebook; ?>" class="social social-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
    <a target="_blank" href="<?php echo $url_pinterest; ?>" class="social social-pinterest"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
    <a target="_blank" href="<?php echo $url_youtube; ?>" class="social social-youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
  </div>
  <?php
}

/**
 * Get home header symbols.
 * @param  string $file_name
 */
function home_header_symbol($file_name) {
    include get_template_directory() . '/images/symbols-home-header/' . $file_name . '.svg';
}

/**
 * Get home body symbols.
 * @param  string $file_name
 */
function home_body_symbol($file_name) {
    include get_template_directory() . '/images/symbols-home-body/' . $file_name . '.svg';
}

/**
 * Get home body symbols for mobile.
 * @param  string $file_name
 */
function home_body_symbol_sm($file_name) {
    include get_template_directory() . '/images/symbols-home-body-sm/symbol-' . $file_name . '.svg';
}

/*
* Modify TinyMCE editor to remove H1 and add address.
*/
//add_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats' );
//function tiny_mce_remove_unused_formats($init) {
//    // Add block format elements you want to show in dropdown
//    $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Address=address;Pre=pre';
//    return $init;
//}


// ========================================
// Create TinyMCE custom style select boxes
// ========================================

// 1. Create custom style dropdowns:

//if (function_exists('create_custom_style_select'))
//{
//    create_custom_style_select('sdc_styles', 'Customize Text', [
//        [
//            'title' => 'Font Styles',
//            'items' =>
//            [
//                [
//                    'title'    => 'SamsungOne 38pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-38 white',
//                ],
//                [
//                    'title'    => 'SamsungOne 38pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-38 black',
//                ],
//                [
//                    'title'    => 'SamsungOne 38pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-38 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 38pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-38 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 29pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-29 white',
//                ],
//                [
//                    'title'    => 'SamsungOne 29pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-29 black',
//                ],
//                [
//                    'title'    => 'SamsungOne 29pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-29 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 29pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-29 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 22pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-22 white',
//                ],
//                [
//                    'title'    => 'SamsungOne 22pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-22 black',
//                ],
//                [
//                    'title'    => 'SamsungOne 22pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-22 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 22pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-22 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 16pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-16 white',
//                ],
//                [
//                    'title'    => 'SamsungOne 16pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-16 black',
//                ],
//                [
//                    'title'    => 'SamsungOne 16pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-16 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 16pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-16 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 12pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-12 white',
//                ],
//                [
//                    'title'    => 'SamsungOne 12pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-12 black',
//                ],
//                [
//                    'title'    => 'SamsungOne 12pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-12 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungOne 12pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungone size-12 light-blue',
//                ],
//
//                [
//                    'title'    => 'SamsungSharpSans 38pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-38 white',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 38pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-38 black',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 38pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-38 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 38pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-38 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 29pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-29 white',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 29pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-29 black',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 29pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-29 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 29pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-29 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 22pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-22 white',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 22pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-22 black',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 22pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-22 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 22pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-22 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 16pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-16 white',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 16pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-16 black',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 16pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-16 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 16pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-16 light-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 12pt (White)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-12 white',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 12pt (Black)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-12 black',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 12pt (Dark Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-12 dark-blue',
//                ],
//                [
//                    'title'    => 'SamsungSharpSans 12pt (Light Blue)',
//                    'block'    => 'span',
//                    'classes'  => 'samsungsharpsans size-12 light-blue',
//                ],
//            ]
//        ]
//    ]);
//}


// 2. Create available wysiwyg toolbars:

//add_filter('acf/fields/wysiwyg/toolbars', function($toolbars) {
//    // Uncomment to view current format of $toolbars:
////     print_r($toolbars);
//     var_export($toolbars);
//
//    return [
//        'Full' => [
//            1 => [
//                'formatselect',
//                'bold',
//                'italic',
//                'bullist',
//                'numlist',
//                'blockquote',
//                'alignleft',
//                'aligncenter',
//                'alignright',
//                'link',
//                'wp_more',
//                'spellchecker',
//                'fullscreen',
//                'wp_adv'
//            ],
//            2 => [
//                'strikethrough',
//                'hr',
//                'forecolor',
//                'pastetext',
//                'removeformat',
//                'charmap',
//                'outdent',
//                'indent',
//                'undo',
//                'redo',
//                'wp_help',
//                'sdc_styles',
//            ],
//        ],
//        'Minimal' => [
//            1 => [
//                'sdc_styles',
//                'bold',
//                'italic',
//                'underline',
//                'blockquote',
//                'strikethrough',
//                'bullist',
//                'numlist',
//                'alignleft',
//                'aligncenter',
//                'alignright',
//                'undo',
//                'redo',
//                'link',
//                'fullscreen'
//            ]
//        ]
//    ];
//
////    return [
////        'Full' => [
////            // Full list, in original order:
////            1 => [
////                'bold',
////                'italic',
////                'strikethrough',
////                'bullist',
////                'numlist',
////                'blockquote',
////                'alignleft',
////                'aligncenter',
////                'alignright',
////                'link',
////                'unlink',
////                'wp_more',
////                'spellchecker',
////                'wp_fullscreen',
////                'wp_adv',
////                'separator',
////            ],
////            2 => [
////                'formatselect',
////                'sdc_styles',
////                'underline',
////                'forecolor',
////                'pastetext',
////                'pasteword',
////                'removeformat',
////                'charmap',
////                'outdent',
////                'indent',
////                'undo',
////                'redo',
////                'wp_help',
////            ]
////        ],
////        'Minimal' => [
////            1 => [
////                'sdc_styles',
////                'separator',
////                'bold',
////                'italic',
////                'underline',
////                'strikethrough',
////                'separator',
////                'link',
////                'unlink',
////                'separator',
////                'undo',
////                'redo',
////            ]
////        ]
////        // Create others here
////    ];
//});
