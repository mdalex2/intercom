
<?php
require_once('../../funciones/poner_link.php');
$link = new link();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();

try{
?>

<div class="box box-default">
<div class="box-header with-border">
  <h3 class="box-title">Nuevo</h3>

  <div class="box-tools pull-right">
	  <?php $accion_new_encrip=$encriptado->encriptar('buscar','');?>
	  <a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Buscar">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;</a>
	  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
	  &nbsp;<a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>	  
	  &nbsp;<button id="btn_min_res_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  </div>
</div>		
<div class="box-body">
<div class="col-md-12">
<form class="form-horizontal" onSubmit="" action="" method="post"  id="form_nuevo" autocomplete="off">
<fieldset>
<!-- Form Name -->
	<div class="row">
		<div class="col-md-6"> <!-- primer acolumna -->
			<legend>Datos personales!</legend>
				
				
			<div class="form-group" id="div_cedula">
			  <label class="col-md-6 control-label" >Nº identificación</label> 
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			  <input name="txt_cedula" placeholder="Ejm: 12554238" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="">
				</div>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-6 control-label">Nombres y apellidos / Razón social</label>  
			  <div class="col-md-6 inputGroupContainer">
			  <div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<textarea class="form-control" rows="3" name="txt_nom_ape_raz_soc" placeholder="Ejm: Pedro Perez"></textarea>
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
			<legend>Contacto / Ubicación</legend>
			<!-- Text input-->   
			<div class="form-group">
			  <label class="col-md-6 control-label">Nº teléfono</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
					<input name="phone" placeholder="(274)1234567" class="form-control" type="text" value="">
				</div>
			  </div>
			</div>
			<!-- Text input-->   
			<div class="form-group">
			  <label class="col-md-6 control-label">Dirección</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
					<textarea class="form-control" rows="3" name="address" placeholder="Dirección de facturación"></textarea>

				</div>
			  </div>
			</div>
			

			<div class="form-group">
			  <label class="col-md-6 control-label">Ciudad</label>  
				<div class="col-md-6 inputGroupContainer">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
			  <input name="city" placeholder="Ciudad" class="form-control"  type="text" value="">
				</div>
			  </div>
			</div>					
		</div><!-- fin col a mitad xs6 aprte 1 izquierda -->
		

		
		<div class="col-md-6"> <!-- otro div columna de la derecha  -->
		<legend>Cuenta de usuario</legend>
		<div class="form-group">
			<label class="col-md-6 control-label">Contraseña</label>  
			 <div class="col-md-6 inputGroupContainer">
			  	<div class="input-group">
			  		<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
			  		<input type="password" data-minlength="6" class="form-control" id="password" name="password" placeholder="Contraseña" required value="">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-6 control-label">Confirmación</label>  
			 <div class="col-md-6 inputGroupContainer">
			  	<div class="input-group">
			  		<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input type="password" class="form-control" name="confirmPassword"
                		data-fv-identical="true"
               			 data-fv-identical-field="password"
                		data-fv-identical-message="El password y su confirmación no coninciden" placeholder="Confirme la contraseña" value=""/>
				</div>
			</div>
		</div>	
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="col-md-6 control-label">Tipo de cuenta</label>  
			 <div class="col-md-6 inputGroupContainer">
			  	<div class="input-group">
			  		<span class="input-group-addon"><i class="glyphicon glyphicon-wrench"></i></span>
					<select id="cmb_tip_cuenta" name="cmb_tip_cuenta" class="form-control select2" required>
						<option value="">Seleccione...</option>
					<?php
					foreach ($filas as $fila):
					?>
					  <option value="<?php echo $fila->id_cate_tipo_usua;?>"><?php echo $fila->cate_tipo_usua;?></option>
					<?php
					endforeach;
					?>
                </select>
				</div>
			</div>
		</div>  
		<div class="form-group">
			  <label class="col-md-6 control-label">Información adicional</label>  
			  <div class="col-md-6 inputGroupContainer">
			  <div class="input-group">
			  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<textarea class="form-control" rows="5" name="txt_nota" placeholder="Cualquier otra información de importancia"></textarea>
				</div>
			  </div>
			</div>
              <!-- /.form-group -->
			<div class="form-group">
			  <label class="col-md-6 control-label"></label>
			  <div class="col-md-6">
				<!--<button id="btn_enviar" type="submit" class="btn cart" data-loading-text="Verificando..." >Crear usuario <span class="glyphicon glyphicon-user"></span></button>-->
				  <button type="submit" id="btn_guardar" data-loading-text="Verificando..." class="btn btn-primary btn-lg btn-danger" style="display: block; margin-top: 10px;" disabled>Crear usuario &nbsp;<span class="glyphicon glyphicon-user"></span></button>
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
