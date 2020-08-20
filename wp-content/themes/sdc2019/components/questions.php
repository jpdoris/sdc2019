<?php
$cta = get_field('contact_cta');
$ctaText = "";
$buttonText = "";
if ($cta) {
    $ctaText = $cta['cta_text'];
    $buttonText = $cta['button_text'];
}
$form_header_desktop = get_field('contact_form_header_desktop');
$form_header_mobile = get_field('contact_form_header_mobile');
?>

<div class="container mt-xl-5">
    <div class="row">
        <div class="search-wrapper col-sm-12 col-md-7 col-xl-3">
            <div class="row no-gutters">
                <div class="search-box order-xl-last col-12 no-gutters">
                    <form id="faq-search">
                        <input type="text" placeholder="Search topics..." data-list=".grid">
                    </form>
                </div>
                <div class="topics order-xl-first col-12 no-gutters">
                    <h2>FAQ Topics</h2>
                    <ul class="topics-list">
                        <li><a id="a-information" href="#information-anchor">Conference Information</a></li>
                        <li><a id="a-register" href="#register-anchor">Registration & Payment</a></li>
                        <li><a id="a-policies" href="#policies-anchor">Policies</a></li>
                    </ul>
                </div>
            </div>
            <div class="contact-cta">
                <p><?php echo $ctaText; ?></p>
                <button id="a-contact" class="contact-cta__btn" type="button" data-toggle="modal" data-target="#contactModal">
                    <?php echo $buttonText; ?>
                </button>
            </div>
        </div>

        <div class="questions col-sm-12 col-xl-8 ml-xl-4">
            <div class="wrapper">
                <?php if (have_rows('faq_conference_information')): ?>
                    <span class="anchor" id="information-anchor"></span>
                    <div id="conference-information">
                        <h3 class="section-header first-section">Conference Information</h3>
                        <div class="conference-information grid">
                            <?php while (have_rows('faq_conference_information')): the_row(); ?>
                                <div class="grid-sizer"></div>
                                <div class="grid-item">
                                    <div class="question"><?= the_sub_field('faq_question'); ?></div>
                                    <div class="answer"><?= the_sub_field('faq_answer'); ?></div>
                                </div>
                                <hr class="gridrule" />
                            <?php endwhile; ?>
                            <div class="no-results">Sorry, nothing matches your search.</div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (have_rows('faq_registration_payment')): ?>
                    <span class="anchor" id="register-anchor"></span>
                    <div id="register">
                        <h3 class="section-header">Registration & Payment</h3>
                        <div class="register grid">
                            <?php while (have_rows('faq_registration_payment')): the_row(); ?>
                                <div class="grid-sizer"></div>
                                <div class="grid-item">
                                    <div class="question"><?= the_sub_field('faq_question'); ?></div>
                                    <div class="answer"><?= the_sub_field('faq_answer'); ?></div>
                                </div>
                                <hr class="gridrule" />
                            <?php endwhile; ?>
                            <div class="no-results">Sorry, nothing matches your search.</div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (have_rows('faq_policies')): ?>
                    <span class="anchor" id="policies-anchor"></span>
                    <div id="policies">
                        <h3 class="section-header">Policies</h3>
                        <div class="policies grid">
                            <?php while (have_rows('faq_policies')): the_row(); ?>
                                <div class="grid-sizer"></div>
                                <div class="grid-item">
                                    <div class="question"><?= the_sub_field('faq_question'); ?></div>
                                    <div class="answer"><?= the_sub_field('faq_answer'); ?></div>
                                </div>
                                <hr class="gridrule" />
                            <?php endwhile; ?>
                            <div class="no-results">Sorry, nothing matches your search.</div>
                        </div>
                    </div>
                <?php endif; ?>

                <span class="anchor" id="contact-anchor"></span>
                <div class="modal" id="contactModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-xl mx-auto">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="close-desktop d-none d-xl-inline"><img src="<?php echo get_template_directory_uri(); ?>/images/modal-close.svg"></span>
                                    <span aria-hidden="true" class="close-mobile d-inline d-xl-none"><</span>
                                </button>
                                <h3 class="d-none d-xl-block"><?php echo $form_header_desktop; ?></h3>
                                <h3 class="d-block d-xl-none"><?php echo $form_header_mobile; ?></h3>
                                <?= do_shortcode('[contact-form-7 id="189" title="Contact Form"]'); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>