export default class ParentClass {
  constructor() {

    // Define Bootstrap Breakpoints.
    this.breakpoints = {
      xs: 0,
      sm: 576,
      md: 768,
      lg: 992,
      xl: 1200
    }
  }

  /**
   * If module class does not exist on the current page then prevents the
   * current js module class from defining properties and calling methods.
   * @param  {String}  [moduleClass=''] Module container class name.
   * @return {Boolean}
   */
  runThisModule(moduleClass = '') {
    return !(moduleClass && !$(moduleClass).length);
  }

  /**
   * Get the current breakpoint to use.
   * @returns {number}
   */
  getCurrentBreakpoint() {
    const windowWidth = $(window).width();

    if (windowWidth < this.breakpoints.xs) {
      return this.breakpoints.xxs;
    } else if (windowWidth >= this.breakpoints.xs && windowWidth < this.breakpoints.sm) {
      return this.breakpoints.xs;
    } else if (windowWidth >= this.breakpoints.sm && windowWidth < this.breakpoints.md) {
      return this.breakpoints.sm;
    } else if (windowWidth >= this.breakpoints.md && windowWidth < this.breakpoints.lg) {
      return this.breakpoints.md;
    } else if (windowWidth >= this.breakpoints.lg) {
      return this.breakpoints.lg;
    } else {
      return 0;
    }
  }

  /**
   * Get a url parameter value
   * @param sParam
   * @returns {*}
   */
  getUrlParameter(sParam) {
    const sPageURL = window.location.search.substring(1);
    const sURLVariables = sPageURL.split('&');
    for (let i = 0; i < sURLVariables.length; i++) {
      let sParameterName = sURLVariables[i].split('=');
      if (sParameterName[0] == sParam) {
        return sParameterName[1];
      }
    }
    return false;
  }

  /**
   * Smooth scroll to an element on the page.
   * @param fromElement
   * @param toElement
   * @param duration
   */
  smoothScrollOnClick(fromElement, toElement, duration) {
    $(fromElement).on('click', e => {
      e.preventDefault();
      if (!$(toElement).length) return;
      $('html, body').animate({
        scrollTop: $(toElement).offset().top + 'px'
      }, duration, 'swing');
    });
  }

  /**
   * Apply equal heights to multiple elements inside of a container.
   * @param object $page Jquery object of the page container. E.g. $('.content-example')
   * @param object $container Jquery object of the container element. E.g. $('.content-example .row')
   * @param string el Class of the elements inside of the container. E.g. '.grant .content-top'
   * @param integer bp Breakpoint above which the equal heights takes affect.
   *
   * E.g.
   this.equalHeights($('.content-example'), $('.content-example .row'), '.grant .top-content', 767);
   */
  equalHeights($page, $container, el, bp) {
    if ($page.length) {
      $(window).on('load resize', function() {
        if ($(window).width() >= bp) {
          // Reset before setting.
          $container.find(el).attr('style', '');
          // Iterate over container elements.
          $container.each(function() {
            let h = 0;
            // Iterate over target elements.
            $(this).find(el).each(function() {
              let elHeight = $(this).height();
              if (elHeight > h)
                h = elHeight;
            });
            // Apply height to target elements.
            $(this).find(el).height(h);
          });
        } else {
          // Remove element styling.
          $container.find(el).attr('style', '');
        }
      });
    }
  }

  /**
   * Format dates to e.g. 'March 15, 2017'.
   * @param  {string} str Date string.
   * @return {string}     Formatted date.
   */
  formatDate(str) {
    // Remove ordinals.
    const str2 = str.replace(/(\d+)(st|nd|rd|th)/, '$1');
    const date = new Date(str2);
    const monthNames = [
      'January', 'February', 'March',
      'April', 'May', 'June', 'July',
      'August', 'September', 'October',
      'November', 'December',
    ];
    const day = date.getDate();
    const monthIndex = date.getMonth();
    const year = date.getFullYear();
    return `${monthNames[monthIndex]} ${day}, ${year}`;
  }


  /**
   * Set a cookie.
   * @param {string} cname  Cookie name.
   * @param {string} cvalue Cookie value.
   * @param {integer} exdays Number of days until expiration.
   */
  setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  /**
   * Get a cookie.
   * @param  {string} cname Name of the cookie.
   * @return {string}       Return the value of the cookie if exists. Else empty string.
   */
  getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  /**
   * Check if a cookie exists.
   * @return {boolean} True/false
   */
  checkCookie(cname) {
    var cvalue = this.getCookie(cname);
    return (cvalue && cvalue != "") ? true : false;
  }

  /**
   * Add/remove a class on the body tag at a given scroll location.
   * @param string scrollClass Scroll class.
   * @param integer breakpoint  Breakpoint at which to start adding the class.
   * @param integer scrollLoc   Scroll location from the top of the document.
   */
  addClassOnScrollLoc(scrollClass, breakpoint, scrollLoc) {
    $(window).on('scroll resize load', function () {
      if ($(this).width() >= breakpoint) {
        if ($(this).scrollTop() >= scrollLoc && !$('body').hasClass(scrollClass)) {
          $('body').addClass(scrollClass);
        } else if ($(this).scrollTop() < scrollLoc && $('body').hasClass(scrollClass)) {
          $('body').removeClass(scrollClass);
        }
      } else {
        if ($('body').hasClass(scrollClass)) {
          $('body').removeClass(scrollClass);
        }
      }
    });
  }
}
