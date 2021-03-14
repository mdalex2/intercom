
<?php
require_once('../../funciones/poner_link.php');
$link = new link();
require_once('../../clases/class.encriptar.php');
$encriptado= new encriptado();

try{
?>

<div class="box box-default">
<div class="box-header with-border">
  <h3 class="box-title">Editar</h3>

  <div class="box-tools pull-right">
	  <?php $accion_new_encrip=$encriptado->encriptar('buscar','');?>
	  <a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Buscar">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;</a>
	  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
	  &nbsp;<a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-box-tool btn-default lg" title="Nuevo">&nbsp;<span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;</a>	  
	  &nbsp;<button id="btn_min_res_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  </div>
</div>		
<div class="box-body">
<?php
	require_once('bd-consultas-gen.php');
	//verifico que el id se halla pasado
	$id_clie=(isset($_GET['id']))? $_GET['id'] : '';
	$_SESSION['id_clie_abierto']=$id_clie;
	$consultas_bd = new consultas();
	$result_bd=$consultas_bd->obtener_datos_clie($id_clie);
	$num_reg=$result_bd->rowCount();
	$filas=$result_bd->fetchAll(\PDO::FETCH_OBJ);
	
	//var_dump($filas);
	if ($num_reg>0){
		foreach($filas as $valor){ 
   			$id_clie_ant=$valor->id_clie;
			$ciudad = $valor->ciudad;
			$desc_clie=$valor->desc_clie;
			$dire_fisc=$valor->dire_fisc;
			$telf=$valor->telf;
			$email=$valor->email;
			$fech_regi=new DateTime($valor->fech_regi);
			$nota_obse=$valor->nota_obse;
			$item_visi=$valor->item_visi;
		}
?>
<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab"><strong>Datos personales</strong></a></li>
              <li><a href="#tab_2" data-toggle="tab"><strong>Cuentas bancarias</strong></a></li>
              <!--<li><a href="#tab_3" data-toggle="tab"><strong>Mi perfil</strong></a></li>-->
				<!--
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  Dropdown <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                  <li role="presentation" class="divider"></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                </ul>
              </li>
				-->
              <!--<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>-->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

			<form class="form-horizontal" onSubmit="" action="" method="post"  id="form_editar" autocomplete="off">
			<fieldset>
			<!-- Form Name -->
				<div class="row">
					<div class="col-md-6"> <!-- primer acolumna -->
						<div class="form-group" id="div_cedula">
							<input type="hidden" id="txt_cedula_ant" name="txt_cedula_ant"  value="<?php echo $id_clie_ant;?>">
						  <label class="col-md-6 control-label" >Nº identificación</label> 
							<div class="col-md-6 inputGroupContainer">
							<div class="input-group">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						  <input name="txt_cedula" id="txt_cedula" placeholder="Ejm: 12554238" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $id_clie_ant;?>">
							</div>
						  </div>
						</div>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-6 control-label">Nombres y apellidos / Razón social</label>  
						  <div class="col-md-6 inputGroupContainer">
						  <div class="input-group">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<textarea class="form-control" rows="3" name="txt_nom_ape_raz_soc" placeholder="Ejm: Pedro Perez"><?php echo $desc_clie;?></textarea>
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
								<input name="email" placeholder="Correo electrónico" class="form-control"  type="text" value="<?php  echo $email;?>">
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
								<input name="phone" placeholder="(274)1234567" class="form-control" type="text" value="<?php echo $telf;?>">
							</div>
						  </div>
						</div>
						<!-- Text input-->   
						<div class="form-group">
						  <label class="col-md-6 control-label">Dirección</label>  
							<div class="col-md-6 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
								<textarea class="form-control" rows="3" name="address" placeholder="Dirección de facturación"><?php echo $dire_fisc;?></textarea>

							</div>
						  </div>
						</div>


						<div class="form-group">
						  <label class="col-md-6 control-label">Ciudad</label>  
							<div class="col-md-6 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
						  <input name="city" placeholder="Ciudad" class="form-control"  type="text" value="<?php echo $ciudad;?>">
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
								<input type="password" data-minlength="6" class="form-control" id="password" name="password" placeholder="Contraseña" required value="" disabled>
								
							</div>
							<div class="checkbox">
                    			<label>
                      			<input id="chk_mod_pwd" name="chk_mod_pwd" type="checkbox">
                      			Modificar clave
                    			</label>
							</div>							
						</div>
					</div>
					
					<div class="form-group">
						<?php
							$conex= new bd_conexion();
							$conex=$conex->bd_conectarse();
							$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
							$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
							//obtendo el tipo de usuario para seleccionar el opcion automaticamente
							$consulta_tipo_usuario=$consultas_bd->obtener_tipo_usua($id_clie);
							$filas_tipo_usuario=$consulta_tipo_usuario->fetchAll(\PDO::FETCH_OBJ);
							
							foreach ($filas_tipo_usuario as $fila_tipo_usua){
								$id_categ_usu_actual=$fila_tipo_usua->id_cate_tipo_usua;
							}
						?>
						<label class="col-md-6 control-label">Tipo</label>  
						 <div class="col-md-6 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-wrench"></i></span>
								<select id="cmb_tip_cuenta_usu" name="cmb_tip_cuenta_usu" class="form-control select2" required>
									<option value="">Seleccione...</option>
								<?php
								foreach ($filas as $fila):
									$selected=($id_categ_usu_actual==$fila->id_cate_tipo_usua)? " selected " : "";
								?>
								  <option value="<?php echo $fila->id_cate_tipo_usua;?>" <?php echo $selected;?></optio><?php echo $fila->cate_tipo_usua;?></option>
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
							<div class="checkbox">
                    			<label>
                      			<input id="chk_bloq_usu" name="chk_bloq_usu" type="checkbox">
                      			Bloquear usuario
                    			</label>
							</div>
						  </div>
						</div>
						  <!-- /.form-group -->
						<div class="form-group">
						  <label class="col-md-6 control-label"></label>
						  <div class="col-md-6">
							<!--<button id="btn_enviar" type="submit" class="btn cart" data-loading-text="Verificando..." >Crear usuario <span class="glyphicon glyphicon-user"></span></button>-->
							  <button type="submit" id="btn_guardar" data-loading-text="Verificando..." class="btn btn-primary btn-lg btn-danger" style="display: block; margin-top: 10px;">Guardar &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span></button>
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
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

        <div class="row">
        
			
		<div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Cuentas registradas</h3>
  <div class="box-tools pull-right">
	 <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Large modal</button>-->
	  <?php $accion_new_encrip=$encriptado->encriptar('nueva-cuenta','');?>
	  
	  <?php $accion_new_encrip=$encriptado->encriptar('nuevo','');?>
	  &nbsp;<a href="#" class="btn btn-primary lg" title="Nuevo"  data-toggle="modal" data-target="#exampleModal">&nbsp;<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>&nbsp;Agregar cuenta</a>	  
	  
  </div>
<form id="form_reg_cuenta_banco" name="form_reg_cuenta_banco" enctype="multipart/form-data" autocomplete="off">
<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Registrar cuenta bancaria</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<!-- FORMULARIO MODAL REGISTRO CUENTA -->
	  
      <div class="modal-body">
		  <div class="row">		  
		  <div class="col-md-6">		  
			  <div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
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
			<label class="control-label">Banco</label>  
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
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
			?>
			<label class="control-label">Tipo de Cuenta</label>  
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
		  </div> <!-- /div col-md6 -->
		  
		   <div class="col-md-6">
			  <div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Tipo de cliente</label>  
					<select id="cmb_tip_cliente" name="cmb_tip_cliente" class="form-control select2" required style="width: 100%" onChange="$('#form_reg_cuenta_banco').bootstrapValidator('revalidateField', 'cmb_operador_cuenta_add');">
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
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Tipo de divisa</label>  
					<select id="cmb_tip_divisa" name="cmb_tip_divisa" class="form-control select2" required style="width: 100%">
						<option value="">Seleccione...</option>
					<?php
					foreach ($filas_tipo_divisa as $fila):
					?>
					  <option value="<?php echo $fila->id_cate_tipo_divisa;?>"><?php echo $fila->tipo_divisa." (".$fila->tipo_divisa_cort.")";?></option>
					<?php
					endforeach;
					?>
                </select>
		</div>
		  </div> <!-- /div col-md6 de la derecha -->
	  	</div>
		
        <div class="form-group" id="div_cuenta">
		  <label class="control-label" >Nº cuenta</label> 
			<div class="inputGroupContainer">
			<input name="num_cuenta" placeholder="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="" >
			</div>
		</div>
		 
		<div class="form-group" id="div_cuenta">
		  <label class="control-label" >Monto máximo de transferencias diarias</label> 
			<div class="inputGroupContainer">
			<input name="monto_max" placeholder="" class="form-control" style="text-transform:uppercase; width: 50%;" onkeyup="javascript:this.value=this.value.toUpperCase();" onChange="$(this).val(numeralEsp($(this)));" value="" required>
		  </div>
		</div>
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Operador de la cuenta</label> 
					<div id="img_carg_oper" hidden="hidden"><img src="../../images/sistema/loading-red.gif"></div>
					<select id="cmb_operador_cuenta_add" name="cmb_operador_cuenta_add" class="form-control select2" required style="width: 100%">
						<option value="">Seleccione...</option>			
                </select>
		</div>  
      	</div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>&nbsp;
        <button id="btn_reg_cuenta_banco" type="submit" class="btn btn-primary">Registrar y asociar</button>
      </div>
	  

    </div>
		
  </div>
	  
</div>				
</form>  
<!-- MODIFICAR CUENTA BANCARIA -->
<form id="form_modif_cuenta_banco" name="form_modif_cuenta_banco" enctype="multipart/form-data" autocomplete="off">
<div class="modal fade" id="modificar_cuenta" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Modificar cuenta bancaria</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<!-- FORMULARIO MODAL REGISTRO CUENTA -->
	  
      <div class="modal-body">
		  <div class="row">
		  <div class="col-md-6">
			  <div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
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

			<label class="control-label">Banco</label>  
					<select id="cmb_banco_edit" name="cmb_banco_edit" class="form-control select2 " required style="width: 100%">
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
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
			?>
			<label class="control-label">Tipo de Cuenta</label>  
					<select id="cmb_tip_cuenta_edit" name="cmb_tip_cuenta_edit" class="form-control select2" required style="width: 100%">
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
		  </div> <!-- /div col-md6 -->
		  
		   <div class="col-md-6">
			  <div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Tipo de cliente</label>  
					<select id="cmb_tip_cliente_edit" name="cmb_tip_cliente_edit" class="form-control select2" required style="width: 100%">
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
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Tipo de divisa</label>  
					<select id="cmb_tip_divisa_edit" name="cmb_tip_divisa_edit" class="form-control select2" required style="width: 100%">
						<option value="">Seleccione...</option>
					<?php
					foreach ($filas_tipo_divisa as $fila):
					?>
					  <option value="<?php echo $fila->id_cate_tipo_divisa;?>"><?php echo $fila->tipo_divisa." (".$fila->tipo_divisa_cort.")";?></option>
					<?php
					endforeach;
					?>
                </select>
		</div>
		  </div> <!-- /div col-md6 de la derecha -->
	  	</div>
        <div class="form-group" id="div_cuenta">
			<input type="hidden" id="id_cuenta_ant" name="id_cuenta_ant" value="">
		  <label class="control-label" >Nº cuenta</label> 
			<div class="inputGroupContainer">
			<input id="num_cuenta_edit" name="num_cuenta_edit" placeholder="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="" required >
			</div>
		</div> 
		<div class="form-group" id="div_cuenta">
		  <label class="control-label" >Monto máximo de transferencias diarias</label> 
			<div class="inputGroupContainer">
			<input id="monto_max_edit" name="monto_max_edit" placeholder="" class="form-control" style="text-transform:uppercase; width: 50%;" onkeyup="javascript:this.value=this.value.toUpperCase();" onChange="$(this).val(numeralEsp($(this)));" value="" required>
		  </div>
		</div>
		
		<div class="checkbox">
        	<label>
            <input id="chk_bloq_cuenta_banc" name="chk_bloq_cuenta_banc" type="checkbox">
            	Cuenta bloqueada
            </label>
		</div>
      	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>&nbsp;
        <button id="btn_modif_cuenta_banco" type="submit" class="btn btn-primary">Modificar cuenta</button>
      </div>
	  

    </div>
		
  </div>
	  
</div>				
</form> 
<!-- FIN MODIFICAR CUENTA BANCARIA -->
<!-- ASIGNAR OPERADOR -->
<form id="form_asig_oper" name="form_asig_oper" enctype="multipart/form-data" autocomplete="off">
<div class="modal fade" id="asignar_operador" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Asignar operador a la cuenta</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<!-- FORMULARIO MODAL REGISTRO CUENTA -->
	  
      <div class="modal-body">
		  <input type="hidden" id="id_cuenta_ant_oper" name="id_cuenta_ant_oper" value="">
		  <div class="row">		  		  		  
		  <div class="col-md-6">
			  <div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Tipo de cliente</label>  
					<select id="cmb_tip_cliente_modal" name="cmb_tip_cliente_modal" class="form-control select2" required style="width: 100%" onChange="$('#form_asig_oper').bootstrapValidator('revalidateField', 'cmb_operador_cuenta_modal');">
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
		</div> <!-- /div col-md6 de la derecha -->
	  	</div>
		<div class="form-group" id="div_cuenta">
		  <label class="control-label" >Monto máximo de transferencias diarias</label> 
			<div class="inputGroupContainer">
			<input id="monto_max_modal" name="monto_max_modal" placeholder="" class="form-control" style="text-transform:uppercase; width: 50%;" onkeyup="javascript:this.value=this.value.toUpperCase();" onChange="$(this).val(numeralEsp($(this)));" value="" required>
		  </div>
		</div>
		<div class="form-group">
			<?php
				$conex= new bd_conexion();
				$conex=$conex->bd_conectarse();
				$consulta=$bd_usuarios->obtener_categ_tipo_usuarios($conex);
				$filas = $consulta->fetchAll(\PDO::FETCH_OBJ);
				
			?>
			<label class="control-label">Operador de la cuenta</label> 
					<div id="img_carg_oper_modal" hidden="hidden"><img src="../../images/sistema/loading-red.gif"></div>
					<select id="cmb_operador_cuenta_modal" name="cmb_operador_cuenta_modal" class="form-control select2" required style="width: 100%">
						<option value="">Seleccione...</option>			
                </select>
		</div>  
      	</div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>&nbsp;
        <button id="btn_add_operador" type="submit" class="btn btn-primary">Registrar y asociar</button>
      </div>
	  

    </div>
		
  </div>
	  
</div>				
</form> 				
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding" id="div_cuentas_bancos">
              <!-- NO ELIMINAR AQUI SE MUESTRA EN AJAX EL RESULTADO DE LA CONSULTA -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>			
      </div>
          <!-- /.row -->
        
        <!-- /.box-body -->
        
                    
              </div>
              <!-- /.tab-pane -->
				  <!--
              <div class="tab-pane" id="tab_3">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                It has survived not only five centuries, but also the leap into electronic typesetting,
                remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                like Aldus PageMaker including versions of Lorem Ipsum.
              </div>
-->
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->        
      </div>
      <!-- /.row -->
	<?php
	}
	// fin de si se enconto el registro
	else {
		echo "<h3>No se encontró el registro; por favor intente de nuevo la búsqueda</h3>";
	}
	?>
</div> <!--/ box-body -->
</div>
<?php
} catch (PDOException $pdoExcetion) {
		echo $error= 'Error hacer la consulta: '.$pdoExcetion->getMessage();
		return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		return false;		}
?>
