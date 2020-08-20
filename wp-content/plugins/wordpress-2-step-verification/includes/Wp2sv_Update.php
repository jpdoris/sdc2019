<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 24-Oct-18
 * Time: 10:25 AM
 */

class Wp2sv_Update
{
    protected $version_key='wp2sv_version';
    function run(){
        $currentVersion=get_site_option($this->version_key);
        if(!$currentVersion || version_compare($currentVersion,'2.0','<')){
            $this->update20();
        }
    }
    function update20(){
        $metaKeys=[
            'wp2sv_email_sent',
            'wp2sv_email_sent_success',
            'wp2sv_email',
            'wp2sv_mobile_dev',
            'wp2sv_enabled',
            'wp2sv_user_fav_trusted',
            'last_selected_device',
            'wp2sv_secret_key',
            'wp2sv_backup_failed',
            'wp2sv_backup_codes',
            'wp2sv_lastday',
            'wp2sv_app_passwords'
        ];
        $newMetaKeys=[
            'wp2sv_enabled',
            'wp2sv_emails',
            'wp2sv_mobile_dev',
            'wp2sv_secret_key',
            'wp2sv_backup_codes',
            'wp2sv_email_sent',
            'wp2sv_email_sent_success',
            'wp2sv_backup_failed',
            'wp2sv_today',
            'wp2sv_app_passwords',
            'wp2sv_session_tokens',
        ];
        $users=get_users(['meta_key'=>'wp2sv_email','fields'=>'ids']);
        foreach ($users as $user){
            $email=get_user_meta($user,'wp2sv_email',true);
            Wp2sv_Model::forUser($user)->addEmail($email);
        }

        $removeKeys=array_diff($metaKeys,$newMetaKeys);
        foreach ($removeKeys as $key){
            delete_metadata( 'user', 0, $key, false, true );
        }
        update_site_option($this->version_key,'2.0');

    }
}