<div class="all">
	<?php $this->load->view('home/logo');?>
	<?php $this->load->view('menu');?>
	<input type="hidden" name="hdnEmpresa" value="<?=$empresa?>"/>
	<div class="map">
		<div id="map"></div>
	</div>
	<?php $this->load->view('home/toselect');?>
	<div class="options">		
		<?php $this->load->view('home/search');?>
		<h2>Resultado</h2>
		<div class="result">
		</div>

		<p class="info">
			<span style="font-weight:bold; float: left; width:268px; text-align:center">Sogil</span>
			<span style="font-size:9px; float: left; clear:left; padding-right: 8px; text-align: right; width: 126px;">
				<strong>Sentido 1</strong>
				<br>Gravata&iacute;/Outra Cidade
				<br>Bairro-Centro
				<br>P. 107 - P. 59
			</span>
			<span style="font-size:9px; float: left; padding-left: 8px; text-align: left; width: 126px;">
				<strong>Sentido 2</strong>
				<br>Outra Cidade/Gravata&iacute;
				<br>Centro-Bairro
				<br>P. 59 - P. 107
			</span>
			<span style="font-weight:bold; float: left; width:268px; text-align:center">Porto Alegre</span>
			<span style="font-size:9px; float: left; clear:left; padding-right: 8px; text-align: right; width: 126px;">
				<strong>Sentido 1</strong>
				<br>Circular
				<br>Bairro-Centro
				<br>Norte-Leste
				<br>Norte-Sul
			</span>
			<span style="font-size:9px; float: left; padding-left: 8px; text-align: left; width: 126px;">
				<strong>Sentido 2</strong>
				<br>Centro-Bairro
				<br>Leste-Norte
				<br>Sul-Norte
			</span>
		</p>
	</div>
	<div id="hora"></div>
	<div id="share"><input type="text" readonly /></div>
</div>