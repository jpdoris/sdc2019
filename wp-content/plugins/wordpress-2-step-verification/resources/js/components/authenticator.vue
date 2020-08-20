

<script>
    import $ from '../libs/jquery';
    import l10n from '../libs/l10n';
    export default {
        props:['enroll'],
        template:'#wp2sv-authenticator',
        data:function(){
            return {
                step:'select-device',
                device:'android',
                error_code:'',
                code:'',
                qr_url:'',
                secret:'',
                l10n:l10n,
            };
        },
        mounted:function(){

            var $modal=$(this.$el).closest('.wp2sv-modal');
            var self=this;
            $modal.on('close',function(){
                self.reset();
            });
        },
        watch:{
            step:function(){
                if(this.step==='setup'){
                    this.loadQrCodes();
                }
            }
        },
        computed:{
            formatted_secret:function(){
                return this.secret.replace(/(.{4})/g, '$1 ').trim()
            }
        },
        methods:{
            next:function(){
                var self=this;
                switch(this.step){
                    case 'select-device':
                        this.step='setup';
                        break;
                    case 'setup':
                    case 'manually-setup':
                        this.step='test';
                        break;
                    case 'test':
                        this.testCode();
                        break;
                    case 'complete':
                        self.$root.$emit('update','mobile_dev',this.device);
                        wp2sv.closeModal();
                        break;
                    case 'turn-on':
                        this.disabled=true;
                        wp2sv.post({
                            action:'enable',
                            code:this.code,
                            secret:this.secret,
                            device:this.device
                        }).done(function(result){
                            if(result&&result.success){
                                self.$root.$emit('reload:data');
                            }else{
                                self.$root.$emit('reload');
                            }
                        }).always(function(){
                            self.disabled=false;
                        });
                        break;
                }

            },
            testCode:function(){
                var self=this;
                if(!this.code){
                    this.error_code=l10n.code.required;
                    return ;
                }
                this.disabled=true;
                wp2sv.post({
                    action:'test-code',
                    code:this.code,
                    secret:this.secret,
                    changeDevice:this.enroll?'':1,
                    device:this.device
                }).done(function(result){
                    if(!result){
                        return ;
                    }
                    if(result.success){
                        if(self.enroll) {
                            self.step = 'turn-on';
                        }else {
                            self.step = 'complete';
                        }
                    }else{
                        self.error_code=l10n.code.invalid;
                    }
                }).always(function(){
                    self.disabled=false;
                });
            },
            back:function(){
                this.step='setup';
            },
            manually:function(){
                this.step='manually-setup';
            },
            reset:function(){
                $.extend(this.$data,{
                    step:'select-device',
                    device:'android',
                    error_code:'',
                    code:'',
                    qr_url:'',
                    secret:''
                });
            },
            loadQrCodes:function(){
                var self=this;
                wp2sv.get('qrcode').then(function(result){
                    if(result&&result.success){
                        return result.data;
                    }
                    return false;
                }).then(function(data){
                    if(data) {
                        self.qr_url = data.url;
                        self.secret = data.secret;
                    }
                });
            },
            useEmail:function(e){
                e&&e.preventDefault();
                this.$root.$emit('enroll:email-flow');
            }

        }
    }
</script>