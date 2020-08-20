<?php
/*
 Template Name: FAQ
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/

get_header('page');

get_template_part( 'partials/full-width-banner' );
?>

<main class="content-faq">
    <h1><?php echo get_the_title(); ?></h1>
    <?php
    component('search');
    component('topics');
    component('contact_button');
    component('questions');
    ?>
</main>

<?php get_footer('page'); ?>
