
var page;

var heightTotal;
var widthTotal;
$(document).ready(function () {  

	page = new Page();
	page.initialize();	
	google.maps.event.addListener(page.map, 'click', function(event) {
		page.insertNode(event.latLng); 
	});

	$('button:eq(0)')
		.button()
		.click(function(){
			$('#formulario').dialog();
		});

	$('[name="btnSalvar"]')
		.button()
		.click(function(){
			link = $(window.location).attr('href').split('/');
			code = link[link.length-1];
			if(typeof code == 'number')
				page.edit(code);
			else
				page.insert();
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

		$('#map').css('width',widthTotal);
		$('#map').css('height',heightTotal);
	});

});