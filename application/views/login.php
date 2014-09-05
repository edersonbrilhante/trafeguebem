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
        <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
               -moz-border-radius: 5px;
                    border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
               -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                    box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
        }

        </style>
        <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="/js/html5shiv.js"></script>
        <![endif]-->
    
            <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    </head>

    <body>

        <div class="container">

            <form class="form-signin" onsubmit="return false">
                <h2 class="form-signin-heading">Fa&ccedil;a login</h2>
                <input type="text" id="txtUsuario" class="input-block-level" placeholder="Usu&aacute;rio">
                <input type="password" id="pwdSenha" class="input-block-level" placeholder="Senha">
                <button class="btn btn-large btn-primary" id="btnLogin" type="submit">Sign in</button>
                <p id="msg" class="text-error"></p>
            </form>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->    
        <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {  
            $('#btnLogin').click(function(){
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
                        }
                        else
                            location.replace("/adm")
                    } 
                })
            })
        })
        </script>

    </body>
</html>
