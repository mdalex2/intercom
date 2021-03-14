<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Procesar solicitud</h3>
		  <?php 
			require_once("class-consultas-bd.php");
			$consultas_bd = new consultas_bd();
			$id_sol=$_REQUEST['id'];
			$consulta=$consultas_bd->obtener_detalle_solicitud($id_sol);
			$filas   = $consulta->fetchAll(\PDO::FETCH_OBJ);
			//var_dump($filas);
			?>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="div_limpiar">
	    <form id="frm_procesar" name="frm_procesar" enctype="multipart/form-data">
		<div class="row">
  			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						
						<?php 
						$formato_numero = new formato_numero;
						
						foreach ($filas as $fila){ 
							$id_sol_formato=$formato_numero->pon_cero_izq($fila->id_sol,6);
							$monto_sol_formato_esp=$formato_numero->convertir_mysql_esp($fila->monto_soli);
						?>
						<h3><strong><input name="txt_id_sol" id="txt_id_sol" type="hidden" value="<?php echo $id_sol;?>">Id solicitud: </strong> <?php echo $id_sol_formato;?><br tyle="margin: 3px"></h3>
						<strong>Gestor: </strong> <?php echo $_SESSION['desc_clie'];?><br tyle="margin: 3px">
						<h4>Datos del cliente:</h4><hr style="margin: 3px">
						<h3><strong>Cliente: </strong><?php echo $fila->desc_clie;?><br tyle="margin: 3px"></h3>
						<strong>Nº identificación: </strong> <?php echo $fila->num_ide;?><br tyle="margin: 3px">
						<strong>Email: </strong> <?php echo $fila->email;?><br tyle="margin: 3px">
						<strong>Teléfono: </strong> <?php echo $fila->telf;?><br tyle="margin: 3px"><hr style="margin: 3px">
						<h4>Datos bancarios: </h4><hr style="margin: 3px">
						<strong>Banco: </strong> <?php echo $fila->deno_banco;?><br tyle="margin: 3px">
						<strong>Tipo cuenta: </strong> <?php echo $fila->tipo_cuenta;?><br tyle="margin: 3px">
						<strong>Nº cuenta: </strong> <?php echo $fila->num_cuenta;?><br tyle="margin: 3px">
						<h3><strong>Monto: </strong> <?php echo $monto_sol_formato_esp;?><br tyle="margin: 3px"><hr style="margin: 3px"></h3>
						<?php } ?>
					</div>
					<!-- col-md-6 -->
					<div class="col-md-4">
					<div class="form-group">
						<label>Fecha de transferencia</label>
						<div class="input-group date">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
						  <input id="txt_fecha"  name="txt_fecha" type="text" class="form-control pull-right" onChange="$('#frm_procesar').bootstrapValidator('revalidateField', 'txt_fecha');">
						</div>
						<!-- /.input group -->
					  </div>			
					</div>										
					<div class="col-md-4">
					 <div class="form-group">				   
							<label class="control-label" >Número de referencia bancaria</label>
							<input  id="txt_num_ref" name="txt_num_ref" placeholder="" class="form-control" value="" required>
					</div>
					</div>
					<div class="col-md-4">
					<div class="form-group">
						  <label for="arch_soporte">Soporte transferencia</label>
						  <input type="file" id="arch_soporte" name="arch_soporte" accept="image/png, .jpeg, .jpg,application/pdf">

						  <p class="help-block">Archivo JPG,PNG ó PDF de la transferencia.</p>
					</div>				
					</div>
					<div class="col-md-5">              
						<br><button id="btn_enviar" type="submit" class="btn btn-primary "><span class="glyphicon glyphicon-transfer"></span> &nbsp;Procesar solicitud</button>
            		</div>
				<!-- col-md-12 -->
  			</div>
		</div>
		</form>
        </div>
		
        <!-- /.box-body -->
		<div id="procesando" hidden="hidden"><p><img src="../../images/sistema/loading-red.gif"> Procesando...</p></div>		
        <div class="box-footer" id="msg_info">
          	
        </div>
      </div>