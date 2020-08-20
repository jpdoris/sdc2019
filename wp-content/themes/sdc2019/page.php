<?php get_header('misc-page'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<main class="content-page">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1 class="font-size-64 font-size-tablet-only-48 font-size-mobile-only-36"><?php the_title(); ?></h1>
        <div class="content-inner">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </div>

</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
