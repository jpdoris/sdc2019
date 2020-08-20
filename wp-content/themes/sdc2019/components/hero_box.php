<?php
if (get_field('hero_box')):

    $image = get_field('hero_box');
    $attr = array(
        'class' => 'right-col-image',
    );
    ?>

    <div class="hero-wrapper">
        <img class="home-header-symbols-sm" src="<?php echo get_template_directory_uri(); ?>/images/home-header-symbols-sm.png" alt="Header Symbols">
        <?php partial('symbols_home_header'); ?>

        <section class="container content-hero-box">
            <div class="section-hero">
                <?php echo wp_get_attachment_image( $image, 'full', false, $attr ); ?>
            </div>
        </section>

        <div class="clearfix"></div>

<?php endif; ?>
