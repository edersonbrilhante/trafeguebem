<html>
	<? $this->load->view('head_insert');?>
	<body class="adm">
		<div class="all">
			<div id="topo">
				<div class="logoEmpresa<?=companyId()?>"><h1>Logo</h1></div>
				<a class="logoff" href="/logoff">Sair</a>
			</div>

			<div id="login">
				<h2>Login</h2>
				<p><input type="text" placeholder="Usuario" id="txtUsuario" /></p>
				<p><input type="password" placeholder="Senha" id="pwdSenha" /></p>
				<p><button id="btnLogin">Login</button></p>
				<p id="msg"></p>
			</div>
		</div>	
		<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.custom.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function () {  
			$('#btnLogin').button().click(function(){
				user = $('#txtUsuario').val();
				password = $('#pwdSenha').val();
				$.ajax({
					type: 'POST',
					url: '/logon',
					dataType:'json',
					data: {
						user:user,
						password: password
					},
					success: function(res){
						if(res.erro!=0){
							$('#msg').html(res.msg).show()
							$('#confirm').dialog("close");							
						}
						else
							location.replace("/linhas")
					}	
				})
			})
		})
		</script>
	</body>
</html>