define([
  'underscore',
  'backbone',
], 
function(_, Backbone) {

  var CourseModel = Backbone.Model.extend({
        urlRoot: function() {
            var action = this.isNew() ? 'create' : 'update';
            if (this.hasChanged()) {
                action = 'show';
            }
            action = 'show';
            return 'server/index.php?action=' + action + '&subject=course';
        },
        idAttribute: 'coursecode',

        defaults: {
            coursecode: null,
            coursetitle: "",
            department: ""
        },
        
        initialize: function () {
            this.getCourseName = function () {
                return this.get('coursecode') + ' ' + this.get('coursetitle');
            };
        }
    });
  return CourseModel;

});

