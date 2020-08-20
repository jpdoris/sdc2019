<?php
/**
 * @var string|null $error
 */
?>
<div class="wrap">
    <h2>
        <?php _e('Etouches (Aventri) - Manual Update') ?>
    </h2>
    <p>
        <?php if ($error === null): ?>
            <?php _e('The sessions and speakers were successfully updated!') ?>
        <?php else: ?>
            <?php _e('An error occurred while updating!') ?>
            <br>
            <?php _e('Message') ?>:
            <?= $error ?>
        <?php endif; ?>
    </p>
</div>
