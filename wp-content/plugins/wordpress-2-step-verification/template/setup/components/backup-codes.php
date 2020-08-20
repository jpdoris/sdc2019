<template id="wp2sv-backup-codes">
    <div class="card-content">
        <div class="card-head">
            <div class="wp2sv-h1"><?php _e('Save your backup codes','wordpress-2-step-verification')?></div>
            <p><?php _e('Keep these backup codes somewhere safe but accessible.','wordpress-2-step-verification')?></p>
        </div>
        <div class="card-body">

            <div v-if="backup_codes" class="backup-codes wp2sv-text-center">
                <table class="backup-codes-list">
                    <tr v-for="row in backup_codes">
                        <td v-for="col in row">
                            <span class="cb" v-if="!col.used"></span>
                            <span>{{col.used?'<?php _e('ALREADY USED','wordpress-2-step-verification')?>':col.code}}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="wp2sv-loading" v-else>
                <div class="wp2svi-loading"><svg class="icircular" viewBox="25 25 50 50">
                        <circle class="ipath" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                    </svg></div>
                <p><?php _e('Loading backup codes','wordpress-2-step-verification')?>...</p>
            </div>
            <div v-if="backup_codes" class="backup-codes">
                <div class="backup-info">
                    <div><img src="<?php echo wp2sv_assets('images/icon-128x128.png');?>"></div>
                    <p><?php echo site_url();?> (<?php echo $this->user->user_login;?>)</p>
                </div>
                <ul class="backup-note">
                    <li><?php _e('You can only use each backup code once.','wordpress-2-step-verification')?></li>
                    <li class="show-if-print"><?php printf(__('Need more? Visit %s','wordpress-2-step-verification'),sprintf('<a href="%1$s">%1$s</a>',$this->wp2sv->url()))?></li>
                    <li><?php _e('These codes were generated on','wordpress-2-step-verification')?>: {{date}}</li>
                </ul>
            </div>

            <p class="wp2sv-text-center hide-if-print btn-new-codes">
                <button class="wp2sv-btn" @click="generate"><?php _e('Get new codes','wordpress-2-step-verification')?></button>
            </p>
        </div>
        <div class="card-footer">
            <div class="wp2sv-row">
                <div class="pull-right">
                    <span class="wp2sv-action wp2sv-modal-close"><?php _e('Close','wordpress-2-step-verification')?></span>
                    <span class="wp2sv-action" @click="download"><?php _e('Download','wordpress-2-step-verification')?></span>
                    <span class="wp2sv-action" @click="print"><?php _e('Print','wordpress-2-step-verification')?></span>
                </div>

            </div>
        </div>
    </div>
</template>