

<script>
    import $ from '../libs/jquery';
    import l10n from '../libs/l10n';
    export default {
        template:'#wp2sv-backup-codes',
        data:function(){
            return {
                backup_codes:false,
                date:''
            }
        },
        mounted:function(){
            this.loadCode();
        },
        methods:{
            download:function(){
                var url=ajaxurl+'?action=wp2sv&wp2sv_action=download_backup_codes&wp2sv_nonce='+wp2sv._nonce;
                window.location.href=url;
            },
            loadCode:function(){
                var self=this;
                $(this.$el).closest('.wp2sv-modal').on('open',function(){
                    self.getCodes();
                })
            },
            getCodes:function(generate){
                var self=this;
                self.backup_codes=false;
                wp2sv.get('backup-codes',{'generate':generate?1:0}).then(function(res){
                    if(res && res.success && res.data && res.data.codes){
                        return res.data;
                    }
                }).then(function(backup){
                    if(backup) {
                        self.$root.backup_codes=backup.unused;
                        self.backup_codes = backup.codes;
                        self.date=backup.date;
                    }
                })
            },


            print:function(){
                document.body.scrollTop = 0; // For Chrome, Safari and Opera
                document.documentElement.scrollTop = 0; // For IE and Firefox
                window.print();
            },
            generate:function(){
                var self=this;
                wp2sv.confirm(l10n.backup.get,l10n.backup.confirm,function(yes){
                    if(yes){
                        self.getCodes(true);
                    }
                })
            },
            sprintf:sprintf

        }
    }
</script>