<template id="wp2sv-emails">
    <div class="card-content">
        <div class="card-body">
            <div class="wp2sv-row" v-if="enroll">
                <div class="wp2sv-col-0 card-icon">
                    <div class="wp2sv-logo"></div>
                </div>
                <div class="wp2sv-col">
                    <div class="wp2sv-h1"><?php _e('Protect your account with 2-Step Verification', 'wordpress-2-step-verification'); ?></div>
                    <p><?php _e('Each time you sign in to your account, you\'ll need your password and a verification code. ', 'wordpress-2-step-verification'); ?></p>
                </div>
            </div>
            <div v-if="step=='edit'">
                Edit...
            </div>
            <div v-else-if="step=='email'">
                <div class="wp2sv-h1"><?php _e('Let\'s set up your email', 'wordpress-2-step-verification') ?></div>
                <p><?php _e('What email address do you want to use?','wordpress-2-step-verification') ?></p>
                <div class="wp2sv-form-group" :class="error_email?'wp2sv-error':''">
                    <label for="email" class="has-float-label">
                        <input type="text" id="email" v-model="email" required/>
                    </label>
                    <div v-if="error_email">{{error_email}}</div>
                </div>
            </div>
            <div v-else-if="step=='test'">
                <div class="wp2sv-h1"><?php _e('Confirm that it works','wordpress-2-step-verification')?></div>
                <div class="wp2sv-p"><?php _e('Wp2sv just sent an email with a verification code to','wordpress-2-step-verification')?> {{email}}.</div>
                <div class="wp2sv-form-group" :class="error_code?'wp2sv-error':''">
                    <label for="code" class="has-float-label">
                        <input type="text" id="code" v-model="code" required placeholder="<?php _e('Enter the code','')?>"/>
                    </label>
                    <div v-if="error_code">{{error_code}}</div>
                </div>

                <p><?php _e('Didn\'t get it?','wordpress-2-step-verification')?> <a href="" @click="startOver"><?php _e('Resend','wordpress-2-step-verification')?></a></p>
            </div>

            <div v-else-if="step=='turn-on'">
                <div class="wp2sv-h1"><?php _e('It worked! Turn on 2-Step Verification?','wordpress-2-step-verification')?></div>
                <p><?php _e('Now that you\'ve seen how it works, do you want to turn on 2-Step Verification for your Wordpress Account?','wordpress-2-step-verification')?></p>
            </div>

            <div v-else-if="step=='complete'">
                <div class="wp2sv-h1"><?php _e('Done!','wordpress-2-step-verification')?></div>
                <div class="wp2sv-p"><?php _e("You're all set. From now on, you'll use Email to sign in to your Wordpress Account.",'wordpress-2-step-verification')?></div>
            </div>

        </div>
        <div class="card-footer">
            <div class="wp2sv-row">
                <div class="wp2sv-col" v-if="enroll && step!='turn-on'" >
                    <span class="wp2sv-action" @click="useApp"><?php _e('Use app','wordpress-2-step-verification')?></span>
                </div>
                <div class="pull-right" v-if="enroll">
                    <button class="wp2sv-btn wp2sv-btn-primary" @click="next" :disabled="disabled">{{next_text}}</button>
                </div>
                <div class="pull-right" v-else>
                    <span class="wp2sv-action wp2sv-modal-close" v-if="step!='complete'">{{l10n.cancel}}</span>

                    <span class="wp2sv-action" @click="next" v-if="step=='test'">{{l10n.done}}</span>
                    <span class="wp2sv-action" @click="next" v-else>{{l10n.next}}</span>
                </div>


            </div>
        </div>
    </div>
</template>