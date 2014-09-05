function get_html_translation_table (table, quote_style) {
	// http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: noname
    // +   bugfixed by: Alex
    // +   bugfixed by: Marco
    // +   bugfixed by: madipta
    // +   improved by: KELAN
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Frank Forte
    // +   bugfixed by: T.Wild
    // +      input by: Ratheous
    // %          note: It has been decided that we're not going to add global
    // %          note: dependencies to php.js, meaning the constants are not
    // %          note: real constants, but strings instead. Integers are also supported if someone
    // %          note: chooses to create the constants themselves.
    // *     example 1: get_html_translation_table('HTML_SPECIALCHARS');
    // *     returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}
    var entities = {},
        hash_map = {},
        decimal;
    var constMappingTable = {},
        constMappingQuoteStyle = {};
    var useTable = {},
        useQuoteStyle = {};

    // Translate arguments
    constMappingTable[0] = 'HTML_SPECIALCHARS';
    constMappingTable[1] = 'HTML_ENTITIES';
    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
    constMappingQuoteStyle[2] = 'ENT_COMPAT';
    constMappingQuoteStyle[3] = 'ENT_QUOTES';

    useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
    useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

    if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
        throw new Error("Table: " + useTable + ' not supported');
        // return false;
    }

    entities['38'] = '&amp;';
    if (useTable === 'HTML_ENTITIES') {
        entities['160'] = '&nbsp;';
        entities['161'] = '&iexcl;';
        entities['162'] = '&cent;';
        entities['163'] = '&pound;';
        entities['164'] = '&curren;';
        entities['165'] = '&yen;';
        entities['166'] = '&brvbar;';
        entities['167'] = '&sect;';
        entities['168'] = '&uml;';
        entities['169'] = '&copy;';
        entities['170'] = '&ordf;';
        entities['171'] = '&laquo;';
        entities['172'] = '&not;';
        entities['173'] = '&shy;';
        entities['174'] = '&reg;';
        entities['175'] = '&macr;';
        entities['176'] = '&deg;';
        entities['177'] = '&plusmn;';
        entities['178'] = '&sup2;';
        entities['179'] = '&sup3;';
        entities['180'] = '&acute;';
        entities['181'] = '&micro;';
        entities['182'] = '&para;';
        entities['183'] = '&middot;';
        entities['184'] = '&cedil;';
        entities['185'] = '&sup1;';
        entities['186'] = '&ordm;';
        entities['187'] = '&raquo;';
        entities['188'] = '&frac14;';
        entities['189'] = '&frac12;';
        entities['190'] = '&frac34;';
        entities['191'] = '&iquest;';
        entities['192'] = '&Agrave;';
        entities['193'] = '&Aacute;';
        entities['194'] = '&Acirc;';
        entities['195'] = '&Atilde;';
        entities['196'] = '&Auml;';
        entities['197'] = '&Aring;';
        entities['198'] = '&AElig;';
        entities['199'] = '&Ccedil;';
        entities['200'] = '&Egrave;';
        entities['201'] = '&Eacute;';
        entities['202'] = '&Ecirc;';
        entities['203'] = '&Euml;';
        entities['204'] = '&Igrave;';
        entities['205'] = '&Iacute;';
        entities['206'] = '&Icirc;';
        entities['207'] = '&Iuml;';
        entities['208'] = '&ETH;';
        entities['209'] = '&Ntilde;';
        entities['210'] = '&Ograve;';
        entities['211'] = '&Oacute;';
        entities['212'] = '&Ocirc;';
        entities['213'] = '&Otilde;';
        entities['214'] = '&Ouml;';
        entities['215'] = '&times;';
        entities['216'] = '&Oslash;';
        entities['217'] = '&Ugrave;';
        entities['218'] = '&Uacute;';
        entities['219'] = '&Ucirc;';
        entities['220'] = '&Uuml;';
        entities['221'] = '&Yacute;';
        entities['222'] = '&THORN;';
        entities['223'] = '&szlig;';
        entities['224'] = '&agrave;';
        entities['225'] = '&aacute;';
        entities['226'] = '&acirc;';
        entities['227'] = '&atilde;';
        entities['228'] = '&auml;';
        entities['229'] = '&aring;';
        entities['230'] = '&aelig;';
        entities['231'] = '&ccedil;';
        entities['232'] = '&egrave;';
        entities['233'] = '&eacute;';
        entities['234'] = '&ecirc;';
        entities['235'] = '&euml;';
        entities['236'] = '&igrave;';
        entities['237'] = '&iacute;';
        entities['238'] = '&icirc;';
        entities['239'] = '&iuml;';
        entities['240'] = '&eth;';
        entities['241'] = '&ntilde;';
        entities['242'] = '&ograve;';
        entities['243'] = '&oacute;';
        entities['244'] = '&ocirc;';
        entities['245'] = '&otilde;';
        entities['246'] = '&ouml;';
        entities['247'] = '&divide;';
        entities['248'] = '&oslash;';
        entities['249'] = '&ugrave;';
        entities['250'] = '&uacute;';
        entities['251'] = '&ucirc;';
        entities['252'] = '&uuml;';
        entities['253'] = '&yacute;';
        entities['254'] = '&thorn;';
        entities['255'] = '&yuml;';
    }

    if (useQuoteStyle !== 'ENT_NOQUOTES') {
        entities['34'] = '&quot;';
    }
    if (useQuoteStyle === 'ENT_QUOTES') {
        entities['39'] = '&#39;';
    }
    entities['60'] = '&lt;';
    entities['62'] = '&gt;';


    // ascii decimals to real symbols
    for (decimal in entities) {
        if (entities.hasOwnProperty(decimal)) {
            hash_map[String.fromCharCode(decimal)] = entities[decimal];
        }
    }

    return hash_map;
}

