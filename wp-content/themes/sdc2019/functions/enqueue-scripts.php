<?php
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Enqueue Scripts And Styles:
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

add_action( 'wp_enqueue_scripts', function () {
  if (!is_admin()) {

    wp_enqueue_style( 'main-style', get_stylesheet_directory_uri() . '/css/main.css', array(), '1.0.0', 'all' );

    wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '1.0.0', true );
    wp_localize_script( 'main-js', 'ajax_object', ['ajax_url' => admin_url('admin-ajax.php')] );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }
  }
}, 999 );
