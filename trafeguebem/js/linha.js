var page,code;

var heightTotal;
var widthTotal;
$(document).ready(function () {  

	link = $(window.location).attr('href').split('/');
	code = $('#hdnId').val();

	page = new Page();
	page.initialize();	
	
	$('body').tooltip();
	$('button').button().css({
		'position' : 'absolute',
		'left' : '49%',
		'top' :'25px'
	}).click(function(){
		title = $(this).attr('data-name')
		page.showHour(code,title);
	})

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

	$('.menu').css('width',widthTotal-$('#logo').width());
	$('#map').css('width',widthTotal);
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

		$('.menu').css('width',widthTotal-134);
		$('#map').css('width',widthTotal);
		$('#map').css('height',heightTotal-25);	

	});

	page.getLine(code);

});