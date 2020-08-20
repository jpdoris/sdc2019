<?php if( get_field('display_featured_speakers') && have_rows('featured_speakers') ): ?>
    <section class="content-featured-speakers">
        <h2>2018 Speakers</h2>
        <div class="slick-speakers-outer">
            <div class="slick-speakers">
                <?php
                while ( have_rows('featured_speakers') ) : the_row();
                    $image = get_sub_field('image');
                    $speakername = get_sub_field('speaker_name');
                    $description = get_sub_field('description');
                    ?>
                    <div class="speakers-slide">
                        <div class="img-outer">
                            <img src="<?= $image['url']; ?>" alt="<?= $image['alt'] ?>">
                        </div>
                        <div class="speaker-info">
                            <h3><?= $speakername; ?></h3>
                            <p><?= $description; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <h4>Check back soon for this year's featured speakers.</h4>
        </div>
    </section>
<?php endif; ?>
