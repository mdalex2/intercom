<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Intercom - Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../../bower_components/jvectormap/jquery-jvectormap.css">
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
  <link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'c0b798bde97181e71f5e5677cdc78f5fee8f5d61';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
	
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
  <?php
    error_reporting(-1);
	include_once("../vistas-globales/barra-superior.php");
	include_once("../vistas-globales/menu-izquierdo.php");
    //date_default_timezone_set($_SESSION['zona_hora']); // ESTABLEZCO LA ZONA HORARIA DE LA SESSION
	$menu_izquierdo = new menu_izquierdo();
	$menu_izquierdo->abrir_menu();	
	$menu_izquierdo->mostrar_profile();
	$menu_izquierdo->mostrar_buscar_funciones();
	$menu_izquierdo->mostrar_menu_funciones();
	$menu_izquierdo->cerrar_menu();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Intercom
        <small>Version 2018.01</small>
      </h1>
      <ol class="breadcrumb">
        <li id="xxx"><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Pantalla de bienvenida</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
		  <?php 
			
			$usuario_actual=$_SESSION['id_clie'];
			try{
			$conex= new bd_conexion();
			$conex=$conex->bd_conectarse();
			$sql="SELECT clie_operarios.id_cuenta,clie_cuentas_bancos.id_banco,cate_banco.deno_banco,clie_cuentas_bancos.num_cuenta,cate_tipo_cuenta.tipo_cuenta,clie.desc_clie FROM (clie_operarios 
			INNER JOIN clie ON clie.id_clie=clie_operarios.id_clie 
			INNER JOIN clie_cuentas_bancos ON clie_cuentas_bancos.id_cuenta=clie_operarios.id_cuenta 
			INNER JOIN cate_banco on cate_banco.id_banco=clie_cuentas_bancos.id_banco 
			INNER JOIN cate_tipo_cuenta ON cate_tipo_cuenta.id_cate_tipo_cuenta = clie_cuentas_bancos.id_cate_tipo_cuenta
			) 
			where clie_operarios.id_oper=:id_oper ORDER BY id_banco asc";
			$data=array('id_oper'=>$usuario_actual);
			$consulta = $conex->prepare($sql); //evita sql injection
			$resultado = $consulta->execute($data); //se puede o no guardar en variable por si se usa luego
			//$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);				
			$filas   = $consulta->fetchAll(\PDO::FETCH_OBJ);
		} catch (PDOException $PDOException) {
			echo $error= 'Error hacer la consulta: '.$PDOException->getMessage();
			//return $error;
		} catch (Exception $e) {
			echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
			//return $error;		
		} 
			 
					//include_once($_SERVER['DOCUMENT_ROOT'].'/controladores/class.log.php');
					//$log=new log();
					//$pagina=$_SERVER['SERVER_NAME'].'/home/';//pagina inicial del sistema
					//$fech_ini=obtener_primer_dia_mes(date('Y-m-d'),'Y-m-d');
					//$fech_fin=obtener_ultimo_dia_mes(date('Y-m-d'),'Y-m-d');
					//$num_visitas=$log->obtener_num_visitas($pagina,$fech_ini,$fech_fin);
		  		if ($consulta->rowCount()==0){					
				?>
		  		<div class="alert bg-gray alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h4><i class="icon fa fa-warning"></i> Información!</h4>
                	No se encontraron cajas en divisas para el usuario actual.<br>Para efectuar traspasos a su usuario, el administrador o mayorista, deberá asignar un contenedor o caja en la divisa a utilizar.
              	</div>
		 		<?php
				} else {
					$cont=0;
					foreach ($filas as $fila){
						$cont++;
						require_once('../../clases/class-bd-consulta-saldo.php');
						$saldos_cuenta = new saldos_cuenta();
						$saldo_cuenta_actual=$saldos_cuenta->ver_saldo_cuenta($fila->id_cuenta);
						switch ($cont){
							case 1:
								$icono="ion ion-cube";
								$color="bg-aqua";
								break;
							case 2:
								$icono="ion ion-cube";
								$color="bg-red";
								break;
							case 3:
								$icono="ion ion-cube";
								$color="bg-green";
								break;
							case 4:
								$icono="ion ion-cube";
								$color="bg-green";
								break;
							case 5:
								$icono="ion ion-cube";
								$color="bg-orange";
								break;
							default:
								$icono="ion ion-cube";
								$color="bg-grey";
								break;
						}
					?>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon <?php echo $color;?>"><i class="<?php echo $icono;?>"></i></span>

            <div class="info-box-content">
				
              <span class="info-box-text">SALDO <?php echo $fila->deno_banco;?></span>
              <span class="info-box-number" style="font-size: 16px;font-weight: 700;"><?php echo $saldo_cuenta_actual;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
		<?php
					} //end foreach
			} //end else
		?>
        <!-- /.col --><!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
			
              <span class="info-box-text">Nuevos clientes</span>
              <span class="info-box-number"><?php echo "20";?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
		<!-- aqui va el codigo del grafico -->
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">

          <!-- /.box -->
          <div class="row">
            

            
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Solicitudes recientes</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
				
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Nº solicitud</th>
                    <th>Cliente</th>
					<th>Cant. solicitada</th>
                    <th>Estatus</th>
                    <th>Fecha</th>
                  </tr>
                  </thead>
                  <tbody>
				<?php
				$lista_pedidos=array();
				if (count($lista_pedidos)==0):
				?>
				<tr><td colspan="4">No existen solicitudes para mostrar</td></tr>
				<?php
				else:					 
				  foreach ($lista_pedidos as $fila_pedidos):
					  $id_pres=$fila_pedidos->id_pres;
					  $id_pres_formato=$formato_numero->pon_cero_izq($fila_pedidos->id_pres,6);
					  $desc_clie=$fila_pedidos->desc_clie;
					  $fecha_pedido=formato_fecha('CH',$fila_pedidos->fech_pres);
					  $estatus_pedido=($fila_pedidos->pres_remi == 0) ? 'Pendiente' : 'Procesado';
					  $estilo_label_estatus=($fila_pedidos->pres_remi == 0) ? 'danger' : 'success';
					  
					  $accion=$encriptar->encriptar('procesar','');
					  $id_pres_encrip=$encriptar->encriptar($id_pres,'');
					  $link_pedido='../pedidos/pedidos.php?accion='.$accion.'&id_pres='.$id_pres_encrip;
				?>
                  <tr>
                    <td><a href="<?php echo $link_pedido;?>"><?php echo $id_pres_formato;?></a></td>
                    <td><?php echo $desc_clie;?></td>
                    <td><span class="label label-<?php echo $estilo_label_estatus;?>"><?php echo $estatus_pedido;?></span></td>
                    <td><?php echo $fecha_pedido;?></td>
                  </tr> 
				<?php
				  endforeach;
				endif;
				?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Nuevo pedido</a>-->
			  <?php 
				$accion=$encriptar->encriptar('listado','');
				$link_todos_pedidos='../pedidos/pedidos.php?accion='.$accion;
				?>
              <a href="javascript:void(location.href ='<?php echo $link_todos_pedidos;?>')" class="btn btn-sm btn-info btn-flat btn-flat pull-right">Ver todas las solicitudes pendientes</a>
            </div>
            <!-- /.box-footer -->
          </div>			
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          

          <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estado de cuenta</h3>				
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			
              <ul class="products-list product-list-in-box">				
                <li class="item">
                  <div class="product-img">
                    <img src="../../images/sistema/succes_ico.png" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="#" class="product-title" target="_blank">Banesco
                      <span class="label label-warning pull-right" title="Precio">1.500.000.000 $</span></a>
                    <span class="product-description">
                          10-05-2018
                        </span>
                  </div>
                </li>
                <!-- /.item -->
				
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="../../../catalogo/catalogo.php" class="uppercase" target="new">Ver todos los bancos</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php
	include_once('../vistas-globales/main-footer.php');
	include_once('../vistas-globales/side-bar-config.php'); //barra de cambio de color etc
  ?>
  

</div>
<!-- ./wrapper -->

<?php 
	
include_once("../vistas-globales/pie-pagina.php");    
?><!-- jQuery 3 -->

</body>
</html>
