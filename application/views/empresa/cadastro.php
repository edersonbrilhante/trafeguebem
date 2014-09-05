<html>
	<? $this->load->view('head_insert');?>

	<body class="adm">
		<div class="all">
			<div id="topo">
				<div class="logoEmpresa<?=companyId()?>"><h1>Logo</h1></div>
				<a href="/logoff">Sair</a>
			</div>
			<div id="navegacao">
				<?=create_breadcrumb()?>
			</div>
			<div id="empresa">
				<form onsubmit="return false">
					<input type="hidden" name="hdnId" value="<?=$id?>">
					<p>
						<label>Nome</label>
						<input type="text" name="txtNome" value="<?=$nome?>">
					</p>
					<div id="divTabs">
						<ul>
							<li><a href="#usuario">Usu&aacute;rios</a></li>
						</ul>
						<div id="usuario">
							<div style="float: left; width: 50%; margin:10px 0 0 0">
								<h3>Para selecionar, arraste as empresas desejadas</h3>

								<div id="divListaUsuario" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px">
									<p class="pesquisa">
										<label for="txtPesquisaUsuario">Pesquisar</label>
										<input type="text" class="text" id="txtPesquisaUsuario">
									</p>
									<ul id="ulEmpresa" class='droptrue'></ul>
								</div>
							</div>

							<div style="float: right; width: 49%; margin:10px 0 0 0">
								<h3>Usu&aacute;rios selecionados</h3>
								<div id="divListaUsuarioSelecionado" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px;">
									<ul id="ulUsuarioSelecionado" class='droptrue' title="Para remover, arraste de volta">
										<?php
											for ($i=0;$i<sizeof($usuario); ++$i) {
												echo '<li class="ui-state-default" id="emp'.intval($usuario[$i]->Id).'" >'.string_html($usuario[$i]->Name).'</li>';
											}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<p class="botoes">
						<button type="submit">Salvar</button>
						<button type="reset" onClick="loadFormCadastro();">Cancelar</button>
					</p>
				</form>
				<div id="divSearch" style="display: none;">
					<div id="divSearchField">
						<form id="formSearch" action="/adm/empresa/lista" onSubmit="return false;">
							<input type="text" class="text" id="search" value="" name="search" size="45">
							<button onClick="mySearch('formSearch');" class="button" style="width: 78px;">Pesquisar</button>
						</form>
					</div>
					<div id="divSearchResponse"></div>
				</div>	
			</div>		
		</div>		
		<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.custom.min.js"></script>
		<script type="text/javascript" src="/js/jquery.ui.prompt.js"></script>
		<script type="text/javascript" src="/js/funcoes.js"></script>
		<script>
			$(document).ready(function () {
				$('button').button();
				$('#divTabs').tabs({
					create: function(event, ui) {
						fillList('ulEmpresa', '/adm/usuario/lista', {page : 1});
					}
				});

				$('ul.droptrue').sortable({
					connectWith: 'ul',
					placeholder: 'ui-state-highlight',
					forcePlaceholderSize: true
				});

				$('[name=txtNome]').prompt({func: function(){
					$('#divSearch').dialog({
						title: 'Pesquisar empresas...',
						modal: true,
						resizable: false,
						width: 500,
						height: 300,
						closeOnEscape: true,
						position: ['center', 100]});
				}});


				// Busca os targets ao digitar no campo pesquisa
				$('#txtPesquisaUsuario').keyup(function() {
					var sSearch = $(this).val(),
						oData = {search : sSearch, page : 1};

					$('#ulUsuario').html('');
					fillList('ulUsuario', '/adm/usuario/lista', oData);
				}).keydown(function(e){
					if(e.keyCode == 13) return false;
				});

				$("button[type='submit']").click(function() {
					var attr = new Object();

					var	usuarioSel = $("#ulUsuarioSelecionado li").map(function() {
						return $(this).attr('id').substring(3);
					}).get().join();					
					
					attr.url = '/adm/empresa/cadastrar';
					attr.data = {
						id : $('[name=hdnId]').val(),
						nome : $('[name=txtNome]').val(),
						usuario : usuarioSel
					}
					submitForm(attr);
				});

				function fillList(pListID, url, oData) {
					// Concatenamos # para usar com Jquery
					var listID = '#' + pListID;

					$.ajax({
						url : url,
						data : oData,
						type : 'GET',
						dataType : 'json',
						jsonp: null,
						jsonpCallback: null,
						success : function(data) {
							var	item,page = 0;

							page = parseInt($(listID).next().attr('id')) || 1;
							$(listID).next().remove();

							$(data).each(function(index) {
								var itemID = 'emp'+data[index].id;

								if (typeof $('#'+itemID).attr('id') != 'string') {
									item = $('<li>')
											.addClass('ui-state-default')
											.text(data[index].name)
											.attr('id', itemID);

									// se tem descricao, criamos o title para tooltip
									if (data[index].desc) {
										$(item).attr('title', data[index].desc);
									}

									$(listID).append(item);
								}
							});

							// Cria a opcao "Ver mais..."
							if (data && oData.page) {
								item = $('<div>')
										.attr('id', page + 1)
										.css('cursor', 'pointer')
										.css('padding-left', '10px')
										.text('Ver mais...')
										.click(function() {
											oData.page = page + 1;
											fillList(pListID, url, oData);
										});

								$(listID).after(item);
							}
						},
						error : function(response) {
						},
						complete: function(){
							//Dbl Clique funciona como arrastar:
							$("ul#ulUsuario li").unbind("dblclick");
							$("ul#ulUsuario li").dblclick(function(){
								$(this).unbind('dblclick')
									.clone()
									.appendTo("ul#ulUsuarioSelecionado")
									.end().end()
									.remove();
							});
						}
					});
				}

			})
			function mySearch(formId){
				var columns = [
					{title: 'Nome', index: 'nome'},
					{title: 'Editar'},
					{title: 'Excluir', url: '/adm/empresa/deletar'}];

				searchCadastros(formId, columns);
			}

		</script>
	</body>
</html>		