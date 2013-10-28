define([
        'backbone',
        'libs/text!../templates/content.html'
    ],
    function (Backbone, headerTemplate) {

        var HeaderView = Backbone.View.extend({
            el: $('#wrapper'),

            template: _.template(headerTemplate),

            initialize: function () {
                this.render();
            },

            render: function (eventName) {
                $(this.el).html(this.template());
                return this;
            }

        });
        return HeaderView;
    }
);
