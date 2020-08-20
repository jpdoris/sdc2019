<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<div class="wrap wp2sv-setup wp2sv" id="wp2sv-setup">
    <h2 class="wp-heading-inline">
        <a href="<?php echo $this->url();?>" class="wp2sv-back"><span class="dashicons dashicons-arrow-left-alt2"></span></a>
        <?php _e('App passwords', 'wordpress-2-step-verification'); ?></h2>
    <p><?php _e('App passwords let you sign in to your Wordpress Account from apps on devices that don\'t support 2-Step Verification. You\'ll only need to enter it once so you don\'t need to remember it. Learn more', 'wordpress-2-step-verification'); ?></p>

    <wp2sv-app-passwords v-bind:app_passwords="app_passwords"></wp2sv-app-passwords>


    <div id="app-password-created" class="wp2sv-modal" tabindex="0">
        <div class="wp2sv-card">
        <div class="wp2sv-h1">
            <?php _e('Generated app password','wordpress-2-step-verification'); ?>
        </div>
        <div class="card-body">
            <div class="apc-title"><?php _e('Your app password for your device', 'wordpress-2-step-verification'); ?></div>
            <div class="apc-pass"></div>
            <div class="apc-direction">
                <div class="apc-title"><?php _e('How to use it', 'wordpress-2-step-verification'); ?></div>

                <p><?php _e('Go to the settings for your Wordpress Account in the application or device you are trying to set up. Replace your password with the 16-character password shown above.', 'wordpress-2-step-verification'); ?><br>
                    <?php _e('Just like your normal password, this app password grants complete access to your Wordpress Account. You won\'t need to remember it, so don\'t write it down or share it with anyone.', 'wordpress-2-step-verification'); ?></p>
            </div>
        </div>
        <div class="card-footer">
            <div class="wp2sv-row">
                <div class="pull-right">
                    <span class="wp2sv-action wp2sv-modal-close"><?php _e('Done', 'wordpress-2-step-verification'); ?></span>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<template id="wp2sv-app-passwords">
    <div class="wp2sv-app-passwords">
        <div class="wp2sv-container">

        <table id="the-app-passwords" class="wp2sv-table" v-if="passwords.length">
            <thead>
            <tr class="row">
                <th class="col-name"><?php _e('Name', 'wordpress-2-step-verification'); ?></th>
                <th class="col-created"><?php _e('Created', 'wordpress-2-step-verification'); ?></th>
                <th class="col-last-used"><?php _e('Last used', 'wordpress-2-step-verification'); ?></th>
                <th class="col-access"><?php _e('Access', 'wordpress-2-step-verification'); ?></th>
            </tr>
            </thead>
            <tbody>

                <tr v-for="(p,i) in passwords" class="app-password-item row">
                    <td class="col-name">{{p.n}}</td>
                    <td class="col-created"
                        data-c="">{{p.c}}</td>
                    <td class="col-last-used">{{p.u?p.u:'&ndash;'}}</td>
                    <td class="col-access">
                        <button class="wp2sv-btn" @click="remove(i)">
                            <span class="dashicons dashicons-trash wp2sv-clickable"></span>
                            <?php _e('Revoke', 'wordpress-2-step-verification'); ?>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
        <div v-else class="no-app-pass"><?php _e('You have no app passwords.', 'wordpress-2-step-verification'); ?></div>
        <div class="app-add-password">
                <span>
                    <input v-model="name" type="text" maxlength="100" class="app-name" placeholder="e.g. WP on my Android">
                </span>

            <button class="wp2sv-btn wp2sv-btn-primary" :disabled="!name" @click="generate"><?php _e('Generate', 'wordpress-2-step-verification'); ?></button>

        </div>
    </div>
    </div>
</template>
