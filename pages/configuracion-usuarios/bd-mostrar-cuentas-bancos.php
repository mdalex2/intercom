<?php 
session_start();
require_once('../../funciones/poner_link.php');
require_once('../../funciones/func_formato_num.php');
$formato_num = new formato_numero();
$link = new link();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();
try{
	if (isset($_SESSION['id_clie_abierto']) && !empty($_SESSION['id_clie_abierto'])):
		require_once("../../clases/class.conexion.php");
		$conex= new bd_conexion();
		$conex=$conex->bd_conectarse();	
		$id_clie=$_SESSION['id_clie_abierto'];
		$sql="select 
		clie_cuentas_bancos.id_cuenta,
		clie_cuentas_bancos.id_banco,
		clie_cuentas_bancos.id_cate_tipo_cuenta,
		clie_cuentas_bancos.id_cate_tipo_divisa,
		clie_cuentas_bancos.id_cate_tipo_usua,
		clie_cuentas_bancos.num_cuenta,
		clie_cuentas_bancos.monto_max_tranf,
		clie_cuentas_bancos.item_visi,
		cate_banco.deno_banco,
		cate_tipo_divisa.tipo_divisa,
		cate_tipo_divisa.tipo_divisa_cort,
		cate_tipo_cuenta.tipo_cuenta
		
		FROM (clie_cuentas_bancos 
			INNER JOIN cate_banco ON cate_banco.id_banco=clie_cuentas_bancos.id_banco 
			INNER JOIN cate_tipo_divisa ON cate_tipo_divisa.id_cate_tipo_divisa=clie_cuentas_bancos.id_cate_tipo_divisa 
			INNER JOIN cate_tipo_cuenta ON cate_tipo_cuenta.id_cate_tipo_cuenta=clie_cuentas_bancos.id_cate_tipo_cuenta
		) 
		where id_clie=:id_clie ORDER BY	clie_cuentas_bancos.id_banco,clie_cuentas_bancos.num_cuenta ASC ";
		$query1 = $conex->prepare($sql); //preparo la conexion evitar sql injection
		$data = array(
            'id_clie'=> $id_clie
        );
		
		if(!$query1->execute($data)):
			throw new Exception('No se pudo realizar la consulta con la base de datos');
		elseif ($query1->rowCount()>0):
			//OBTENGO LOS REGISTROS Y LOS MUESTRO
			$filas   = $query1->fetchAll(\PDO::FETCH_OBJ);
			$tot_reg = $query1->rowCount();
			//var_dump($filas);
	
		
		
?>
		
            <!-- /.box-header -->
            <div class="box-body">
			<form id="frm_edit_cuenta" name="frm_edit_cuenta" enctype="multipart/form-data">
			  <div class="table-responsive">
              <table id="tabla_cuentas" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><input id="chk_todos" name="chk_todos" type="checkbox"></th>
                  <th width="0px">Nº cuenta</th>
                  <th>Banco / tip. cuenta</th>
                  <th>Monto máx. <br>Tipo de divisa</th>
                  <th>Operador(es) de la cuenta </th>
                  <th>Estatus</th>
				  <th title="Opciones" width="80px">Opc.</th>
                </tr>
                </thead>
                <tbody>
				
				<?php				
				
				foreach ($filas as $fila):
					$estatus=($fila->item_visi==false)? ' Bloqueada ' : 'Activa';
					$id_cuenta=$fila->id_cuenta;
					//OBTENGO LOS OPERADORES PARA CASA CUENTA
					require_once('class-controlador-bd.php');
					$bd_usuarios = new bd_usuarios;

					$consulta_operadores=$bd_usuarios->obtener_operadores_cuentas_banco($id_clie,$id_cuenta);
					$filas_operadores = $consulta_operadores->fetchAll(\PDO::FETCH_OBJ);
					//var_dump($consulta_operadores);
				?>
                <tr id="<?php echo $fila->id_cuenta;?>'">
				   <td><input id="<?php echo $fila->id_cuenta;?>" name="chk_id[<?php echo $fila->id_cuenta;?>]" type="checkbox"></td>
                  <td>
					  <input id="id_cuenta<?php echo $fila->id_cuenta;?>" name="id_cuenta" type="hidden" value="<?php echo $fila->id_cuenta;?>">
					  <input id="num_cuenta_ocu<?php echo $fila->id_cuenta;?>" name="num_cuenta_ocu" type="hidden" value="<?php echo $fila->num_cuenta;?>">
					  <?php echo $fila->num_cuenta;?>
				</td>
                  <td>
					  
					  <input id="id_cate_tipo_cuenta<?php echo $fila->id_cuenta;?>" name="id_cate_tipo_cuenta" type="hidden" value="<?php echo $fila->id_cate_tipo_cuenta;?>">
					  <input id="id_banco<?php echo $fila->id_cuenta;?>" name="id_banco" type="hidden" value="<?php echo $fila->id_banco;?>">
					  <?php echo $fila->deno_banco." <br>(".$fila->tipo_cuenta.")"; ?>
				  </td>
                  <td>
					  <input id="id_cate_tipo_divisa<?php echo $fila->id_cuenta;?>" name="id_cate_tipo_divisa" type="hidden" value="<?php echo $fila->id_cate_tipo_divisa;?>">
					  <input id="id_cate_tipo_usua<?php echo $fila->id_cuenta;?>" name="id_cate_tipo_usua" type="hidden" value="<?php echo $fila->id_cate_tipo_usua;?>">
					  <?php echo $fila->tipo_divisa ?>
					  <input id="monto_max_tranf<?php echo $fila->id_cuenta;?>" name="monto_max_tranf" type="hidden" value="<?php echo $formato_num->convertir_mysql_esp($fila->monto_max_tranf);?>"><br>
					  <?php echo $formato_num->convertir_mysql_esp($fila->monto_max_tranf)." (".$fila->tipo_divisa_cort.")"; ?>
				  </td>
                  <td>
					  <div id="">
					  <?php
						foreach ($filas_operadores as $fila_oper):
					  ?>
					  	<p id="p<?php echo $fila_oper->id_cuenta."|".$fila_oper->id_oper;?>"><?php echo $fila_oper->desc_clie;?>
						<button id="<?php echo $fila_oper->id_cuenta."|".$fila_oper->id_oper;?>" type="button" class="close" aria-label="Quitar operador" title="Quitar operador" onClick="quitar_operador($(this).attr('id'))"><span aria-hidden="true">&times;</span></button>							
						</p><hr style="margin-top: -7px;margin-bottom: 7px;">
					  	
					  <?php
						endforeach;
					  ?>
					  </div>
					  <div id="quitando_oper" hidden="hidden"><p><img src="../../images/sistema/loading-red.gif"> Procesando...</p></div>
				  </td>
                  <td>
					  <input type="hidden" id="item_visi<?php echo $fila->id_cuenta;?>" value="<?php echo $fila->item_visi;?>" ></input>
					  <div id="estatus_<?php echo $fila->id_cuenta;?>"><?php echo $estatus;?></div>
				  </td>
				  <td>
					  <div class="btn-group">
						  <a href="#" type="button" class="btn btn-default" data-toggle="modal" data-target="#modificar_cuenta" onClick="pasar_datos_form_edit_cuenta('<?php echo $fila->id_cuenta;?>')">Edit.</a>
						  <a type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						  </a>
						  <ul class="dropdown-menu" role="menu">
							  <li><a href="#" data-toggle="modal" data-target="#modificar_cuenta" onClick="pasar_datos_form_edit_cuenta('<?php echo $fila->id_cuenta;?>')">Mostrar / editar</a></li>
							  <li><a href="#" data-toggle="modal" data-target="#asignar_operador" onClick="pasar_datos_form_oper_add('<?php echo $fila->id_cuenta;?>')">Asignar operador</a></li>
							<li class="divider"></li>
							<li><a id="<?php echo $fila->id_cuenta;?>" href="#" onClick="bloquear_cuenta_banco($(this).attr('id'))">Bloquear</a></li>
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
	  <h3 class="box-title">No se encontraron cuentas bancarias registradas...</h3>

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
	
	$('#tabla_cuentas').DataTable({
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

