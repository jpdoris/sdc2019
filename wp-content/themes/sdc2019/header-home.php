<?php get_template_part('head'); ?>

<header class="main-header header-home-launch">
  <div class="early-bird-banner">
      <span class="lg">Learn, network, and get hands-on tech time<span class="dash d-none d-md-inline">—</span><br class="d-md-none"></span>
      <span class="sm">register before Sept 27 for early bird pricing</span>
  </div>
  <nav class="navbar navbar-expand-xl" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand mr-0">
          <img src="<?php echo get_template_directory_uri(); ?>/images/logo-white-sm-cropped.png" alt="Samsung Developer Conference 2019">
      </a>
        <?php
        $postID = get_option( 'page_on_front' );
        $registerBtn = get_field('sdc2019_register_button', $postID);
        wp_nav_menu(array(
            'theme_location'  => 'main-menu', // where it's located in the theme
            'container'       => 'div', // remove nav container
            'container_id'    => 'navbarContent',
            'container_class' => 'collapse navbar-collapse',
            'menu'            => __( 'The Main Menu', 'cu_textdomain' ), // nav name
            'menu_class'      => 'navbar-nav', // adding custom nav class  // mr-auto
            'before'          => '', // before the menu
            'after'           => '', // after the menu
            'link_before'     => '', // before each link
            'link_after'      => '', // after each link
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Navwalker(),
        ));
        ?>
      <div id="register-button-container" class="">
          <a href="<?php echo $registerBtn['link']; ?>" id="register-btn" class="btn" target="_blank"><?php echo $registerBtn['text']; ?></a>
      </div>
  </nav>
</header>
