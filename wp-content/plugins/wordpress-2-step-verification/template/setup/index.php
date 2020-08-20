<?php
/**
 * @var Wp2sv_Setup $this
 */
?>
<div class="wrap wp2sv-setup wp2sv" id="wp2sv-setup">
    <div class="icon32" id="icon-options-general"><br/></div>
    <h1 class="wp-heading-inline"><?php _e('Wordpress 2-step verification', 'wordpress-2-step-verification') ?></h1>

    <div v-cloak class="v-cloak">
        <?php $this->loadTemplate('setup.partials.status') ?>
        <div v-if="!enabled" class="wp2sv-container wp2sv-start">
            <wp2sv-start></wp2sv-start>
        </div>
        <div class="wp2sv-container" v-else>
            <?php $this->loadTemplate('setup.partials.settings') ?>
        </div>
    </div>


    <div id="wp2sv-change-device" class="wp2sv-modal" tabindex="0">
        <authenticator></authenticator>
    </div>
    <div id="wp2sv-add-email" class="wp2sv-modal" tabindex="0">
        <wp2sv-emails></wp2sv-emails>
    </div>
    <div id="wp2sv-backup-codes-modal" class="wp2sv-modal" tabindex="0">
        <backup-codes></backup-codes>
    </div>
    <div id="wp2sv-confirm" class="wp2sv-modal">
        <div class="wp2sv-modal-content">
            <div class="wp2sv-h1"></div>
            <div class="wp2sv-p"></div>
            <div class="wp2sv-row">
                <div class="pull-right wp2sv-actions">
                    <span class="wp2sv-action wp2sv-confirm-btn wp2sv-cancel-btn wp2sva-black"><?php _e('Cancel','wordpress-2-step-verification')?></span>
                    <span class="wp2sv-action wp2sv-confirm-btn" data-btn-ok><?php _e('Ok','wordpress-2-step-verification')?></span>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
$this->loadTemplate('setup.components.backup-codes');
$this->loadTemplate('setup.components.authenticator');
$this->loadTemplate('setup.components.emails');
$this->loadTemplate('setup.components.enroll-welcome');


