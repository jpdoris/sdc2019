<template id="wp2sv-authenticator">
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
            <div v-if="step==='select-device'">
                <div class="wp2sv-h1"><?php _e('Get codes from the Authenticator app','wordpress-2-step-verification')?></div>
                <div class="wp2sv-text"><?php _e('Instead of waiting for text messages, get verification codes for free from the Authenticator app. It works even if your phone is offline.')?></div>
                <div class="wp2sv-h2"><?php _e('What kind of phone do you have?','wordpress-2-step-verification')?></div>
                <div class="wp2sv-p"><label><input type="radio" value="android" v-model="device"> Android</label></div>
                <div class="wp2sv-p"><label><input type="radio" value="iphone" v-model="device"> Iphone</label></div>
            </div>
            <div v-if="step==='setup'">
                <div class="wp2sv-h1"><?php _e('Set up Authenticator','wordpress-2-step-verification');?></div>
                <ol class="wp2sv-p" v-if="device==='android'">
                    <li><?php printf(
                            __('Get the Authenticator App from the %s.','wordpress-2-step-verification'),
                            sprintf('<a target="_blank" href="%s">%s</a>',
                            'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2',
                            __('Play Store','wordpress-2-step-verification')
                            )
                        )
                        ?></li>
                    <li><?php _e('In the App select <b>Set up account.</b>','wordpress-2-step-verification')?></li>
                    <li><?php _e('Choose <b>Scan a barcode.</b>','wordpress-2-step-verification')?></li>
                    <div class="wp2sv-center">
                        <img v-if="qr_url" class="wp2sv-qrcode" :src="qr_url">
                        <span class="wp2sv-action" @click="manually"><?php _e("Can't scan it?",'wordpress-2-step-verification')?></span>
                    </div>

                </ol>


                <ol class="wp2sv-p" v-else>
                    <li><?php printf(
                            __('Get the Authenticator App from the %s.','wordpress-2-step-verification'),
                            sprintf('<a target="_blank" href="%s">%s</a>',
                                'https://itunes.apple.com/en/app/google-authenticator/id388497605',
                                __('App Store','wordpress-2-step-verification')
                            )
                        )
                        ?></li>
                    <li><?php _e('In the App select <b>Set up account.</b>','wordpress-2-step-verification')?></li>
                    <li><?php _e('Choose <b>Scan a barcode.</b>','wordpress-2-step-verification')?></li>
                    <div class="wp2sv-center">
                        <img v-if="qr_url" class="wp2sv-qrcode" :src="qr_url">
                        <span class="wp2sv-action" @click="manually"><?php _e("Can't scan it?",'wordpress-2-step-verification')?></span>
                    </div>
                </ol>

            </div>
            <div v-else-if="step==='manually-setup'">
                <div class="wp2sv-h1"><?php _e("Can't scan the barcode?",'wordpress-2-step-verification')?></div>

                <ol class="wp2sv-p">
                    <li><?php _e('Tap <b>Menu </b>, then <b>Set up account</b>.','wordpress-2-step-verification')?></li>
                    <li><?php _e('Tap <b>Enter provided key</b>.','wordpress-2-step-verification')?></li>
                    <li><?php _e('Enter your username and this key:','wordpress-2-step-verification')?></li>
                    <div class="wp2sv-bd wp2sv-text-center">
                        <div class="wp2sv-bb">{{formatted_secret}}</div>
                        <br><?php _e("spaces don't matter",'wordpress-2-step-verification')?>
                    </div>
                    <li><?php _e('Make sure <b>Time based</b> is turned on, and tap <b>Add</b> to finish.','wordpress-2-step-verification')?></li>
                </ol>

            </div>

            <div v-else-if="step==='test'">
                <div class="wp2sv-h1"><?php _e('Set up Authenticator','wordpress-2-step-verification')?></div>
                <div class="wp2sv-p">
                    <?php _e('Enter the 6-digit code you see in the app.','wordpress-2-step-verification')?>
                </div>
                <div class="wp2sv-form-group" :class="error_code?'wp2sv-error':''">
                    <label>
                        <input type="text" id="test-code" v-model="code" maxlength="6" placeholder="Enter code">
                    </label>
                    <div v-if="error_code">{{error_code}}</div>
                </div>
            </div>

            <div v-else-if="step==='complete'">
                <div class="wp2sv-h1"><?php _e('Done!','wordpress-2-step-verification')?></div>
                <div class="wp2sv-p"><?php _e("You're all set. From now on, you'll use Authenticator to sign in to your Wordpress Account.",'wordpress-2-step-verification')?></div>
            </div>

            <div v-else-if="step==='turn-on'">
                <div class="wp2sv-h1"><?php _e('It worked! Turn on 2-Step Verification?','wordpress-2-step-verification')?></div>
                <p><?php _e('Now that you\'ve seen how it works, do you want to turn on 2-Step Verification for your Wordpress Account?','wordpress-2-step-verification')?></p>
            </div>
        </div>
        <div class="card-footer">
            <div class="wp2sv-row">
                <div class="wp2sv-col" v-if="enroll&&step!=='turn-on'">
                    <span class="wp2sv-action" @click="useEmail"><?php _e('Use email','wordpress-2-step-verification');?></span>
                </div>
                <div class="pull-right" v-if="enroll">
                    <button class="wp2sv-btn wp2sv-btn-primary" v-if="step==='manually-setup'" @click="back">{{l10n.back}}</button>

                    <button class="wp2sv-btn wp2sv-btn-primary" @click="next" v-if="step==='turn-on'">{{l10n.turn_on}}</button>
                    <button class="wp2sv-btn wp2sv-btn-primary" @click="next" v-else-if="step==='test'">{{l10n.verify}}</button>
                    <button class="wp2sv-btn wp2sv-btn-primary" @click="next" v-else>{{l10n.next}}</button>
                </div>
                <div class="pull-right" v-else>
                    <span class="wp2sv-action" v-if="step==='manually-setup'" @click="back">{{l10n.back}}</span>
                    <span class="wp2sv-action wp2sv-modal-close" v-else-if="step!=='complete'">{{l10n.cancel}}</span>

                    <span class="wp2sv-action" @click="next" v-if="step==='complete'">{{l10n.done}}</span>
                    <span class="wp2sv-action" @click="next" v-else-if="step==='test'">{{l10n.verify}}</span>
                    <span class="wp2sv-action" @click="next" v-else>{{l10n.next}}</span>
                </div>
            </div>
        </div>
    </div>

</template>