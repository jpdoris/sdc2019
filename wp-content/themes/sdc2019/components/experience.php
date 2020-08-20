<?php
$experience = get_field('experience');
if ($experience):
    $button = $experience['cta']['button'] ?? '';
    $mobileButton = $experience['cta']['mobile_button'] ?? '';
    $buttonText = $experience['cta']['text'] ?? '';
    $youtubeVideoID = $experience['youtube_video_id'] ?? '';
    $attr = array(
        'class' => 'img-left-inline',
    );
?>

        <section class="container content-experience">
            <div class="experience-wrapper">
                <h2><?php echo $experience['title']; ?></h2>
                <p><?php echo $experience['description']; ?></p>
                <span class="experience-cta">

                    <button class="experience-cta__btn d-none d-lg-inline-block" type="button" data-toggle="modal" data-target="#videoModal">
                        <img src="<?php echo wp_get_attachment_url($button); ?>" alt="">
                        <span>
                        <?php echo $buttonText; ?>
                        </span>
                    </button>
                    <button class="experience-cta__btn experience-cta__mobileBtn d-lg-none" type="button" data-toggle="modal" data-target="#videoModal">
                        <img src="<?php echo wp_get_attachment_url($mobileButton); ?>">
                        <span>
                        <?php echo $buttonText; ?>
                        </span>
                    </button>

                </span>
            </div>
            <div class="character-man">
                <img src="<?php echo get_template_directory_uri(); ?>/images/character-man.svg" alt="Character Man">
            </div>
        </section>

    </div>
    <div class="clearfix"></div>

    <div class="modal" id="videoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered mx-auto">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" width="100%" height="315" src="https://www.youtube.com/embed/<?php echo $youtubeVideoID;?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
