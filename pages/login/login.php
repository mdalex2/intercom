<?php 
if (session_status() === PHP_SESSION_ACTIVE) {session_destroy();}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Intercom | Acceso al sistema</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Inter</b>com</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar sesión en el sistema</p>

    <form id="frm_login" name="frm_login" action="#" method="post">
      <div class="form-group has-feedback">
        <input id="email" name="email" type="email" class="form-control" placeholder="Email" onKeyPress="$('#div_msg').hide();" value="admin@intercom.com">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="password" name="password" type="password" class="form-control" placeholder="Password" onKeyPress="$('#div_msg').hide();">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
		<div class="col-xs-12" id="div_msg" hidden="hidden">
			
		</div>
		<div class="col-xs-12" id="div_procesando" hidden="hidden">
			<p><img src="../../images/sistema/loading-red.gif"> Procesando...</p>
		</div>
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Recordar mis datos
            </label>
          </div>
        </div>
		
        <!-- /.col -->
        <div class="col-xs-4">
          <button id="btn_enviar" type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
        </div>
		
        <!-- /.col -->
      </div>
    </form>
	<!--
    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
-->
    <!-- /.social-auth-links -->

    <a href="#">Olvidé mi contraseña</a><br>
    <a href="register.html" class="text-center">Registrarse</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- BOOTSTRAP VALIDATOR -->
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.min.js'></script>
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.es_CL.js'></script>
	
<script src="../../validaciones-js/config-usuario/validacion-login.js"></script>	
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
var enviando=false;
// EL FORMULARIO DE EDITAR PARA EL GUARDADO	
$("#frm_login").submit(function (e) {	
	e.preventDefault();	
	$("#btn_enviar").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false && $("#email").val!="" && $("#password").val()!=""){
	
	$.ajax({
		type: 'POST',
		url:  'valida-login.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#div_msg").empty();
			$("#div_msg").show();
			$("#div_procesando").show();
			$("#btn_enviar").prop("disabled",true);
			enviando=true;			
		},
		success: function(data) {
		  $("#div_procesando").hide();
		  $("#div_msg").append(data);		  
		  $("#btn_enviar").prop("disabled",false);
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});		
</script>
</body>
</html>
