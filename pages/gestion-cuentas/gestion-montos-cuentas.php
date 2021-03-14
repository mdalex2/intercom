<?php
session_start();
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/func_formato_num.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/fechas_func.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.encriptar.php');
	//require_once('class-controlador-bd.php');
	//$bd_usuarios = new bd_usuarios;
	
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
        Gestionar montos de cuentas bancarias 
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../home/"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="usuarios.php">cuentas bancarias</a></li>
        <li class="active">Actualizar montos</li>
      </ol>
    </section>
	<!-- Main content -->
    <section class="content">
	<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"></h3>
		  <?php 
			require_once("class-consultas-bd.php");
			$consultas_bd = new consultas_bd();
			$usuario_actual=$_SESSION['id_clie'];
			$consulta=$consultas_bd->obtener_listado_cuentas_asignadas($usuario_actual);
			$filas   = $consulta->fetchAll(\PDO::FETCH_OBJ);

			//var_dump($filas);
			?>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
		  <form id="frm_gestion_cuenta" name="frm_gestion_cuenta" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
			  
              <div class="form-group">
                <label>Seleccione la cuenta</label>
                <select id="cmb_cuenta" name="cmb_cuenta" class="form-control select2" style="width: 100%;" required>
				  <?php
				    $texto_combo=($consulta->rowCount()>0)? "Seleccione una cuenta" : "No tiene cuentas asignadas";
					?>
                  <option selected="selected" value=""><?php echo $texto_combo;?></option>
				  <?php 
					foreach ($filas as $fila):
				  ?>
                  	<option value="<?php echo $fila->id_cuenta;?>"><?php echo $fila->deno_banco." - ".$fila->num_cuenta ." (".$fila->tipo_cuenta.")";?></option>
				  <?php
					endforeach;
				  ?>
                </select>
              </div>
              <!-- /.form-group -->
			<div class="col-md-6">
              <div class="form-group" id="div_cuenta">
					<label for="txt_monto" class="control-label" >Monto a ingresar</label>
					<input id="txt_monto" name="txt_monto" placeholder="" class="form-control" style="text-transform:uppercase; width: 100%;" onkeyup="javascript:this.value=this.value.toUpperCase();" onChange="$(this).val(numeralEsp($(this)));" value="" required>
			 </div>
			<div class="form-group">
                <label>Fecha de depósito</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input id="txt_fecha"  name="txt_fecha" type="text" class="form-control pull-right" onChange="$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_fecha');">
                </div>
                <!-- /.input group -->
              </div>			
			</div>
			<div class="col-md-6">
			 <div class="form-group" id="div_cuenta">
				  <label class="control-label" >Número de referencia bancaria</label> 
					<input  id="txt_num_ref" name="txt_num_ref" placeholder="" class="form-control" style="text-transform:uppercase; width: 100%;"  value="" required>
			</div>
			<br><button id="btn_enviar" type="submit" class="btn btn-primary">Agregar monto</button>
			</div>
			
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
				<h3>
                <label>Saldo disponible:</label>&nbsp;
                <label id="lbl_saldo"></label>
				</h3>
              </div>
				
            </div>
            <!-- /.col -->
				
          </div>
			  
          <!-- /.row -->
		</form>
        </div>
		
        <!-- /.box-body -->
		<div id="procesando" hidden="hidden"><p><img src="../../images/sistema/loading-red.gif"> Procesando...</p></div>		
        <div class="box-footer" id="msg_info">
          	
        </div>
      </div>
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
<script src="../../bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>
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
    $('#txt_fecha').datepicker({
      autoclose: true,
	  language: 'es',
	  todayHighlight: true,
	  format: "dd-mm-yyyy"
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
	

var enviando=false;
$("#frm_gestion_cuenta").submit(function (e) {	
	e.preventDefault();	
	$("#btn_enviar").prop("disabled",true);
//SE DEBE VALIDAR DEL LADO DEL SERVIDOR PARA EVITAR CAMPOS VACÍOS SIEMPRE IMPORTANTE!!! *******
if (enviando==false){
	
	$.ajax({
		type: 'POST',
		url:  'bd-monto-banco-guardar.php',
		data: $(this).serialize(),
		//data: $("#contact_form").serialize(),
		beforeSend: function() {
			e.preventDefault();
			$("#msg_info").empty();
			$("#btn_enviar").prop("disabled",true);
			$("#procesando").show();
			enviando=true;
		},
		success: function(data) {
		  $("#msg_info").append(data);
		  $("#btn_enviar").prop("disabled",false);
		  $("#procesando").hide();	
		  //alert('termine');
		},
		error: function(xhr) { // if error occured
			$("#procesando").hide();
			$("#btn_enviar").prop("disabled",false);
			alert('Se produjo un error al procesar la solicitud');
		},
		complete: function() {
		   enviando=false; //pongo a falso que no se está enviando nada
		},
		dataType: 'html',
		})	
	}
});
	


	
//AL SELECCIONAR UNA CUENTA, SE AGREGA EL SALDO
$('#cmb_cuenta').change(function(){
	var id_cuenta=$(this).val();
	$.ajax({
		type: 'POST',
		url:  'ajax-bd-consulta-saldo.php',
		data: {id_cuenta:id_cuenta},
		beforeSend: function() {
			$("#lbl_saldo").empty();
			$("#procesando").show();
			//enviando=true;			
		},
		success: function(data) {
		  $("#lbl_saldo").append(data);
		  $("#procesando").hide();	
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
//cargar_cuentas_bancos();
$(document).ready(function() {
    $("#myModal").modal({backdrop: false}).modal("show");
    $("#cmb_tip_cuenta").select2();

});
</script>
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.min.js'></script>
<script src='../../bower_components/bootsrap-validator/js/bootstrapvalidator.es_CL.js'></script>
<script src="../../validaciones-js/gestion-montos/validacion-gestion-montos.js"></script>
