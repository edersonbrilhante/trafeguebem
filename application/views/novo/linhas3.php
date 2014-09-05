<!DOCTYPE html>
<html lang="pt-br">
	<head>
	    <meta charset="utf-8">
	    <title>Login - Trafegue Bem</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="Login">
	    <meta name="author" content="Trafegue Bem">

	    <!-- Le styles -->
	    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
	    <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	    <style type="text/css">
	    .logoEmpresa8{
			background: url("../img/company/8.jpg") no-repeat #2D2D2D center;		
			width: 134px;
			float: left;
			height:26px;	
		}
		.logoEmpresa{
			background: url("../img/logo-curto.png") no-repeat #2D2D2D center;	
			width: 136px;
			float: left;
			height:26px;	
		}
		.logo{
			background: url("../img/logo.png") no-repeat #2D2D2D center;
			width: 350px;
			float: left;
			height:50px;						
		}
		#topo {
		    background: none repeat scroll 0 0 #EAF4FD;
		    border-bottom: 1px solid #CCCCCC;
		    float: left;
		    width: 100%;
		}

		#topo a {
		    color: #578BB3;
		    float: right;
		    font-size: 13px;
		    text-decoration: none;
		    margin: 3px 0;
		}
		#topo a:hover{
			text-decoration: underline;
		}
		.button{
			float: left;
			clear: left;
			margin: 10px;
		}
		#linhas, #horario{
			float: left;
			clear: left;
			margin: 10px;	
			margin-top: 0;
		}
		#linhas input, #linhas select{
			margin: 0
		}
		#linhas .horario,
		#linhas .editar,
		#linhas .clonar,
		#linhas .excluir{
			cursor: pointer;
		}

		 #idConfirmDialog {
				width: 450px;
				height: 165px;
				background-color: whitesmoke;
				}
			 #idConfirmDialog .btn {
				width: 70px;
				margin-bottom: 8px;
				margin-right: 15px;
				margin-top: 80px;
				float: right;
				}
			 #idConfirmDialog h3 {
				margin-left: 60px;
				margin-top:15px;
				}
			 #idConfirmDialog img {
				float: left;
				margin-left: 15px;
				margin-top: 15px;
				}

	    </style>

	    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	    <!--[if lt IE 9]>
	      <script src="/js/html5shiv.js"></script>
	    <![endif]-->

	    <!-- Fav and touch icons -->
	    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
  	</head>

  	<body class="adm">
		<div class="all">
			<div id="topo">
				<div class="logoEmpresa<?=companyId()?>"></div>
				<a class="logoff" href="/logoff">Sair</a>
			</div>
			<div id="navegacao">
				<?=create_breadcrumb()?>
			</div>

			<div class="button">
				<button id="novo" class="btn btn-primary">Novo</button>
			</div>
			
			<div id="linhas" class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th class="span1">
								<select class="span1" id="selBuscaCompany">
									<option value="">Empresa</option>
									<? foreach ($companies as $key => $company) : ?>
									<option value="<?=$key?>"><?=$company?></option>
									<? endforeach ?>
								</select>
							</th>
							<th class="span1"><input class="input-mini" type="text" placeholder="C&oacute;digo Interno" id="txtBuscaCodigoInterno" /></th>
							<th class="span1"><input class="input-mini" type="text" placeholder="C&oacute;digo" id="txtBuscaCodigo" /></th>
							<th class="span1"><input class="input-mini" type="text" placeholder="Linha" id="txtBuscaLinha" /></th>
							<th class="span1">
								<select class="span1" id="selBuscaSentido">
									<option value="">Sentido</option>
									<option value="1">Sentido 1</option>
									<option value="2">Sentido 2</option>
								</select>
							</th>						
							<th class="span1">
								<select class="span1" id="selBuscaModalidade">
									<option value="">Modalidade</option>
									<option value="S">Semi-direto</option>
									<option value="C">Comum</option>
									<option value="T">Turismo</option>
								</select>
							</th>			
							<th class="span1">
								<select class="span1" id="selBuscaRestrito">
									<option value="">Restri&ccedil;&atilde;o</option>
									<option value="1">Sim</option>
									<option value="0">N&atilde;o</option>
								</select>
							</th>	
							<th class="span1">
								<select class="span1" id="selBuscaStatus">
									<option value="">Liberado</option>
									<option value="1">Sim</option>
									<option value="2">N&atilde;o</option>
								</select>
							</th>
							<th class="span1">Horarios</th>
							<th class="span1">Editar</th>
							<th class="span1">Clonar</th>
							<th class="span1">Excluir</th>
						</tr>
					</thead>
				</table>
			</div>
			<div id="idConfirmDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
				<img src="http://static.scripting.com/larryKing/images/2013/01/09/alert.gif" width="34" height="28" alt="confirm icon">
				<h3 id="idConfirmDialogPrompt"></h3>
				<a href="#" class="btn btn-primary" onclick="okConfirmDialog ();">OK</a>
				<a href="#" class="btn" onclick="closeConfirmDialog ();">Cancel</a>
				</div>
			</div>	
        <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bootstrap/js/bootstrap.confirmmodal.js"></script>
		<script type="text/javascript">
		$(document).ready(function () {  
			function resultado(page){
				page = (typeof page == 'undefined')?1:page;
				codigoInterno = $('#txtBuscaCodigoInterno').val();
				codigo = $('#txtBuscaCodigo').val();
				linha = $('#txtBuscaLinha').val();
				sentido = $('#selBuscaSentido').val();
				company = $('#selBuscaCompany').val();
				modalidade = $('#selBuscaModalidade').val();
				restrito = $('#selBuscaRestrito').val();
				status = $('#selBuscaStatus').val();
				$.ajax({
					type: 'GET',
					url: '/busca/lista',
					data: {
						company:company,
						page:page,
						codigoInterno:codigoInterno,
						codigo:codigo,
						linha:linha,
						modalidade:modalidade,
						restrito:restrito,
						sentido:sentido,
						status:status
					},			
					dataType: "json",
					success : function (json) {
						$('table tr').not(':eq(0)').remove();
						var html = '';
						$.each( json['linhas'], function( i, linha ) {
							html += '<tr data-id="'+linha.key+'">'+
										'<td>'+((typeof linha.company != 'undefined')?linha.company:'')+'</td>'+
										'<td>'+((typeof linha.codigoInterno != 'undefined')?linha.codigoInterno:'')+'</td>'+
										'<td>'+((typeof linha.codigo != 'undefined')?linha.codigo:'')+'</td>'+
										'<td>'+((typeof linha.nome != 'undefined')?linha.nome:'')+'</td>'+
										'<td>'+((typeof linha.way != 'undefined')?linha.way:'')+'</td>'+
										'<td>'+((typeof linha.modalidade != 'undefined')?linha.modalidade:'')+'</td>'+
										'<td>'+((typeof linha.restrito != 'undefined')?linha.restrito:'')+'</td>'+
										'<td>'+((typeof linha.status != 'undefined')?linha.status:'')+'</td>'+
										'<td class="horario"><i class="icon-info-sign"></i></td>'+	
										'<td class="editar"><i class="icon-pencil"></i></td>'+
										'<td class="clonar"><i class="icon-retweet"></i></td>'+
										'<td class="excluir"><i class="icon-trash"></i></td>'+
									'</tr>'
						})	
						$('table').append(html)
					}
				})
			};

			$('input').keyup(function(e,trigger) {
				if(e.keyCode == 13 || typeof trigger != 'undefined'){
					resultado(1)
				}
			}).keydown(function(e){
				if(e.keyCode == 13) return false;
			});

			$('body').on("change", 'th select', function(e){		
				$('input:eq(0)').trigger('keyup',[true])
			});

			$('body').on('click','.horario',function(){
				window.open("/novo/horario?id=" + $(this).parent().attr('data-id') + "","_blank");
			})

			$('body').on('click','.editar',function(){
				window.open("/novo?id=" + $(this).parent().attr('data-id') + "","_blank");
			})

			$('body').on('click','.clonar',function(){
				window.open("/novo?id=" + $(this).parent().attr('data-id') + "&clonar=1","_blank");
			})

			$('#novo').button().click(function(){
				window.open("/novo","_blank")
			})

			$('body').on('click','.excluir2',function(){
				if(typeof $('#confirm').html() == 'undefined')$('body').append('<div id="confirm"><p>Tem certeza que deseja deletar?</p></div>')
				id = $(this).parent().attr('data-id');
				$('#confirm').dialog({
					title : 'Excluir',
					buttons: {
						'Sim': function(e){
							$.ajax({
								type: 'GET',
								url: '/deletar/linha',
								data: {id:id},
								success: function(res){
									$('#confirm').dialog("close");
									resultado()
								}	
							})
						},
						Não: function() {
							$('#confirm').dialog( "close" );
						}
					}
				});
			})

			$('body').on('click','.excluir',function(e){
				$("#idConfirmDialog").modal('hide'); 
			});

			resultado();
		})
		function closeConfirmDialog () {
			$("#idConfirmDialog").modal('hide'); 
		};
		function okConfirmDialog (id) {
			$("#idConfirmDialog").modal('hide'); 
			id = $(this).parent().attr('data-id');
			$.ajax({
				type: 'GET',
				url: '/deletar/linha',
				data: {id:id},
				success: function(res){
					$('#confirm').dialog("close");
					resultado()
				}	
			})
		};
		</script>
	</body>
</html>