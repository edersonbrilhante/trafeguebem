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
			<div id="horario">
				<table>
					<tr>
						<th>Hora</th>
						<th>Segunda</th>
						<th>Terça</th>
						<th>Quarta</th>
						<th>Quinta</th>
						<th>Sexta</th>
						<th>Sábado</th>
						<th>Domingo</th>
						<th>Especial</th>
						<th>Seletivo</th>
						<th>Ações</th>
					</tr>
					<? 
				if($horarios):
					foreach ($horarios as $horario):?>
					<tr>
						<td>
							<input type="hidden" name="hdnId[]" value="<?=$horario['id']?>" />
							<input type="text" name="txtHora[]" value="<?=$horario['horario']?>" />
						</td>
						<td><input type="checkbox" value="segunda" <?=($horario['segunda']=='t')?'checked="checked"':''?> name="cbxSegunda[]" /></td>
						<td><input type="checkbox" value="terca" <?=($horario['terca']=='t')?'checked="checked"':''?> name="cbxTerca[]" /></td>
						<td><input type="checkbox" value="quarta" <?=($horario['quarta']=='t')?'checked="checked"':''?> name="cbxQuarta[]" /></td>
						<td><input type="checkbox" value="quinta" <?=($horario['quinta']=='t')?'checked="checked"':''?> name="cbxQuinta[]" /></td>
						<td><input type="checkbox" value="sexta" <?=($horario['sexta']=='t')?'checked="checked"':''?> name="cbxSexta[]" /></td>
						<td><input type="checkbox" value="sabado" <?=($horario['sabado']=='t')?'checked="checked"':''?> name="cbxSabado[]" /></td>
						<td><input type="checkbox" value="domingo" <?=($horario['domingo']=='t')?'checked="checked"':''?> name="cbxDomingo[]" /></td>
						<td><input type="checkbox" value="especial" <?=($horario['especial']=='t')?'checked="checked"':''?> name="cbxEspecial[]" /></td>
						<td><input type="checkbox" value="seletivo" <?=($horario['seletivo']=='t')?'checked="checked"':''?> name="cbxSeletivo[]" /></td>
						<td><span class="novo"></span><span class="excluir"></span></td>
					</tr>
					<?endforeach;
				else:?>			
					<tr>
						<td>
							<input type="hidden" name="hdnId[]" value="" />
							<input type="text" name="txtHora[]" value="" />
						</td>
						<td><input type="checkbox" value"segunda" name="cbxSegunda[]" /></td>
						<td><input type="checkbox" value"terca" name="cbxTerca[]" /></td>
						<td><input type="checkbox" value"quarta" name="cbxQuarta[]" /></td>
						<td><input type="checkbox" value"quinta" name="cbxQuinta[]" /></td>
						<td><input type="checkbox" value"sexta" name="cbxSexta[]" /></td>
						<td><input type="checkbox" value"sabado" name="cbxSabado[]" /></td>
						<td><input type="checkbox" value"domingo" name="cbxDomingo[]" /></td>
						<td><input type="checkbox" value"especial" name="cbxEspecial[]" /></td>
						<td><input type="checkbox" value"seletivo" name="cbxSeletivo[]" /></td>
						<td><span class="novo"></span><span class="excluir"></span></td>
					</tr>	
				<?endif;?>
				</table>
				<p><button id="btnSalvar">Salvar</button></p>
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.min.js"></script>
		<script type="text/javascript" src="/js/globals.min.js"></script> 
		<script type="text/javascript">
		$(document).ready(function () {  
			$.datepicker.setDefaults($.datepicker.regional['pt-br']);
			$.timepicker.regional['pt-br'] = {
				timeOnlyTitle: 'Selecione o horário',
				timeText: 'Horário',
				hourText: 'Hora',
				minuteText: 'Minuto',
				secondText: 'Segundo',
				currentText: 'Agora',
				closeText: 'Pronto',
				ampm: false
			};
			$.timepicker.setDefaults($.timepicker.regional['pt-br']);

			$('[name^=txtHora]').timepicker();

			$('#btnSalvar').button().click(function(){
				var id = $('[name^=hdnId]').map(function(){ return this.value }).get().join();
				var horario = $('[name^=txtHora]').map(function(){ return this.value }).get().join();
				var segunda = $('[name^=cbxSegunda]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var terca = $('[name^=cbxTerca]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var quarta = $('[name^=cbxQuarta]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var quinta = $('[name^=cbxQuinta]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var sexta = $('[name^=cbxSexta]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var sabado = $('[name^=cbxSabado]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var domingo = $('[name^=cbxDomingo]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var especial = $('[name^=cbxEspecial]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();
				var seletivo = $('[name^=cbxSeletivo]').map(function(){ return ($(this).is(':checked'))?1:0}).get().join();

				$.ajax({
					type: 'POST',
					url: '/insert/horario',
					data: {
						rotaid : $.getUrlVar('id'),
						id : id,
						horario : horario,
						segunda : segunda,
						terca : terca,
						quarta : quarta,
						quinta : quinta,
						sexta : sexta,
						sabado : sabado,
						domingo : domingo,
						especial : especial,
						seletivo : seletivo
					},
					dataType: 'json',
			    	success: function(json) {
						instance.isInserting = false;
						location.reload(true);
					}
				})
			})

			$('body').on('click','.novo',function(){
				$(this).parent().parent().after($(this).parent().parent().clone(true));
				$(this).parent().parent().next().find('input').val('').removeAttr('checked').removeAttr('class').removeAttr('id');
				$('[name^=txtHora]').timepicker();
			})

			$('body').on('click','.excluir',function(){
				$(this).parent().parent().remove();
			})
		})
		</script>	
	</body>
</html>