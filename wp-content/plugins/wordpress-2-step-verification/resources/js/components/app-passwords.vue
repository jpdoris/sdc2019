<script>
    import l10n from '../libs/l10n';
    import $ from '../libs/jquery';

    let toastL10n = l10n.toast;
    export default {
        props: ["app_passwords"],
        template: '#wp2sv-app-passwords',
        data: function () {
            return {
                passwords: this.app_passwords || [],
                name: ""
            }
        },
        methods: {
            generate: function () {
                var self = this;
                wp2sv.toast.info(toastL10n.working);
                wp2sv.post('password_create', {name: this.name}).done(function (result) {
                    if (result.data) {
                        self.passwords.push(result.data);
                        self.showPassword(result.data.p);
                    }

                    wp2sv.toast.hide();
                });


            },
            remove: function (index) {
                var self = this;
                var i = this.passwords[index].i;
                wp2sv.toast.info(toastL10n.working);
                wp2sv.post('password_remove', {index: i}).done(function (result) {
                    if (result.success) {
                        self.passwords.splice(index, 1);
                    }

                    wp2sv.toast.hide();
                });

            },
            showPassword: function (pass) {
                var $modal=$('#app-password-created');
                var res = '';
                if (typeof pass === "string") {
                    var chunk = pass.match(/.{1,4}/g);
                    chunk.forEach(function (p) {
                        res += '<span class="apc-pchunk"><span>' + p.split('').join('</span><span>') + '</span></span>';
                    });
                }
                $modal.find('.apc-pass').html(res);
                wp2sv.openModal($modal);
            }
        },
        mounted: function () {
            console.log(this.passwords);
        }
    }
</script>