<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 15-Oct-17
 * Time: 10:37 AM
 */

/**
 * Class Wp2sv_Model
 *
 * @property $ID
 *
 * @property $email_sent
 * @property $enabled
 * @property $emails
 * @property $mobile_dev
 * @property $secret_key
 * @property $backup_codes
 * @property $backup_failed
 * @property $app_passwords
 * @property $session_tokens
 * @property-read  $enabled_at

 */
class Wp2sv_Model extends Wp2sv_User{
    protected $fillable=[
        'enabled',
        'emails',
        'mobile_dev',
        'secret_key',
        'backup_codes',
        'email_sent',
        'email_sent_success',
        'backup_failed',
        'app_passwords',
        'session_tokens',
        'today',];
    protected $map_attributes=[
        'id'=>'ID',
        'ID'=>'ID',
    ];
    protected function getEnabledAtAttribute(){
        if(strlen($this->enabled)>4) {
            $timestamp=strtotime($this->enabled);
            if(date('Y')==date('Y',$timestamp)){
                $format='F j, g:i A';
            }else{
                $format='F j, Y g:i A';
            }
            $since = date_i18n($format,$timestamp);
            return $since;
        }
        return '';
    }
    static function forUser($user_id){
        return new static($user_id);
    }
    function status($withTime=true){
        if($this->enabled) {
            if($withTime && $since=$this->enabled_at) {
                return sprintf(__('On since: %s', 'wordpress-2-step-verification'), $since);
            }else{
                return sprintf(__('On', 'wordpress-2-step-verification'));
            }
        }
        return __('Off','wordpress-2-step-verification');
    }
    function enable(){
        $this->enabled=current_time('mysql');
        return $this;
    }
    function disable(){
        $this->enabled='';
        return $this;
    }
    function maybeClearRestriction(){
        if($this->isNewDay()){
            $this->clearRestriction();
        }
    }
    function clearRestriction(){
        $this['email_sent']='';
        $this['backup_failed']='';
    }
    function clearSettings(){
        foreach ($this->fillable as $key){
            $this->offsetUnset($key);
        }
    }
    protected function isNewDay(){
        $today=date('Y-m-d',current_time('timestamp'));
        if($today!=$this['today']){
            $this['today']=$today;
            return true;
        }
        return false;
    }
    function addEmail($email){
        if(!is_email($email)){
            return false;
        }
        $email=sanitize_email($email);
        if(!$email){
            return false;
        }
        $emails=$this->emails;
        if(!is_array($emails)){
            $emails=[];
        }
        $id=md5(strtolower($email));
        $new_email=['e'=>$email,'t'=>current_time('mysql')];
        $emails[$id]=$new_email;
        $this->emails=$emails;
        return true;
    }
    function updateEmail($email){
        $this->addEmail($email);
    }
    function removeEmail($email){
        $emails=$this->emails;
        if(!is_array($emails)){
            $emails=[];
        }
        if(isset($emails[$email])) {
            unset($emails[$email]);
            $this->emails=$emails;
            return true;
        }
        return false;
    }
    function primaryEmail($email){
        $emails=$this->emails;
        if(!is_array($emails)){
            $emails=[];
        }
        if(isset($emails[$email])) {
            $tmp=$emails[$email];
            unset($emails[$email]);
            $emails=[$email=>$tmp]+$emails;
            $this->emails=$emails;
            return true;
        }
        return false;
    }

    function getEmails($for_js=true){
        if(!is_array($this->emails)){
            return [];
        }
        $emails=[];
        foreach ($this->emails as $id=>$email){
            $email['id']=$id;
            $pad='';
            $end=substr($email['e'],strpos($email['e'],'@')-2);
            for ($i=0,$stop=strlen($email['e'])-strlen($end);$i<$stop;$i++){
                $pad.='•';
            }
            $email['ending']=$pad.$end;
            $email['added']=mysql2date(get_option('date_format'),$email['t']);
            $emails[$id]=$email;
        }
        if($for_js){
            return array_values($emails);
        }
        return $emails;
    }
    function getEmailAddresses(){
        return array_map(function ($e) {
            return isset($e['e']) ? $e['e'] : '';
        }, $this->getEmails());
    }
}