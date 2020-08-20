<?php
$page = get_page_by_path('privacy-policy', OBJECT);
$permalink = get_permalink($page->ID);
?>

<div class="cookie-policy">
        We use cookies to make your experience of our website better. Continuing on our website, you give us your consent to set these cookies. <a href="<?php echo $permalink;?>#cookies" class="text-decoration-underline" id="cookie-policy__link" target="_blank">Find out more</a>

        <button type="button" id="cookie-policy__close-btn"><span class="sr-only">Accept cookie</span></button>
      </div>
