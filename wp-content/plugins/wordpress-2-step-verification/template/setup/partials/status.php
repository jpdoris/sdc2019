<div id="wp2sv-status" class="wp2sv-row" :class="enabled?'updated':'error'">
    <div class="wp2sv-col">
        <p v-if="enabled">
            <?php printf(__('2-step verification is <strong>ON</strong> since %s', 'wordpress-2-step-verification'),'{{enabled_at}}'); ?>
        </p>
        <p v-else>
            <?php printf(__('2-step verification is <strong>OFF</strong>', 'wordpress-2-step-verification'), $this->model->display_name); ?>
        </p>
        <p>

            <a v-if="enabled" href="#" id="wp2sv-disable-link" @click="disable"><?php _e('Turn off 2-step verification', 'wordpress-2-step-verification'); ?>
                ...</a>

            <a v-else href="#" @click="enable"><?php _e('Turn on 2-step verification', 'wordpress-2-step-verification'); ?>
                ...</a>

        </p>
    </div>
    <div class="wp2sv-col">
        <wp2sv-clock gmt-offset="<?php echo get_option('gmt_offset');?>"
                     server-time="<?php echo $this->otp->time(); ?>"
                     local-time="<?php echo $this->otp->localTime(); ?>"
                     server-text="<?php _e('Your server time in UTC is','wordpress-2-step-verification')?>"
                     local-text="<?php _e('Your local time in UTC%s is','wordpress-2-step-verification')?>"
                     sync-text="<?php _e('Sync time','wordpress-2-step-verification')?>"
        >
        </wp2sv-clock>
    </div>
</div>