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

			<div class="button">
				<button>Dados da linha</button>
			</div>
			<div>
				<p id="tamanho"></p>
			</div>
			<div class="map">
				<div id="map"></div>
			</div>			
			<div id="formulario" style="display:none;">
				<form onsubmit="return false">
					<input type="hidden" name="hdnId" value="<?=$id?>"/>
					<input type="hidden" name="hdnClonar" value="<?=$clonar?>"/>
					<p>
						<label>Codigo Interno</label>
						<input type="text" name="txtCodigoInterno" value="<?=$codigoInterno?>"/>
					</p>
					<p>
						<label>Codigo</label>
						<input type="text" name="txtCodigo" value="<?=$codigo?>"/>
					</p>
					<p>
						<label>Nome</label>
						<input type="text" name="txtNome" value="<?=$nome?>"/>
					</p>
					<p>
						<label>Empresa</label>
						<select name="selCompany">				
							<? foreach ($companies as $key => $c) : ?>
							<option value="<?=$key?>" <?=($company==$key)?'selected':''?>><?=$c?></option>
							<? endforeach ?>
						</select>
					</p>
					<p>
						<label>Cidade</label>
						<select name="selCity">		
							<? foreach ($cities as $key => $c) : ?>
							<option value="<?=$key?>" <?=($city==$key)?'selected':''?>><?=$c?></option>
							<? endforeach ?>
						</select>	
					</p>
					<p>
						<label>Way</label>
						<select name="selWay">
							<option value="" <?=($way=='')?'selected':''?>>Sem Sentido</option>
							<option value="1" <?=($way=='1')?'selected':''?>>Sentido 1</option>
							<option value="2" <?=($way=='2')?'selected':''?>>Sentido 2</option>
						</select>	
					</p>
					<p>
						<label>Tipo</label>
						<select name="selTipo">
							<option value="B" <?=($tipo=='B')?'selected':''?>>Onibus</option>
							<option value="L" <?=($tipo=='L')?'selected':''?>>Lota&ccedil;&atilde;o</option>
						</select>	
					</p>
					<p>
						<label>Modalidade</label>
						<select name="selModalidade">
							<option value="S" <?=($modalidade=='S')?'selected':''?>>Semi-direto</option>
							<option value="C" <?=($modalidade=='C')?'selected':''?>>Comum</option>
							<option value="T" <?=($modalidade=='T')?'selected':''?>>Turismo</option>
						</select>	
					</p>
					<p>
						<label>Restrito</label>							
						<input type="checkbox" name="chkRestrito" <?=($restrito=='t')?'checked="checked"':''?>/>
					</p>
					<p>
						<label>Liberado</label>							
						<input type="checkbox" name="chkStatus" <?=($status=='1')?'checked="checked"':''?>/>
					</p>
					<p>
						<button id="btnSalvar">Salvar</button>
					</p>
				</form>
			</div>
		</div>

		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> 
		<script type="text/javascript" src="/js/markerclusterer.min.js"></script>
		<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.custom.min.js"></script>
		<script type="text/javascript" src="/js/jquery.json-2.2.min.js"></script>
		<script type="text/javascript" src="/js/globals.min.js"></script> 
		<script type="text/javascript" src="/js/system.insert.js"></script> 
		<script type="text/javascript" src="/js/insert.js"></script> 
	</body>
</html>