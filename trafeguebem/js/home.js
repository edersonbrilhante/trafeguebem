
var page;
(function( $ ){
	//plugin buttonset vertical
	$.fn.buttonsetv = function() {
		$(':radio, :checkbox', this).wrap('<div style="margin: 1px"/>');
		$(this).buttonset();
		$('label:first', this).removeClass('ui-corner-left').addClass('ui-corner-top');
		$('label:last', this).removeClass('ui-corner-right').addClass('ui-corner-bottom');
		mw = 0; // max witdh
		$('label', this).each(function(index){
			w = $(this).width();
			if (w > mw) mw = w; 
		})
		$('label', this).each(function(index){
			$(this).width(mw);
		})
	};
})(jQuery);

var heightTotal;
var widthTotal;
$(document).ready(function () {  

	page = new Page();
	page.initialize();	
	google.maps.event.addListener(page.map, 'click', function(event) {
		page.addSearchMarker(event.latLng); 
	});

	$('body').tooltip();

	$('button').button();
	$('.toSelect').buttonsetv();
	$('#chkLotacao').click(function(){
		page.typeSearch['l'] = ($('#chkLotacao').attr('checked')=='checked')?true:false;
		page.showAddress(true);
	});
	$('#chkTaxi').click(function(){
		page.typeSearch['t'] = ($('#chkTaxi').attr('checked')=='checked')?true:false;
		page.showAddress(true);
	});
	$('#chkOnibus').click(function(){
		page.typeSearch['b'] = ($('#chkOnibus').attr('checked')=='checked')?true:false;
		page.showAddress(true);
	});
	$('input[type=submit]')
		.button()
		.live('click',function(){
			if($('#rdoNome').attr('checked')){
				termo = $('#txtNome').attr('value');				
				if(termo.length>=2)
					page.search(null,null,null,null,termo)
				else
					alert('O termo deve ter mais de 1 caracter')
			}
			else{
				page.showAddress(true);
			}
		});
	
	$('.line .linhas,.line .codigos').live('click',function(){
		code = $(this).parent().attr('data-linha');
		page.showLine(code, false);
	});

	$('.line .horario').live('click',function(){
		code = $(this).parent().attr('data-linha');
		page.showHour(code);
	});

    $.contextMenu({
        selector: '.line', 
        callback: function(key, options) {
        	if(key=='share'){
        		$.contextMenu.getInputValues(options);
        		linha = $(options.$trigger.context).attr('data-linha');
        		code = $(options.$trigger.context).find('.codigos>span').html();
        		nome = $(options.$trigger.context).find('.linhas>span').html();
        		$('#share input').attr('value',window.location.href+'linha/'+linha);
        		$('#share').dialog({title:code+' - '+nome});
        	}
        },
        items: {
            "share": {name: "Compartilhar", icon: "link"}
        }
    });
    
    $('.context-menu-one').on('click', function(e){
        console.log('clicked', this);
    })

	$('#tipoTusca').buttonset();

	$('#rdoNome').live('click',function(){
		$('#endereco').hide();
		$('#nome').show();
	});

	$('#rdoEndereco').live('click',function(){
		$('#endereco').show();
		$('#nome').hide();
	});

	if(self.innerHeight)
		heightTotal = window.innerHeight
	else if(document.documentElement && document.documentElement.clientHeight)
		heightTotal = document.documentElement.clientHeight;
	else if(document.body)
		heightTotal = document.body.clientHeight;

	if(self.innerWidth)
		widthTotal = window.innerWidth
	else if(document.documentElement && document.documentElement.clientWidth)
		widthTotal = document.documentElement.clientWidth;
	else if(document.body)
		widthTotal = document.body.clientWidth;

	//widthTotal = 	widthTotal - 40;

	$('.menu').css('width',widthTotal-$('[class^=logo]').width());
	$('.toSelect').css('height',heightTotal-37);
	$('.options').css('height',heightTotal-72);
	$('.result').css('height',$('.options').height()-$('.search').height()-109);
	$('#map').css('width',widthTotal-400);
	$('#map').css('height',heightTotal-25);

	$(window).resize(function(){			

		if(self.innerHeight)
			heightTotal = window.innerHeight
		else if(document.documentElement && document.documentElement.clientHeight)
			heightTotal = document.documentElement.clientHeight;
		else if(document.body)
			heightTotal = document.body.clientHeight;

		if(self.innerHeight)
			widthTotal = window.innerWidth
		else if(document.documentElement && document.documentElement.clientWidth)
			widthTotal = document.documentElement.clientWidth;
		else if(document.body)
			widthTotal = document.body.clientWidth;

		$('.menu').css('width',widthTotal-350);
		$('.toSelect').css('height',heightTotal-37);
		$('.options').css('height',heightTotal-72);
		$('.result').css('height',$('.options').height()-$('.search').height()-109);
		$('#map').css('width',widthTotal-400);
		$('#map').css('height',heightTotal-35);
	});

});