<?php
session_start();
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/func_formato_num.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/fechas_func.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.encriptar.php');
	require_once('class-controlador-bd.php');
	$bd_usuarios = new bd_usuarios;
	
	$encriptar=new encriptado();
	
	require_once("../vistas-globales/menu-izquierdo.php");
    //ESTABLECER ZONA HORARIA ALMACENADA EN LA SESION
    //date_default_timezone_set($_SESSION['zona_hora']);
	$accion_ejecutar = (!empty($_GET['accion']))? $encriptar->desencriptar($_GET['accion'],'') : 'buscar';
	$id = (!empty($_GET['id']))? $encriptar->desencriptar($_GET['id'],'') : '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Intercom - Configuración de usuarios</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">	
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="icon" type="image/png" sizes="16x16" href="../../images/sistema/icon.png">	
  <!-- PARA LAS ALERTAS PERSONALIZADAS -->
  <link href="../../bower_components/PNotify/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" /> 
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
	<!-- AQUI VA LA BARRA DE ARRIBA -->
	<?PHP
  	require_once("../vistas-globales/barra-superior.php");
	?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
	$menu_izq= new menu_izquierdo();
	$menu_izq->abrir_menu();
	$menu_izq->mostrar_profile();
	$menu_izq->mostrar_buscar_funciones();
	$menu_izq->mostrar_menu_funciones();
	$menu_izq->cerrar_menu();
	?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Administrador de clientes / usuarios 
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../home/"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="usuarios.php">Clientes / usuarios</a></li>
        <li class="active">Administrar</li>
      </ol>
    </section>
	<!-- Main content -->
    <section class="content">
      
	  
	  <?php
		  //echo $accion_ejecutar;
		  switch ($accion_ejecutar){
			  case 'buscar':
				  include_once('form-buscar.php');
				  break;
			  case 'nuevo':
				  include_once('form-nuevo.php');
				  //include_once('../../../login/frm_crear_cuenta.php');
				  break;
			  case 'mostrar':
				  include_once('form-editar.php');
				  break;
		  }
		 ?>	  	
      
          <!-- /.box -->
      <!-- /.row -->

    </section>
    <!-- /.content -->	  
	
    
  </div>
  <!-- /.content-wrapper -->
  <?php
	include_once('../vistas-globales/main-footer.php'); //barra de copyright
	include_once('../vistas-globales/side-bar-config.php'); //barra de cambio de color etc
  ?>
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>	
<!-- Select2 -->
<script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../bower_components/moment/min/moment.min.js"></script>
<script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- PARA LAS ALERTAS PERSONALIZADAS -->
 <script type="text/javascript" src="../../bower_components/PNotify/pnotify.custom.min.js"></script>
<!-- PARA EL NUMERO EN ESPAÑOL -->
<script src="../../bower_components/numeral-js/numeral.min.js"></script>
<script src="../../bower_components/numeral-js/numeral.locales.es.min.js"></script>
	
<!-- Page script -->
<script>
	
$("#chk_mod_pwd").change(function() {
    if(this.checked) {
       $("#password").prop("disabled",false);
	   $("#password").focus();
	} else {
	   $("#password").prop("disabled",true);
    }
});
  //CUANDO CAMBIA EL COMBO CAMBIO EL TEXTO DE LA BUSQUEDA
  $('#cmb_tip_bus').change(function(){
	  $('#lbl_tipo_busc').text($('option:selected',this).text());	 
	  var opcion = $('option:selected',this).val();
	  switch (parseInt(opcion)){
		  case 1:
			 $('#txt_buscar').attr("placeholder", "Nombres / apellidos o razón social");			  
			 break;
		  case 2:
			 $('#txt_buscar').attr("placeholder", "Nº cédula / RIF");
			 break;
		  case 3:
			 $('#txt_buscar').attr("placeholder", "Login o email de acceso");
			 break;	  
	  }
	  $("#txt_buscar").focus(); //posiciono el focus en el buscar
  });

//ESTO ESTABA PERIDDO VER DE DONDE ES
	/*
 var checkboxes = $(this).closest('frm_buscar').find(':checkbox');
 checkboxes.prop('checked', $(this).is(':checked'));
	*/
	
