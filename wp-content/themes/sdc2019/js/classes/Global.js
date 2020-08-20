import ParentClass from './Parent.js';
import CookiePolicy from '../modules/cookiepolicy.js';

class Global extends ParentClass {
    constructor() {
        super();
        const moduleClass = '';
        if (!super.runThisModule(moduleClass)) return;

        $('a.prevent-default').click((e) => e.preventDefault());

        let $html = $('html'),
            $cookiePolicy = $html.find('.cookie-policy');

        $cookiePolicy.each((index, $element) => {
            const cp = new CookiePolicy($($element));
        });

        this.openNav();
        this.closeNav();

        /* sticky header */
        $(window).scroll(function () {
          let $header = $('.header-launch');
          if ($(window).scrollTop() > 0) {
            $header.addClass('sticky');
          } else {
            $header.removeClass('sticky');
          }
        });
    }

    openNav() {
        $('.collapse').on('shown.bs.collapse', function() {
            if (!$('#navbarContent .close-nav').length) {
                $('#navbarContent').append(
                    '<button type="button" class="close-nav navbar-toggle" data-toggle="collapse" data-target="#navbarContent" aria-label="Close">\n' +
                    '  <span class="close-nav-content" aria-hidden="true">&times;</span>\n' +
                    '</button>');
            }

            if ($(window).width() < 1200) {
                $('.close-nav').css('visibility', 'visible');
            }
        });


        $('#navbarContent').on('show.bs.collapse', () => {
            if (this.getCurrentBreakpoint() < 1200) {
                $('body').addClass('mobile-no-scroll');
            }
        });
    }

    closeNav() {
        $('.close-nav').on('click', function() {
            $('.close-nav').css('visibility', 'hidden');
        });

        $('#navbarContent').on('hide.bs.collapse', () => {
            if ($('body').hasClass('mobile-no-scroll')) {
                $('body').removeClass('mobile-no-scroll');
            }
        });
    }
}

jQuery(document).ready(function() {
    new Global();
});