function html_entity_decode (string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: john (http://www.jd-tech.net)
    // +      input by: ger
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: marc andreu
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Ratheous
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Nick Kolosov (http://sammy.ru)
    // +   bugfixed by: Fox
    // -    depends on: get_html_translation_table
    // *     example 1: html_entity_decode('Kevin &amp; van Zonneveld');
    // *     returns 1: 'Kevin & van Zonneveld'
    // *     example 2: html_entity_decode('&amp;lt;');
    // *     returns 2: '&lt;'
    var hash_map = {},
        symbol = '',
        tmp_str = '',
        entity = '';
    tmp_str = string.toString();

    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    }

    // fix &amp; problem
    // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
    delete(hash_map['&']);
    hash_map['&'] = '&amp;';

    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }
    tmp_str = tmp_str.split('&#039;').join("'");

    return tmp_str;
}

function criaLoadingDialog(dialogId, dialogHtml) {
	var interval = null;

	if (!dialogHtml) {
		dialogHtml = '<p style="text-align:left;font-size:1.5em;padding:1.5em 1.5em 0;"><span id="processandoDots">Processando</span><br>Por favor aguarde.</p>';
	}

	if (!dialogId) {
		dialogId = 'loadingDialog';
	}

	if (typeof $('#'+dialogId).html() == 'undefined') {
		$('body').append('<div id="'+dialogId+'">'+dialogHtml+'</div>');
	}
	else {
		$('#'+dialogId).html(dialogHtml);
	}
	$('#'+dialogId).dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		width: 300,
		height: 150,
		closeOnEscape: false,
		position: ['center', 250],
		open: function(event, ui) {
			$('#'+dialogId).parent().find('.ui-dialog-titlebar:first').hide();
			interval = addDots('processandoDots');
		},
		close: function(){
			clearInterval(interval);
		}
	});
}

function addDots(el){
	$('#'+el).append($('<em>'));
	return setInterval("doItUp($('#"+el+"').find('em'))", 600);
}

function doItUp(el){
	if(el.html()=='...') el.html('');
	else el.html(el.html()+'.');
}

function displayMsg(titulo, conteudo, tipo, callback) {
	var divClass = 'ui-state-error', 
		divIcon	 = 'ui-icon-alert',
		divMsg;

	if (tipo == 'ok') {
		divClass = 'ui-state-highlight';
		divIcon = 'ui-icon-check';
	}

	if ($('div#message').length) {
		divMsg = $('div#message');
	}
	else {
		divMsg = $('<div>');
		divMsg.attr('id','message')
			.addClass('ui-corner-all')
			.css('padding','0.5em')
			.css('display','none')
			.css('z-index','99999')
			.css('position','fixed')
			.css('top','-4px')
			.css('left','-4px')
			.css('width','100%');
		divMsg.appendTo('body');
	}

	divMsg.attr('class', divClass)
		.html("<span class=\"ui-icon "+divIcon+"\""+
				" style=\"float: left; margin-left: 0.3em; margin-right: 0.3em;\"></span>"+
				"<b>"+titulo+"</b> <br/>"+conteudo.replace(/\\n/g, '<br>'))
		.fadeIn('fast').fadeTo(2000,1).fadeOut('slow', callback);
}

function submitForm(attr){
	if(attr.data.nome=='' || attr.data.user =='' || (attr.data.id=='' && attr.data.password=='')){
		displayMsg('N&atilde;o foi poss&iacute;vel completar a requisi&ccedil;&atilde;o','','nok');
	}
	else
		$.ajax({
			type: 'post',
			url: attr.url,
			dataType : 'json',
			data: attr.data,

			beforeSend: function() {
				if (typeof $('#loadingDialog').html() === 'undefined') {
					criaLoadingDialog();
				}
				$('#loadingDialog').dialog('open');
			},

			success: function(response) {		
				displayMsg(response.title,'',response.res);
				$('#loadingDialog').dialog('close');
			},

			error: function() {
				$('#loadingDialog').dialog('close');
				displayMsg('N&atilde;o foi poss&iacute;vel completar a requisi&ccedil;&atilde;o','','nok');
			}

		});
}

