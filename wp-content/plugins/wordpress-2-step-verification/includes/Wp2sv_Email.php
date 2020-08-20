<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 29-Oct-18
 * Time: 8:05 AM
 */

class Wp2sv_Email extends Wp2sv_Base
{
    protected $email_limit_per_day=10;

    function getEmailSubject(){
        return __('Your verification code','wordpress-2-step-verification');
    }
    function getEmailContent(){
        $code=$this->otp->generate();
        $code=str_pad($code[1],6,'0',STR_PAD_LEFT);
        return sprintf(__('Your verification code is %s','wordpress-2-step-verification'),$code);
    }

    /**
     * Sent code to registered email
     * @param $email
     * @return bool
     */
    function sendCodeToEmail($email=''){
        if(!$email){
            if(method_exists($this,'get_email')) {
                $email = $this->get_email();
            }
        }
        if($email) {
            return @wp_mail($email, $this->getEmailSubject(), $this->getEmailContent());
        }
        return false;
    }
    function handle(){
        $action=$this->post('wp2sv_action');
        $email=$this->handler->getEmail();
        $code=$this->post('wp2sv_code');
        $scale=1;
        if($email) {
            $sent = $this->model->email_sent;
            $sent = absint($sent);
            if ($action == 'send-email' || ($this->handler->getPrimaryMethod() == 'email' && !$sent)) {
                if ($sent < $this->email_limit_per_day) {
                    if ($this->sendCodeToEmail($email)) {
                        $sent++;
                        $this->model->email_sent=$sent;
                        $this->model['email_sent_success']=true;
                    } else {
                        $this->handler->error(__('The e-mail could not be sent.','wordpress-2-step-verification').' '.__('Possible reason: your host may have disabled the mail() function...', 'wordpress-2-step-verification'));
                    }
                } else {
                    $this->handler->error(__('Total emails can send per day has reached limit!', 'wordpress-2-step-verification'));
                }
            }else{
                $this->handler->error('');
            }
            if ($code && $this->model['email_sent_success']) {
                $scale = $sent + 1;
                $this->model['email_sent_success']=false;
            }
        }
        return $scale;
    }
}