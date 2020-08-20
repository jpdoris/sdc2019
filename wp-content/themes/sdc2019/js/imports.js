import $ from 'jquery';
window.$ = window.jQuery = $;
// window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('bootstrap');
import 'slick-slider';

// Custom select dropdown library
// https://www.npmjs.com/package/facade-select
// import FacadeSelect from 'facade-select';

import 'jquery-form';
import 'jquery-validation';
import './modules/mailchimp-validate.js';
import './modules/mailchimp.js';

import Global from './classes/Global.js';
import Home from './classes/Home.js';
import HomeHeaderSymbols from './classes/HomeHeaderSymbols.js';
import HomeAnimations from './classes/HomeAnimations.js';
import GettingHere from './classes/GettingHere.js';
import Faq from './classes/Faq.js';
import Schedule from './classes/Schedule.js';

// Components
import FeaturedSpeakers from './components/FeaturedSpeakers.js';
