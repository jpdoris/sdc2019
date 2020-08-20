<?php
class Wp2sv_Setup extends Wp2sv_Base {
    protected $page;
    function run(){
        add_action('admin_menu',array($this,'menu'));
        add_action('network_admin_menu',array($this,'menu'));
        add_action('wp_footer',array($this,'menu'));
        add_action( 'admin_notices', array($this,'notice') );
        add_action('wp_ajax_wp2sv',[$this,'ajax']);
        add_action('wp_ajax_wp2sv_setup_data',[$this,'setupData']);
        add_action('admin_enqueue_scripts', function($hook){
            if($this->page==$hook){
                $this->enqueueScripts();
            }
        });
        add_action( 'profile_personal_options', array( $this, 'profilePersonalOptions') );
    }
    function profilePersonalOptions(){
        ?>
        <h3><?php _e('2-Step Verification','wordpress-2-step-verification');?></h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('Status:','wordpress-2-step-verification');?></th>
                <td><label><?php echo $this->model->status();?></label>
                    <a class="button" href="<?php echo $this->url()?>"> <?php _e('Edit','wordpress-2-step-verification');?> </a></td>
            </tr>
            </tbody>
        </table>
        <?php
    }
    function enqueueScripts(){
        wp_enqueue_script( 'wp2sv_setup',wp2sv_assets('/js/setup.js'),array('wp2sv'),'1.1',true );
        wp_enqueue_style( 'wp2sv_setup',wp2sv_assets('/css/setup.css'),['wp2sv_admin'] );

        $setupL10n=[
            'toast'=>[
                'loading'=>__('Loading...','wordpress-2-step-verification'),
                'working'=>__('Working...','wordpress-2-step-verification'),
                'failed'=>__('Failed','wordpress-2-step-verification'),
            ],
            'code'=>[
                'required'=>__('Please enter your verification code.','wordpress-2-step-verification'),
                'invalid'=>__('Invalid code. Please try again.','wordpress-2-step-verification'),
            ],
            'next'=>__('Next','wordpress-2-step-verification'),
            'back'=>__('Back','wordpress-2-step-verification'),
            'verify'=>__('Verify','wordpress-2-step-verification'),
            'done'=>__('Done','wordpress-2-step-verification'),
            'cancel'=>__('Cancel','wordpress-2-step-verification'),
            'turn_on'=>__('Turn on','wordpress-2-step-verification'),
            'turn_off'=>[
                'confirm'=>[
                    'title'=>__('Turn off 2-Step Verification?','wordpress-2-step-verification'),
                    'message'=>__('Turning off 2-Step Verification will remove the extra security on your account, and you’ll only use your password to sign in.','wordpress-2-step-verification'),
                ]
            ],
            'backup'=>[
                'get'=>__('Get new codes?','wordpress-2-step-verification'),
                'confirm'=>__('If you get a new set of backup codes, none of your current codes will work.','wordpress-2-step-verification'),
            ],
            'email'=>[
                'invalid'=>__('Invalid email, try again.'),
                'add'=>__('Add email'),
            ],
            'remove'=>[
                'app_confirm'=>__('Removing this option will make verification codes on %s your default second step. Are you sure you want to proceed?'),
                'not_allowed'=>__('2-Step Verification isn\'t allowed without an email or the Authenticator app'),
            ],
        ];

        $setupData = $this->getData();
        $setupData['l10n']=$setupL10n;
        wp_localize_script( 'wp2sv_setup', 'wp2sv_setup', $setupData );


    }
    function setupData(){
        wp_send_json($this->getData());
    }
    function getData(){
        $unusedBackupCodes=$this->backup_code->getCodes('unused');
        return [
            'enabled'=>$this->isEnabled(),
            'enabled_at'=>$this->model->enabled_at,
            'emails'=>$this->model->getEmails(),
            'mobile_dev'=>wp2sv_get_device_name($this->model->mobile_dev),
            'mobile_added'=>'',
            'backup_codes'=>$unusedBackupCodes,
            'app_passwords'=>$this->getAppPasswords(),
            '_nonce'=>wp_create_nonce('wp2sv_setup'),
            'home'=>$this->wp2sv->url(),
        ];
    }
    function getAppPasswords(){
        $passwords=$this->app_password->getPasswords();
        foreach ($passwords as $i=>&$password){
            $password['i']=$i;
        }
        return array_values($passwords);
    }

