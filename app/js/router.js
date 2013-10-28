/**
 * A wrapper around the AppRouter class.  This wrapper creates an instance of AppRouter
 * and returns its initialise method.
 *
 * @class AppRouterWrapper
 * @static
 */
define([
  'backbone',
  'views/CourseListView',
  'views/CourseView',
  'models/CourseCollection',
  'models/StudentCollection',
  'views/StudentListView',
  'views/ContentView'
], 
function(Backbone, CourseListView, CourseView, CourseCollection, 
         StudentCollection, StudentListView, ContentView) {

    /*
     * The router is, in effect, the Controller.  It decodes urls and links views and models as needed
     *
     * @class AppRouter
     * @for AppRouterWrapper
     * @extends Backbone.Router
     */
    var AppRouter = Backbone.Router.extend({
            routes: {
               "": "listCourse",
               "showcourse/:id": "showCourse",
               // Default
              '*actions': 'defaultAction'
            },

            /**
             * The method called with the class is first created,
             *
             * @method AppRouter.initialize
             * @return {null}
             */
            
            initialize: function () {
                var tabHeader = '.tab > :header';
                
                this.contentView = new ContentView(); // renders itself
                // check if logged in and draw appropriate login or logout view
                $(document).ajaxStart( function() { 
                    $('#wrapper').addClass("loading"); 
                });
                $(document).ajaxStop( function() { 
                    $('#wrapper').removeClass("loading"); 
                });

                $(tabHeader).each(function () {
                    this.title = 'Click to expand or collapse ' + this.innerHTML;
                });
                
                $(tabHeader).click(function () {
                    var self = this;
                    $(this).parent().children('.contents').slideToggle('slow', function (){
                        if ($(this).is(':hidden')) {
                            $(self).removeClass('selectedTab');
                        } else {
                            $(self).addClass('selectedTab');
                        }
                    });
                });
                
            },
        /**
         * Fetches a course from the server and creates a view and renders it.
         * @method AppRouter.listCourse
         * @return {null}
         */
            listCourse: function () {
                var self = this;  // store a reference to this so it can be used inside the fetch => success callback
                this.courseList = new CourseCollection();  // create a new courseList model instance
                
                this.courseList.fetch({  // fetch the courses to populate the list
                    success: function (collection, response, option) {
                        // if the fetch succeeded then create instance of CourseListView passing in an object which declares 
                        // which model to use and display it
                        self.courseListView = new CourseListView({model: self.courseList});
                        self.courseListView.render();
                        if (self.requestedCourseID) {
                            self.showCourse(self.requestedCourseID);
                        }
                    },
                    error: function (model, xhr, options) {
                        console.log('fetch failed for courseList');
                    }
                });
            },

        /**
         * Display a course matching the id
         * @method AppRouter.showCourse
         * @param {string} id
         */
            showCourse:function (id) {
                // if the list is showing, and we have an id
                if (this.courseList && id) {
                    // retrieve the course with id from the list
                    this.course = this.courseList.get(id);
                    // if a view is already open then close it
                    if (this.courseView) { 
                        this.courseView.close();
                    }
                    // make a new view showing the course with id
                    this.courseView = new CourseView({model:this.course});
                    this.courseView.render();  // display the course
                    /**
                     * now find the students on that course and render them
                     */
                    this.listStudentOnCourse(id);
                } else {  // we either don't have a list or an id so create a list
                    this.requestedCourseID = id;
                    this.listCourse();
                }
            },
            
            listStudentOnCourse: function(coursecode) {
                var self = this;
                // if there's no student list or course we want isn't the one showing                                                           
                if ((!this.studentList) || (this.requestedCourseID !== coursecode)) {
                    /**                                                                                                                         
                     * remove any models already in existence                                                                                   
                     */
                    if (this.studentList) {
                        this.studentList.remove();
                    }
                    this.studentList = new StudentCollection();
                    this.studentList.coursecode = coursecode;
                    this.studentList.fetch({
                        success: function () {
                            /**                                                                                                                 
                             * remove any view and listeners already in place                                                                   
                             */
                            if (self.studentListView) {
                                self.studentListView.remove();
                            }
                            self.studentListView = new StudentListView({model: self.studentList});//, vent: self.vent});                        
                            self.studentListView.render();
                            self.requestedCourseID = coursecode;  // set which course we've retrieved so we don't get it again                  
                        },
                        error: function () {
                            console.log('fetch failed for student list');
                        }
                    });
                }
            }

  });
    /**
     * Creates an instance of AppRouter, sets up routes and is the value returned by the AppRouter Wrapper
     *
     * @property initialize
     * @type {function}
     *
     */
  var initialize = function(){
        /**
         * create a new AppRouter instance local, start the history bookmark
         * @attribute {AppRouter} app_router
         */
      var app_router = new AppRouter;
      
      Backbone.on('courseChange', function (id) {
          app_router.navigate('showcourse/'+id, {trigger: true});
      });
      
      Backbone.history.start();
  };
  
  return {   // return a handle to the initialize function
    initialize: initialize
  };
});

