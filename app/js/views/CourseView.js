define([
  'backbone',
  'models/CourseModel',
  'models/CourseCollection',
  'libs/text!../templates/course-details.html'
], function(Backbone, CourseModel, CourseCollection, courseDetails){

      var CourseView = Backbone.View.extend({
            el: "#courseInfo",
            template: _.template(courseDetails),
            
            initialize: function () {
                this.model.bind("change", this.render, this);
            },
            
            render: function (eventName) {
                $(this.el).html(this.template(this.model.toJSON()));
                return this;
            },
            
            events:{
                "click": "log"
            },
            
            log: function (event) {
                console.debug('clicked on course ' + this.model.get('coursecode') + ": "+this.model.getCourseName());
            },
         
            close:function () {
                $(this.el).unbind();
                $(this.el).empty();
            }
    
        });
    
        return CourseView;
  
  });
