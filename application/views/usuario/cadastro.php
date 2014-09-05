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
			<div id="usuario">
				<form onsubmit="return false">
					<input type="hidden" name="hdnId" value="<?=$id?>">
					<p>
						<label>Nome</label>
						<input type="text" name="txtNome" required value="<?=$name?>">
					</p>
					<p>
						<label>Senha</label>
						<input type="password" name="pwdSenha" <?=($id==''?"required":'')?> value="">
					</p>
					<p>
						<label>Usuario</label>
						<input type="text" name="txtUsuario" required value="<?=$user?>">
					</p>
					<?if(isRoot()):?>
						<p>
							<label>Permiss&atilde;o</label>
							<select name="selPermissao">
								<option value="1" <?=($permissao==1)?'selected':''?>>Root</option>
								<option value="2" <?=($permissao==2)?'selected':''?>>Admin</option>
								<option value="3" <?=($permissao==3)?'selected':''?>>User</option>
							</select>
						</p>
					<?endif?>
					<div id="divTabs" class="divTabs">
						<ul>
							<li><a href="#empresa">Empresas</a></li>
							<li><a href="#rota">Rotas</a></li>
						</ul>
						<div id="empresa">
							<div style="float: left; width: 50%; margin:10px 0 0 0">
								<h3>Para selecionar, arraste os Empresas desejadas</h3>

								<div id="divListaEmpresa" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px">
									<p class="pesquisa">
										<label for="txtPesquisaEmpresa">Pesquisar</label>
										<input type="text" class="text" id="txtPesquisaEmpresa">
									</p>
									<ul id="ulEmpresa" class='droptrue'></ul>
								</div>
							</div>

							<div style="float: right; width: 49%; margin:10px 0 0 0">
								<h3>Empresas selecionadas</h3>
								<div id="divListaEmpresaSelecionada" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px;">
									<ul id="ulEmpresaSelecionada" class='droptrue' title="Para remover, arraste de volta">
										<?php
											for ($i=0;$i<sizeof($empresa); ++$i) {
												echo '<li class="ui-state-default" id="emp'.intval($empresa[$i]->Id).'" >'.string_html($empresa[$i]->Nome).'</li>';
											}
										?>
									</ul>
								</div>
							</div>
						</div>
						<div id="rota">
							<div style="float: left; width: 50%; margin:10px 0 0 0">
								<h3>Para selecionar, arraste as rotas desejados</h3>

								<div id="divListaRota" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px">
									<p class="pesquisa">
										<label for="txtPesquisaRota">Pesquisar</label>
										<input type="text" class="text" id="txtPesquisaRota">
									</p>
									<ul id="ulRota" class='droptrue'></ul>
								</div>
							</div>

							<div style="float: right; width: 49%; margin:10px 0 0 0">
								<h3>Rotas selecionadas</h3>
								<div id="divListaRotaSelecionada" style="border: 1px solid #D5CFBB; overflow: auto; max-height: 280px;">
									<ul id="ulRotaSelecionada" class='droptrue' title="Para remover, arraste de volta">
										<?php
											for ($i=0;$i<sizeof($rota); ++$i) {
												echo '<li class="ui-state-default" id="emp'.intval($rota[$i]->Id).'" >'.string_html($rota[$i]->Nome).'</li>';
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
						<form id="formSearch" action="/adm/usuario/lista" onSubmit="return false;">
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
						fillList('ulEmpresa', '/adm/empresa/lista', {page : 1});
						fillList('ulRota', '/adm/linha/lista', {page : 1});
					}
				});

				$('ul.droptrue').sortable({
					connectWith: 'ul',
					placeholder: 'ui-state-highlight',
					forcePlaceholderSize: true
				});

				$('[name=txtNome]').prompt({func: function(){
					$('#divSearch').dialog({
						title: 'Pesquisar usu&aacute;rios...',
						modal: true,
						resizable: false,
						width: 500,
						height: 300,
						closeOnEscape: true,
						position: ['center', 100]});
				}});


				// Busca os targets ao digitar no campo pesquisa
				$('#txtPesquisaEmpresa').keyup(function() {
					var sSearch = $(this).val(),
						oData = {search : sSearch, page : 1};

					$('#ulEmpresa').html('');
					fillList('ulEmpresa', '/adm/empresa/lista', oData);
				}).keydown(function(e){
					if(e.keyCode == 13) return false;
				});


				// Busca os targets ao digitar no campo pesquisa
				$('#txtPesquisaRota').keyup(function() {
					var sSearch = $(this).val(),
						oData = {search : sSearch, page : 1};

					$('#ulRota').html('');
					fillList('ulRota', '/adm/linha/lista', oData);
				}).keydown(function(e){
					if(e.keyCode == 13) return false;
				});

				$("button[type='submit']").click(function() {
					var attr = new Object();

					var	empresaSel = $("#ulEmpresaSelecionada li").map(function() {
						return $(this).attr('id').substring(3);
					}).get().join();

					var	rotaSel = $("#ulRotaSelecionada li").map(function() {
						return $(this).attr('id').substring(3);
					}).get().join();			
					
					attr.url = '/adm/usuario/cadastrar';
					attr.data = {
						id : $('[name=hdnId]').val(),
						nome : $('[name=txtNome]').val(),
						user : $('[name=txtUsuario]').val(),
						password : $('[name=pwdSenha]').val(),
						permissao : $('[name=selPermissao]').val(),
						empresa : empresaSel,
						rota : rotaSel
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
											.text(data[index].nome)
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
							$("ul#ulEmpresa li").unbind("dblclick");
							$("ul#ulEmpresa li").dblclick(function(){
								$(this).unbind('dblclick')
									.clone()
									.appendTo("ul#ulEmpresaSelecionada")
									.end().end()
									.remove();
							});
						}
					});
				}

			})
			function mySearch(formId){
				var columns = [
					{title: 'Nome', index: 'name'},
					{title: 'Usuário', index: 'user'},
					{title: 'Permissão', index: 'permissao'},
					{title: 'Editar'},
					{title: 'Excluir', url: '/adm/usuario/deletar'}];

				searchCadastros(formId, columns);
			}

		</script>		
	</body>
</html>		