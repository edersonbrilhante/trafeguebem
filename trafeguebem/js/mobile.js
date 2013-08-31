var page;

var heightTotal;
var widthTotal;
$(document).ready(function () {  

	page = new Page();
	page.initialize();	

	$('#btnMarker').live('click',function(){
		google.maps.event.addListener(page.map, 'click', function(event) {
			page.addSearchMarker(event.latLng); 
		});
	});

	$('#btnResult').live('click',function(){
		if($('.result').html() != '')
			$('.result').show();
	});

	$('#btnLine').live('click',function(){
		$('#rdoEndereco').removeAttr('checked');
		$('#rdoNome').attr('checked','checked');
		$('.search').show();
		$('#endereco').hide();
		$('#nome').show();
	});

	$('#btnAdress').live('click',function(){
		$('#rdoNome').removeAttr('checked');
		$('#rdoEndereco').attr('checked','checked');
		$('.search').show();
		$('#endereco').show();
		$('#nome').hide();
	});

	$('#btnBack').live('click',function(){
		$('.result').hide();
		$('.search').hide();
	});

	$('body').tooltip();

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

		/*$('.toSelect').css('height',heightTotal-37);
		$('.options').css('height',heightTotal-72);
		$('.result').css('height',$('.options').height()-$('.search').height()-109);*/
		$('.result').css('height',heightTotal-35);
		$('.search').css('height',heightTotal-35);
		$('#map').css('width',widthTotal);
		$('#map').css('height',heightTotal-35);

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

		/*$('.toSelect').css('height',heightTotal-37);
		$('.options').css('height',heightTotal-72);
		$('.result').css('height',$('.options').height()-$('.search').height()-109);*/
		$('.search').css('height',heightTotal-35);
		$('.result').css('height',heightTotal-35);
		$('#map').css('width',widthTotal);
		$('#map').css('height',heightTotal-35);
	});

});