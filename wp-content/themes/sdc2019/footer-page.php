<?php get_template_part( 'partials/cookiepolicy' ); ?>

<footer class="footer-home-launch footer-page mt-auto">
    <nav class="navbar navbar-expand-xl" role="navigation">

        <div class="container-fluid">
        <div class="row w-100 no-gutters">
            <div class="col-12 hashtag-menu-col">
                <div class="row align-items-xl-center no-gutters">
                    <div class="hashtag text-left col-12 hashtag-menu-col__hashtag-col">#SDC19</div>
                    <div class="col-12 hashtag-menu-col__menu-col">
                        <?php
                        wp_nav_menu(array(
                            'theme_location'  => 'footer-menu', // where it's located in the theme
                            'container'       => false, // remove nav container
                            // 'container_id'    => '',
                            // 'container_class' => 'col-xl-7',
                            'menu'            => __( 'Footer Menu', 'cu_textdomain' ), // nav name
                            'menu_class'      => 'navbar-nav mx-auto', // adding custom nav class
                            'before'          => '', // before the menu
                            'after'           => '', // after the menu
                            'link_before'     => '', // before each link
                            'link_after'      => '', // after each link
                            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                            'walker'          => new WP_Bootstrap_Navwalker(),
                        ));
                        ?>
                        <div class="">
                            <div class="d-flex">
                                <p id="footer-launch__subscribe-text" class="mr-2">Subscribe for Updates</p>
                                <div id="mc_embed_signup" class="flex-lg-fill">
                                    <form action="https://samsungdevelopers.us11.list-manage.com/subscribe/post?u=498f4566539b5e6a3e0505d7d&amp;id=9db2845c65" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

                                        <div id="mc_embed_signup_scroll" class="d-flex align-items-center">
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_498f4566539b5e6a3e0505d7d_9db2845c65" tabindex="-1" value=""></div>

                                           <div class="mc-field-group flex-fill w-100 text-left">
                                                <input type="email" value="" name="EMAIL" id="mce-EMAIL" placeholder="Enter your email address"  required class="required email w-100">
                                            </div>

                                            <button type="submit" name="submit" value="Subscribe" id="mc_embed_signup__submit" class="ml-3">
                                                <span class="sr-only">Submit</span>
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-xl-3 col-xl-7">
                        <div id="mc_embed_signup__response" class="mt-1">
                            <div id="mc_embed_signup__response__inner">
                                <div class="mc-field-group__email__errors"></div>
                                <div id="mce-responses" class="clear">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $socialLinks = get_field('social_links', 'option');
            ?>
            <div class="social-container col-12 social-container-col">
                <ul id="social-links" class="list-inline">
                    <li><a href="<?php echo $socialLinks['youtube']; ?>" target="_blank"><img class="list-inline-item"
                                         src="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-youtube.png"
                                         srcset="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-youtube.png 24w, <?php echo get_template_directory_uri(); ?>/images/iconmonstr-youtube.png 41w"
                                         sizes="(min-width: 1399px) 41px, 24px"
                                         alt="YouTube"></a></li>
                    <li><a href="<?php echo $socialLinks['facebook']; ?>" target="_blank"><img class="list-inline-item"
                                         src="<?php echo get_template_directory_uri(); ?>/images/mobile-icons8-facebook-filled.png"
                                         srcset="<?php echo get_template_directory_uri(); ?>/images/mobile-icons8-facebook-filled.png 22w, <?php echo get_template_directory_uri(); ?>/images/icons8-facebook-filled.png 37w"
                                         sizes="(min-width: 1399px) 37px, 22px"
                                         alt="Facebook"></a></li>
                    <li><a href="<?php echo $socialLinks['twitter']; ?>" target="_blank"><img class="list-inline-item"
                                         src="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-twitter.png"
                                         srcset="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-twitter.png 23w,<?php echo get_template_directory_uri(); ?>/images/iconmonstr-twitter.png 39w"
                                         sizes="(min-width: 1399px) 39px, 23px"
                                         alt="Twitter"></a></li>
                    <li><a href="<?php echo $socialLinks['instagram']; ?>" target="_blank"><img class="list-inline-item"
                                         src="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-instagram.png"
                                         srcset="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-instagram.png 22w,<?php echo get_template_directory_uri(); ?>/images/iconmonstr-instagram.png 37w"
                                         sizes="(min-width: 1399px) 37px, 22px"
                                         alt="Instagram"></a></li>
                    <li><a href="<?php echo $socialLinks['linkedin']; ?>" target="_blank"><img class="list-inline-item"
                                         src="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-linkedin.png"
                                         srcset="<?php echo get_template_directory_uri(); ?>/images/mobile-iconmonstr-linkedin.png 21w,<?php echo get_template_directory_uri(); ?>/images/iconmonstr-linkedin.png 36w"
                                         sizes="(min-width: 1399px) 36px, 21px"
                                         alt="LinkedIn"></a></li>
                </ul>
            </div>
        </div>
        </div>
    </nav>
</footer>

<?php wp_footer(); ?>
