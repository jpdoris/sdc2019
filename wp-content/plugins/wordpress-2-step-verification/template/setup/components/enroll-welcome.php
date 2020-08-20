<template id="wp2sv-enroll-welcome">
    <div class="wp2sv-card">

        <div class="card-content">

            <div class="card-body">
                <div class="wp2sv-row">
                    <div class="wp2sv-col-0 card-icon">
                        <div class="wp2sv-logo"></div>
                    </div>
                    <div class="wp2sv-col">
                        <div class="wp2sv-h1"><?php _e('Protect your account with 2-Step Verification','wordpress-2-step-verification');?></div>
                        <p><?php _e('Each time you sign in to your account, you\'ll need your password and a verification code. ','wordpress-2-step-verification');?></p>
                    </div>
                </div>

                <div class="wp2sv-row mt">
                    <div class="wp2sv-col-0">
                        <div class="wp2sv-icon wp2svi-additional-layer"></div>
                    </div>
                    <div class="wp2sv-col">
                        <div class="wp2sv-h2 mt"><?php _e('Add an extra layer of security','wordpress-2-step-verification');?></div>
                        <div class="wp2sv-p"><?php _e('Enter your password and a unique verification code that\'s sent to your phone.','wordpress-2-step-verification');?></div>
                    </div>
                </div>

                <div class="wp2sv-row mt">
                    <div class="wp2sv-col-0">
                        <div class="wp2sv-icon wp2svi-hacker"></div>
                    </div>
                    <div class="wp2sv-col">
                        <div class="wp2sv-h2"><?php _e('Keep the bad guys out','wordpress-2-step-verification')?></div>
                        <div class="wp2sv-p"><?php _e('Even if someone else gets your password, it won\'t be enough to sign in to your account.','wordpress-2-step-verification')?></div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="wp2sv-row">
                    <button class="wp2sv-btn wp2sv-btn-primary pull-right" @click="$root.$emit('enroll:start')"><?php _e('Get started','wordpress-2-step-verification')?></button>
                </div>
            </div>
        </div>
    </div>
</template>