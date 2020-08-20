import ParentClass from './Parent.js';
import 'waypoints/lib/noframework.waypoints.min.js';
import 'hideseek/jquery.hideseek.js';

class Faq extends ParentClass {
    constructor() {
        super();
        const moduleClass = '.content-faq';
        if (!super.runThisModule(moduleClass)) return;

        const self = this;

        /**
         * Regex validators for specific contact form fields.
         * @var object
         */
        this.validators = {
            'your-name': /\S+/,
            'your-email': /[\S+]+\@[\S+]+\.[\S+]+/,
            'your-message': /\S+/,
        };

        /* hide sent form when mail is sent and thank you message appears */
        var wpcf7Elm = document.querySelector( '.wpcf7' );

        wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
            $('.contact-form-collapse').collapse('hide');

            if ($(window).width() < 1200) {
                $('.close').css('float', 'right');
                $('.close').css('width', '18px');
                $('.close-mobile').html('<img src="/wp-content/themes/sdc2019/images/modal-close.svg">');
            } else {
                $('.modal-body').find('h3').attr('class', 'd-none');
            }
        }, false );


        /**
         * A hash of Hideseek JS configuration options.
         * @var object
         */
        this.hideSeekOptions = {
            headers: '.question',
            highlight: true
        };


        const $primary_color = '#1428A0';

        // set sticky TOC for IE support
        // let stickyElements = $('.toc');
        // Stickyfill.add(stickyElements);

        // set nav waypoints
        // let waypointInfo = new Waypoint({
        //     element: $('#information-anchor'),
        //     handler: function() {
        //         $('.topics-list').find('a').css('color', 'black');
        //         $('#a-information').css('color', $primary_color);
        //     }
        // });
        // let waypointRegister = new Waypoint({
        //     element: $('#register-anchor'),
        //     handler: function() {
        //         $('.topics-list').find('a').css('color', 'black');
        //         $('#a-register').css('color', $primary_color);
        //     }
        // });
        // let waypointPolicies = new Waypoint({
        //     element: $('#policies-anchor'),
        //     handler: function() {
        //         $('.topics-list').find('a').css('color', 'black');
        //         $('#a-policies').css('color', $primary_color);
        //     }
        // });
        // let waypointContact = new Waypoint({
        //     element: $('#contact-anchor'),
        //     handler: function() {
        //         // $('.topics-list').find('a').css('color', 'black');
        //         // $('#a-contact').css('color', $primary_color);
        //     }
        // });

        /* make anchor point links account for sticky header by scrolling higher */
        window.addEventListener("hashchange", function () {
            let $offset = 63;
            let $windowsize = $(window).width();
            if ($windowsize > 576) {
                $offset = 111;
            }
            window.scrollTo(window.scrollX, window.scrollY - $offset);
        });

        // Validate contact form when values change.
        $('.wpcf7-form input, .wpcf7-form textarea').change(function() {
            self.validateContactForm($(this).attr('name'));
        });

        // Search FAQs, show results.
        $('#faq-search input').hideseek(this.hideSeekOptions).on('_after', function(e) {
            self.showEmptyText();

            // Expand/contract all questions when search begins/clears.
            if ($(this).val()) {
                $('.grid-item .answer').addClass('open');
                $('.grid-item').addClass('open');
            }
            else {
                $('.grid-item .answer').removeClass('open');
                $('.grid-item').removeClass('open');
            }
        });

        // Prevent search form from submitting.
        $('#faq-search').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        // Open/close FAQ accordions.
        $('.grid-item .question').on('click', function(e) {
            $(this).siblings('.answer').toggleClass('open');
            $(this).parent().toggleClass('open');
        });

        /**
         * Validate the fields from this.validators.
         *
         * @param string name
         *   the form field name to validate
         * @returns {Hilton.Modules.Contact}
         */
        this.validateContactForm = function(name) {
            var regex = this.validators[name] || /\S+/;
            var $input = $('[name="' + name + '"]');
            var $validator = $input.parent().siblings('.validator');
            if (!$input.length || !$validator.length || !regex) return this;

            if ($input.val().match(regex)) {
                $validator.addClass('valid');
                return true;
            }
            else {
                $validator.removeClass('valid');
                return false;
            }

            return this;
        };

        /**
         * Check if each grid row should have an empty results text showing.
         */
        this.showEmptyText = function() {
            $('.grid').each(function() {
                if ($(this).children('.grid-item:visible').length) {
                    $(this).children('.no-results').hide();
                }
                else {
                    $(this).children('.no-results').show();
                }
            });
        }

    }
}

jQuery(document).ready(function() {
    new Faq();
});