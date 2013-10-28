/**
 * The application class which starts off the application by exposing Router
 * and returning the initialize method
 *
 * @class App
 * @constructor
 */
define([
  'router' // Request router.js
], function(Router){
  var initialize = function(){
    // Pass in our Router module and call it's initialize function
    Router.initialize();
  };

  return { 
    initialize: initialize
  };
});