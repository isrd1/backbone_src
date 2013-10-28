define([
  'underscore',
  'backbone',
], 
function(_, Backbone) {

  var StudentModel = Backbone.Model.extend({
        urlRoot: function() {
            return 'server/index.php?action=show&subject=student';
        },
        idAttribute: 'studentid',
        
        defaults: {
            studentid: null,
            surname: "",
            forename: "",
            stage: "",
            email: ""
        },
        
        initialize: function () {
            this.getFullName = function () {
                return this.get('forename') + ' ' + this.get('surname');
            };
        }
    });
  return StudentModel;

});

