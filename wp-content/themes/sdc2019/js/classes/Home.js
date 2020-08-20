import ParentClass from './Parent.js';

class Home extends ParentClass {
  constructor() {
    super();
    const moduleClass = '.content-home';
    if (!super.runThisModule(moduleClass)) return;

    $("#signup-link").on('click', function(ev){
        ev.preventDefault();

        $(this).addClass('d-none');
        $("#mc_embed_signup").removeClass("d-none");
        $("#mc_embed_signup__response__inner").removeClass("d-none");

        $("#mc_embed_signup__close_btn").removeClass("invisible");
    });

    $("#mc_embed_signup__close_btn").on('click', function(ev){
        ev.preventDefault();

        $("#mc_embed_signup").addClass('d-none');
        $("#signup-link").removeClass("d-none");
        $("#mc_embed_signup__response__inner").addClass('d-none');

        $(this).addClass("invisible");
    });

    const $videoModal = $("#videoModal");
    const videoModalEmbed = $("#videoModal").find('iframe').first().get(0);
    const originalEmbedUrl = videoModalEmbed.getAttribute('src');
    // const queryString = originalEmbedUrl.indexOf('?') !== -1 ? '&autoplay=1' : '?autoplay=1';
    const queryString = '?autoplay=1';
    if( $videoModal.length ){
        $videoModal.on('shown.bs.modal', function(e){
            videoModalEmbed.setAttribute('src', originalEmbedUrl+queryString);
        });
        $videoModal.on('hide.bs.modal', function(ev){
            videoModalEmbed.setAttribute('src', originalEmbedUrl);
        });
    }

      /* sticky header below static promo bar */
      $(window).scroll(function() {
          let $header = $('.main-header');
          let $banner = $('.early-bird-banner');
          if ($(window).scrollTop() > 0) {
              $banner.addClass('hidden');
              $header.addClass('sticky');
              $('body').addClass('sticky-header');
          } else {
              $banner.removeClass('hidden');
              $header.removeClass('sticky');
              $('body').removeClass('sticky-header');
          }
      });

  }
}

jQuery(document).ready(function() {
	new Home();
});
