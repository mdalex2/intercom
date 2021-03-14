<?php
	$remitente=$_SESSION['desc_clie'];
	$telf_remitente=$_SESSION['num_telf'];
	$email_remitente=$_SESSION['login'];
	include_once("../../../controladores/class.carrito.php"); //quitar despues de usar ya esta arriba y probar que funcione
	include_once("../../../controladores/class.usuarios-cliente.php");
	$carrito = new carrito();
	$clientes_usuarios=new clientes_usuarios();

	$id_pres=$encriptar->desencriptar($_REQUEST['id_pres'],'');
	$formato_num=new formato_numero();
	$id_pres_formato= $formato_num->pon_cero_izq($id_pres,6);
	$info_pedido=$carrito->obtener_info_pedido($id_pres);
    $cost_flet=0;
	//obtendo los datos del pedidos
	foreach($info_pedido as $fila_pedido) {
        $id_clie=$fila_pedido->id_clie;
		$fech_pres=$fila_pedido->fech_pres;	
		$porc_desc_impu=$fila_pedido->porc_desc_impu;
		$cost_flet=$fila_pedido->cost_flet;
        $nota_obse=$fila_pedido->nota_obse;
		break; //salgo del bucle
    }
    $filas_iva=$carrito->obtener_porc_desc_iva();
    foreach ($filas_iva as $iva){
        $porc_desc_impu=$iva->porc_mont_impu;
    }
    
	$fech_hoy = date("d-m-Y");
	//Incrementando 2 dias
	$fech_pago = date("d-m-Y",strtotime($fech_hoy."+ 1 days"));
	$deta_pedido=$carrito->obtener_detalle_pedido($id_pres);
	$info_cliente=$clientes_usuarios->obtener_datos_cliente($id_clie);
	//asigno a las variables los datos de la consulta
	foreach ($info_cliente as $fila_cliente){
		$num_id_clie=$fila_cliente->num_iden;
		$desc_clie=$fila_cliente->desc_clie;
		$dire_clie=$fila_cliente->dire_fisc;
		$email_clie=$fila_cliente->login;
		break;
	}
	
