define([
  'backbone',
  'libs/text!../templates/student-list.html',
  'backgrid',
  'paginator'
], function(Backbone, ListTemplate, Backgrid, Paginator){

  var StudentListView = Backbone.View.extend({
        template: _.template(ListTemplate),
        
        useBackgrid: true,
        
        initialize: function (options) {
            // set the students div (in the sidebar at the moment) to hold the template the list of students
            $('#students').html(this.template());
            this.el = '#studentlist';                      // define el to be the id of the div in that template
            this.model.bind("change", this.render, this);  // bind any change in the model to diplay the list
            Backbone.on('rowclicked', function (model) {
                console.log('Clicked on student ' + model.id + ', ' + model.getFullName());
            });
            
            this.clickRow = Backgrid.Row.extend({
                events: {
                    'click': 'onClick'
                },
                onClick: function(e) {
                    Backbone.trigger('rowclicked', this.model);
                }
            });
            
            this.grid = new Backgrid.Grid({
                columns: [
                           {
                               name:"studentid",
                               label: "StudentID",
                               cell: 'string',
                               editable: false
                            },
                           {
                                name: 'surname',
                                label: 'Surname',
                                cell: 'string',
                                editable: false
                           },
                           {
                               name: 'forename',
                               label: 'Forename',
                               cell: 'string',
                               editable: false
                           },
                           {
                               name: 'stage',
                               label: 'Stage',
                               cell: 'string',
                               editable: false
                           },
                           {
                               name: 'email',
                               label: 'Email',
                               cell: 'string',
                               editable: false
                           }
                ],
                row: this.clickRow,
                collection: this.model,
                
                footer: Backgrid.Extension.Paginator
            });
            
            console.log('displaying students on ' + options.model.coursecode);
        },
    
        render: function (eventName) {
            // need to remove any content before appending the student list items to prevent 
            // duplicate list if models are changed
            $(this.el).empty();
            if (this.useBackgrid) {  // if we're using backgrid then render differently to if we're creating a row for each element
                $(this.el).append(this.grid.render().$el);
            } else {
                _.each(this.model.models, function (student) {
                    $(this.el).append(new StudentListItemView({model:student}).render().el);
                }, this);
            }
            return this;
        }

    });

    return StudentListView;
  
  });
