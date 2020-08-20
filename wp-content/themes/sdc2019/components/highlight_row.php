<?php if( have_rows('highlight_row') ): ?>
    <section class="content-highlight-row">
        <?php partial('symbols_home_body'); ?>
        <div class="container">
            <div class="home-body-symbols-sm is-top">
                <?php home_body_symbol_sm(0); ?>
            </div>
            <?php
            $i = 1;
            while ( have_rows('highlight_row') ) : the_row();
                $image = get_sub_field('image');
                $title = get_sub_field('title');
                $description = get_sub_field('description');
                $align = get_sub_field('alignment');
                ?>
                <div class="highlight-row section-align-<?php echo $align; ?>">
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>">
                    <div class="description">
                        <?php if ($i <= 5): ?>
                            <div class="home-body-symbols-sm">
                                <?php home_body_symbol_sm($i . '-1'); ?>
                                <?php home_body_symbol_sm($i . '-2'); ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $description; ?></p>
                    </div>
                </div>
                <?php
                $i++;
            endwhile; ?>
        </div>
    </section>
<?php endif;