function searchCadastros(formId, columns) {

	//jQuery.ajaxSetup({jsonp: null, jsonpCallback: null});
	var interval;

	$.ajax({
		type: 'GET',
		dataType: 'json',
		url: $('#'+formId).attr('action'),
		data: $('#'+formId).serialize(),

		beforeSend: function() {
            if (typeof $('#loadingDialog').html() === 'undefined') {
				criaLoadingDialog();
			}
			$('#loadingDialog').dialog('open');

			$('#divSearchResponse').html('<span id="loadingTabMetrica">Carregando</span>');
			interval = 	('divSearchResponse');
		},

		success: function(jsonData) {

			if (jsonData != null) {
				var tableObj = montaTableHtml(jsonData, columns);
				tableObj.attr('id', 'tbSearchResult');

				$('#divSearchResponse').html(tableObj);

				pintaTabela('tbSearchResult');
			}
			else {
				$('#divSearchResponse').html('<h4>Sua pesquisa n&atilde;o encontrou nenhum registro!</h4>');
			}
		},
		complete: function() {
			$('#loadingDialog').dialog('close');

			clearInterval(interval);
			$('#divSearchResponse').find('#loadingTabMetrica').remove();
		}
	});
}

function pintaTabela(tableId) {
	$('#'+tableId+' tr').removeClass('cor1').removeClass('cor2');
	$('#'+tableId+' tr:odd').addClass('cor1');
	$('#'+tableId+' tr:even').addClass('cor2');
}

function montaTableHtml(jsonData, columns) {

	var tableObj = $('<table>').width('100%'),
	trObj = $('<tr>'),
	flagEditar = false,
	flagExcluir = false,
	flagClonar = false,

	excluirURL = false;

	$(columns).each(function(index) {

		var thObj = $('<th>');
		thObj.text(html_entity_decode(columns[index].title));
		trObj.append(thObj);

		if (columns[index].title == 'Editar') {
			flagEditar = true;
		}
		if (columns[index].title == 'Excluir') {
			flagExcluir = true;
			excluirURL = columns[index].url;
		}
		if (columns[index].title == 'Clonar') {
			flagClonar = true;
		}
	});

	tableObj.append(trObj);

	$(jsonData).each(function(row) {
		var trObj = $('<tr>');
		trObj.attr('id', jsonData[row].id);

		if (jsonData[row].descricao) {
			trObj.attr('title', jsonData[row].descricao);
		}

		$(columns).each(function(index) {

			// Colunas de edicao, exclusao nao levam indice, pois sao criadas a parte
			if (columns[index].index) {
				$('<td>')
					.text(html_entity_decode(jsonData[row][columns[index].index]))
					.appendTo(trObj);
			}
		});

		if (flagClonar) {
			trObj.append('<td style="text-align: center;"><a onClick="loadFormCadastro(\'id='+jsonData[row].id+'&clonar=true\');">'+
			'<img width="12px" height="12px" alt="Clonar" title="Clonar" src="/img/clonar.gif"></a></td>');
		}

		if (flagEditar) {
			trObj.append('<td style="text-align: center;"><a onClick="loadFormCadastro(\'id='+jsonData[row].id+'\');">'+
			'<img width="12px" height="12px" alt="Editar" title="Editar" src="/img/editar.gif"></a></td>');
		}

		if (flagExcluir) {
			trObj.append('<td style="text-align: center;"><a onClick="excluirCadastro(\''+jsonData[row].id+'\',\''+excluirURL+'\');">'+
			'<img width="12px" height="12px" alt="Excluir" title="Excluir" src="/img/excluir.gif"></a></td>');
		}

		tableObj.append(trObj);
	});

	return tableObj;
}

function loadFormCadastro(pvURLQuery) {
	if (pvURLQuery) {
		location.replace(location.pathname + '?' + pvURLQuery);
	}
	else {
		location.replace(location.pathname);
	}
}

function excluirCadastro(id, url) {

	var response = confirm(html_entity_decode('Confirmar exclus&atilde;o?'))
	if (!response) {
		return false;
	}

	$.ajax({
		type: 'POST',
		url: url,
		data: {id: id},
		dataType: 'json',

		beforeSend: function() {
			if (typeof $('#loadingDialog').html() === 'undefined') {
				criaLoadingDialog();
			}
			$('#loadingDialog').dialog('open');
		},

		success: function(response) {
			$('#loadingDialog').dialog('close');
			displayMsg(response.title,'',response.res);
			if (response.res == 'ok') {
				$('#'+id).hide();
			}
 		}
	});
}