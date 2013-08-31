<body>
	<div class="all">
		<?php $this->load->view($page);?>
	</div>	
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> 
	<script type="text/javascript" src="/js/markerclusterer.min.js"></script>
	<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.9.1.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery.json-2.2.min.js"></script>
	<script type="text/javascript" src="/js/globals.min.js"></script> 
	<script type="text/javascript" src="/js/system.insert.js"></script> 
	<script type="text/javascript" src="/js/<?=$page?>.js"></script> 
	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-21973595-2']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>

</body>