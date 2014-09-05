(function($, undefined){

$.widget("ui.checkmenu", {

	options: {
		buttons: {
			'Todos': function(){alert('ok'); return false;}
		},
		check: function(){selectAll($('body'));},
		checked: false
	},

	_create: function(){
		var d = this;
		var buttonsLength = 0;
			
		var html = '<input class="checkAll" type="checkbox"><span id="spanArrow" class="ui-button-icon-secondary ui-icon ui-icon-carat-1-s" style="right: 0;"></span></div><div id="menuCheck" class="menuCheck ui-dialog ui-widget ui-widget-content ui-corner-all" style="width: 75px; /*height: '+'px;*/ margin-top: -1px; display: block; z-index: 1012; top: 21px; outline: 0px none; position: absolute; display: none;"><ul class="checkList">';

		/*Adiciona botoes conforme option "buttons"*/
		for(li in this.options.buttons) {
			html += '<li class="liCheckMenu"><button id="buttonMenuCheck-'+li.replace(' ','_')+'" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only buttonMenuCheck-'+li.replace(' ','_')+'" style="text-align:left; padding-left: 4px;" >'+li+'</button></li>';
			buttonsLength++;
		}

		html += '</ul></div><div id="menuCheckShadow" class="menuCheckShadow ui-widget-shadow ui-corner-all" style="position: absolute; width: 80px; height: '+((buttonsLength*20)+7)+'px; display: none; z-index: 1011;"></div>';

		//Cria elemento na arvore dom apos elemento original
		this.element.addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icons-only ui-checkmenu");
		this.element.html(html);

		//Adiciona a function para cada options.buttons
		for(li in this.options.buttons){
			this.element.find('#buttonMenuCheck-'+li.replace(' ','_')).click(this.options.buttons[li]);
			this.element.find('#buttonMenuCheck-'+li.replace(' ','_')).bind('click', function(e){
				d.element.find('#menuCheck').hide();
				d.element.find('#menuCheckShadow').hide();
				d.element.find('input.checkAll').attr('checked',true);
				e.stopPropagation();
			});
		}

		//Função do checkbox
		this.element.find('input.checkAll').click(this.options.check);

		//Mostra menu qdo clica no botao com a seta para baixo
		this.element.click(function(event){
			if($(event.target).attr('class') != 'checkAll' ) {
				$('#menuCheck', $(this)).slideToggle('fast');
				$('#menuCheckShadow', $(this)).slideToggle('fast');
				return false;
			}
		});

		//Esconde menu qdo clicar em qualquer outro elemento exceto o proprio
		$(document).bind('click', function(event){
			if ( !$(event.target).closest('.menuCheck').length ) {
				$('.menuCheck').hide();
				$('.menuCheckShadow').hide();
			} 
		});
	},

	destroy: function() {
		$.Widget.prototype.destroy.apply(this, arguments); // default destroy
	}
});


$.extend( $.ui.checkmenu, {
	version: "@VERSION"
});


})(jQuery);
