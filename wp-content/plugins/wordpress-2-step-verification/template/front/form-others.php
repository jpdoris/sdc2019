<?php
/**
 * @var Wp2sv_Handler $this
 */
?>
<div class="form other-methods">
<ol id="possible-methods">
    <?php if($this->havePhone()){?>
    <li>
        <form method="POST">
            <input type="hidden" name="wp2sv_type" value="phone">
            <button type="submit"><img src="<?php echo wp2sv_assets('images/authenticator.png'); ?>"><span class="btn-text">
                    <span class="text"><?php _e('Get a verification code from the <b>Google Authenticator</b> app','wordpress-2-step-verification');?></span>
                </span></button>
        </form>
    </li>
    <?php }else{?>

    <?php }?>
    <?php
        foreach ($this->getEmails() as $email){
    ?>
    <li>
        <form method="post">
            <input type="hidden" name="wp2sv_type" value="email">
            <input type="hidden" name="wp2sv_action" value="send-email">
            <input type="hidden" name="wp2sv_email" value="<?php echo $email['id'];?>">
            <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
            <button type="submit"><img src="<?php echo wp2sv_assets('images/email.png'); ?>"><span class="btn-text">
                    <?php printf(__('Get an email with a verification code at %s','wordpress-2-step-verification'),$email['ending']);?>
                </span></button>
        </form>
    </li>
        <?php }?>
    <?php if($this->haveBackupCodes()){?>
    <li>
        <form method="post">
            <input type="hidden" name="wp2sv_type" value="backup-codes">
            <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
            <button type="submit"><img src="<?php echo wp2sv_assets('images/backup.png'); ?>"><span class="btn-text">
                    <?php _e('Use backup code', 'wordpress-2-step-verification'); ?>
                </span></button>
        </form>
    </li>
    <?php }?>
    <?php if($this->canRecovery()){?>
        <li class="recovery">
            <form method="post">
                <input type="hidden" name="wp2sv_type" value="recovery">
                <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
                <button type="submit"><img src="<?php echo wp2sv_assets('images/manualrecovery.png'); ?>"><span class="btn-text">
                    <?php _e('Manual recovery', 'wordpress-2-step-verification'); ?>
                        <em class="help-block"><?php _e('You will need ftp access to upload verification file', 'wordpress-2-step-verification'); ?></em>
                </span></button>
            </form>
        </li>
    <?php }?>
</ol>

</div>