$(function () {
  	  
	  /*
	$('#tabla_busc').DataTable()
	$('#tabla').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
	*/
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>
</body>
</html>
<script type="text/javascript">
	
function bloquear_clie(id_clie){
	$.ajax({
		type: 'POST',
		url:  'bd-bloquear-usu.php',
		data: {id_usu:id_clie},
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			$("#procesando").show();
		},
		success: function(data) {
		  $("#msg").show();
		  $("#msg").append(data);
		  $("#procesando").hide();	
			
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

function quitar_operador(id_cuenta_oper){
	$.ajax({
		type: 'POST',
		url:  'bd-quitar-operador-banco.php',
		data: {id_cuenta_oper:id_cuenta_oper},
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			$("#quitando_oper").show();
		},
		success: function(data) {
		  $("#msg").show();
		  $("#msg").append(data);
		  $("#quitando_oper").hide();
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
	
function bloquear_cuenta_banco(id_cuenta){
	$.ajax({
		type: 'POST',
		url:  'bd-bloquear-cuenta-banco.php',
		data: {id_cuenta:id_cuenta},
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			$("#procesando").show();
		},
		success: function(data) {
		  $("#msg").show();
		  $("#msg").append(data);
		  $("#procesando").hide();	
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
var enviando=false;
$("#frm_buscar").submit(function (e) {	
	e.preventDefault();	
	$("#btn_buscar").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false){
	
	$.ajax({
		type: 'POST',
		url:  'ajax-buscar-datos.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#div_res_bus").empty();
			$("#btn_buscar").prop("disabled",true);
			$("#procesando").show();
			enviando=true;
		},
		success: function(data) {
		  $("#msg").show();
		  $("#div_res_bus").append(data);
		  $("#btn_buscar").prop("disabled",false);
		  $("#procesando").hide();	
			enviando=false;
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});
	
$("#form_nuevo").submit(function (e) {	
	e.preventDefault();	
	$("#btn_guardar").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false && $("#txt_cedula").val!="" && $("#cmb_tip_cuenta").val()!=""){
	
	$.ajax({
		type: 'POST',
		url:  'bd-guardar.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			
			e.preventDefault();
			$("#div_res_gua").empty();
			$("#btn_guardar").prop("disabled",true);
			$("#div_res_gua").show();
			$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  $("#msg").show();
		  $("#div_res_gua").append(data);
		  $("#btn_guardar").prop("disabled",false);
		  $("#procesando").hide();
		  enviando=false;
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});	
	
	//FORMULARIO DE REGISTRO DE CUENTA:
$("#form_reg_cuenta_banco").submit(function (e) {	
	e.preventDefault();	
	$("#btn_reg_cuenta_banco").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false && $("#num_cuenta").val!="" && $("#cmb_banco").val()!=""){
	
	$.ajax({
		type: 'POST',
		url:  'bd-cuenta-banco-guardar.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#div_res_gua").empty();
			$("#btn_reg_cuenta_banco").prop("disabled",true);
			$("#div_res_gua").show();
			//$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  //$("#msg").show();
		  $("#div_res_gua").append(data);
		  $("#btn_reg_cuenta_banco").prop("disabled",false);
		  enviando=false;
		  //$("#procesando").hide();	
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});	
	
//FORMULARIO DE ASIGNCION DE OPERARIO
$("#form_asig_oper").submit(function (e) {	
	e.preventDefault();	
	$("#btn_add_operador").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false && $("#cmb_tip_cliente_modal").val!="" && $("#cmb_operador_cuenta_modal").val()!=""){
	
	$.ajax({
		type: 'POST',
		url:  'bd-cuenta-banco-asig-oper.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#div_res_gua").empty();
			$("#btn_add_operador").prop("disabled",true);
			$("#div_res_gua").show();
			//$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  //$("#msg").show();
		  $("#div_res_gua").append(data);
		  $("#btn_add_operador").prop("disabled",false);
			enviando=false;
		  //$("#procesando").hide();	
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});		
// EL FORMULARIO DE EDITAR PARA EL GUARDADO	
$("#form_editar").submit(function (e) {	
	e.preventDefault();	
	$("#btn_guardar").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false && $("#txt_cedula").val!="" && $("#cmb_tip_cuenta_usu").val()!=""){
	
	$.ajax({
		type: 'POST',
		url:  'bd-guardar-modif.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#div_res_gua").empty();
			$("#btn_guardar").prop("disabled",true);
			$("#div_res_gua").show();
			$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  $("#msg").show();
		  $("#div_res_gua").append(data);
		  $("#btn_guardar").prop("disabled",false);
		  $("#procesando").hide();	
		  //alert('termine');
			enviando=false;
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});	
function cargar_cuentas_bancos(){
	//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false){
	
	$.ajax({
		type: 'POST',
		url:  'bd-mostrar-cuentas-bancos.php',
		//data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			//alert("entre");
			//e.preventDefault();
			$("#div_cuentas_bancos").empty();
			//$("#btn_reg_cuenta_banco").prop("disabled",true);
			//$("#div_res_gua").show();
			$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  //$("#msg").show();
		  $("#div_cuentas_bancos").append(data);
		  //$("#btn_reg_cuenta_banco").prop("disabled",false);
		  $("#procesando").hide();	
		  //alert('termine');
			enviando=false;
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
} // FIN CARGAR CUENTAS BANCOS
	
function pasar_datos_form_edit_cuenta(id_cuenta){
	//esta funcion pasa modo jquery los datos al form editar para se editados mediante ajax posteriormente
	$('#id_cuenta_ant').val($('#id_cuenta'+id_cuenta).val());
	$('#cmb_banco_edit').val($('#id_banco'+id_cuenta).val()).trigger('change');
	$('#cmb_tip_cuenta_edit').val($('#id_cate_tipo_cuenta'+id_cuenta).val()).trigger('change');
	$('#cmb_tip_cliente_edit').val($('#id_cate_tipo_usua'+id_cuenta).val()).trigger('change');
	$('#cmb_tip_divisa_edit').val($('#id_cate_tipo_divisa'+id_cuenta).val()).trigger('change');
	$('#num_cuenta_edit').val($('#num_cuenta_ocu'+id_cuenta).val());
	$('#monto_max_edit').val($('#monto_max_tranf'+id_cuenta).val());
	if ($('#item_visi'+id_cuenta).val()==0){
		$('#chk_bloq_cuenta_banc').prop('checked', true);
	} else {
		$('#chk_bloq_cuenta_banc').prop('checked', false);
	}
}
function pasar_datos_form_oper_add(id_cuenta){
	//esta funcion pasa modo jquery los datos al form editar para se editados mediante ajax posteriormente
	$('#id_cuenta_ant_oper').val($('#id_cuenta'+id_cuenta+'').val());
	//$('#cmb_tip_cliente_oper').val($('#id_cate_tipo_usua'+id_cuenta).val()).trigger('change');
}	

$("#form_modif_cuenta_banco").submit(function (e) {	
	e.preventDefault();	
	$("#btn_modif_cuenta_banco").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
	
if (enviando==false){
	$.ajax({
		type: 'POST',
		url:  'bd-cuenta-banco-modif.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			
			e.preventDefault();
			$("#div_res_gua").empty();
			$("#btn_modif_cuenta_banco").prop("disabled",true);
			$("#div_res_gua").show();
			$("#procesando").show();
			enviando=true;			
		},
		success: function(data) {
		  
		  $("#msg").show();
		  $("#div_res_gua").append(data);
		  $("#btn_modif_cuenta_banco").prop("disabled",false);
		  $("#procesando").hide();	
			enviando=false;
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
			enviando=false;
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})	
	}
});	
	
//AL CAMBIAR EL TIPO DE CLIENTE AGREGO LOS OPERADORES (USUARIOS QUE PUEDEN MANEJAR LA CUENTA) DE ESE TIPO DE CLIENTE
$('#cmb_tip_cliente').change(function(){
	var tipo_oper=$(this).val();
	$.ajax({
		type: 'POST',
		url:  'bd-cargar-combo-operadores.php',
		data: {tipo_oper:tipo_oper},
		beforeSend: function() {
			$("#cmb_operador_cuenta_add").empty();
			$("#img_carg_oper").show();
			//enviando=true;			
		},
		success: function(data) {
		  $("#cmb_operador_cuenta_add").append(data);
		  $("#img_carg_oper").hide();	
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
		},
		complete: function() {
		   //enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})		   
});
	
//ESTE PARA EL MODAL
	$('#cmb_tip_cliente_modal').change(function(){
	var tipo_oper=$(this).val();
	$.ajax({
		type: 'POST',
		url:  'bd-cargar-combo-operadores.php',
		data: {tipo_oper:tipo_oper},
		beforeSend: function() {
			$("#cmb_operador_cuenta_modal").empty();
			$("#img_carg_oper_modal").show();
			//enviando=true;			
		},
		success: function(data) {
		  $("#cmb_operador_cuenta_modal").append(data);
		  $("#img_carg_oper_modal").hide();	
		},
		error: function(xhr) { // if error occured
			alert('Se produjo un error al procesar la solicitud');
		},
		complete: function() {
		   //enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',

		})		   
});
	
function numeralEsp(control){
    numeral.locale('es');
    var cost_flet=$(control).val();
    //pongo el valor en la casilla correspondiente
    return numeral(cost_flet).format('0,0.00');
}

	function numero_esp_mysql(num_esp){
    var num_eng = num_esp;
    var num_eng = num_eng.replace(/[.]/gi,'');
    num_eng = num_eng.replace(/[,]/gi,'.');
    return num_eng;
} 	

	$(document).ready(function() {
    
    $("#myModal").modal({backdrop: false}).modal("show");
    $("#cmb_tip_cuenta").select2();

});
</script>
<?php 
	 if ($accion_ejecutar=='mostrar'){echo "<script>cargar_cuentas_bancos();</script>";}
?>
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.min.js'></script>
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.es_CL.js'></script>
<script src="../../validaciones-js/config-usuario/validacion-registro-usuario.js"></script>
