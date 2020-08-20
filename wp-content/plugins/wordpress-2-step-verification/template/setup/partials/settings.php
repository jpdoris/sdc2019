
<h2><?php _e('Your second step','wordpress-2-step-verification')?></h2>
<p><?php _e('After entering your password, you’ll be asked for a second verification step.','wordpress-2-step-verification')?></p>

<div class="wp2sv-card" v-if="mobile_dev">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-ga"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Authenticator app','wordpress-2-step-verification')?> <strong class="">(<?php _e('Default','wordpress-2-step-verification')?>)</strong>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-row">
                    <div class="wp2sv-col">
                        <div class="wp2sv-h2">{{sprintf('<?php _e('Authenticator on %s','wordpress-2-step-verification');?>',mobile_dev)}}</div>
                    </div>
                    <div class="wp2sv-col-0"><span @click="removeApp" class="dashicons dashicons-trash wp2sv-clickable"></span></div>
                </div>
                <div class="wp2sv-text" v-if="mobile_added"><?php _e('Added','wordpress-2-step-verification')?>: {{mobile_added}}</div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-change-device"><?php _e('Change phone','wordpress-2-step-verification')?></span>
            </div>
        </div>
    </div>
</div>

<div class="wp2sv-card" v-if="emails.length">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-message"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Text message','wordpress-2-step-verification')?> <strong v-if="!mobile_dev">(<?php _e('Default','wordpress-2-step-verification')?>)</strong>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-email" v-for="(email, index) in emails">
                    <div class="wp2sv-row">
                        <div class="wp2sv-col">
                            <div class="wp2sv-h2">{{email.e}} <small v-if="index===0" class="wp2sv-text-primary">(<?php _e('Primary','wordpress-2-step-verification')?>)</small></div>
                        </div>
                        <div class="wp2sv-col-0">
                            <span v-if="index>0" title="Set as primary" class="dashicons dashicons-sticky wp2sv-clickable" @click="primaryMail(index)"></span>
                            <span title="Remove" class="dashicons dashicons-trash wp2sv-clickable" @click="removeEmail(index)"></span>
                        </div>
                    </div>
                    <div class="wp2sv-text" v-if="email.t" :title="email.t"><?php _e('Added','wordpress-2-step-verification')?>: {{email.added}}</div>
                </div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-add-email"><?php _e('Add email','wordpress-2-step-verification')?></span>
            </div>
        </div>
    </div>
</div>
<div class="wp2sv-card" v-if="backup_codes">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-airplane"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Backup codes','wordpress-2-step-verification')?>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-text">{{sprintf('<?php _e('%s single-use codes are active at this time, but you can generate more as needed.','wordpress-2-step-verification')?>',backup_codes)}}</div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-backup-codes-modal"><?php _e('Show codes','wordpress-2-step-verification')?></span>
            </div>
        </div>
    </div>
</div>
<div class="wp2sv-alternative-section" v-if="!mobile_dev || emails.length<1 || !backup_codes">
<h2><?php _e('Set up alternative second step','wordpress-2-step-verification')?></h2>
<p><?php _e("Set up at least one backup option so that you can sign in even if your other second steps aren’t available.",'wordpress-2-step-verification')?></p>
<div class="wp2sv-card" v-if="!mobile_dev">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-ga"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Authenticator app','wordpress-2-step-verification')?>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-text"><?php _e('Use the Authenticator app to get free verification codes, even when your phone is offline. Available for Android and iPhone.','wordpress-2-step-verification')?></div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-change-device"><?php _e('Set up','wordpress-2-step-verification')?></span>
            </div>
        </div>
    </div>
</div>
<div class="wp2sv-card" v-if="emails.length < 1">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-message"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Backup email','wordpress-2-step-verification')?>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-text"><?php _e('Add a backup phone so you can still sign in if you lose your phone.','wordpress-2-step-verification')?></div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-add-email"><?php _e('Add email')?></span>
            </div>
        </div>
    </div>
</div>
<div class="wp2sv-card" v-if="!backup_codes">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-airplane"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Backup codes','wordpress-2-step-verification')?>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-text"><?php _e('These printable one-time passcodes allow you to sign in when away from your phone, like when you’re traveling.','wordpress-2-step-verification')?></div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-backup-codes-modal"><?php _e('Set up','wordpress-2-step-verification');?></span>
            </div>
        </div>
    </div>
</div>
    <div class="wp2sv-card" v-if="false">
        <div class="wp2sv-row">
            <div class="card-icon">
                <div class="wp2sv-icon wp2svi-usb"></div>
            </div>
            <div class="card-content wp2sv-col">
                <div class="card-head">
                    <div class="wp2sv-h1">
                        <?php _e('Security Key','wordpress-2-step-verification')?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="wp2sv-text"><?php _e('A Security Key is a small physical device used for signing in. It plugs into your computer\'s USB port.','wordpress-2-step-verification')?>
                    <a href="">Learn more</a>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-backup-codes-modal"><?php _e('Add Security Key','wordpress-2-step-verification');?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<h2><?php _e('App passwords','wordpress-2-step-verification')?></h2>
<p><?php _e('App passwords let you sign in to your Wordpress Account from apps on devices that don\'t support 2-Step Verification','wordpress-2-step-verification')?></p>

<div class="wp2sv-card">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-app-passwords"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php
                    $app_passwords_count=count($this->getAppPasswords());
                    ?>
                    <a class="wp2sv-action" href="<?php echo $this->url(['config-page'=>'app-passwords']);?>"><?php printf(_n('%d password','%d passwords',$app_passwords_count ,'wordpress-2-step-verification'),$app_passwords_count)?></a>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>

<h2><?php _e('Devices that do not need a second step.','wordpress-2-step-verification')?></h2>
<p><?php _e('You can skip the second step on devices you trust, such as your own computer.','wordpress-2-step-verification')?></p>

<div class="wp2sv-card">
    <div class="wp2sv-row">
        <div class="card-icon">
            <div class="wp2sv-icon wp2svi-devices"></div>
        </div>
        <div class="card-content wp2sv-col">
            <div class="card-head">
                <div class="wp2sv-h1">
                    <?php _e('Devices you trust','wordpress-2-step-verification')?>
                </div>
            </div>
            <div class="card-body">
                <div class="wp2sv-text"><?php _e('Revoke trusted status from your devices that skip 2-Step Verification.','wordpress-2-step-verification')?></div>
            </div>
            <div class="card-footer">
                <span class="wp2sv-action" data-wp2sv-modal="#wp2sv-revoke"><?php _e('Revoke all','wordpress-2-step-verification')?></span>
            </div>
        </div>
    </div>
</div>