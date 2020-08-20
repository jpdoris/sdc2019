(function(factory){
    // Establish the root object, `window` (`self`) in the browser, or `global` on the server.
    // We use `self` instead of `window` for `WebWorker` support.
    var root = (typeof self === 'object' && self.self === self && self) ||
        (typeof global === 'object' && global.global === global && global);
    root.wp2sv=root.wp2sv||{};
    root.wp2sv.setup=factory(Vue,_,jQuery);
})
(function(Vue,_,$){
    const sprintf=require("sprintf-js").sprintf;
    var l10n=wp2sv_setup.l10n;
    var toastL10n=l10n.toast;

    var module={
        init:function(){
            this.registerComponents();
            if($('#wp2sv-setup').length) {
                this.vm = new Vue({
                    el: '#wp2sv-setup',
                    data: function () {
                        return wp2sv_setup;
                    },
                    mounted: function () {
                        this.$on('reload:data', this.reloadData);
                        this.$on('reload', this.reload);
                        this.$on('refresh', this.reloadData);
                        this.$on('update',this.updateData);
                    },
                    methods: {
                        enable: function (e) {
                            e && e.preventDefault();
                            this.$root.$emit('enroll:start')
                        },
                        disable: function (e) {
                            e && e.preventDefault();
                            var self = this;
                            wp2sv.confirm(l10n.turn_off.confirm.title, l10n.turn_off.confirm.message, function (yes) {
                                if (yes) {
                                    wp2sv.post('disable').done(function () {
                                        self.enabled = false;
                                    });
                                }
                            });

                        },
                        reload: function () {
                            window.location.href = this.home;
                        },
                        reloadData: function () {
                            var self = this;
                            wp2sv.toast.info(toastL10n.loading);
                            return $.ajax({
                                type: 'POST',
                                dataType: "json",
                                url: ajaxurl,
                                data: {action: 'wp2sv_setup_data'}
                            }).done(function (data) {
                                if (data) {
                                    Object.assign(self.$data, data);
                                    wp2sv.toast.hide();
                                } else {
                                    self.reload();
                                }
                            }).fail(function () {
                                self.reload();
                            });
                        },
                        removeApp: function () {
                            var self = this;
                            if (this.emails.length < 1) {
                                return wp2sv.alert(l10n.remove.not_allowed)
                            }

                            wp2sv.confirm('', sprintf(l10n.remove.app_confirm, this.emails[0].e), function (yes) {
                                if (yes) {
                                    wp2sv.post({
                                        action: 'remove-app'
                                    }).done(function (result) {
                                        if (!result) {
                                            return;
                                        }
                                        if (result.success) {
                                            self.mobile_dev = '';
                                        } else {

                                        }
                                    }).always(function () {
                                    });
                                }
                            })
                        },
                        removeEmail: function (id) {
                            var email = this.emails[id],
                                self = this;
                            if (!this.mobile_dev && this.emails.length === 1) {
                                return wp2sv.alert(l10n.remove.not_allowed)
                            }
                            wp2sv.toast.info(toastL10n.working);
                            wp2sv.post({
                                action: 'remove-email',
                                email: email.id
                            }).done(function (result) {
                                if (!result) {
                                    return;
                                }
                                if (result.success) {
                                    self.emails.splice(id, 1);
                                } else {

                                }
                            }).always(function () {
                                wp2sv.toast.hide();
                            });

                        },
                        primaryMail: function (index) {
                            var email = this.emails[index],
                                self = this;
                            wp2sv.toast.info(toastL10n.working);
                            wp2sv.post({
                                action: 'primary-email',
                                email: email.id
                            }).done(function (result) {
                                if (!result) {
                                    return;
                                }
                                if (result.success) {
                                    self.emails.splice(index, 1);
                                    self.emails.unshift(email);
                                } else {

                                }
                            }).always(function () {
                                wp2sv.toast.hide();
                            });
                        },
                        updateData:function(key,value){
                            this[key]=value;
                        },
                        sprintf: sprintf
                    },
                    computed: {}
                });
            }
        },
        registerComponents:function(){
            Vue.component('wp2sv-clock',require('./components/clock'));
            Vue.component('wp2sv-enroll-email',require('./components/enroll-email'));
            Vue.component('wp2sv-enroll-app',require('./components/enroll-app'));
            Vue.component('wp2sv-enroll-welcome',require('./components/enroll-welcome'));

            Vue.component('wp2sv-start',require('./components/start'));

            Vue.component('authenticator', require('./components/authenticator'));

            Vue.component('backup-codes',require('./components/backup-codes'));

            Vue.component('wp2sv-emails',require('./components/emails'));
            Vue.component('wp2sv-app-passwords',require('./components/app-passwords'));
        }
    };
    module.init();
    return module;
});