import ParentClass from './Parent.js';
import {TweenMax, Power2, TimelineLite} from 'gsap/TweenMax';
import ScrollMagic from 'scrollmagic';

class HomeAnimations extends ParentClass {
  constructor() {
    super();
    const moduleClass = '.content-home';
    if (!super.runThisModule(moduleClass)) return;

    this.controller = new ScrollMagic.Controller();
    this.$homeBodySymbols = $('.home-body-symbols');
    this.scrollPos = 0;

    this.animHighlightRows();
    // this.animSymbols();
    this.animFooterSymbols();

    $(window).on('load scroll', e => {
      this.scrollPos = $(e.currentTarget).scrollTop();
      this.parallax();
    });

    this.symbolClassesOnScroll(this.$homeBodySymbols.find('.symbol'));
  }

  parallax() {
    let offset = -.15;
    // Add adjustment so the symbols aren't so far off when in view.
    let pos = this.scrollPos - 900;
    if (this.scrollPos <= 0) pos = 0;
    TweenMax.set(this.$homeBodySymbols, {
      x: 0,
      y: -(pos * offset)
    });
  }

  animHighlightRows() {
    let img = $('.highlight-row img');
    let desc = $('.highlight-row .description');
    this.anim(img, 100);
    this.anim(desc);
  }

  animSymbols() {
    let symbols = this.$homeBodySymbols.find('.symbol');
    this.anim(symbols);
  }

  animFooterSymbols() {
    if ($(window).width() >= 1200) {
      let symbols = $('.content-footer-box img.footer-symbols');
      this.anim(symbols, 200);
    }
  }

  anim(el, offset = 50) {
    for (let i=0; i<el.length; i++) {
      new ScrollMagic.Scene({
        triggerElement: el[i],
        offset: offset,
        triggerHook: 0.9,
      })
      .setClassToggle(el[i], 'anim-visible')
      .addTo(this.controller);
    }
  }

  symbolClassesOnScroll(el) {
    let factor = 150;
    let elH = el.outerHeight();
    let elHalf = elH / 2;
    for (let i=0; i<el.length; i++) {
      new ScrollMagic.Scene({
        triggerElement: el[i],
        offset: elHalf + factor,
        triggerHook: 1, // fraction of the window height. (1 = 100%)
        duration: $(window).height() - (factor * 2),
      })
      .setClassToggle(el[i], 'view-in')
      .addTo(this.controller);
    }
  }

}

jQuery(document).ready(function() {
	new HomeAnimations();
});
