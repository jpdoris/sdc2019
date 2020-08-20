<script>
    import $ from '../libs/jquery';
    import l10n from '../libs/l10n';
    const toastL10n=l10n.toast;
    export default {
        props:['enroll'],
        template:'#wp2sv-emails',
        data:function(){
            return {
                step:'email',
                l10n:l10n,
                next_text:l10n.next,
                email:'',
                code:'',
                error_email:'',
                error_code:'',
                disabled:false
            };
        },
        mounted:function(){
            var $modal=$(this.$el).closest('.wp2sv-modal');
            var self=this;
            $modal.on('close',function(){
                self.reset();
            });
        },
        methods:{
            useApp:function(){
                this.$root.$emit('enroll:app-flow');
            },
            startOver:function(e){
                e&&e.preventDefault();
                this.step='email';
            },
            next:function(){
                var self=this;
                switch (this.step){
                    case 'email':
                        this.error_email='';

                        if(!this.email){
                            this.error_email=l10n.email.invalid;
                        }else{
                            this.disabled=true;
                            wp2sv.toast.info(toastL10n.working);
                            wp2sv.post({action:'send-email','email':self.email}).done(function(data){
                                if(!data){
                                    return;
                                }
                                if(data && data.success){
                                    self.step='test';
                                }else{
                                    if(data.data&&data.data.message){
                                        self.error_email=data.data.message;
                                    }
                                }
                            }).fail(function(){
                                wp2sv.toast.error(toastL10n.failed);
                            }).always(function(){
                                self.disabled=false;
                                wp2sv.toast.hide();
                            });

                        }

                        break;
                    case 'test':
                        this.disabled=true;

                        wp2sv.post({
                            action:'test-code',
                            code:this.code,
                            email:this.email,
                            updateEmail:!this.enroll
                        }).done(function(result){
                            if(!result){
                                return ;
                            }
                            if(result.success){
                                if(self.enroll) {
                                    self.step = 'turn-on';
                                }else {
                                    self.$root.$emit('update','emails',result.data.emails);
                                    self.complete();
                                }
                            }else{
                                self.error_code=l10n.code.invalid;
                            }
                        }).always(function(){
                            self.disabled=false;
                        });
                        break;
                    case 'turn-on':
                        this.disabled=true;
                        wp2sv.post({action:'enable','code':this.code,'email':this.email}).done(function(result){
                            if(result&&result.success){
                                self.$root.$emit('reload:data');
                            }else{
                                self.$root.$emit('reload');
                            }
                        }).always(function(){
                            self.disabled=false;
                        });
                        break;
                    case 'complete':
                        wp2sv.closeModal();
                        break;

                }

            },
            complete:function(){
                wp2sv.closeModal();
            },
            reset:function(){
                $.extend(this.$data,{
                    step:'email',
                    next_text:l10n.next,
                    email:'',
                    code:'',
                    error_email:'',
                    error_code:'',
                    disabled:false
                });
            },
            cancel:function(){
                this.$root.$emit('enroll:cancel');
            }
        },
        watch:{
            step:function(){
                if(this.step==='turn-on'){
                    this.next_text = l10n.turn_on;
                }else{
                    this.next_text=l10n.next;
                }
            }
        },
    }
</script>