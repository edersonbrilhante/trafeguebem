Page = function() {

	this.geocoder = new google.maps.Geocoder();
	
	this.initialLocation = new google.maps.LatLng(-30.02172, -51.184702);	
	this.infowindow = new google.maps.InfoWindow();
	this.loadedTaxi = false;
	this.loadedStops = false;
	this.showingStops = false;
	this.showingTaxi = false;
	this.showPercursos = true;
	this.showStopsLine = true;
	this.stops = new Array();//paradas do mapa
	this.taxis = new Array();//taxis do mapa
	this.lines = new Array();//linhas do mapa
	this.linesGeo = new Array();//linhas do mapa
	this.linesKeys = new Array();//chaves de cada linha
	this.selectedStops = new Array();
	this.selectedTaxis = new Array();
	this.markerClustererTaxi = null;
	this.cacheJson = new Array();
	this.typeSearch = new Array();
	this.map = null;


	this.typeSearch['l'] = true;
	this.typeSearch['b'] = true;
	this.typeSearch['t'] = false;

	this.initialize = function () {
		
		var mapOptions = {
			center : this.initialLocation,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoom: ZOOM_MIN,	
			maxZoom: ZOOM_MAX,
			minZoom: ZOOM_MIN,
			streetViewControl: false,
			mapTypeControl: false,
			navigationControl: true,
			navigationControlOptions: {
			  style: google.maps.ZoomControlStyle.SMALL,
			  position: google.maps.ControlPosition.LEFT_BOTTOM
			}
		};	
		this.map = new google.maps.Map($("#map").get(0), mapOptions);
				
	};
		
	//Ponto Inicial e Final começam nulos
	this.searchMarker = {
		start : null,
		end : null,
		type : null,
		startCircle : null,
		endCircle : null
	};

	this.plotCircle = function(){
		circle = new google.maps.Circle({
	        strokeColor: "#1D5987",
	        strokeOpacity: 0.8,
	        strokeWeight: 2,
	        fillColor: "#1D5987",
	        fillOpacity: 0.35,
			map: this.map,
			editable:true,
			radius: 200
		});

		return circle;
	}
  
	//Adiciona Ponto de Busca(start,end)
	this.addSearchMarker = function (latLng) {

		var instMap = this;
		if (this.searchMarker.start == null) {

			this.searchMarker.start = new google.maps.Marker({
				position : latLng,
				title : "Origem",
				draggable : true,
				map : this.map,
      			icon: MARKER_START
			});

			google.maps.event.addListener(this.searchMarker.start, "dragend", function () {
				instMap.changedSearchMarkers();
			});

			this.searchMarker.startCircle = this.plotCircle();		

        	this.searchMarker.startCircle.bindTo('center', this.searchMarker.start, 'position');

        	google.maps.event.addListener(this.searchMarker.startCircle, 'radius_changed', function() {				        
				instMap.changedSearchMarkers()
		    });

			this.changedSearchMarkers()

			google.maps.event.clearListeners(page.map, 'click');

		} 
		else {

			if (this.searchMarker.end == null) {

				this.searchMarker.end = new google.maps.Marker({
					position : latLng,
					title : "Destino",
					draggable : true,
					map : this.map,
      				icon: MARKER_END
				});

				google.maps.event.addListener(this.searchMarker.end, "dragend", function () {
					instMap.changedSearchMarkers();
				});


				this.searchMarker.endCircle = this.plotCircle();

        		this.searchMarker.endCircle.bindTo('center', this.searchMarker.end, 'position');

	        	google.maps.event.addListener(this.searchMarker.endCircle, 'radius_changed', function() {				        
					instMap.changedSearchMarkers()
			    });

				this.changedSearchMarkers();

				google.maps.event.clearListeners(page.map, 'click');

			} 
			else {

				this.clearMarker("start");
				this.clearMarker("end");			
				$('#txtEnderecoInicial').attr('value','');
				$('#txtEnderecoFinal').attr('value','');				
				this.searchMarker.startCircle.setMap(null);
				this.searchMarker.endCircle.setMap(null);
				this.addSearchMarker(latLng);
			}
		}
	};

	
	//Alterar Busca
	/*Chama page.search verificando se existe no mapa um ou dois pontos(start, end)*/
	this.changedSearchMarkers = function (numRetries) {
		if (numRetries == undefined) {
            numRetries = 0
        }
        if (numRetries >= 1) {
            return
        }
		var instMap = this;

		if (this.searchMarker.end) {
			
			instMap.geocoder.geocode({
				latLng: instMap.searchMarker.end.getPosition()
			}, function (result, status) {
				
				if (status == google.maps.GeocoderStatus.OK) {
					$("#txtEnderecoFinal").attr('value',result[0].formatted_address);
					instMap.search(instMap.searchMarker.start.getPosition(),  instMap.searchMarker.startCircle.radius, instMap.searchMarker.end.getPosition(),instMap.searchMarker.endCircle.radius)
				} 
				else {
					instMap.changedSearchMarkers(numRetries + 1)
				}
			})
		}
		else if (instMap.searchMarker.start) {
			
			instMap.geocoder.geocode({
				latLng: instMap.searchMarker.start.getPosition()
			}, function (result, status) {
				
				if (status == google.maps.GeocoderStatus.OK) {
					
					$("#txtEnderecoInicial").attr('value',result[0].formatted_address);
					
					if (instMap.searchMarker.end) {
					
						instMap.geocoder.geocode({
							latLng: instMap.searchMarker.end.getPosition()
						}, function (result, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								$("#txtEnderecoFinal").attr('value',result[0].formatted_address);
								instMap.search(instMap.searchMarker.start.getPosition(), instMap.searchMarker.startCircle.radius, instMap.searchMarker.end.getPosition(), instMap.searchMarker.endCircle.radius)
							} else {
								instMap.changedSearchMarkers(numRetries + 1)
							}
						})					
					} 
					else {
						instMap.search(instMap.searchMarker.start.getPosition(), instMap.searchMarker.startCircle.radius, null, null)
					}
				} 
				else {
					instMap.changedSearchMarkers(numRetries + 1)
				}
			})
		}
		
	};	
				
	//Limpa Ponto de Busca(Start,End)
	this.changeMarker = function (latLng,marker) {
		if (marker == "start") {
			this.clearMarker('start');
			this.searchMarker.start = new google.maps.Marker({
				position : latLng,
				title : "Origem",
				draggable : true,
				map : this.map,
      			icon: MARKER_START			
			});

			google.maps.event.addListener(this.searchMarker.start, "dragend", function () {
				this.changedSearchMarkers();
			});

			this.searchMarker.startCircle = this.plotCircle();		

        	this.searchMarker.startCircle.bindTo('center', this.searchMarker.start, 'position');

        	google.maps.event.addListener(this.searchMarker.startCircle, 'radius_changed', function() {				        
				this.changedSearchMarkers()
		    });		        
			this.changedSearchMarkers()

		} else {
			if (marker == "end") {
				
			this.clearMarker('end');
				this.searchMarker.end = new google.maps.Marker({
					position : latLng,
					title : "Destino",
					draggable : true,
					map : this.map,
      				icon: MARKER_END
				});

				google.maps.event.addListener(this.searchMarker.end, "dragend", function () {
					this.changedSearchMarkers();
				});


				this.searchMarker.endCircle = this.plotCircle();

        		this.searchMarker.endCircle.bindTo('center', this.searchMarker.end, 'position');

	        	google.maps.event.addListener(this.searchMarker.endCircle, 'radius_changed', function() {				        
					this.changedSearchMarkers()
			    });			        
					this.changedSearchMarkers()
			}
		}
	};
				
	//Limpa Ponto de Busca(Start,End)
	this.clearMarker = function (marker) {
		if ((marker == "start") && this.searchMarker.start) {
			this.searchMarker.start.setMap(null);
			this.searchMarker.start = null;
			this.searchMarker.startCircle.setMap(null);
		} else {
			if ((marker == "end") && this.searchMarker.end) {
				this.searchMarker.end.setMap(null);
				this.searchMarker.end = null;
				this.searchMarker.endCircle.setMap(null);
			}
		}
	};
	
	//Pesquisa por endereço
	this.showAddress = function(changeMarker) {			
		var instMap = this;
		var address1 = $('#txtEnderecoInicial').attr('value');
		address1 = (address1 != '')?address1 + ", RS, Brasil":''
		var address2 = $('#txtEnderecoFinal').attr('value');
		address2 = (address2 != '')?address2 + ", RS, Brasil":''
		if(address1 =='')instMap.clearMarker('start')
		if(address2 =='')instMap.clearMarker('end')
		instMap.geocoder.geocode( {'address': address1 }, function(rIni, sIni) {
			if (sIni == google.maps.GeocoderStatus.OK) {
				instMap.geocoder.geocode( {'address': address2 }, function(rFim, sFim) {
					if (sFim == google.maps.GeocoderStatus.OK) {

						var ini = rIni[0].geometry.location;
						var fim = rFim[0].geometry.location;

						if(typeof changeMarker == 'undefined'){
							instMap.changeMarker(ini,'start');
							instMap.changeMarker(fim,'end');
						}

						instMap.search(ini, instMap.searchMarker.startCircle.radius, fim, instMap.searchMarker.endCircle.radius)

					} else {
						var ini = rIni[0].geometry.location;
						if(instMap.searchMarker.start == null)instMap.addSearchMarker(ini);

						if(typeof changeMarker == 'undefined'){
							instMap.changeMarker(ini,'start');
						}

						instMap.search(ini, instMap.searchMarker.startCircle.radius,null, null)
					}			
				});		
			}			
		});
	}


	this.getLine = function(code){
		var url = HOST+'/linha/'+code;
		instMap = this;
		$.ajax({
			type: 'GET',
			url: url,
			async: false,
			jsonpCallback: 'linha',
			contentType: "application/json",
			dataType: 'jsonp',
			cache: false,
	    	success: function(json) {
				instMap.cacheJson[json.key] = json.geom;
				$('.horario').attr('data-name',json.code+' - '+json.name);
				instMap.showLine(json.key,false);
				instMap.map.setZoom(ZOOM_MIN);
	    	}
		});    

	}
	
	//Busca apartir do Pontos Inicial e Final(opcional) ou termo(opcional), dnup(atualizar linhas)
	this.search = function (from, distFrom, to, distTo, term) {
		
		//$("#container_linhas").html("<img src='http://www.trafeguebem.com.br/assets/icons/loading.gif' style='vertical-align: middle;'/> Buscando...")
		
		if (this.isSearching) { 
			if (this.searchInProgress) {
				this.searchInProgress.abort();
				this.searchInProgress = null;
				this.isSearching = false
			}
		}
		
		this.isSearching = true;
		json = new Object;	
		var attributes = new Array();

		json.term = (typeof term != 'undefined')?term:null;
		json.coord = new Object;
		json.type= new Object;
		json.type.l = this.typeSearch['l'];
		json.type.b = this.typeSearch['b'];
		json.type.t = this.typeSearch['t'];
		json.coord.start = (from != null)?[from.lat(), from.lng(), distFrom]:null;
		json.coord.end = (to != null)?[to.lat(), to.lng(), distTo]:null;
		var JSONstring = $.toJSON(json);
		var instMap = this;

		var url = HOST+'/busca';

		this.searchInProgress = $.ajax({
			type: 'GET',
			url: url,
			async: false,
			data:{json:JSONstring},
			jsonpCallback: 'busca',
			contentType: "application/json",
			dataType: 'jsonp',
			cache: false,
	    	success: function(json) {

				if (instMap.isSearching) {
				
					instMap.isSearching = false;
					if(typeof json.bus != 'undefined' || typeof json.lotacao != 'undefined'){

						instMap.hideLines();
						//instMap.hideStopsLines();

						html = '<table id="tableLinhas">'+
								'<tr>'+
									'<td class="codigos">'+
										'<span>Codigo - Sentido</a>'+
									'</td>'+
									'<td class="linhas">'+
										'<span>Linha</a>'+
									'</td>'+
									'<td class="horario">'+
										'<span>Horario</a>'+
									'</td>'+
								'</tr>';
				
						var tb = (typeof(json.bus) == 'undefined')?0:json.bus.length;
						for (var i = 0; i < tb; i++) {
							instMap.cacheJson[json.bus[i].key] = json.bus[i].geom;
							html += '<tr class="line" data-linha="'+json.bus[i].key+'">'+
							'<td class="codigos onibus">'+
								'<span>'+json.bus[i].code+'</span>'+
							'</td>'+
							'<td class="linhas">'+
								'<span>'+json.bus[i].name+'</span>'+
							'</td>'+
							'<td class="horario">'+
								'<span>Horario</span>'+
							'</td></tr>';
						}	
				
						var tl = (typeof(json.lotacao) == 'undefined')?0:json.lotacao.length;
						for (var i = 0; i < tl; i++) {
							instMap.cacheJson[json.lotacao[i].key] = json.lotacao[i].geom;
							html += '<tr class="line" data-linha="'+json.lotacao[i].key+'">'+
							'<td class="codigos lotacao">'+
								'<span>'+json.lotacao[i].code+'</span>'+
							'</td>'+
							'<td class="linhas">'+
								'<span>'+json.lotacao[i].name+'</span>'+
							'</td>'+
							'<td class="horario">'+
							'</td>'+
							'</tr>';
						}			
				
						html += '</table>';
				
						if(tb == 0 && tl == 0)html = '';
					}
					else
					{
						html = '<p>Sua busca n&atilde;o retornou resultados!</p>';
					}
					
					$(".result").html(html).show();
										
					instMap.clearTaxis();
					if(typeof json.taxi != 'undefined'){
						for (var i = 0; i < json.taxi.length; i++) {
							var taxi = new page.taxi(instMap.map, new google.maps.LatLng(json.taxi[i].geom.lat, json.taxi[i].geom.lng), true, false, json.taxi[i].adress);
							taxi.setKey(json.taxi[i].key);
							instMap.taxis.push(taxi);	

							//location = new google.maps.LatLng(json.taxi[i].geom.lat, json.taxi[i].geom.lng);
							//lert(instMap.taxis[i].getPosition())
							
						}
						instMap.loadedTaxi = true;
						instMap.showTaxi(true);
					}
				}

		   },
		    error: function(e) {
	      		console.log(e.message);
		    }
		});

	};	
		
	//Cria Linha
	this.plotLine = function (lineKey) {
		var lineOptions = {
			strokeColor : COLOR[this.linesKeys.length],
			strokeOpacity : 0.7,
			strokeWeight : 5
		};
		var line = new google.maps.Polyline(lineOptions);
		line.setMap(this.map);
		return line
	};
	
	//Mostra Linha
	this.showLine = function (lineKey,hideStop) {
		var instMap = this;
		if($('[data-linha=' + lineKey + ']').hasClass("selected"))
		{
			//instMap.hideStopsLines(lineKey);
			instMap.hideLines(lineKey);
			return
		}
		else
		{
			instMap.lines[lineKey] = new Array();
			var pl = instMap.plotLine(lineKey);
			//Linha
			animate = false;
			if (animate) {
				instMap.lineAnimationStep(pl, instMap.cacheJson[lineKey], 0, lineKey)()
			} else {
				var path = pl.getPath();
				for (var i = 0; i < instMap.cacheJson[lineKey].length; i++) {
					path.push(new google.maps.LatLng(instMap.cacheJson[lineKey][i].lat, instMap.cacheJson[lineKey][i].lng))
					instMap.lines[lineKey].push(pl);
				}
			}

			instMap.linesKeys.push({
				key : lineKey,
				index : instMap.lines.length - 1
			});
					//Paradas 
					//instMap.selectedStops[lineKey] = new Array();
					//for (var i = 0; i < values.length; i++) {
					//	page2 = new Page;
					//	//instMap.stop(instMap.map, new google.maps.LatLng(json.stops[i].pos[0], json.stops[i].pos[1]), false, true, json.stops[i].name);
					//	//instMap.stop.setKey(lineKey);
					//	//if(!instMap.showStopsLine)stop.hide();
					//	/*google.maps.event.addListener(stop, 'click', function(event) {
					//		var contentString = "<table>teste";
//
//							//		/*for(var i = 0; i < linhas.length; i++) {
//							//			contentString += "<tr><td style='text-align:left'><a onclick=plotPolyline(" + item.linhas[i].idLinha + ",'blue'" + ")>" + item.linhas[i].codigoLinha + "</a></td>";
//							//			contentString += "<td style='text-align:left'><a onclick=plotPolyline(" + item.linhas[i].idLinha + ",'blue'" + ")>" + item.linhas[i].nomeLinha + "</a></td></tr>"
//							//		}
//							//		contentString +="</table>";
//
//							//		infowindow.setContent(contentString);
//							//		infowindow.setPosition(event.latLng);
//							//		infowindow.open(instMap.map);
//							//	});*/
//
//							//	//instMap.selectedStops[lineKey].push(stop);
					//}			
				
					
		//		}
		}
		$('[data-linha=' + lineKey + ']').addClass("selected")
		$('[data-linha=' + lineKey + ']').css('background',(COLOR[this.linesKeys.length-1]));	
	};
	
	//Anima Linha
	this.lineAnimationStep = function (polyLine, nodes, index, lineKey, time) {
        if (time == undefined) {
            time = 0
        }
        var instMap = this;
        return function () {
            if (index < nodes.length) {
				if(index == 0){
					//if(instMap.searchMarker.start != null)
					//{
					//	page.map.setCenter(instMap.searchMarker.start.getPosition());
					//}else
					//{
					//	page.map.setCenter(new google.maps.LatLng(nodes[index].pos[0], nodes[index].pos[1]));
					//}
					//page.map.setZoom(ZOOM_DEFAULT);
				}
                var path = polyLine.getPath();
                path.push(new google.maps.LatLng(nodes[index].lat, nodes[index].lng));
				instMap.lines[lineKey].push(polyLine);
                setTimeout(instMap.lineAnimationStep(polyLine, nodes, index + 1, lineKey, time), time)				
            }
        }
    };
	
	//Oculta Lina
	this.hideLines = function (lineKey) {
		var instMap = this;
		if(typeof lineKey == 'undefined')
		{
			for(var j = 0; j < instMap.linesKeys.length; j++)
			{
				for (var i = 0; i < instMap.lines[instMap.linesKeys[j].key].length; i++) {
					instMap.lines[instMap.linesKeys[j].key][i].setMap(null);
				}	
				$('[data-linha=' + instMap.linesKeys[j].key + ']').removeClass("selected");
				$('[data-linha=' + instMap.linesKeys[j].key + ']').removeAttr('style');
			}
		}
		else
		{
			for (var i = 0; i < instMap.lines[lineKey].length; i++) {
				instMap.lines[lineKey][i].setMap(null);
			}
			$('[data-linha=' + lineKey + ']').removeClass("selected");
			$('[data-linha=' + lineKey + ']').removeAttr('style');
		}
	};
	
	//Oculta Paradas das Linhas
	this.hideStopsLines = function (lineKey) {
		if(typeof lineKey != 'undefined')
		{
			for (var i = 0; i < this.selectedStops[lineKey].length; i++) {
				this.selectedStops[lineKey][i].hide()
			}	
		}
		else
		{
			for(var j = 0; j < this.linesKeys.length; j++)
			{
				for (var i = 0; i < this.selectedStops[this.linesKeys[j].key].length; i++) {
					this.selectedStops[this.linesKeys[j].key][i].hide();
				}		
			}
		}
	};
	
	//Mostra Paradas das Linhas
	this.showStopsLines = function () {
		for(var j = 0; j < this.linesKeys.length; j++)
		{
			for (var i = 0; i < this.selectedStops[this.linesKeys[j].key].length; i++) {
				this.selectedStops[this.linesKeys[j].key][i].show()
			}
		}
	};
	
	
	//Mostra horario
	this.showHour = function(lineKey,title){
		var url = HOST+'/horario/'+lineKey;

		$.ajax({
			type: 'GET',
			url: url,
			async: false,
			jsonpCallback: 'busca',
			contentType: "application/json",
			dataType: 'jsonp',
			cache: false,
			success : function (json2) {
				var html = '';
				ts = (typeof(json2.util) == 'undefined')?0:json2.util.length;
				if(ts > 0)html += '<h6>Dias Ut&eacute;is</h6><ul>';
				for (var i = 0; i < ts; i++) {
					html += '<li'+((json2.util[i].especial == 't')?' class="especial"':'')+'>'+json2.util[i].horario+'</li>';
				}
				if(ts > 0)html += '</ul>';
				ts = (typeof(json2.sabado) == 'undefined')?0:json2.sabado.length;
				ts = ts;
				if(ts > 0)html += '<h6>S&aacute;bado</h6><ul>';
				for (var i = 0; i < ts; i++) {
					html += '<li'+((json2.util[i].especial == 't')?' class="especial"':'')+'>'+json2.sabado[i].horario+'</li>';
				}		
				if(ts > 0)html += '</ul>';
				ts = (typeof(json2.domingo) == 'undefined')?0:json2.domingo.length;
				ts = ts;
				if(ts > 0)html += '<h6>Domingo</h6><ul>';
				for (var i = 0; i < ts; i++) {
					html += '<li'+((json2.util[i].especial == 't')?' class="especial"':'')+'>'+json2.domingo[i].horario+'</li>';
				}		
				if(ts > 0)html += '</ul>';
				if(html=='')html ='<p>N&atilde;o foi encontrado hor&aacute;rios</p>'
				if(typeof title == 'undefined') title = $('[data-linha='+lineKey+']').find('.codigos>span').html()+' - '+$('[data-linha='+lineKey+']').find('.linhas>span').html()
				$('#hora').html(html).dialog({title:title,width:235,height:300})
			}				
		});
	};
	
	//Switch mostra/oculta paradas das linhas
	this.showStopLine = function(){
		this.showStopsLine = (!this.showStopsLine);
		if(this.showStopsLine)this.showStopsLines();
		else this.hideStopsLines();
	};
	
	this.isSearching = false;
	this.searchInProgress = null;
	
	//Mostrar Paradas
	this.showStops = function () {
		if (!this.loadedStops) {
			var instMap = this;
			setTimeout(function () {
				instMap.showStops()
			}, 100)
		} else {
			if (this.MarkerClustererStop == null) {
				this.MarkerClustererStop = new MarkerClusterer(this.map, [], {
						gridSize : 40,
						maxZoom : 14,
						styles : MARKER_CLUSTER_STYLE
					})
			}
			var stopMarkers = new Array();
			for (var i = 0; i < this.stops.length; i++) {
				stopMarkers.push(this.stops[i].marker)
			}
			this.MarkerClustererStop.addMarkers(stopMarkers);
			this.showingStops = true
		}		
	};
		
	//Mostrar Taxi
	this.showTaxi = function () {
		var instMap = this;
		if (!this.loadedTaxi) {
			setTimeout(function () {
				instMap.showTaxi()
			}, 100)
		} else {
			if (this.markerClustererTaxi == null) {
				this.markerClustererTaxi = new MarkerClusterer(instMap.map, [], {
						gridSize : 40,
						maxZoom : 14,
						styles : MARKER_CLUSTER_STYLE
					})
			}
			var taxiMarkers = new Array();
			for (var i = 0; i < this.taxis.length; i++) {

				this.taxis[i].addListener("click", function () {
					instMap.infowindow.setContent('json.taxi[i].fone');
					instMap.infowindow.setPosition(this.taxis[i].getPosition());
					instMap.infowindow.open(this.map);
				});
				taxiMarkers.push(this.taxis[i].marker)
			}
			this.markerClustererTaxi.addMarkers(taxiMarkers);
			this.showingTaxi = true
		}		
	};
	
	//Limpa Pontos de Taxi
	this.clearTaxis = function () {
		for (var i = 0; i < this.taxis.length; i++) {
			this.taxis[i].hide()
		}
		this.taxis = new Array();
		if(this.markerClustererTaxi!=null)
		this.markerClustererTaxi.clearMarkers();
	};	
	
	//Limpa stops
	this.clearStops = function () {
		for (var i = 0; i < this.stops.length; i++) {
			this.stops[i].hide()
		}
		this.stops = new Array();
	};
	
	this.clearMap = function(){
		$('#container_linhas').html('');
		this.hideLines();
		this.hideStopsLines();
		this.clearTaxis();
		this.clearStops();
	}
	
	//system_insert
	
	//Parada de Onibus
	this.stop = function(e, d, a, c, b) {
		if (a == undefined) {
			a = false
		}
		if (c == undefined) {
			c = false
		}
		if (b == undefined) {
			b = ""
		}
		this.map = e;
		this.marker = new google.maps.Marker({
					position : d,
					draggable : a,
					map : this.map
				});
		this.city = "";
		this.selected = null;
		this.key = "";
		this.name = b;
		this.addListener = function (g, f) {
			google.maps.event.addListener(this.marker, g, f)
		};
		this.clearListeners = function (f) {
			google.maps.event.clearInstanceListeners(this.marker)
		};
		this.show = function () {
			this.marker.setMap(this.map)
		};
		this.hide = function () {
			this.marker.setMap(null)
		};
		this.remove = function () {};
		this.select = function () {
			this.selected = true;
			this.marker.setIcon(SELECTED_STOPS_ICON)
		};
		this.deselect = function () {
			this.selected = false;
			this.marker.setIcon(STOPS_ICON)
		};
		this.setTitle = function (f) {
			this.marker.setTitle(f)
		};
		this.getTitle = function () {
			return this.marker.getTitle()
		};
		this.getCity = function () {
			return this.city
		};
		this.getPosition = function () {
			return this.marker.getPosition()
		};
		this.setKey = function (f) {
			this.key = f
		};
		this.getKey = function () {
			return this.key
		};
		this.updateInfo = function () {
			var instMap = this;
			instMap.geocoder.geocode({
					location : this.marker.getPosition()
				}, function (l, k) {
					if (k == google.maps.GeocoderStatus.OK) {
						var g = "";
						var j = "";
						var h = "";
						for (var m = 0; m < l[0].address_components.length; m++) {
							var n = l[0].address_components[m].types;
							for (var p = 0; p < n.length; p++) {
								if (n[p] == "locality") {
									j = l[0].address_components[m].long_name
								} else {
									if (n[p] == "street_number") {
										h = l[0].address_components[m].long_name
									} else {
										if (n[p] == "route") {
											g = l[0].address_components[m].long_name
										}
									}
								}
							}
						}
						var o = "Rua ?";
						if (g != "") {
							o = g
						}
						if (h != "") {
							o += ", " + h
						}
						if (j != "") {
							o += " - " + j
						}
						instMap.city = j;
						instMap.name = o;
						instMap.marker.setTitle(o)
					} else {
						instMap.city = "";
						instMap.name = "Parada";
						instMap.marker.setTitle("Parada")
					}
				})
		};
		if (b == "") {
			this.updateInfo()
		} else {
			this.setTitle(this.name)
		}
		if (c) {
			this.select()
		} else {
			this.deselect()
		}
	};
	
	//Taxi
	this.taxi = function(e, d, a, c, b) {
		if (a == undefined) {
			a = false
		}
		if (c == undefined) {
			c = false
		}
		if (b == undefined) {
			b = ""
		}
		this.map = e;
		this.marker = new google.maps.Marker({
			position : d,
			draggable : a,
			map : this.map
		});
		this.city = "";
		this.selected = null;
		this.key = "";
		this.name = b;
		this.addListener = function (g, f) {
			google.maps.event.addListener(this.marker, g, f)
		};
		this.clearListeners = function (f) {
			google.maps.event.clearInstanceListeners(this.marker)
		};
		this.show = function () {
			this.marker.setMap(this.map)
		};
		this.hide = function () {
			this.marker.setMap(null)
		};
		this.remove = function () {};
		this.select = function () {
			this.selected = true;
			this.marker.setIcon(TAXI_ICON)
		};
		this.deselect = function () {
			this.selected = false;
			this.marker.setIcon(TAXI_ICON)
		};
		this.setTitle = function (f) {
			this.marker.setTitle(f)
		};
		this.getTitle = function () {
			return this.marker.getTitle()
		};
		this.getCity = function () {
			return this.city
		};
		this.getPosition = function () {
			return this.marker.getPosition()
		};
		this.setKey = function (f) {
			this.key = f
		};
		this.getKey = function () {
			return this.key
		};
		this.updateInfo = function () {
			var f = this;
			instMap.geocoder.geocode({
					location : this.marker.getPosition()
				}, function (l, k) {
					if (k == google.maps.GeocoderStatus.OK) {
						var g = "";
						var j = "";
						var h = "";
						for (var m = 0; m < l[0].address_components.length; m++) {
							var n = l[0].address_components[m].types;
							for (var p = 0; p < n.length; p++) {
								if (n[p] == "locality") {
									j = l[0].address_components[m].long_name
								} else {
									if (n[p] == "street_number") {
										h = l[0].address_components[m].long_name
									} else {
										if (n[p] == "route") {
											g = l[0].address_components[m].long_name
										}
									}
								}
							}
						}
						var o = "Rua ?";
						if (g != "") {
							o = g
						}
						if (h != "") {
							o += ", " + h
						}
						if (j != "") {
							o += " - " + j
						}
						instMap.city = j;
						instMap.name = o;
						instMap.marker.setTitle(o)
					} else {
						instMap.city = "";
						instMap.name = "Ponto de Taxi";
						instMap.marker.setTitle("Ponto de Taxi")
					}
				})
		};
		if (b == "") {
			this.updateInfo()
		} else {
			this.setTitle(this.name)
		}
		if (c) {
			this.select()
		} else {
			this.deselect()
		}
	};


	//this.initialize();	

}
