import ParentClass from '../classes/Parent.js';

class FeaturedSpeakers extends ParentClass {
  constructor() {
    super();
    const moduleClass = '.content-featured-speakers';
    if (!super.runThisModule(moduleClass)) return;

    this.initSlick();
  }

  initSlick() {
    $('.slick-speakers').slick({
      centerMode: true,
      // centerPadding: '255px',
      slidesToShow: 2,
      variableWidth: true,
      slidesToScroll: 1,
      // focusOnSelect: true,
      dots: true,
      arrows: false,
      infinite: true,
      swipeToSlide: true,
      // responsive: [
      //   {
      //     breakpoint: 768,
      //     settings: {
      //       arrows: false,
      //       centerMode: true,
      //       centerPadding: '40px',
      //       slidesToShow: 1
      //     }
      //   },
      //   {
      //     breakpoint: 480,
      //     settings: {
      //       arrows: false,
      //       centerMode: true,
      //       centerPadding: '40px',
      //       slidesToShow: 1
      //     }
      //   }
      // ]
    });
  }
}

jQuery(document).ready(function() {
	new FeaturedSpeakers();
});
