jQuery(function($) {
	window.Photo = Backbone.Model.extend({
		url: function() {
			var base = '/kk_epub2/my/photos';
			if (this.isNew()) return base + '.json';
			return base + (base.charAt(base.length - 1) == '/' ? '': '/') + encodeURIComponent(this.id) + '.json';	
		},
	});

	window.PhotoList = Backbone.Collection.extend({
		model: Photo,
		url: '/kk_epub2/my/photos.json'
	});

	window.Photos = new PhotoList;

	window.PhotoView = Backbone.View.extend({
		tagName: 'div',

		template: $('#photo-template').template(),

		events: {
			"click .photo-destroy": "destroy"
		},

		initialize: function() {
			_.bindAll(this, 'render', 'remove');
			this.model.bind('change', this.render);
			this.model.bind('destroy', this.remove);
		},

		render: function() {
			var element = jQuery.tmpl(this.template, this.model.toJSON());
			$(this.el).html(element);
			return this;
		},

		remove: function() {
			$(this.el).remove();
		},

		destroy: function() {
			var that = this;
			options = {
				error: function(model, resp, options) {
					if (resp.status == 403) {
						alert('Fobbiden');
					}
				}
			}
			this.model.destroy(options);
		}
	});

	window.AppView = Backbone.View.extend({
		el: $('#photo-app'),

		initialize: function() {
			_.bindAll(this, 'addOne', 'addAll', 'render');
			Photos.bind('all', this.render);
			Photos.bind('refresh', this.addAll);
			Photos.bind('add', this.addOne);
			Photos.fetch();
		},

		render: function() {
		},

		addOne: function(photo) {
			var view = new PhotoView({ model: photo });
			this.$('#photo-list').append(view.render().el);
		}, 

		addAll: function() {
			$('#photo-list').html('');
			Photos.each(this.addOne);
		}

	});

	window.App = new AppView;

});

