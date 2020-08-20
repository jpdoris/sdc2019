<?php

use AventriEventSync\Aventri;

?>
<div class="wrap">
    <form action="options.php" method="post">
        <?php
        settings_fields(Aventri::KEY);
        do_settings_sections(Aventri::KEY);
        ?>
        <?php submit_button() ?>
    </form>
</div>
<hr>
<h2>
    <?php _e('Manual Update') ?>
</h2>
<p>
    <?php _e('If you do not want to wait for the automatic synchronization, '
        . 'you can trigger it manually via the button below.') ?>
    <br>
    <?php _e('It may take a while, so you will have to wait some time, before it finishes.') ?>
</p>
<a class="button button-hero" href="<?php menu_page_url('aventri_manual_update') ?>" target="_blank">
    <?php _e('Trigger update') ?>
</a>
