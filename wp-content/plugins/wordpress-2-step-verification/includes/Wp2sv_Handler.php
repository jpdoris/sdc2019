<?php
class Wp2sv_Handler extends Wp2sv_Base {


    protected $run=false;
    public $error_message;
    protected $email_limit_per_day=10;
    protected $logged_in_with_app_password;
    function _construct()
    {
        add_action('wp_logout',array($this->auth,'clear_cookie'));
        $this->protectXmlRpc();
    }
    function run()
    {
        if ($this->run) {
            return;
        }
        $this->run = true;

        $this->handle();
    }

    function protectXmlRpc(){
        if($this->isXmlRpcRequest()) {
            add_filter('check_password', array($this, 'checkAppPassRestrict'), PHP_INT_MAX, 4);
        }else{
            add_action('set_auth_cookie',array($this, 'collectUserToken'),10,4);
            add_filter('check_password', array($this, 'checkAllowAppPassword'), PHP_INT_MAX, 4);
        }
    }

    function handle(){
        $this->error_message='';
        if(!$this->isEnabled()) {
            return;
        }
        if($this->validate()) {
            return;
        }
        $this->model->maybeClearRestriction();
        $scale=1;
        $code=$this->post('wp2sv_code');
        $nonce=$this->post('wp2sv_nonce');
        $action=$this->post('wp2sv_action');
        $this->checkLogout();

        if(($code||$action)&&!wp_verify_nonce($nonce,'wp2sv_nonce')){//submit without nonce
            wp_die('You do not have sufficient permissions to access this page.');
        }
        $method=$this->getReceiveMethod();
        if($method=='recovery'){
            $this->recovery->handle();
        }
        if(in_array($method,array('email','user_email'))){
            $scale=$this->email->handle();
        }

        if($code){
            if($this->getReceiveMethod()!='backup-codes'){
                $scale=absint($scale);
                $scale=max($scale,1);
                $scale=min($scale,10);
                if($this->otp->check($code,$scale)){
                    $this->passed();
                }else{
                    $this->error_message=__("The code you entered didn't verify.",'wordpress-2-step-verification');
                }
            }else{
                if($this->backup_code->isLocked()){
                    $this->error_message=__("Too many failed attempts with backup codes.",'wordpress-2-step-verification');
                }else{
                    $check_backup_result=$this->backup_code->check($code);
                    if($check_backup_result===1){
                        $this->passed();
                    }else{
                        $this->backup_code->failed();
                        if($check_backup_result===-1) {
                            $this->error_message = __("The code you entered have already used.", 'wordpress-2-step-verification');
                        }else{
                            $this->error_message = __("The code you entered didn't verify.", 'wordpress-2-step-verification');
                        }
                    }
                }

            }
        }
        $this->getEnterCodeTemplate();
        die;
    }
    function isRestRequest(){
        if(!function_exists('rest_api_register_rewrites')){
            return false;
        }
        rest_api_register_rewrites();
        $wp=new WP();
        $wp->add_query_var('rest_route');
        $wp->parse_request();
        return !empty($wp->query_vars['rest_route']);
    }


    function getReceiveMethod(){
        $method=$this->post('wp2sv_type');
        $allowed=array('email','mobile','backup-codes','others');
        if($this->canRecovery()){
            $allowed[]='recovery';
        }
        if(in_array($method,$allowed)){
            return $method;
        }
        if($method=$this->getPrimaryMethod()){
            return $method;
        }
        return 'mobile';
    }
    function canRecovery(){
        return current_user_can('administrator');
    }
    function getPrimaryMethod(){
        if($this->model->mobile_dev){
            return 'mobile';
        }
        if($this->model->emails){
            return 'email';
        }
        return false;
    }
    function getEnterCodeTemplate(){
        $template_file='front.wp2sv';
        $this->loadTemplate($template_file);
    }

    protected function checkLogout(){
        $action=$this->post('wp2sv_action');
        $action2=$this->get('action');
        if($action=='logout'||$action2=='logout'){
            check_admin_referer('log-out');

            $user = wp_get_current_user();

            wp_logout();

            if ( ! empty( $_REQUEST['redirect_to'] ) ) {
                $redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
            } else {
                $redirect_to = 'wp-login.php?loggedout=true';
                $requested_redirect_to = '';
            }

            /**
             * Filter the log out redirect URL.
             *
             * @since 4.2.0
             *
             * @param string  $redirect_to           The redirect destination URL.
             * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
             * @param WP_User $user                  The WP_User object for the user that's logging out.
             */
            $redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    function validate(){
        if(wp2sv_is_strict_mode()) {//Allow third party by pass in strict mode only
            if (apply_filters('wp2sv_trusted_request', false)) {
                return true;
            }
        }
        return $this->wp2sv->user()->ID===$this->auth->validateCookie();
    }

    /**
     * Only allow app password, current user password will not be allowed
     * @param $check
     * @param $password
     * @param $hash
     * @param $user_id
     * @return bool
     */
    function checkAppPassRestrict($check, $password, $hash, $user_id){
        if($this->isEnabled($user_id)){
            return $this->checkAppPassword($user_id,$password);
        }
        return $check;
    }
    function checkAllowAppPassword($check, $password, $hash, $user_id){
        if($this->checkAppPassword($user_id,$password,$match_index)){
            $this->logged_in_with_app_password=$match_index;
            return true;
        }
        return $check;
    }

    /**
     * Check app password on for user
     * @param $user_id
     * @param $password
     * @param $index
     * @return boolean true if password valid false otherwise
     */
    protected function checkAppPassword($user_id, $password, &$index=''){
        $app_pass=new Wp2sv_AppPass($user_id);
        return $app_pass->verify($password,$index);
    }
    function collectUserToken($auth_cookie, $expire, $expiration, $user_id){
        $app_pass=new Wp2sv_AppPass($user_id);
        if($app_pass->attachUserToken($auth_cookie,$this->logged_in_with_app_password)) {
            $this->auth->setCookie($user_id);
        }
    }



    function passed(){
        $remember=$this->post('wp2sv_remember');
        $this->model->clearRestriction();
        $this->auth->setCookie($this->model->ID,$remember);
        $redirect_to=wp_get_referer();
        if(!$redirect_to) {
            $redirect_to=$_SERVER['REQUEST_URI'];
        }
        wp_safe_redirect($redirect_to);
        die;
    }
    function getEmails(){
        return $this->model->getEmails();
    }
    function getEmail(){
        $emails=$this->model->emails;
        if(!$emails){
            return false;
        }
        $email=null;
        if($requested=$this->request('wp2sv_email')){
            if(isset($emails[$requested])){
                $email=$emails[$requested];
            }
        }else{
            $email=reset($emails);
        }
        if($email) {
            return $email['e'];
        }
        return false;
    }
    function getEmailEnding(){
        $email=$this->getEmail();
        $pad='';
        $end=substr($email,strpos($email,'@')-2);
        for ($i=0,$stop=strlen($email)-strlen($end);$i<$stop;$i++){
            $pad.='•';
        }
        return $pad.$end;
    }
    function havePhone(){
        return (bool)$this->model->mobile_dev;
    }
    function haveBackupCodes(){
        return (bool)$this->backup_code->getCodes('unused');
    }
    function isXmlRpcRequest(){
        return defined('XMLRPC_REQUEST')&&XMLRPC_REQUEST;
    }
    function error($message){
        $this->error_message=$message;
    }
}