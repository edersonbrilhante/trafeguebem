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
			<div id="cidade">
				<form onsubmit="return false">
					<input type="hidden" name="hdnId" value="<?=$id?>">
					<p>
						<label>Nome</label>
						<input type="text" name="txtNome" value="<?=$nome?>">
					</p>
					<p>
						<label>Estado</label>
						<select name="selEstado">
							<option value="RS">RS</option>
						</select>
					</p>
					<p class="botoes">
						<button type="submit">Salvar</button>
						<button type="reset" onClick="loadFormCadastro();">Cancelar</button>
					</p>
				</form>
				<div id="divSearch" style="display: none;">
					<div id="divSearchField">
						<form id="formSearch" action="/adm/cidade/lista" onSubmit="return false;">
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
				$('[name=txtNome]').prompt({func: function(){
					$('#divSearch').dialog({
						title: 'Pesquisar cidades...',
						modal: true,
						resizable: false,
						width: 500,
						height: 300,
						closeOnEscape: true,
						position: ['center', 100]});
				}});

				$("button[type='submit']").click(function() {
					var attr = new Object();					
					
					attr.url = '/adm/cidade/cadastrar';
					attr.data = {
						id : $('[name=hdnId]').val(),
						nome : $('[name=txtNome]').val(),
						estado : $('[name=selEstado]').val()
					}
					submitForm(attr);
				});

			});
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