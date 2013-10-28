// Require.js allows us to configure shortcut aliases
// Their usage will become more apparent futher along in the tutorial.
require.config({
  shim: {
    underscore: {
      exports: '_'
    },
    backbone: {
      deps: ["underscore", "jquery"],
      exports: "Backbone"
    },
    backgrid: {
        deps: ["underscore", "jquery", "backbone"],
        exports: "Backgrid"
    },
    pageable: {
        deps: ['underscore', 'jquery', 'backbone'],
        exports: 'PageableCollection'
    },
    paginator: {
        deps: ['pageable', 'backgrid'],
        exports: 'Paginator'
    }
  },

  paths: {
    jquery: 'libs/jquery-1.9.0',
    underscore: 'libs/underscore',
    backbone: 'libs/backbone',
    backgrid: 'libs/backgrid',
    pageable: 'libs/backbone-pageable',
    paginator: 'libs/extensions/paginator/backgrid-paginator'
  }

});

require([
  // Load our app module and pass it to our definition function
  'app'
], function(App){
  // The "app" dependency is passed in as "App"
  // Again, the other dependencies passed in are not "AMD" therefore don't pass a parameter to this function
  App.initialize();
});
