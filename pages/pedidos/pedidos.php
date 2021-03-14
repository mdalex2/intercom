<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Hogarísima - Procesar pedidos</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
    <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
<link rel="apple-touch-icon" sizes="57x57" href="../../../images/ico/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../../../images/ico/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../../../images/ico/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../../../images/ico/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../../../images/ico/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../../../images/ico/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../../../images/ico/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../../../images/ico/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../../../images/ico/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="../../../images/ico/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="../../../images/ico/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="../../../images/ico/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="../../../images/ico/favicon-16x16.png">	
</head>
<body class="hold-transition skin-red-light sidebar-mini">
<div class="wrapper">

<?php
	error_reporting(-1);
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/func_formato_num.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/fechas_func.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/clases/class.encriptar.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/controladores/class.carrito.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/controladores/class.usuarios-cliente.php');
	
	$encriptar=new encriptado();
	
	include_once("../vistas-globales/barra-superior.php");
	include_once("../vistas-globales/menu-izquierdo.php");
    //ESTABLECER ZONA HORARIA ALMACENADA EN LA SESION
    date_default_timezone_set($_SESSION['zona_hora']);
	$menu_izquierdo = new menu_izquierdo();
	$menu_izquierdo->abrir_menu();	
	$menu_izquierdo->mostrar_profile();
	$menu_izquierdo->mostrar_buscar_funciones();
	$menu_izquierdo->mostrar_menu_funciones();
	$menu_izquierdo->cerrar_menu();

	
	$conex= new bd_conexion();
	$conex=$conex->bd_conectarse();

	try {
		$sql = 'SELECT pres_vent.id_pres,
		pres_vent.fech_pres,
		clie.desc_clie 
		FROM (pres_vent  
			INNER JOIN clie ON pres_vent.id_clie=clie.id_clie 
		) WHERE pres_rech=0 and pres_remi=0 ORDER BY pres_vent.fech_pres DESC';
		//echo "<br>".$sql;
		$query = $conex->prepare($sql);	
		$query->execute();
		$list = $query->fetchAll();	
	} catch (PDOException $e) {
		echo 'PDOException : '.  $e->getMessage();
	}
  ?>
  <!-- CÓDIGO SEPARADO DEL SIDE BAR (BARRA DE MENU) -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   <?php
	  //OBTENGO GET DE LA ACCION PARA VER QUE VOY A MOSTRAR EN EL AREA DE TRABAJO
	  
	  $accion=(isset($_GET['accion'])) ? $encriptar->desencriptar($_GET['accion'],'')  : '';
	  switch ($accion){
			case 'listado':
			  	//INCLUYO LOS PEDIDOS PENDIENTES
			  	include_once('pedidos-pend.php');
			  	break;
			case 'procesar':
			  	//INCLUYO LOS PEDIDOS PENDIENTES
			  	include_once('pedidos-proc.php');
			  	break;
            case 'enviar':
			  	//INCLUYO LOS PEDIDOS PENDIENTES
			  	include_once('pedidos-enviar.php');
			  	break;              
			  
			default:
			echo '
	  			<div class="alert bg-gray alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                	<h4><i class="icon fa fa-warning"></i> Notificiación!</h4>
                	Acceso restringido, la funcion a la que está intentando acceder no está disponible.
              	</div>';			  	
			  	break;
	  }
	  ?>
  </div>
  <!-- /.content-wrapper -->
 <?php
	include_once('../vistas-globales/main-footer.php');
	?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<?php 
include_once("../vistas-globales/pie-pagina.php");    
?>

</body>
</html>
<!-- para formatear numeros -->

<script src='../../../js/bootstrapvalidator.min.js'></script>
<script src='../../../js/bootstrapvalidator.es_CL.js'></script>
<script  src="../../../js/validaciones/validacion-enviar-pedido.js"></script>
    
<script src="../../../js/numeral.min.js"></script>
<script src="../../../js/numeral.locales.es.min.js"></script>
<!-- fin formatear numeros -->	
<script>
function calcular_totales(){
    numeral.locale('es');
    var i = 0;
    var sub_total_prod = 0;
    $('input[name="txt_id_prod_serv[]"]').each(function() { 
        var id_prod = $(this).val(); 
        //alert(id_prod);
        var cantidad = parseFloat(numero_esp_mysql($('#txt_cant_prod'+id_prod).text()));
        //OPERADOR TERNARIO DE SÍ ES VACÍO
        var precio = ($('#txt_prec_unit'+id_prod).val()=='') ? 0 : parseFloat(numero_esp_mysql($('#txt_prec_unit'+id_prod).val()));
                
        var total_producto = cantidad * precio;
        sub_total_prod = sub_total_prod + total_producto;
        //alert(' cant: ' + cantidad + 'precio: ' + precio);
        $('#txt_sub_tot_prod'+id_prod).text(numeral(total_producto).format('0,0.00'));
		$('#txt_sub_tot_prod_ocu'+id_prod).val(numeral(total_producto).format('0,0.00'))
        i++;
    });
    //CALCULO EL SUBTOTAL INCLUYENDO EL FLETE
    var sub_total = 0;
    var costo_flete = ($('#txt_cost_flet').val()=='') ? 0 : parseFloat(numero_esp_mysql($('#txt_cost_flet').val()));
    //var  costo_flete = parseFloat(numero_esp_mysql($('#txt_cost_flet').val()));
    sub_total = sub_total_prod + costo_flete;
    $('#sub_total').text( numeral(sub_total).format('0,0.00'));
    $('#txt_sub_total_ocu').val( numeral(sub_total).format('0,0.00')); //valor oculto para email al cliente
	
    //CALCULO EL IVA
    var iva = parseFloat(numero_esp_mysql($('#txt_iva').val()));
    var total_iva = (sub_total * iva)/100;
    $('#txt_monto_iva').text( numeral(total_iva).format('0,0.00'));
    $('#txt_monto_iva_ocu').val( numeral(total_iva).format('0,0.00')); //para email al cliente
    
    //CALCULO EL TOTAL GENERAL
    var total_general = sub_total + total_iva;
    $('#txt_tot_gen').text( numeral(total_general).format('0,0.00'));
    $('#txt_tot_gen_ocu').val( numeral(total_general).format('0,0.00'));
}
function numeralEsp(control){
    numeral.locale('es');
    var cost_flet=$(control).val();
    //pongo el valor en la casilla correspondiente
    return numeral(cost_flet).format('0,0.00');
}
    /*
function calcular_total_gen(){
    numeral.locale('es');
    var cost_flet_ant=numero_esp_mysql($('#txt_cost_flet_ant').val());
    //pongo el valor en la casilla correspondiente
    return numeral(cost_flet).format('0,0.00');
}   */ 
function numero_esp_mysql(num_esp){
    var num_eng = num_esp;
    var num_eng = num_eng.replace(/[.]/gi,'');
    num_eng = num_eng.replace(/[,]/gi,'.');
    return num_eng;
}     
    

 
// -------------- OTRAS FUNCIONES
  $(function () {

      
    $('#tabla_full').DataTable({
        "language": {
            "url": "../../bower_components/datatables.net/js/datatables.spanish.lang.json"
        }
    } )
    $('#tabla_simple').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
	
  })
</script>

