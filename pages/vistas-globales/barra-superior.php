<?php 
    error_reporting(-1);	
	require_once($_SERVER['DOCUMENT_ROOT']."/funciones/aplica_config_global.php");

	require_once($_SERVER['DOCUMENT_ROOT']."/clases/verifica_expira_sesion.php");
	$verificar_sesion= new verificar_sesion();
	$verificar_sesion->verifica_expira_sesion(); //verifico si debo cerrra sesion por inactividad
	$verificar_sesion->actualizar_ultimo_acceso();
	//require_once($_SERVER['DOCUMENT_ROOT'].'/controladores/class.carrito.php'); //dejarla arriba porque tiene session_start() para que funcionen los demas modulos que usas session_start
	//require_once($_SERVER['DOCUMENT_ROOT'].'/controladores/class.depa-prod.php');
	require_once($_SERVER['DOCUMENT_ROOT']."/clases/class-bd-consulta-saldo.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/clases/class.encriptar.php");
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/fechas_func.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/func_formato_num.php');
	$formato_numero=new formato_numero();
	$encriptar=new encriptado();//inicio la clase de encruiptar
	//if (!isset($_SESSION['logueado']) || empty($_SESSION['logueado'])){
		//require_once($_SERVER['DOCUMENT_ROOT'].'/funciones/redireccionar.php');
		//redireccionar_js('../login/login.html',0);
		//exit();
  	//}
    
	$primer_nombre=$_SESSION['prim_nomb'];
    $desc_clie=$_SESSION['desc_clie'];
    $fech_regi= $_SESSION['fech_regi'] ;
   	$miembro_desde=ucfirst(formato_fecha('MCA',$fech_regi));

?>

<header class="main-header">

    <!-- Logo -->
    <a href="../home/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" title="Hogarísima - Menú">Men.</span>
      <!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><img src="../../images/sistema/logo.png" width="175px"></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
        
          <li class="dropdown messages-menu">
			 <?php 
			  	
			  	$saldos_solicitudes = new saldos_cuenta();
			  	$total_solicitudes=$saldos_solicitudes->obtener_total_solicitudes($_SESSION['id_clie']);
			  	
			  	
			  ?>
			  
            <a  href="#" class="dropdown-toggle" data-toggle="dropdown" onClick="notificar();">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success" ><?php echo $total_solicitudes;?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo $total_solicitudes;?> solicitudes pendientes</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
				  <?php 
					foreach ($pedidos_pendientes as $fila_pend):
						$accion=$encriptar->encriptar('procesar','');
						$id_pres_encrip=$encriptar->encriptar($fila_pend->id_pres,'');
						$link_pedido='../pedidos/pedidos.php?accion='.$accion.'&id_pres='.$id_pres_encrip;
					?>
                  <li><!-- start message -->
                    <a href="<?php echo $link_pedido;?>">
                      <div class="pull-left">
                        <img src="../../../images/home/profile-default.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        <?php $desc_clie_ped_pend=$fila_pend->desc_clie;echo substr($desc_clie_ped_pend,0,17).'...'?>
                        <small><i class="fa fa-clock-o"></i>
							<?php
								//date_default_timezone_set($_SESSION['zona_hora']);
								echo $tiempo_transcurrido=calcula_tiempo_transcurrido($fila_pend->fech_pres,date('Y-m-d h:i:sa'));
						  		
								
							?>
							
						</small>
                      </h4>
                      <p><?php echo formato_fecha('MDH',$fila_pend->fech_pres); ?></p>
                    </a>
                  </li>
					
					<?php
					 endforeach;
					?>
                  <!-- end message -->                                                                        
                </ul>
              </li>
				
              <li class="footer"><a href="../operador-procesar-solicitudes/procesar-solicitud.php">Ver todos los pedidos pendientes</a></li>
            </ul>
          </li>        
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/fotos-perfil/default.jpg" width="160" height="160" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $primer_nombre;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/fotos-perfil/default.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo substr($desc_clie,0,30).'...';?>
                  <small>Miembro desde <?php echo $miembro_desde;?></small>
                </p>
              </li>
              <!-- Menu Body -->
            <!--
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              <!--</li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!-- <a href="#" class="btn btn-default btn-flat">Mi perfil</a> -->
                </div>
                <div class="pull-right">
                  <a id="btn_cerrar_sess" href="../login/cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar sessión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->