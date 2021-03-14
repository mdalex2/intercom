<?php 
require_once('../../funciones/poner_link.php');
require_once('../../funciones/func_formato_num.php');
$link = new link();
$formato_num= new formato_numero();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();
try{
	if (!empty($_POST['cmb_tip_bus']) && !empty($_POST['txt_buscar']) && trim($_POST['txt_buscar'])!=''):
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();	
		$tipo_busc=$_POST['cmb_tip_bus'];
		$texto_bus=trim($_POST['txt_buscar']);
		switch ($tipo_busc){
			case '1':
				$campo_busc='solicitudes.desc_clie';
				$param_busc=':desc_clie';
				break;
			case '2':
				$campo_busc='solicitudes.num_ide';
				$param_busc=':num_ide';
				break;
			case '3':
				$campo_busc='solicitudes.email';
				$param_busc=':email';
				break;
				
		}
		$sql="select id_sol,desc_clie,monto_soli,fecha_soli,item_proc,cate_tipo_divisa.tipo_divisa_cort FROM 
		(solicitudes 
		INNER JOIN clie_cuentas_bancos ON clie_cuentas_bancos.id_cuenta = solicitudes.id_cuenta_orig
		INNER JOIN cate_tipo_divisa on cate_tipo_divisa.id_cate_tipo_divisa = clie_cuentas_bancos.id_cate_tipo_divisa) where $campo_busc like $param_busc ORDER BY	$campo_busc ASC ";
		$query1 = $conex->prepare($sql); //preparo la conexion evitar sql injection
		$data = array(
            $param_busc=> '%'.$texto_bus.'%'
        );
		if(!$query1->execute($data)):
			throw new Exception('No se pudo realizar la consulta con la base de datos');
		elseif ($query1->rowCount()>0):
			//OBTENGO LOS REGISTROS Y LOS MUESTRO
	
			$filas   = $query1->fetchAll(\PDO::FETCH_OBJ);
			$tot_reg = $query1->rowCount();
			//var_dump($filas);
	
		
?>
		<script type="text/javascript">
			$("#btn_min_bus").click(); //minimizo el panel de búsqueda
		</script>
		<div class="box-header with-border">
          <h3 class="box-title">Resultado de la búsqueda</h3>

          <div class="box-tools pull-right">
			  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
			  <a href="registro-solicitud.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>
			  <!-- &nbsp;<button type="button" class="btn btn-box-tool btn-default lg" title="Eliminar selección" >&nbsp;<span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;</button>
			  -->
              &nbsp;<button id="btn_min_res_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
            <!-- /.box-header -->
            <div class="box-body">
			<form id="frm_buscar" name="frm_buscar" enctype="multipart/form-data">
			  <div class="table-responsive">
              <table id="tabla_busc" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <!-- <th><input id="chk_todos" name="chk_todos" type="checkbox"></th> -->
                  <th width="0px">Nº solicitud</th>
                  <th>Fecha</th>
                  <th>Cliente</th>
                  <th>Monto solicitado</th>
                  <th>Estatus</th>
				  <th title="Opciones">Opc.</th>
				  <!-- <th title="Opciones">Opc.</th> -->
                </tr>
                </thead>
                <tbody>
				
				<?php				
				
				foreach ($filas as $fila):
					$fecha_soli= new datetime($fila->fecha_soli);
					$estatus=($fila->item_proc==true)? ' Procesado ' : 'En trámite';
					$accion_encrip=$encriptado->encriptar('mostrar','');
					$id_sol=$fila->id_sol;
					//$url_most="registro-solicitud.php?accion=$accion_encrip&id=$id_sol";
					$url_most="#";
				?>
                <tr id="<?php echo $fila->id_sol;?>'">
				  <!-- <td><input id="<?php echo $fila->id_sol;?>" name="chk_id[<?php echo $fila->id_sol;?>]" type="checkbox"></td> -->
                  <td><?php echo $link->poner_link($url_most,$formato_num->pon_cero_izq($fila->id_sol,6)); ?></td>
				  <td><?php echo $fecha_soli->format('d-m-Y h:i A'); ?></td>
                  <td><?php echo $link->poner_link($url_most,$fila->desc_clie); ?></td>
                  <td><?php echo $link->poner_link($url_most,$formato_num->convertir_mysql_esp($fila->monto_soli))." (".$fila->tipo_divisa_cort.")"; ?></td>
                  
                  <td><div id="estatus_<?php echo $fila->id_sol;?>"><?php echo $estatus;?></div></td>
				  <td width="50px">
					  <?php $accion_new_encrip=$encriptado->encriptar('mostrar','');?>
			  		  <a href="registro-solicitud.php?accion=<?php echo $accion_new_encrip."id=".$fila->id_sol;?>" class="btn btn-box-tool btn-default lg" title="Mostrar detalles">&nbsp;<span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>&nbsp;</a>
				  </td>
				  <!--
				  <td>
					  <?php if ($fila->item_proc==false): ?>
					  <div class="btn-group">
						 
						  <a href="registro-solicitud.php?accion=<?php echo $accion_encrip;?>&id=<?php echo $id_sol;?>" type="button" class="btn btn-default">Edit.</a>
						  <a type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						  </a>
						  <ul class="dropdown-menu" role="menu">
							<li><?php echo $link->poner_link($url_most,'Mostrar / editar');?></li>

						  </ul>
					</div>
					<?php endif; ?>

				  </td>
				  -->
                </tr>
				<?php
				endforeach;
				?>
				</tbody>
				<tfoot></tfoot>
              </table>
			 </div>
				</form>
            </div>
            <!-- /.box-body -->
<?php
	else:
?>
	<div class="box-header with-border">
	  <h3 class="box-title">No se encontraron registros para mostrar con los criterios de búsqueda suministrados...</h3>

	  <div class="box-tools pull-right">
		<button id="btn_min_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	  </div>
	</div>
<?php
	endif;
else:
?>
	<div class="box-header with-border">
	  <h3 class="box-title">No se recibieron datos, por lo tanto, no se puede procesar la búsqueda...</h3>

	  <div class="box-tools pull-right">
		<button id="btn_min_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	  </div>
	</div>
<?php	
endif;
	
} catch (PDOException $pdoExcetion) {
		echo $error= 'Error hacer la consulta: '.$pdoExcetion->getMessage();
		return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		return false;		}
?>
<script type="text/javascript">
	//SCRIPT PARA SELECCIONAR TODOS LOS DEMÁS CHECK
	$('#chk_todos').change(function(){
        var checkboxes = $(this).closest('form').find(':checkbox');
        if($(this).prop('checked')) {
          checkboxes.prop('checked', true);
        } else {
          checkboxes.prop('checked', false);
        }
    });	
	
	$('#tabla_busc').DataTable({
    	'language': {
      	'url': "../../bower_components/datatables.net/js/datatables.spanish.lang.json"		
    	},
		'paging'      : true,
	  	'lengthChange': true,
	  	'searching'   : true,
	  	'ordering'    : true,
	  	'info'        : true,
	  	'autoWidth'   : true,
		'responsive'  : true,
		"aoColumnDefs": [
      		{ "bSortable": false, "aTargets": [ 0,4 ] }
    	] 
  	});
	//$('#btn_min_bus').click();
	//window.location.href = '#procesando'; 
</script>