
<?php
require_once('../../funciones/poner_link.php');
$link = new link();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();
require_once('class-bd-consultas-gen.php');
$consultas_bd = new consultas_bd();
try{
?>

<div class="box box-default">
<div class="box-header with-border">
  <h3 class="box-title">Saldo en caja / Tipo de divisa</h3>

  <div class="box-tools pull-right">
	  <?php $accion_new_encrip=$encriptado->encriptar('buscar','');?>
	  <a href="registro-solicitud.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Buscar">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;</a>
	  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
	  &nbsp;<a href="registro-solicitud.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>	  
	  &nbsp;<button id="btn_min_res_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  </div>
</div>		
<div class="box-body" id="div_nuevo">
<div class="col-md-12" >
<form class="form-horizontal" onSubmit="" action="" method="post" id="form_nuevo">
<fieldset>
<!-- Form Name -->
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
			

			//var_dump($filas);
			?>
	<?php
	$texto_combo=($consulta->rowCount()>0)? "Seleccione el saldo a usar" : "No tiene caja de saldos asignadas";
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="table-responsive">
				<table class="table"><tr>
		<?php 
		foreach ($filas as $fila):
			require_once('../../clases/class-bd-consulta-saldo.php');
			$saldos_cuenta = new saldos_cuenta();
			$saldo_cuenta_actual=$saldos_cuenta->ver_saldo_cuenta($fila->id_cuenta);
			
		?>
		
        
            <!-- <span class="info-box-icon bg-aqua"><i class="ion ion-card"></i></span> -->
			<td style="padding-left: 10px;padding-right: 10px;padding-bottom: 0px;padding-top: 0px;background-color: #3c8dbc;">
				  <div class="radio">
                    <h4><label style="background: #FFFFFF;border-radius: 8px;font-weight: 900; margin-top: -5px; padding-top: 5px;padding-bottom: 5px;padding-left: 25px;padding-right: 5px;">
                      <input type="radio" name="id_cuenta_orig" value="<?php echo $fila->id_cuenta;?>">
                    <?php echo $saldo_cuenta_actual;?></label></h4>
                  </div>
			</td>
		<?php
		endforeach;
		?>
		</tr></table>
		</div>
		</div>
		</div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
      </div>
	<br>
      <!-- /.row -->
	<div class="row" style="margin-top: -20px;">
		<div class="col-md-6"> <!-- primer acolumna -->
			<legend>Datos personales del cliente</legend>
			<div class="form-group" id="div_cedula">
			  <label class="col-md-6 control-label" >Nº identificación</label> 
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			  <input name="num_ide" placeholder="Ejm: 12554238" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="">
				</div>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-6 control-label">Nombres y apellidos / Razón social</label>  
			  <div class="col-md-6 inputGroupContainer">
			  <div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<textarea class="form-control" rows="3" name="desc_clie" placeholder="Ejm: Pedro Perez"></textarea>
				</div>
			  </div>
			</div>
			<!-- Text input-->
			<!-- Text input-->
			  <div class="form-group">
			  <label class="col-md-6 control-label">E-Mail</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
			  		<input name="email" placeholder="Correo electrónico" class="form-control"  type="text" value="">
				</div>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-6 control-label">Nº teléfono</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
					<input name="telf" placeholder="(274)1234567" class="form-control" type="text" value="">
				</div>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-6 control-label">Notas / observaciones</label>  
			  <div class="col-md-6 inputGroupContainer">
			  <div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
				<textarea class="form-control" rows="5" name="notas" placeholder="Cualquier otra información de importancia"></textarea>
				</div>
			  </div>
			</div>			
					
		</div><!-- fin col a mitad xs6 aprte 1 izquierda -->
		
		<div class="col-md-6"> <!-- otro div columna de la derecha  -->
		
		<legend>Efectuar transferencia en:</legend>
			<!-- Text input-->   			
			<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$consultas_bd->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
		
				//OBTENGO LOS DATOS DE LOS BANCOS
				$consulta_bancos=$consultas_bd->obtener_bd_bancos();
				$filas_bancos=$consulta_bancos->fetchAll(\PDO::FETCH_OBJ);
		
				//OBTENGO LOS DATOS DE LOS TIPO DE CUENTA DE BANCOS
				$consulta_tipo_cuenta_bancos=$consultas_bd->obtener_tipo_cuenta_bancos();
				$filas_tipo_cuenta_bancos=$consulta_tipo_cuenta_bancos->fetchAll(\PDO::FETCH_OBJ);
		
				//OBTENGO LOS DATOS DE LOS TIPO DE DIVISAS (CATEGORIAS9)
				$consulta_tipo_divisa=$consultas_bd->obtener_tipo_divisa();
				$filas_tipo_divisa=$consulta_tipo_divisa->fetchAll(\PDO::FETCH_OBJ);
				
				
		
			?>
			  <label class="col-md-6 control-label">Banco</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
					<select id="cmb_banco" name="cmb_banco" class="form-control select2 " required style="width: 100%">
						<option value="">Seleccione...</option>
					<?php
					foreach ($filas_bancos as $fila):
					?>
					  <option value="<?php echo $fila->id_banco;?>"><?php echo $fila->deno_banco;?></option>
					<?php
					endforeach;
					?>
                </select>
				</div>
			  </div>
			</div>
			<div class="form-group">			
			  <label class="col-md-6 control-label">Tipo de cuenta</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
					<select id="cmb_tip_cuenta" name="cmb_tip_cuenta" class="form-control select2" required style="width: 100%" >
						<option value="">Seleccione...</option>
					<?php
					foreach ($filas_tipo_cuenta_bancos as $fila):
					?>
					  <option value="<?php echo $fila->id_cate_tipo_cuenta;?>"><?php echo $fila->tipo_cuenta;?></option>
					<?php
					endforeach;
					?>
                	</select>
				</div>
			  </div>
			</div>
			<!-- Text input-->   
			<div class="form-group">
			  <label class="col-md-6 control-label">Número de cuenta</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
			  <input name="num_cuenta" placeholder="" class="form-control"  type="text" value="">
				</div>
			  </div>
			</div>	
			<div class="form-group">
			  <label class="col-md-6 control-label">Monto solicitado</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
			  <input name="monto_soli" placeholder="0,00" class="form-control"  type="text" value="" onChange="$(this).val(numeralEsp($(this)));">
				</div>
			  </div>
			</div>
					
			
              <!-- /.form-group -->
			<div class="form-group">
			  <label class="col-md-6 control-label"></label>
			  <div class="col-md-6">
				<!--<button id="btn_enviar" type="submit" class="btn cart" data-loading-text="Verificando..." >Crear usuario <span class="glyphicon glyphicon-user"></span></button>-->
				  <button type="submit" id="btn_guardar" data-loading-text="Verificando..." class="btn btn-primary btn-lg btn-danger" style="display: block; margin-top: 10px;" disabled>Registrar solicitud &nbsp;<span class="glyphicon glyphicon-plus-sign"></span></button>
			  </div>
			</div>
			<div id="msg" hidden="hidden"></div>
	  		<div id="procesando" hidden="hidden"><p><img src="../../images/sistema/loading-red.gif"> Procesando...</p></div>
      		<div class="box" id="div_res_gua" hidden="hidden">
				
		</div><!-- fin col a mitad xs6 parte 2 derecha -->
	</div>
</fieldset>
</form>
          
    </div>
        <!--/.col (right) -->
</div> <!--/ box-body -->
</div>
<?php
} catch (PDOException $pdoExcetion) {
		echo $error= 'Error hacer la consulta: '.$pdoExcetion->getMessage();
		return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		return false;		}
unset($_SESSION['id_clie_abierto']); //elimino el expediente abierto
?>
