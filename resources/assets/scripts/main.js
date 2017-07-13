/** import external dependencies */
// This way import files from node_modules and bower_components folder
import 'jquery';
import 'bootstrap';

// This way import file elem.js in assets/components folder
// import 'components/elem.js'

/** import local dependencies */
import Router from './util/Router';
import common from './routes/common';
// import home from './routes/home';
// import aboutUs from './routes/about';

/**
 * Populate Router instance with DOM routes
 * @type {Router} routes - An instance of our router
 */
const routes = new Router({
  /** All pages */
  common,
  /** Home page */
  //home,
  /** About Us page, note the change from about-us to aboutUs. */
  //aboutUs,
});

/** Load Events */
jQuery(document).ready(() => routes.loadEvents());
