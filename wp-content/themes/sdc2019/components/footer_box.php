<?php
$footerbox = get_field('footer_box');
if ($footerbox): ?>

    <section class="container content-footer-box">
        <div class="footer-box-wrapper">
            <img class="footer-symbols is-left" src="<?php echo get_template_directory_uri(); ?>/images/footer-symbols.left.png" alt="Footer Symbols">
            <h2><?php echo $footerbox['title']; ?></h2>
            <p><strong><?php echo $footerbox['description']; ?></strong></p>
            <p><?php echo $footerbox['pricing']; ?></p>
            <span class="footer-box-button">
                <a class="register-btn" href="<?php echo $footerbox['register_button']['url']; ?>" target="<?php echo $footerbox['register_button']['target']; ?>"><?php echo $footerbox['register_button']['title']; ?></a>
            </span>
            <a class="press" href="<?php echo $footerbox['press_info']['url']; ?>" target="<?php echo $footerbox['press_info']['target']; ?>"><?php echo $footerbox['press_info']['title']; ?></a>
            <img class="footer-symbols is-right" src="<?php echo get_template_directory_uri(); ?>/images/footer-symbols.right.png" alt="Footer Symbols">
        </div>
    </section>

    <div class="clearfix"></div>

<?php endif; ?>
