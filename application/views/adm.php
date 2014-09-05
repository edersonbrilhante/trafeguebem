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
			<div>
				<ul class="block">
					<?foreach ($modulos as $modulo):?>
					<li>
						<div class="btn">
							<a href="<?=$modulo['link']?>">
								<img src="/icon/<?=$modulo['icone']?>" class="btn-img"><?=$modulo['nome']?>
							</a>
						</div>
					</li>
					<? endforeach?>
				</ul>
			</div>
		</div>
	</body>
</html>