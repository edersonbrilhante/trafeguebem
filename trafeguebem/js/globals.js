var TAXI_ICON = "../icon/taxi_small.png";
var STOPS_ICON = "../icon/bus_stop_small.png";
var SELECTED_STOPS_ICON = "../icon/bus_stop_small_red.png";
var MARKER_CLUSTER_STYLE = [{url: '../icon/bus_cluster_small.png',
							 height: 37,
							 width: 32,
							 backgroundPosition: [0, 36],
							 textColor: '#ffffff',
							 textSize: 14
							}, {
							 url: '../icon/bus_cluster_med.png',
							 height: 37,
							 width: 32,
							 backgroundPosition: [0, 36],
							 textColor: '#ffffff',
							 textSize: 14
							}, {
							 url: '../icon/bus_cluster_big.png',
							 height: 37,
							 width: 32,
							 backgroundPosition: [0, 36],
							 textColor: '#ffffff',
							 textSize: 14
							}];
var MARKER_START = "../icon/marker-start.png";
var MARKER_END = "../icon/marker-end.png";
var ZOOM_DEFAULT = 15;
var ZOOM_MAX = 18;
var ZOOM_MIN = 3;
var COLOR = ['#FF6666','#FFCC33','#FFFF99','#C0C0C0','#FF0000','#FF9900','#FFFF00','#999999','#CC0000','#FF6600','#FFCC00','#666666','#990000','#CC6600','#999900','#333333','#660000','#993300','#666600','#000000','#330000','#663300','#333300']
$.extend({
	getUrlVars: function(){
		var vars, hash;
		c = (window.location.href.indexOf('?') == -1)?window.location.href.length:window.location.href.indexOf('?')
		var hashes = window.location.href.slice(c + 1).split('&');
		if(window.location.href.slice(c + 1) != ''){
			vars = [];
			for(var i = 0; i < hashes.length; i++)
			{
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
			}
		}
		return vars;
	},
	getUrlPaths: function(){
		var vars = [], hash;
		c = (window.location.href.indexOf('?') == -1)?window.location.href.length:window.location.href.indexOf('?')
		var hashes = window.location.href.substring(0,c).split('/');
		for(var i = 0; i < hashes.length; i++)
		{
			vars[hashes[i]] = hashes[i]
		}
		return vars;
	},
	getUrlVar: function(name){
		var n = $.getUrlVars();
		if(typeof n == 'undefined') return n;		
		n = n[name];
		if(typeof n == 'undefined' || n=='') return n;
		n = n.split('#');
		return n[0];
	},
	setUrl: function(url){
		window.location.replace(url);
	},
	getUrl: function(){
		c = (window.location.href.indexOf('?') == -1)?window.location.href.length:window.location.href.indexOf('?')
		return  (window.location.href.substring(0,c) == '') ? window.location.href : window.location.href.substring(0,c);
	},
    getUrlComplete: function(url){
        return window.location.href;
    },
	getUrlPath: function(name){
		return $.getUrlPaths()[name];
	},
    getUrlHost: function(){
        return window.location.host;
    }
});
	