?>
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Procesar pedido
        <small>#<?php echo $id_pres_formato;?></small>
      </h1>
      <ol class="breadcrumb">        
		<li><a href="../home/" ><i class="fa fa-dashboard"></i> Inicio</a></li>
		  <li><a href="#" onclick="javascript:history.go(-1)"><i class="fa fa-arrow-circle-left"></i> Regresar</a></li>
        <li class="active">Procesar pedidos</li>
      </ol>
    </section>   
    <!-- Main content -->
    <section class="invoice">
    <?php
        //encripto la acción de guardar el precio del pedido y enviar al correo del cliente
        $accion=$encriptar->encriptar('enviar','');
        ?>
    <form id="frm_pedidos" action="pedidos.php?accion=<?php echo $accion;?>" enctype="multipart/form-data" method="post" autocomplete="off">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-id-card-o"></i> Cliente:<input type="hidden" name="txt_desc_clie" value="<?php echo $desc_clie;?>"> <?php echo $desc_clie;?>
            <small class="pull-right">Fecha de pedido:<input name="txt_fech_pres" type="hidden" value="<?php echo $fech_pres;?>"> <?php echo date("d-m-Y",strtotime($fech_pres));?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <b>ID Cliente:</b>
            <input type="hidden" name="txt_id_cliente" value="<?php echo $id_clie;?>">
            <input type="hidden" name="txt_num_ide" value="<?php echo $num_id_clie;?>">            
            <?php echo $num_id_clie;?>
          <address>
            <?php echo $dire_clie;?><br>           
            <strong>Email:</strong><input name="txt_email_clie" type="hidden" value="<?php echo $email_clie;?>"> <?php echo $email_clie;?><br>
			<strong>Teléfono:</strong> <?php ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Gestor del pedido
            <address>
            <strong><input type="hidden" name="txt_gestor" value="<?php echo $remitente;?>"> <?php echo $remitente;?></strong><br>            
            Email: <?php echo $email_remitente;?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>
              <input name="txt_id_pres" type="hidden" value="<?php echo $id_pres;?>">Pedido #<?php echo $id_pres_formato;?></b><br>
          <br>                        
          <!--<b>Fecha de pago:</b> <spam id="txt_fech_pago"> <?php echo $fech_pago;?> </spam>   -->
            <!-- date picker -->
            <div class="form-group">
                <label>Fecha de pago:</label>
                <div class="input-group input-append date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>                  
                  <input type="text" class="form-control" id="txt_fech_pago" name="txt_fech_pago" value='' required onChange="$('#frm_pedidos').bootstrapValidator('revalidateField', 'txt_fech_pago');">
                </div>
                <!-- /.input group -->
              </div>
            
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Producto</th>
              <th>Código</th>
              <th>Descripción</th>
              <th style="text-align: center;">Cant.</th>
              <th title="Precio Unitario" style="text-align: center;">Precio Unit.</th>
              <th>Sub-total</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$sub_total_general=0;
			$sub_total_prod=0;
			foreach ($deta_pedido as $fila_prod):
				$desc_prod_serv=$fila_prod->desc_prod_serv;
				$deta_prod_serv=($fila_prod->deta_prod_serv!='')? $fila_prod->deta_prod_serv : 'No posee detalles';
				$id_prod_serv=$fila_prod->id_prod_serv;
				$id_prod_serv_formato= $formato_num->pon_cero_izq($id_prod_serv,6);
				$cant_prod=$fila_prod->cant_prod;
				$prec_unit=$fila_prod->prec_unit;
				$prec_unit_formato=($prec_unit>0)? $formato_num->convertir_mysql_esp($prec_unit) : '';
				$sub_total_prod=$cant_prod*$prec_unit;				
				$sub_total_prod_formato=$formato_num->convertir_mysql_esp($sub_total_prod);	
                $sub_total_general=$sub_total_general+$sub_total_prod;
			?>
            <tr>
              <td><input type="hidden" id="txt_id_prod_serv<?php echo $id_prod_serv?>" name="txt_id_prod_serv[]" value="<?php echo $id_prod_serv?>"><input type="hidden" name="txt_desc_prod_serv[]" value="<?php echo $desc_prod_serv;?>"><?php echo $desc_prod_serv;?></td>
              <td><?php echo $id_prod_serv_formato;?></td>
              <td><?php echo $deta_prod_serv;?></td>
              <td><p id="txt_cant_prod<?php echo $id_prod_serv?>"><input type="hidden" name="txt_cant_prod[]" value="<?php echo $cant_prod;?>"><?php echo $cant_prod;?></p></td>
				
              <td>
                  
                  <div class="form-group">
                    <input id="txt_prec_unit<?php echo $id_prod_serv?>" name="txt_prec_unit[]" type="text"  value="<?php echo $prec_unit_formato;?>" onChange="$(this).val(numeralEsp($(this)));calcular_totales();" style="border: 1px solid #BDBDBD;text-align: right;" class="form-control">
                  </div>

			  </td>
           	  <td style="text-align: right">	
				 <input type="hidden" id="txt_sub_tot_prod_ocu<?php echo $id_prod_serv?>" name="txt_sub_tot_prod_ocu<?php echo $id_prod_serv?>" value="<?php echo $sub_total_prod_formato;?>" class="form-control">
			    <p id="txt_sub_tot_prod<?php echo $id_prod_serv?>"> <?php echo $sub_total_prod_formato;?></p>
			  </td>
            </tr>
			<?php
			endforeach;
            //Cáculo de totales generales fuera del foreach
            //para evitar repetir o duplicar cantidades cuando hay mas de 1 producto
                $sub_total_general=$sub_total_general+$cost_flet; //le sumo el costo del flete a parte
                $total_porc_desc_iva=($sub_total_general*$porc_desc_impu)/100;
				$total_general=$sub_total_general+$total_porc_desc_iva;
			?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
			<!--
          <p class="lead">Payment Methods:</p>
          <img src="../../dist/img/credit/visa.png" alt="Visa">
          <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
          <img src="../../dist/img/credit/american-express.png" alt="American Express">
          <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
            dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
          </p>
		-->
			<label for="txt_obs">Observaciones:</label><br>
			<textarea name="txt_obs" class="form-control" style="min-width: 100%" rows="8"><?php echo $nota_obse?></textarea>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Resumen del costo</p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Costo envío Bs:</th>
                <td>
                    <div class="form-group">
                      <input id="txt_cost_flet" name="txt_cost_flet" type="text" class="form-control" value="<?php echo ($cost_flet>0) ? $formato_num->convertir_mysql_esp($cost_flet) : '';?>" onKeyPress="return (event.keyCode <= 13 || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode==44 || event.keyCode==46);"  onChange="$(this).val(numeralEsp($(this)));calcular_totales();">
                    </div>
                </td>
              </tr>
              <tr>
                <th style="width:50%"><h3>Sub-total Bs:</h3></th>
                <td><h3><input type="hidden" id="txt_sub_total_ocu" name="txt_sub_total_ocu" value="<?php echo $formato_num->convertir_mysql_esp($sub_total_general);?>"><p id="sub_total"><?php echo $formato_num->convertir_mysql_esp($sub_total_general);?></p></h3>
                </td>
              </tr>
              <tr>
                <th>
                    <div class="form-group">
                    IVA <input type="text" id="txt_iva" name="txt_iva" style="width: 40px; text-align: center;" value="<?php echo $formato_num->convertir_mysql_esp($porc_desc_impu);?>" onKeyPress="return (event.keyCode <= 13 || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode==44);"  onChange="$(this).val(numeralEsp($(this)));calcular_totales()" > %
                    </div>
                </th>
                <td><input type="hidden" id="txt_monto_iva_ocu" name="txt_monto_iva_ocu" value="<?php echo $formato_num->convertir_mysql_esp($total_porc_desc_iva);?>"><p id="txt_monto_iva"> <?php echo $formato_num->convertir_mysql_esp($total_porc_desc_iva);?></p></td>
              </tr>
              <tr>
                <th><h3>Total Bs:</h3></th>
                <td><h3><input type="hidden" id="txt_tot_gen_ocu" name="txt_tot_gen_ocu" value="<?php echo $formato_num->convertir_mysql_esp($total_general);?>"> <p id="txt_tot_gen"><?php echo $formato_num->convertir_mysql_esp($total_general);?></p></h3></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
            <!--
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
            -->
          <button type="submit"  class="btn bg-red pull-right"><i class="fa fa-paper-plane"></i> Enviar presupuesto
          </button>
            
          <!--
			<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          	</button>
			-->
            
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Validación de datos</h4>
              </div>
              <div class="modal-body">
                <p>Por favor, escribir el precio unitario para cada producto...&hellip;</p>
              </div>
              <div class="modal-footer">                
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>            
        </div>
      </div>
	</form>
    </section>
<!--
	<div class="pad margin no-print">
      <div class="callout bg-gray" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Nota:</h4>
        Esta página se ha diseñado para imprimir. Haga clic en el botón imprimir en la parte inferior de la factura para probar.
      </div>
    </div>
-->
    <!-- /.content -->
<div class="clearfix"></div>

</div>