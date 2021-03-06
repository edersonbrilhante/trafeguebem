(function($) {

$.widget('ui.prompt', {
	defaultElement: "<input>",

	options: {
		func: function(){alert('Default!');}
	},

	_create: function() {
		this._draw();
		this._globalKeys();
	},

	_init: function(){
	},

	_draw: function() {
		var self =	this,
					options = self.options;

		var uiPrompt = self.element.wrap(self._uiPromptHtml())
			.parent()
				.append(self._buttonHtml());

		this.button = uiPrompt.find('.reticencia')
					.attr("tabIndex", -1)
					.attr('title','Clique para pesquisar!')
					.bind('click', self.options.func);
	},

	_uiPromptHtml: function(){
		return '<span></span>';
	},

	_buttonHtml: function() {
		/*
		var width = 23,
			height = this.element.height(),
			left = -25;
		*/

		return '<a class="ui-button ui-widget ui-state-default ui-button-text-only reticencia btn-prompt">'+
			'<span style="top: -20px;">...</span></a>';
	},

	_globalKeys: function(){
		var self = this;
		self.element.keyup(function(e){
			if(e.keyCode == 113) self.options.func();
		});
	},

	destroy: function() {
		 this._super( "destroy" );
	}
});

$.ui.prompt.version = "@VERSION";

})(jQuery);