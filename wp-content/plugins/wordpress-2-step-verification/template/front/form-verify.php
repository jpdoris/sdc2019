<?php
/**
 * @var Wp2sv_Handler $this
 */
?>
<div class="form">
    <form name="verifyForm" method="post" action="" id="verify-form">
        <input type="hidden" name="wp2sv_nonce" value="<?php echo wp_create_nonce('wp2sv_nonce'); ?>"/>
        <input type="hidden" name="wp2sv_type" id="type" value="<?php echo $this->getReceiveMethod(); ?>"/>
        <input type="hidden" name="wp2sv_action" id="action" value="check-code"/>
        <?php switch($this->getReceiveMethod()){
            case 'phone':
                $img_src=wp2sv_assets('images/authenticator.png');
                break;
            case 'email':
                $img_src=wp2sv_assets('images/email.png');
                break;
            case 'backup-codes':
                $img_src=wp2sv_assets('images/backup.png');
                break;
            default:
                $img_src=wp2sv_assets('images/authenticator.png');

        }?>
        <img class="mobile" src="<?php echo $img_src;?>">

        <div class="title">
            <?php if ($this->getReceiveMethod() == 'backup-codes') {
                _e('Enter your backup code', 'wordpress-2-step-verification');
            } else {
                _e('Enter a verification code', 'wordpress-2-step-verification');
            } ?>
        </div>

        <div class="desc" id="verifyText">
            <?php
            $max_code_length = 6;
            $placeholder = __('Enter the 6-digit code', 'wordpress-2-step-verification');
            if ($this->getReceiveMethod() == 'mobile') {
                _e('Get a verification code from the <strong>Google Authenticator</strong> app', 'wordpress-2-step-verification');
            } elseif (in_array($this->getReceiveMethod(), array('email', 'user_email'))) {
                printf(__('An email with a verification code was just sent to <strong>%s</strong>', 'wordpress-2-step-verification'), $this->getEmailEnding());
            } elseif ($this->getReceiveMethod() == 'backup-codes') {
                $max_code_length = 8;
                $placeholder = __('Enter the 8-digit code', 'wordpress-2-step-verification');
            }
            ?>
        </div>
        <div class="theinput">
            <input type="text" title="Verification codes contain only numbers." pattern="[0-9 ]*" dir="ltr"
                   class="input" placeholder="<?php echo $placeholder; ?>" autocomplete="off" required
                   size="<?= $max_code_length; ?>" value="" id="code"
                   name="wp2sv_code">
        </div>
        <?php if ($this->error_message): ?>
            <span class="error" id="error">
                <?php echo $this->error_message; ?>
            </span>
        <?php endif; ?>
        <input type="submit" class="submit" value="<?php _e('Done', 'wordpress-2-step-verification'); ?>">

        <div class="padded" id="persistent-container">
            <input type="checkbox"<?php checked(true, $this->auth->isTrusted()) ?> value="yes"
                   id="PersistentCookie" name="wp2sv_remember">
            <label for="PersistentCookie">
                <?php _e('Remember this computer for 30 days.', 'wordpress-2-step-verification'); ?>
            </label>
        </div>

    </form>
</div>
<?php include(dirname(__FILE__).'/others-link.php');?>