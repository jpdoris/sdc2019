<?php
/*
Template Name: Homepage

Usage: Go to the page that you want to be the homepage.
Under Page Attributes > Template, Choose 'Homepage'.

Using this template file will override the settings in
Settings > Reading > 'Your homepage displays'.
*/
?>

<?php get_header(); ?>

<div class="content-home">

    <div class="d-flex flex-column flex-lg-row w-100">
        <div class="d-lg-flex flex-lg-column justify-content-lg-end">
            <div id="graphic-date-location-container" class="container-fluid">
                <div class="d-flex flex-lg-column justify-content-center">
                    <div id="sdc19-graphic--container">
                        <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/SDC19.png" id="sdc19-graphic" alt="SDC19">
                    </div>
                    <div>
                        <div id="date-location-container" class="d-flex flex-column h-100 pl-2 pl-lg-0">
                            <p class="flex-fill"><?php echo the_field('sdc2019_date'); ?></p>
                            <p><?php echo the_field('sdc2019_location'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $videoAnimations = get_field('sdc2019_video_animation'); ?>
        <div class="flex-fill">
            <video muted loop autoplay playsinline class="video-animation video-animation__desktop d-none d-lg-block ml-lg-auto">
                <source src="<?php echo isset($videoAnimations['desktop_animation']) ? $videoAnimations['desktop_animation'] : ''; ?>" type="video/mp4">
            </video>
            <video muted loop autoplay playsinline class="video-animation video-animation__mobile d-block d-lg-none">
                <source src="<?php echo isset($videoAnimations['mobile_animation']) ? $videoAnimations['mobile_animation'] : ''; ?>" type="video/mp4">
            </video>
        </div>
    </div>

    <?php $registerBtn = get_field('sdc2019_register_button'); ?>
    <?php $signUpLink = get_field('sdc2019_sign_up_link'); ?>

    <div class="container-fluid info-container mt-lg-4">
        <!-- align-items-lg-center -->
        <div class="row text-center text-lg-left align-items-lg-center">
            <!-- col-12 col-lg-5 -->
            <div id="presale-container" class="">
                <p id="presale-text" class="mb-lg-0">
                    <?php echo the_field('sdc2019_presale_text'); ?>
                </p>
            </div>
            <!-- col-12 col-lg-3 -->
            <div id="register-button-container" class="">
                <a href="<?php echo $registerBtn['link']; ?>" id="register-btn" class="btn" target="_blank"><?php echo $registerBtn['text']; ?></a>
            </div>
            <!-- col-8 offset-2 offset-lg-0 col-lg-4 -->
            <div id="signup-col">
                <!-- <a href="#" id="signup-link"><?php echo $signUpLink['text']; ?></a>
                <div id="mc_embed_signup" class="d-none mx-auto mx-lg-0">
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
                    <button type="button" id="mc_embed_signup__close_btn">Close</button>
                </div> -->
                <div id="signup-col_inner">
                    <a href="#" id="signup-link"><?php echo $signUpLink['text']; ?></a>
                    <div id="mc_embed_signup" class="d-none mx-auto mx-lg-0">
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
                <div id="signup-col_btn-container">
                    <button type="button" id="mc_embed_signup__close_btn" class="invisible">Close</button>
                </div>
            </div>
        </div>
        <!-- row -->
        <div id="mc_embed_signup__response" class="">
            <!-- col-8 offset-2 col-lg-4 offset-lg-8 -->
            <!-- <div class=""> -->
                <div id="mc_embed_signup__response__inner">
                <div class="mc-field-group__email__errors"></div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>
                    </div>
            <!-- </div> -->
        </div>
    </div>
</div>

<?php get_template_part( 'partials/cookiepolicy' ); ?>

<?php get_footer(); ?>
