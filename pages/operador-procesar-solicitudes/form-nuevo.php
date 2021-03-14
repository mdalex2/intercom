<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Procesar solicitud</h3>
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
        <div class="box-body" id="div_limpiar">
			<form id="frm_gestion_cuenta" name="frm_gestion_cuenta" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-7">
			<div class="form-group">
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
                <label>Cuenta origen</label>
                <select id="cmb_cuenta" name="cmb_cuenta" class="form-control select2" style="width: 100%;" required>
				  <?php
				    $texto_combo=($consulta->rowCount()>0)? "Seleccione una cuenta" : "No tiene cuentas asignadas";
					?>
                  <option selected="selected" value=""><?php echo $texto_combo;?></option>
				  <?php 
					foreach ($filas as $fila):
						require_once('../../clases/class-bd-consulta-saldo.php');
						$saldos_cuenta = new saldos_cuenta();
						$saldo_cuenta_actual=$saldos_cuenta->ver_saldo_cuenta($fila->id_cuenta);
				  ?>
                  	<option value="<?php echo $fila->id_cuenta;?>"><?php echo $fila->deno_banco." - ".$fila->num_cuenta ." (".$fila->tipo_cuenta.") => ".$saldo_cuenta_actual;?></option>
				  <?php
					endforeach;
				  ?>
                </select>
				</div></div>              
              <!-- /.form-group -->
			<div class="col-md-4">
              <div class="form-group">
					<label class="control-label" >Monto a transferir</label> 
					<input id="txt_monto" name="txt_monto" placeholder="" class="form-control" style="text-transform:uppercase;" onChange="$(this).val(numeralEsp($(this)));" value="" required>
			  </div>
		    </div>
			<div class="col-md-3">
			<div class="form-group">
                <label>Fecha de transferencia</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input id="txt_fecha"  name="txt_fecha" type="text" class="form-control pull-right" onChange="$('#frm_gestion_cuenta').bootstrapValidator('revalidateField', 'txt_fecha');">
                </div>
                <!-- /.input group -->
              </div>			
			</div>			  
			<div class="col-md-4">
			 <div class="form-group">				   
					<label class="control-label" >Número de referencia bancaria</label>
					<input  id="txt_num_ref" name="txt_num_ref" placeholder="" class="form-control" style="text-transform:uppercase; width: 100%;"  value="" required>
			</div>
			</div>
			<div class="col-md-4">
			<div class="form-group">
                  <label for="arch_soporte">Soporte transferencia</label>
                  <input type="file" id="arch_soporte" name="arch_soporte" accept="image/png, .jpeg, .jpg,application/pdf">

                  <p class="help-block">Archivo JPG,PNG ó PDF de la transferencia.</p>
            </div>				
			</div>
			<div class="col-md-7">
			<div class="form-group">
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
			where clie_operarios.id_cate_tipo_usua=2  ORDER BY id_banco asc";
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
			<div class="inputGroupContainer">
                <label>Cuenta destino</label>
                <select id="cmb_cuenta_dest" name="cmb_cuenta_dest" class="form-control select2" style="width: 100%;" required>
				  <?php
				    $texto_combo=($consulta->rowCount()>0)? "Seleccione una cuenta" : "No existen cuentas mayoristas";
					?>
                  <option selected="selected" value=""><?php echo $texto_combo;?></option>
				  <?php 
					foreach ($filas as $fila):
						//require_once('../../clases/class-bd-consulta-saldo.php');
						//$saldos_cuenta = new saldos_cuenta();
						//$saldo_cuenta_actual=$saldos_cuenta->ver_saldo_cuenta($fila->id_cuenta);
				  ?>
                  	<option value="<?php echo $fila->id_cuenta;?>"><?php echo $fila->desc_clie." / ".$fila->deno_banco." - ".$fila->num_cuenta ." (".$fila->tipo_cuenta.")";?></option>
				  <?php
					endforeach;
				  ?>
                </select>
				</div>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-5">              
				<br><button id="btn_enviar" type="submit" class="btn btn-primary "><span class="glyphicon glyphicon-open"></span> &nbsp;Transferir</button>
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