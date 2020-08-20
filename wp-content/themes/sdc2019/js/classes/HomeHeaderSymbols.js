import ParentClass from './Parent.js';
import {TweenMax, Power2, TimelineLite} from 'gsap/TweenMax';

class HomeHeaderSymbols extends ParentClass {
  constructor() {
    super();
    const moduleClass = '.content-home';
    if (!super.runThisModule(moduleClass)) return;

    this.min = -120;
    this.max = 120;

    this.$container = $('.home-header-symbols .container');

    this.symbolPositions();
  }

  symbolPositions() {
    $('.hero-wrapper').mousemove((e) => {
      if ($(window).width() >= 1200) {
        this.nudge(e, '.symbol.plus', -100);
        this.nudge(e, '.symbol.v-white', 50);
        this.nudge(e, '.symbol.s-blue', -25);
        this.nudge(e, '.symbol.dollar-sign-orange', -50);
        this.nudge(e, '.symbol.d-quote-white', 100);
        this.nudge(e, '.symbol.slash', 25);

        this.nudge(e, '.symbol.question-red', -100);
        this.nudge(e, '.symbol.d-quote-blue', 100);
        this.nudge(e, '.symbol.s-red', -50);
        this.nudge(e, '.symbol.exclamation-red-short', -25);
        this.nudge(e, '.symbol.bracket-white', 50);
        this.nudge(e, '.symbol.arc-red', -100);

        this.nudge(e, '.symbol.colon-blue', -100);
        this.nudge(e, '.symbol.exclamation-orange', 50);
        this.nudge(e, '.symbol.exclamation-blue', -25);
        this.nudge(e, '.symbol.v-orange', -50);
        this.nudge(e, '.symbol.v-white-down', 100);
        this.nudge(e, '.symbol.plus-blue', 50);

        this.nudge(e, '.symbol.line-red', -50);
        this.nudge(e, '.symbol.bracket-blue', 100);
        this.nudge(e, '.symbol.d-quote-white-2', -50);
        this.nudge(e, '.symbol.colon-white', -25);
        this.nudge(e, '.symbol.plus-blue-2', 50);
        this.nudge(e, '.symbol.s-red-2', -100);

        this.nudge(e, '.symbol.exclamation-orange-2', -100);
        this.nudge(e, '.symbol.question-blue', 50);
        this.nudge(e, '.symbol.colon-white-2', -25);
        this.nudge(e, '.symbol.v-orange-down', -50);
      } else {
        this.$container.children('div').removeAttr('style');
      }
    });
  }

  nudge(e, target, movement) {
    let relX = e.pageX - this.$container.offset().left;
    let relY = e.pageY - this.$container.offset().top;

    TweenMax.to('.home-header-symbols .container ' + target, 1, {
      x: (relX - this.$container.width() / 2) / this.$container.width() * movement,
      y: (relY - this.$container.height() / 2) / this.$container.height() * movement
    });
  }

  getRandNum() {
    return Math.floor(Math.random() * (this.max - this.min + 1) + this.min);
  }
}

jQuery(document).ready(function() {
	new HomeHeaderSymbols();
});
