<?php 
require_once('../../funciones/poner_link.php');
require_once('../../funciones/func_formato_num.php');
$link = new link();
$formato_num= new formato_numero();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();
try{
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();	

		$sql="select id_sol,desc_clie,monto_soli,fecha_soli,item_proc,cate_tipo_divisa.tipo_divisa_cort FROM 
		(solicitudes 
		INNER JOIN clie_cuentas_bancos ON clie_cuentas_bancos.id_cuenta = solicitudes.id_cuenta_orig
		INNER JOIN cate_tipo_divisa on cate_tipo_divisa.id_cate_tipo_divisa = clie_cuentas_bancos.id_cate_tipo_divisa) where item_proc=0 order by fecha_soli ASC ";
		$query1 = $conex->prepare($sql); //preparo la conexion evitar sql injection
		
		
		if(!$query1->execute()):
			throw new Exception('No se pudo realizar la consulta con la base de datos');
		elseif ($query1->rowCount()>0):
			//OBTENGO LOS REGISTROS Y LOS MUESTRO
			$filas   = $query1->fetchAll(\PDO::FETCH_OBJ);
			$tot_reg = $query1->rowCount();
			//var_dump($filas);
	
		
?>
		
		<div class="box-header with-border">
          <h3 class="box-title">Tus solicitudes pendientes</h3>

          <div class="box-tools pull-right">
			  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
			  <a href="procesar-solicitud.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>
			  <!-- &nbsp;<button type="button" class="btn btn-box-tool btn-default lg" title="Eliminar selección" >&nbsp;<span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;</button>
			  -->
              &nbsp;<button id="btn_min_res_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
            <!-- /.box-header -->
            <div class="box-body">
			
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
					if ($fila->item_proc==true){
						$estatus=' Procesado ';
						$accion_new_encrip=$encriptado->encriptar('mostrar','');
						$clas_icon_url='glyphicon glyphicon-zoom-in';
						$titulo_opc = 'Mostrar detalles';
					} else {
						$estatus=' En trámite ';
						$accion_new_encrip=$encriptado->encriptar('procesar','');
						$clas_icon_url='glyphicon glyphicon-transfer';
						$titulo_opc = 'Procesar solicitud';
					}
					$url_most="procesar-solicitud.php?accion=$accion_new_encrip&id=".$fila->id_sol;
					$id_sol=$fila->id_sol;
					//$url_most="procesar-solicitud.php?accion=$accion_encrip&id=$id_sol";
					
				?>
                <tr id="<?php echo $fila->id_sol;?>'">
				  <!-- <td><input id="<?php echo $fila->id_sol;?>" name="chk_id[<?php echo $fila->id_sol;?>]" type="checkbox"></td> -->
                  <td><?php echo $link->poner_link($url_most,$formato_num->pon_cero_izq($fila->id_sol,6)); ?></td>
				  <td><?php echo $link->poner_link($url_most,$fecha_soli->format('d-m-Y h:i A'));?></td>
                  <td><?php echo $link->poner_link($url_most,$fila->desc_clie); ?></td>
                  <td><?php echo $link->poner_link($url_most,$formato_num->convertir_mysql_esp($fila->monto_soli))." (".$fila->tipo_divisa_cort.")"; ?></td>
                  
                  <td><div id="estatus_<?php echo $fila->id_sol;?>"><strong><?php echo $estatus;?></strong></div></td>
				  <td width="50px">
					  <a href="<?php echo $url_most;?>" class="btn btn-box-tool btn-default lg" title="<?php echo $titulo_opc;?>">&nbsp;<span class="<?php echo $clas_icon_url;?>" aria-hidden="true"></span>&nbsp;</a>
					  
				  </td>
				  <!--
				  <td>
					  <?php if ($fila->item_proc==false): ?>
					  <div class="btn-group">
						 
						  <a href="procesar-solicitud.php?accion=<?php echo $accion_encrip;?>&id=<?php echo $id_sol;?>" type="button" class="btn btn-default">Edit.</a>
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
				
            </div>
            <!-- /.box-body -->
<?php
	else:
?>
	<div class="box-header with-border">
	  <h3 class="box-title">No se encontraron solicitudes asignadas y pendientes para el operador actual...</h3>

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