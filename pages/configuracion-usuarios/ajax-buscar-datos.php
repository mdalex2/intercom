<?php 
require_once('../../funciones/poner_link.php');
$link = new link();
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
				$campo_busc='clie.desc_clie';
				$param_busc=':desc_clie';
				break;
			case '2':
				$campo_busc='clie.id_clie';
				$param_busc=':id_clie';
				break;
			case '3':
				$campo_busc='usua_sist.login';
				$param_busc=':login';
				break;
				
		}
		$sql="select id_usua_sist,login,usua_sist.fech_regi,bloqueado,clie.id_clie,clie.desc_clie FROM 
		(usua_sist INNER JOIN clie on clie.id_clie = usua_sist.id_usua_sist) where $campo_busc like $param_busc ORDER BY	$campo_busc ASC ";
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
			  <a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>
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
                  <th width="0px">Nº identificación</th>
                  <th>Cliente / usuario</th>
                  <th>Login</th>
                  <th>Fecha registro</th>
                  <th>Estatus</th>
				  <th title="Opciones">Opc.</th>
                </tr>
                </thead>
                <tbody>
				
				<?php				
				
				foreach ($filas as $fila):
					$fech_reg= new datetime($fila->fech_regi);
					$estatus=($fila->bloqueado==true)? ' Bloqueado ' : 'Activo';
					$accion_encrip=$encriptado->encriptar('mostrar','');
					$id_clie=$fila->id_usua_sist;
					$url_most="usuarios.php?accion=$accion_encrip&id=$id_clie";
				?>
                <tr id="<?php echo $fila->id_usua_sist;?>'">
				  <!-- <td><input id="<?php echo $fila->id_usua_sist;?>" name="chk_id[<?php echo $fila->id_usua_sist;?>]" type="checkbox"></td> -->
                  <td><?php echo $link->poner_link($url_most,$fila->id_clie); ?></td>
                  <td><?php echo $link->poner_link($url_most,$fila->desc_clie); ?></td>
                  <td><?php echo $link->poner_link($url_most,$fila->login); ?></td>
                  <td><?php echo $fech_reg->format('d-m-Y'); ?></td>
                  <td><div id="estatus_<?php echo $fila->id_usua_sist;?>"><?php echo $estatus;?></div></td>
				  <td>
					  <div class="btn-group">
						  <a href="usuarios.php?accion=<?php echo $accion_encrip;?>&id=<?php echo $id_clie;?>" type="button" class="btn btn-default">Edit.</a>
						  <a type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						  </a>
						  <ul class="dropdown-menu" role="menu">
							<li><?php echo $link->poner_link($url_most,'Mostrar / editar');?></li>
							<li class="divider"></li>
							<li><a id="<?php echo $id_clie;?>" href="#" onClick="bloquear_clie($(this).attr('id'))">Bloquear</a></li>
						  </ul>
					</div>
				  </td>
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
      		{ "bSortable": false, "aTargets": [ 0,6 ] }
    	] 
  	});
	//$('#btn_min_bus').click();
	//window.location.href = '#procesando'; 
</script>