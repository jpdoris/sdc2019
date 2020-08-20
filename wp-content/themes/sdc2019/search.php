<?php get_header(); ?>

<main class="content-single">
  <div class="container">
    <h1><span>Search Results for</span> <?php echo esc_attr(get_search_query()); ?></h1>
    <?php if (have_posts()):
      while (have_posts()): the_post(); ?>
        <a href="<?php the_permalink() ?>">
          <article id="post-<?php the_ID(); ?>">
            <header class="article-header">
              <h2><?php the_title(); ?></h2>
            </header>
            <section>
              <?php the_excerpt(); ?>
            </section>
          </article>
        </a>
      <?php endwhile; ?>
  		<?php cu_pagination(); ?>
  	<?php else: ?>
			<article class="post-not-found">
				<header class="article-header">
					<h2>Sorry, No Results.</h2>
				</header>
				<section>
					<p>Try your search again.</p>
				</section>
			</article>
  	<?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
