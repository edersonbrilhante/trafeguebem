<html>
	<? $this->load->view('head_insert');?>
	<body class="adm">
		<div class="all">
			<div id="topo">
				<div class="logoEmpresa<?=companyId()?>"><h1>Logo</h1></div>
				<a class="logoff" href="/logoff">Sair</a>
			</div>
			<div id="navegacao">
				<?=create_breadcrumb()?>
			</div>

			<div id="linhas">
				<table>
					<tr>
						<th>
							<select id="selBuscaCompany">
								<option value="">Empresa</option>
								<? foreach ($companies as $key => $company) : ?>
								<option value="<?=$key?>"><?=$company?></option>
								<? endforeach ?>
							</select>
						</th>
						<th><input type="text" placeholder="Codigo Interno" id="txtBuscaCodigoInterno" /></th>
						<th><input type="text" placeholder="Codigo" id="txtBuscaCodigo" /></th>
						<th><input type="text" placeholder="Linha" id="txtBuscaLinha" /></th>
						<th>
							<select id="selBuscaSentido">
								<option value="">Sentido</option>
								<option value="1">Sentido 1</option>
								<option value="2">Sentido 2</option>
							</select>
						</th>						
						<th>
							<select id="selBuscaModalidade">
								<option value="">Modalidade</option>
								<option value="S">Semi-direto</option>
								<option value="C">Comum</option>
								<option value="C">Turismo</option>
							</select>
						</th>			
						<th>
							<select id="selBuscaRestrito">
								<option value="">Restri&ccedil;&atilde;o</option>
								<option value="1">Sim</option>
								<option value="0">N&atilde;o</option>
							</select>
						</th>	
						<th>
							<select id="selBuscaStatus">
								<option value="">Liberado</option>
								<option value="1">Sim</option>
								<option value="2">N&atilde;o</option>
							</select>
						</th>
						<th>Visualizar</th>
					</tr>
				</table>
			</div>
		</div>	
		<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.custom.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function () {
			var pageIndex = 1;  
			var request = false;  
			function resultado(page){
				page = (typeof page == 'undefined')?1:page;
				if(page==1)
					$('table tr').not(':eq(0)').remove();
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
					beforeSend : function(){	
						$('.loading').remove();					
						$('#linhas').append('<span class="loading"></span>');
						request = true;
					},
					success : function (json) {
						var html = '';
						if(json['linhas'] != null)
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
										'<td class="horario"></td>'+
									'</tr>'
						})	
						$('table').append(html)
					},
					complete : function(){
						$('.loading').remove();
						request = false;
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
				window.open("/adm/linha/restrito/" + $(this).parent().attr('data-id'),"_blank");
			})

			resultado();

		  	$(window).scroll(function () {
	            if (($(window).scrollTop() == $(document).height() - window.innerHeight) && !request) {
	            	request = true;
	              	pageIndex++;
	              	resultado(pageIndex);
	            }
	        });
		})
		</script>
	</body>
</html>