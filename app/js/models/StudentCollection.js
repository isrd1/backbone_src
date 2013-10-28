/**
 * 
 */

define(['backbone', 'models/StudentModel', 'pageable'],
    function (Backbone, StudentModel, PageableCollection) {
        var StudentCollection = PageableCollection.extend({
            
            baseurl: 'server/index.php?action=listCourse&subject=students',
            url: function () {
                return this.baseurl + '&id=' + this.coursecode;
            },
            model: StudentModel,
            
            // Initial pagination states
            state: {
              pageSize: 10,
              sortKey: "updated",
              order: 0
            },
            
            mode: 'client',
            comparator: function (model) { return model.get("id"); },
            // the data comes from the server not as an array of objects but as an object with a couple
            // of properties then the array of objects, however, it seems that PageableCollection 
            // also uses this parse function for the individual rows of data in addition to the collection
            parse: function (data) {
                if (data.ResultSet) {
                    return data.ResultSet.Result;
                } else {
                    return data;
                }
            }
        });
        
        return StudentCollection;
    }
);