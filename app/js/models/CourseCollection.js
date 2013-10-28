define([
   'backbone', 'models/CourseModel'
], 

    function(Backbone, CourseModel) {
        var CourseCollection = Backbone.Collection.extend({
                url : 'server/index.php?action=list&subject=courses',
                model : CourseModel,
                parse: function (data) {
                    return data.ResultSet.Result;
                }
            });
        return CourseCollection;
    }
);
