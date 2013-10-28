define([
  'backbone',
  'backgrid',
  'libs/text!../templates/course-list.html'
], function(Backbone, Backgrid, ListTemplate){
  var CourseListView = Backbone.View.extend({
        template: _.template(ListTemplate),
        
        initialize: function (options) {
            /**
             *  no longer used since I've added a clickable row, but useful to know how to do this
            var linkCell = Backgrid.UriCell.extend({
                    formatter: {
                        fromRaw: function (rawData) {
                            return '#showcourse/' + rawData;
                        },
                        toRaw: function (formattedData) {
                            return formattedData;
                        }
                    },
                    render: function () {
                        var linkText = this.formatter.fromRaw(this.model.id),
                            rawText = this.formatter.toRaw(this.model.get(this.column.get("name")));
                        
                        this.$el.empty();
                        this.$el.append($("<a>", {
                            href: linkText,
                            title: rawText,
                        }).text(rawText));
                        return this;
                      }
            });
            */
            
            /**
             * create a clickable row to be used by the grid
             */
            this.clickRow = Backgrid.Row.extend({
                events: {
                    'click': 'signalcourseChange'
                },
                /**
                 * raise a global event to which the router is subscribed
                 * which will change the selected course and thus update
                 * related views
                 */
                signalcourseChange: function(e) {
                    var currentRow = this.el;
                    $(currentRow).parent().children('.selected').removeClass('selected');
                    $(currentRow).addClass('selected');
                    Backbone.trigger('courseChange', this.model.id);
                }
            });
          
            this.grid = new Backgrid.Grid({
                columns: [
                           {
                               name:"coursecode",
                               label: "Code",
                               cell: 'string', // linkCell, // not using this since I've added a row click
                               editable: false
                            },
                           {
                                name: 'coursetitle',
                                label: 'Title',
                                cell: 'string', // linkCell,
                                editable: false
                           },
                           {
                               name: 'department',
                               label: 'Dept',
                               cell: 'string',
                               editable: false
                           }
                         ],
                collection: this.model,
                row: this.clickRow
            });
            // set the courses div (in the sidebar at the moment) to hold the template ul for the list of courses
            $('#courses').html(this.template());
            this.el = '#courselist';    // define el to be the id of the ul in that template
            this.model.bind("change", this.render, this);  // bind any change in the model to display the list
        },

        render: function (eventName) {
            $(this.el).html(this.grid.render().$el);
            return this;
        }
    });

    return CourseListView;
  
  });
