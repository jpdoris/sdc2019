!function(e){var t={};function n(s){if(t[s])return t[s].exports;var o=t[s]={i:s,l:!1,exports:{}};return e[s].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,s){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:s})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=4)}([function(e,t){e.exports=function(e,t,n,s,o,i){var r,a=e=e||{},c=typeof e.default;"object"!==c&&"function"!==c||(r=e,a=e.default);var l,u="function"==typeof a?a.options:a;if(t&&(u.render=t.render,u.staticRenderFns=t.staticRenderFns,u._compiled=!0),n&&(u.functional=!0),o&&(u._scopeId=o),i?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),s&&s.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},u._ssrRegister=l):s&&(l=s),l){var p=u.functional,d=p?u.render:u.beforeCreate;p?(u._injectStyles=l,u.render=function(e,t){return l.call(t),d(e,t)}):u.beforeCreate=d?[].concat(d,l):[l]}return{esModule:r,exports:a,options:u}}},function(e,t,n){"use strict";t.a=jQuery},function(e,t,n){"use strict";t.a=wp2sv_setup.l10n},function(e,t,n){var s;!function(){"use strict";var o={not_string:/[^s]/,not_bool:/[^t]/,not_type:/[^T]/,not_primitive:/[^v]/,number:/[diefg]/,numeric_arg:/[bcdiefguxX]/,json:/[j]/,not_json:/[^j]/,text:/^[^\x25]+/,modulo:/^\x25{2}/,placeholder:/^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,key:/^([a-z_][a-z_\d]*)/i,key_access:/^\.([a-z_][a-z_\d]*)/i,index_access:/^\[(\d+)\]/,sign:/^[\+\-]/};function i(e){return function(e,t){var n,s,r,a,c,l,u,p,d,f=1,m=e.length,h="";for(s=0;s<m;s++)if("string"==typeof e[s])h+=e[s];else if(Array.isArray(e[s])){if((a=e[s])[2])for(n=t[f],r=0;r<a[2].length;r++){if(!n.hasOwnProperty(a[2][r]))throw new Error(i('[sprintf] property "%s" does not exist',a[2][r]));n=n[a[2][r]]}else n=a[1]?t[a[1]]:t[f++];if(o.not_type.test(a[8])&&o.not_primitive.test(a[8])&&n instanceof Function&&(n=n()),o.numeric_arg.test(a[8])&&"number"!=typeof n&&isNaN(n))throw new TypeError(i("[sprintf] expecting number but found %T",n));switch(o.number.test(a[8])&&(p=n>=0),a[8]){case"b":n=parseInt(n,10).toString(2);break;case"c":n=String.fromCharCode(parseInt(n,10));break;case"d":case"i":n=parseInt(n,10);break;case"j":n=JSON.stringify(n,null,a[6]?parseInt(a[6]):0);break;case"e":n=a[7]?parseFloat(n).toExponential(a[7]):parseFloat(n).toExponential();break;case"f":n=a[7]?parseFloat(n).toFixed(a[7]):parseFloat(n);break;case"g":n=a[7]?String(Number(n.toPrecision(a[7]))):parseFloat(n);break;case"o":n=(parseInt(n,10)>>>0).toString(8);break;case"s":n=String(n),n=a[7]?n.substring(0,a[7]):n;break;case"t":n=String(!!n),n=a[7]?n.substring(0,a[7]):n;break;case"T":n=Object.prototype.toString.call(n).slice(8,-1).toLowerCase(),n=a[7]?n.substring(0,a[7]):n;break;case"u":n=parseInt(n,10)>>>0;break;case"v":n=n.valueOf(),n=a[7]?n.substring(0,a[7]):n;break;case"x":n=(parseInt(n,10)>>>0).toString(16);break;case"X":n=(parseInt(n,10)>>>0).toString(16).toUpperCase()}o.json.test(a[8])?h+=n:(!o.number.test(a[8])||p&&!a[3]?d="":(d=p?"+":"-",n=n.toString().replace(o.sign,"")),l=a[4]?"0"===a[4]?"0":a[4].charAt(1):" ",u=a[6]-(d+n).length,c=a[6]&&u>0?l.repeat(u):"",h+=a[5]?d+n+c:"0"===l?d+c+n:c+d+n)}return h}(function(e){if(a[e])return a[e];var t,n=e,s=[],i=0;for(;n;){if(null!==(t=o.text.exec(n)))s.push(t[0]);else if(null!==(t=o.modulo.exec(n)))s.push("%");else{if(null===(t=o.placeholder.exec(n)))throw new SyntaxError("[sprintf] unexpected placeholder");if(t[2]){i|=1;var r=[],c=t[2],l=[];if(null===(l=o.key.exec(c)))throw new SyntaxError("[sprintf] failed to parse named argument key");for(r.push(l[1]);""!==(c=c.substring(l[0].length));)if(null!==(l=o.key_access.exec(c)))r.push(l[1]);else{if(null===(l=o.index_access.exec(c)))throw new SyntaxError("[sprintf] failed to parse named argument key");r.push(l[1])}t[2]=r}else i|=2;if(3===i)throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");s.push(t)}n=n.substring(t[0].length)}return a[e]=s}(e),arguments)}function r(e,t){return i.apply(null,[e].concat(t||[]))}var a=Object.create(null);t.sprintf=i,t.vsprintf=r,"undefined"!=typeof window&&(window.sprintf=i,window.vsprintf=r,void 0===(s=function(){return{sprintf:i,vsprintf:r}}.call(t,n,t,e))||(e.exports=s))}()},function(e,t,n){n(5),n(27),n(28),e.exports=n(29)},function(e,t,n){(function(e){var t,s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};(t="object"===("undefined"==typeof self?"undefined":s(self))&&self.self===self&&self||"object"===(void 0===e?"undefined":s(e))&&e.global===e&&e).wp2sv=t.wp2sv||{},t.wp2sv.setup=function(e,t,s){var o=n(3).sprintf,i=wp2sv_setup.l10n,r=i.toast,a={init:function(){this.registerComponents(),s("#wp2sv-setup").length&&(this.vm=new e({el:"#wp2sv-setup",data:function(){return wp2sv_setup},mounted:function(){this.$on("reload:data",this.reloadData),this.$on("reload",this.reload),this.$on("refresh",this.reloadData),this.$on("update",this.updateData)},methods:{enable:function(e){e&&e.preventDefault(),this.$root.$emit("enroll:start")},disable:function(e){e&&e.preventDefault();var t=this;wp2sv.confirm(i.turn_off.confirm.title,i.turn_off.confirm.message,function(e){e&&wp2sv.post("disable").done(function(){t.enabled=!1})})},reload:function(){window.location.href=this.home},reloadData:function(){var e=this;return wp2sv.toast.info(r.loading),s.ajax({type:"POST",dataType:"json",url:ajaxurl,data:{action:"wp2sv_setup_data"}}).done(function(t){t?(Object.assign(e.$data,t),wp2sv.toast.hide()):e.reload()}).fail(function(){e.reload()})},removeApp:function(){var e=this;if(this.emails.length<1)return wp2sv.alert(i.remove.not_allowed);wp2sv.confirm("",o(i.remove.app_confirm,this.emails[0].e),function(t){t&&wp2sv.post({action:"remove-app"}).done(function(t){t&&t.success&&(e.mobile_dev="")}).always(function(){})})},removeEmail:function(e){var t=this.emails[e],n=this;if(!this.mobile_dev&&1===this.emails.length)return wp2sv.alert(i.remove.not_allowed);wp2sv.toast.info(r.working),wp2sv.post({action:"remove-email",email:t.id}).done(function(t){t&&t.success&&n.emails.splice(e,1)}).always(function(){wp2sv.toast.hide()})},primaryMail:function(e){var t=this.emails[e],n=this;wp2sv.toast.info(r.working),wp2sv.post({action:"primary-email",email:t.id}).done(function(s){s&&s.success&&(n.emails.splice(e,1),n.emails.unshift(t))}).always(function(){wp2sv.toast.hide()})},updateData:function(e,t){this[e]=t},sprintf:o},computed:{}}))},registerComponents:function(){e.component("wp2sv-clock",n(7)),e.component("wp2sv-enroll-email",n(10)),e.component("wp2sv-enroll-app",n(12)),e.component("wp2sv-enroll-welcome",n(14)),e.component("wp2sv-start",n(16)),e.component("authenticator",n(19)),e.component("backup-codes",n(21)),e.component("wp2sv-emails",n(23)),e.component("wp2sv-app-passwords",n(25))}};return a.init(),a}(Vue,_,jQuery)}).call(t,n(6))},function(e,t){var n;n=function(){return this}();try{n=n||Function("return this")()||(0,eval)("this")}catch(e){"object"==typeof window&&(n=window)}e.exports=n},function(e,t,n){var s=n(0)(n(8),n(9),!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(3).sprintf;t.default={props:["serverTime","localTime","gmtOffset","serverText","localText","syncText"],data:function(){return{tickerId:null,server:0,local:0,server_time:"",local_time:"",gmt_offset:"",loading:0,ready:0,sprintf:s}},created:function(){this.gmt_offset=this.gmtOffset>0?"+"+this.gmtOffset:this.gmtOffset,this.getTime(this.serverTime,this.localTime),this.ticker(),this.tickerId=setInterval(this.ticker,1e3),this.ready=1,this.syncTime()},destroyed:function(){this.tickerId&&clearInterval(this.tickerId)},methods:{ticker:function(){var e=(new Date).getTime();this.server_time=this.timeString(e+this.server),this.local_time=this.timeString(e+this.local)},syncTime:function(){this.loading=1;var e=this;wp2sv.post("time_sync").then(function(t){return e.loading=0,e.getTime(t.data.server,t.data.local),t})},getTime:function(e,t){e&&(e*=1e3,this.server=e-(new Date).getTime()),t&&(t*=1e3,this.local=t-(new Date).getTime())},timeString:function(e){var t=new Date(e),n=t.getUTCFullYear(),s=t.getUTCMonth()+1,o=t.getUTCDate(),i=t.getUTCHours(),r=t.getUTCMinutes(),a=t.getUTCSeconds(),c=function(e){return e<10&&(e="0"+e),e};return i=c(i),n+"-"+(s=c(s))+"-"+(o=c(o))+" "+i+":"+(r=c(r))+":"+(a=c(a))}}}},function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.ready?n("div",{staticClass:"wp2sv-clock"},[n("p",{staticClass:"time-utc"},[e._v("\n        "+e._s(e.serverText)+": "),n("span",[e._v(e._s(e.server_time))]),e._v(" "),n("a",{staticClass:"sync-link",class:e.loading?"loading":"",attrs:{id:"sync-clock",title:e.syncText},on:{click:e.syncTime}},[e._v(e._s(e.syncText))])]),e._v(" "),n("p",{staticClass:"time-local"},[e._v("\n        "+e._s(e.sprintf(e.localText,e.gmt_offset))+": "),n("span",[e._v(e._s(e.local_time))])])]):e._e()},staticRenderFns:[]}},function(e,t,n){var s=n(0)(null,n(11),!1,null,null,null);e.exports=s.exports},function(e,t){e.exports={render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"wp2sv-card"},[t("wp2sv-emails",{attrs:{enroll:"1"}})],1)},staticRenderFns:[]}},function(e,t,n){var s=n(0)(null,n(13),!1,null,null,null);e.exports=s.exports},function(e,t){e.exports={render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"wp2sv-card"},[t("authenticator",{attrs:{enroll:"1"}})],1)},staticRenderFns:[]}},function(e,t,n){var s=n(0)(n(15),null,!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={template:"#wp2sv-enroll-welcome"}},function(e,t,n){var s=n(0)(n(17),n(18),!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{step:"welcome"}},created:function(){this.$root.$on("enroll:cancel",this.cancel),this.$root.$on("enroll:start",this.start),this.$root.$on("enroll:email-flow",this.emailEnroll),this.$root.$on("enroll:app-flow",this.appEnroll)},methods:{start:function(e){e&&e.preventDefault(),this.emailEnroll()},appEnroll:function(e){this.step="app"},emailEnroll:function(){this.step="email"},cancel:function(){this.step="welcome"}},computed:{stepComponent:function(){return"wp2sv-enroll-"+this.step}}}},function(e,t){e.exports={render:function(){var e=this.$createElement;return(this._self._c||e)(this.stepComponent,{tag:"component"})},staticRenderFns:[]}},function(e,t,n){var s=n(0)(n(20),null,!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(1),o=n(2);t.default={props:["enroll"],template:"#wp2sv-authenticator",data:function(){return{step:"select-device",device:"android",error_code:"",code:"",qr_url:"",secret:"",l10n:o.a}},mounted:function(){var e=this;Object(s.a)(this.$el).closest(".wp2sv-modal").on("close",function(){e.reset()})},watch:{step:function(){"setup"===this.step&&this.loadQrCodes()}},computed:{formatted_secret:function(){return this.secret.replace(/(.{4})/g,"$1 ").trim()}},methods:{next:function(){var e=this;switch(this.step){case"select-device":this.step="setup";break;case"setup":case"manually-setup":this.step="test";break;case"test":this.testCode();break;case"complete":e.$root.$emit("update","mobile_dev",this.device),wp2sv.closeModal();break;case"turn-on":this.disabled=!0,wp2sv.post({action:"enable",code:this.code,secret:this.secret,device:this.device}).done(function(t){t&&t.success?e.$root.$emit("reload:data"):e.$root.$emit("reload")}).always(function(){e.disabled=!1})}},testCode:function(){var e=this;this.code?(this.disabled=!0,wp2sv.post({action:"test-code",code:this.code,secret:this.secret,changeDevice:this.enroll?"":1,device:this.device}).done(function(t){t&&(t.success?e.enroll?e.step="turn-on":e.step="complete":e.error_code=o.a.code.invalid)}).always(function(){e.disabled=!1})):this.error_code=o.a.code.required},back:function(){this.step="setup"},manually:function(){this.step="manually-setup"},reset:function(){s.a.extend(this.$data,{step:"select-device",device:"android",error_code:"",code:"",qr_url:"",secret:""})},loadQrCodes:function(){var e=this;wp2sv.get("qrcode").then(function(e){return!(!e||!e.success)&&e.data}).then(function(t){t&&(e.qr_url=t.url,e.secret=t.secret)})},useEmail:function(e){e&&e.preventDefault(),this.$root.$emit("enroll:email-flow")}}}},function(e,t,n){var s=n(0)(n(22),null,!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(1),o=n(2);t.default={template:"#wp2sv-backup-codes",data:function(){return{backup_codes:!1,date:""}},mounted:function(){this.loadCode()},methods:{download:function(){var e=ajaxurl+"?action=wp2sv&wp2sv_action=download_backup_codes&wp2sv_nonce="+wp2sv._nonce;window.location.href=e},loadCode:function(){var e=this;Object(s.a)(this.$el).closest(".wp2sv-modal").on("open",function(){e.getCodes()})},getCodes:function(e){var t=this;t.backup_codes=!1,wp2sv.get("backup-codes",{generate:e?1:0}).then(function(e){if(e&&e.success&&e.data&&e.data.codes)return e.data}).then(function(e){e&&(t.$root.backup_codes=e.unused,t.backup_codes=e.codes,t.date=e.date)})},print:function(){document.body.scrollTop=0,document.documentElement.scrollTop=0,window.print()},generate:function(){var e=this;wp2sv.confirm(o.a.backup.get,o.a.backup.confirm,function(t){t&&e.getCodes(!0)})},sprintf:sprintf}}},function(e,t,n){var s=n(0)(n(24),null,!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(1),o=n(2),i=o.a.toast;t.default={props:["enroll"],template:"#wp2sv-emails",data:function(){return{step:"email",l10n:o.a,next_text:o.a.next,email:"",code:"",error_email:"",error_code:"",disabled:!1}},mounted:function(){var e=this;Object(s.a)(this.$el).closest(".wp2sv-modal").on("close",function(){e.reset()})},methods:{useApp:function(){this.$root.$emit("enroll:app-flow")},startOver:function(e){e&&e.preventDefault(),this.step="email"},next:function(){var e=this;switch(this.step){case"email":this.error_email="",this.email?(this.disabled=!0,wp2sv.toast.info(i.working),wp2sv.post({action:"send-email",email:e.email}).done(function(t){t&&(t&&t.success?e.step="test":t.data&&t.data.message&&(e.error_email=t.data.message))}).fail(function(){wp2sv.toast.error(i.failed)}).always(function(){e.disabled=!1,wp2sv.toast.hide()})):this.error_email=o.a.email.invalid;break;case"test":this.disabled=!0,wp2sv.post({action:"test-code",code:this.code,email:this.email,updateEmail:!this.enroll}).done(function(t){t&&(t.success?e.enroll?e.step="turn-on":(e.$root.$emit("update","emails",t.data.emails),e.complete()):e.error_code=o.a.code.invalid)}).always(function(){e.disabled=!1});break;case"turn-on":this.disabled=!0,wp2sv.post({action:"enable",code:this.code,email:this.email}).done(function(t){t&&t.success?e.$root.$emit("reload:data"):e.$root.$emit("reload")}).always(function(){e.disabled=!1});break;case"complete":wp2sv.closeModal()}},complete:function(){wp2sv.closeModal()},reset:function(){s.a.extend(this.$data,{step:"email",next_text:o.a.next,email:"",code:"",error_email:"",error_code:"",disabled:!1})},cancel:function(){this.$root.$emit("enroll:cancel")}},watch:{step:function(){"turn-on"===this.step?this.next_text=o.a.turn_on:this.next_text=o.a.next}}}},function(e,t,n){var s=n(0)(n(26),null,!1,null,null,null);e.exports=s.exports},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(2),o=n(1),i=s.a.toast;t.default={props:["app_passwords"],template:"#wp2sv-app-passwords",data:function(){return{passwords:this.app_passwords||[],name:""}},methods:{generate:function(){var e=this;wp2sv.toast.info(i.working),wp2sv.post("password_create",{name:this.name}).done(function(t){t.data&&(e.passwords.push(t.data),e.showPassword(t.data.p)),wp2sv.toast.hide()})},remove:function(e){var t=this,n=this.passwords[e].i;wp2sv.toast.info(i.working),wp2sv.post("password_remove",{index:n}).done(function(n){n.success&&t.passwords.splice(e,1),wp2sv.toast.hide()})},showPassword:function(e){var t=Object(o.a)("#app-password-created"),n="";"string"==typeof e&&e.match(/.{1,4}/g).forEach(function(e){n+='<span class="apc-pchunk"><span>'+e.split("").join("</span><span>")+"</span></span>"});t.find(".apc-pass").html(n),wp2sv.openModal(t)}},mounted:function(){console.log(this.passwords)}}},function(e,t){},function(e,t){},function(e,t){}]);