    function menu(){
        if(!function_exists('add_users_page')){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->page=add_users_page( __('Wordpress 2-step verification','wordpress-2-step-verification'), __('2-Step Verification','wordpress-2-step-verification'), 'read', 'wp2sv', array($this,'view'));

        add_action('admin_bar_menu',array($this, 'adminBar'));
    }

    function chartUrl($secret, $w=144, $h=144){
        $display=$this->user->user_login;
        $name=parse_url(get_bloginfo('wpurl'),PHP_URL_HOST);
        $display=$name.'%3A'.$display;
        $secret_url=sprintf("otpauth://totp/%s?secret=%s&issuer=%s",$display,$secret,$name);
        $secret_url=urlencode($secret_url);
        $chart_url=sprintf("https://chart.googleapis.com/chart?chs=%sx%s&chld=L|0&cht=qr&chl=%s",$w,$h,$secret_url);
        return $chart_url;
    }

    /**
     * @param WP_Admin_Bar $wp_admin_bar
     * @return void
     */
    function adminBar($wp_admin_bar){
        $logout=$wp_admin_bar->get_node('logout');
        $wp_admin_bar->remove_menu('logout');
        $wp_admin_bar->add_menu( array(
            'parent' => 'user-actions',
            'id'     => '2-step-verification',
            'title'  => __( '2-Step Verification' , 'wordpress-2-step-verification' ),
            'href' => menu_page_url('wp2sv',false),
        ) );
        $wp_admin_bar->add_menu( get_object_vars($logout) );
    }
    function view(){
        if($this->request('config-page')=='app-passwords'){
            $this->loadTemplate('setup.app-passwords');
        }else {
            $this->loadTemplate('setup.index',$this->getData());
        }
    }
    function notice(){
        $message=$this->model['message'];
        if($message){
            printf('<div class="notice notice-success is-dismissible"><p>%s</p></div>',$message);
        }
    }

    function ajax(){
        $action=$this->request('wp2sv_action');
        $action=str_replace('-','_',$action);
        $allowed_actions=[
            'qrcode','send_email','test_code',
            'enable','disable','backup_codes','time_sync',
            'remove_app','remove_email','primary_email',
            'password_create','password_remove','download_backup_codes'
        ];
        if(!in_array($action,$allowed_actions)){
            wp_send_json_error(['message'=>'action not allowed']);
        }
        if(!wp_verify_nonce($this->request('wp2sv_nonce'),'wp2sv')){
            wp_send_json_error(['message'=>'action not allowed']);
        }
        $method='ajax_'.$action;
        $result=[];
        if(method_exists($this,$method)) {
            $result = $this->$method();
        }
        if($result) {
            wp_send_json_success($result);
        }else{
            wp_send_json_error();
        }
    }
    protected function ajax_download_backup_codes(){
        $this->loadTemplate('setup.backup_codes_txt');
        die;
    }
    protected function ajax_password_create(){
        return $this->app_password->create($this->request('name'));
    }
    protected function ajax_password_remove(){
        return $this->app_password->revoke($this->request('index'));
    }
    protected function ajax_time_sync(){
        $this->otp->syncTime();
        return array('server'=>$this->otp->time(),'local'=>$this->otp->localTime());
    }
    protected function ajax_remove_app(){
        $this->model->mobile_dev='';
        return true;
    }
    protected function ajax_remove_email(){
        $email=$this->request('email');
        return $this->model->removeEmail($email);
    }
    protected function ajax_primary_email(){
        $email=$this->request('email');
        return $this->model->primaryEmail($email);

    }
    protected function ajax_qrcode(){
        $secret=$this->otp->generateSecretKey();
        $data = ['url'=>$this->chartUrl($secret),'secret'=>$secret];
        wp_send_json_success($data);
    }
    protected function ajax_send_email(){
        $email=$this->post('email');
        $email=sanitize_email($email);
        $dupe=md5(strtolower($email));//dupe check
        $emails=$this->model->getEmails(false);
        if(isset($emails[$dupe])){
            wp_send_json_error(['message'=>'You are already using this email as a second step.']);
        }

        if(!$email){
            wp_send_json_error(['message'=>'Please enter valid email']);
        }
        if($this->email->sendCodeToEmail($email)){
            wp_send_json_success();
        }else {
            wp_send_json_success();
            wp_send_json_error(['message'=>'Cannot send email']);
        }
    }
    protected function ajax_test_code(){
        $code=$this->request('code');
        $secret=$this->request('secret');
        if(!$secret){
            $secret=$this->model->secret_key;
        }
        $changeDevice=$this->request('changeDevice');
        $updateEmail=$this->request('updateEmail');
        $email=sanitize_email($this->request('email'));
        if ($code && $secret && $this->otp->check($code, 1, $secret)) {
            if($changeDevice){
                $this->configureEmailOrApp();
            }elseif($updateEmail&&$email){
                $this->updateEmail($email);
            }
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
    protected function ajax_enable(){
        if($this->configureEmailOrApp()) {
            $this->model->enable();
            wp_send_json_success();
        }
        wp_send_json_error();
    }
    protected function ajax_disable(){
        $this->model->clearSettings();
        $this->model->disable();
    }
    protected function ajax_backup_codes(){
        if(!$this->backup_code->getCodes() || $this->request('generate')){
            $this->backup_code->generate();
        }
        $backup_codes=$this->backup_code->getCodes();
        $codes=[];
        foreach($backup_codes['codes'] as $code=>$unsed){
            $codes[]=['code'=>implode(' ',str_split($code,4)),'used'=>!$unsed];
        }
        $codes=array_chunk($codes,2,true);
        $backup_codes['codes']=$codes;
        $backup_codes['date']=mysql2date(get_option( 'date_format' ),$backup_codes['last']);
        wp_send_json_success($backup_codes);
    }
    protected function updateEmail($email){
        $this->model->updateEmail($email);
        wp_send_json_success(['emails'=>$this->model->getEmails()]);
    }
    protected function configureEmailOrApp(){
        if($email=$this->post('email')) {
            $this->model->updateEmail($email);
        }elseif($device=$this->post('device')){
            $secret=$this->request('secret');
            if(!in_array($device,['android','iphone'])){
                $device='android';
            }
            $this->model->secret_key=$secret;
            $this->model->mobile_dev=$device;
            $this->auth->setCookie($this->model->ID);
        }
        return true;
    }
}