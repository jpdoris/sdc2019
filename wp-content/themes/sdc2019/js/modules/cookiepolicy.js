export default class CookiePolicy {
  constructor($cookiePolicy) {
    this.$cookiePolicy = $cookiePolicy;

    this.config = {
      openClass: 'show',
      cookie: {
        name: "cookie-layer",
        expireDays: '365',
      },
    }

    this.checkCookieLayerStatus();
  }

  checkCookieLayerStatus() {
    if (!this.getCookies(this.config.cookie.name)) {
      this.$cookiePolicy.addClass(this.config.openClass);
      this.bindEvents();
    }
  }

  bindEvents() {
    // this.$cookiePolicy.find('svg').on('click', this.closeCookieLayer.bind(this));
    this.$cookiePolicy.find('#cookie-policy__link, #cookie-policy__close-btn').on('click', this.closeCookieLayer.bind(this));
  }

  closeCookieLayer() {
    $(this.$cookiePolicy).fadeOut();
    this.setCookie(this.config.cookie.name, 'true', this.config.cookie.expireDays);
    if( $('body').hasClass('home') && $('.content-home').length ){
      // $('.content-home').removeClass('cookie-policy-shown').addClass('cookie-policy-hidden');
    }
  }

  getCookies(cname) {
    let name = cname + '=',
      ca = document.cookie.split(';'),
      c;

    for (let i = 0; i < ca.length; i++) {
      c = ca[i];
      while (c.charAt(0) === ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length);
      }
    }
    return ''
  }

  setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = 'expires=' + d.toUTCString();
    document.cookie = cname + '=' + cvalue + ';expires=' + expires + ';path=/';
  }
}
