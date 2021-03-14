<?php
	class menu_izquierdo{
	//ABRE LA BARRA DE SIDEBAR
	function abrir_menu(){
		if (isset($_SESSION['logueado']) && $_SESSION['logueado']==1):?>
			<aside class="main-sidebar">
    		<!-- sidebar: style can be found in sidebar.less -->
    		<section class="sidebar">
		<?php endif;
	}
	
	// FIN ABRIR SIDE BAR IZQUIERDA
	function mostrar_profile(){
		if (isset($_SESSION['logueado']) && $_SESSION['logueado']==1):?>
		<div class="user-panel">
        <div class="pull-left image">
          <img src="../../dist/img/fotos-perfil/default.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p title=""><?php echo $_SESSION['prim_nomb'];?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
        </div>
      </div>
	<?php endif;
	} // fin mostrar profile
	function mostrar_buscar_funciones(){
		if (isset($_SESSION['logueado']) && $_SESSION['logueado']==1):?>
		  <!-- search form -->
		  <!--
		  <form action="#" method="get" class="sidebar-form">
			<div class="input-group">
			  <input type="text" name="q" class="form-control" placeholder="Buscar...">
			  <span class="input-group-btn">
					<button type="submit" name="search" id="search-btn" class="btn btn-flat">
					  <i class="fa fa-search"></i>
					</button>
				  </span>
			</div>
		  </form>
			-->
		  <!-- /.search form -->		
		<?php endif;
	} // fin opcion buscar
        
	function mostrar_menu_funciones(){		
		$encriptar = new encriptado();	
		
		if (isset($_SESSION['logueado']) && $_SESSION['logueado']==1):?>
		  <!-- sidebar menu: : style can be found in sidebar.less -->
		  <ul class="sidebar-menu" data-widget="tree">
			<li class="header">Menú de funciones</li>
			 <!--
			<li>
			  <a href="../../../home/" target="_blank">
				<i class="fa fa-internet-explorer"></i> <span>Ir a Web</span>            
			  </a>
			</li>
			-->
			<?php
			$array_verificar=array('id_cate_tipo_usua'=>'3','id_cate_tipo_usua'=>'4');
			if ((array_search('3', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('4', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
			?>
			<li class="treeview">
			  <a href="#">
				<i class="fa fa-shopping-cart"></i> <span>Solicitudes de clientes</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<!--<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Resumen de pedidos</a></li>-->
				
				<?php
				if (array_search('3', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false):
					$accion=$encriptar->encriptar('nuevo','');
				?>
				<li><a href="../registro-solicitud/registro-solicitud.php?accion=<?php echo $accion;?>"><i class="fa fa-circle-o"></i> Registrar solicitud</a></li>
				<?php
				endif;
				?>
				<?php
				if (array_search('4', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false):
				?>
				<li><a href="../operador-procesar-solicitudes/procesar-solicitud.php"><i class="fa fa-circle-o"></i> Procesar solicitud</a></li>
				<?php
				endif;
				?>
			  </ul>
			</li>
			<?php
			endif;
			?>
		    <?php
			if ((array_search('1', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('5', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('2', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
			?>
			<li class="treeview">
			  <a href="#">
				<i class="fa  fa-th-large"></i> <span>Gestionar</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<!--<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Resumen de pedidos</a></li>-->
				<?php $accion=$encriptar->encriptar('listado','');?>
				<?php
		        if ((array_search('5', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
				?>
				<li><a href="../gestion-cuentas/gestion-montos-cuentas.php"><i class="fa fa-circle-o"></i> Montos bancos</a></li>
				<?php
				endif;
				?>
				<?php
		        if ((array_search('1', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('2', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
				?>
				<li><a href="../configuracion-usuarios/usuarios.php"><i class="fa fa-circle-o"></i> Clientes / usuarios</a></li>
				<?php
				endif;
				?>
			  </ul>
			</li>
			<?php
		    endif;
		    ?>
			<?php
		    if ((array_search('1', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('2', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
			?>
			<li class="treeview">
			  <a href="#">
				<i class="fa fa-exchange"></i> <span>Transferencias</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<!--<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Resumen de pedidos</a></li>-->
				<?php
					if ((array_search('1', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
				?>
				<?php $accion=$encriptar->encriptar('listado','');?>
				<li><a href="../transferencias-mayoristas/transfer-mayoristas.php"><i class="fa fa-circle-o"></i> A mayoristas</a></li>
				<?php
					endif;
				?>
				<?php
		    	if ((array_search('1', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false) || (array_search('2', array_column($_SESSION['privilegios'], 'id_cate_tipo_usua'))!==false)):
			?>
				<li><a href="../transferencias-usuario-final/transferencias-usuario-final.php"><i class="fa fa-circle-o"></i> A usuarios finales</a></li>
				<?php endif; ?>
			  </ul>
			</li>
			<?php
		    endif;
		    ?>
			<li class="treeview">
			  <a href="#">
				<i class="fa  fa-file-text"></i> <span>Reportes</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<!--<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Resumen de pedidos</a></li>-->
				<?php $accion=$encriptar->encriptar('listado','');?>
				<li><a href="#"><i class="fa fa-circle-o"></i> Estado de cuenta</a></li>
				<li><a href="#"><i class="fa fa-circle-o"></i> Multiples criterios</a></li>
			  </ul>
			</li>
			  <!--
			<li class="treeview">
			  <a href="#">
				<i class="fa fa-cogs"></i> <span>Opciones del sistema</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				
				<?php $accion=$encriptar->encriptar('buscar','');?>
				<li><a href="#"><i class="fa fa-group"></i> ...</a></li>
			  </ul>
			</li>
				-->
              <!--
			<li>
			  <a href="../../pages/calendar.html">
				<i class="fa fa-calendar"></i> <span>Mis tareas</span>
				<span class="pull-right-container">
				  <small class="label pull-right bg-red">3</small>
				  <small class="label pull-right bg-blue">17</small>
				</span>
			  </a>
			</li>
			<li>
			  <a href="../../pages/mailbox/mailbox.html">
				<i class="fa fa-envelope"></i> <span>Mensajes</span>
				<span class="pull-right-container">
				  <small class="label pull-right bg-yellow">12</small>
				  <small class="label pull-right bg-green">16</small>
				  <small class="label pull-right bg-red">5</small>
				</span>
			  </a>
			</li>
          -->
		  </ul>		
		<?php endif;
	} //fin mostrar menú funciones
	function cerrar_menu(){
		if (isset($_SESSION['logueado']) && $_SESSION['logueado']==1):?>
			</section>
    		<!-- /.sidebar -->
  			</aside>
		<?php endif;
	}
}
?>
