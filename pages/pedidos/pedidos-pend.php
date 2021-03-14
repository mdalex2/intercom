 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pedidos
        <small>(Pendientes)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../pages/home/"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Procesar pedidos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
			<?PHP 
			if (count($list)==0):
			?>
			 <div class="alert bg-gray alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                No se encontraron solicitudes de nuevos pedidos...
              </div>
			<?php 			  
			else:			
			?>
			  
            <div class="box-header">
              <h3 class="box-title">Pedidos pendientes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="tabla_full" class="table table-hover table-bordered table-striped">
                <thead>
                <tr>
                  <th style="min-width: 45px;">Fecha</th>
                  <th>Código pedido</th>
                  <th>Cliente</th>
                  <th title="Nº días de retraso">Nº días </th>
                  <th width="5px;"></th>                  
                </tr>
                </thead>
                <tbody>
				<?php
				$formato_num=new formato_numero();
				foreach ($list as $fila):
					$id_pres=$fila['id_pres'];					
					$id_pres_encriptado=$encriptar->encriptar($id_pres,''); // dejo vacío para que use clave automática
					$id_pres_formato= $formato_num->pon_cero_izq($id_pres,6);
					$fecha_presup=date('d-m-Y',strtotime($fila['fech_pres']));
					$cliente=$fila['desc_clie'];
					$dias_solicitud=(diferenciaDias($fila['fech_pres'],date('d-m-Y'))<=0)? 0 : diferenciaDias($fila['fech_pres'],date('d-m-Y'));
					$accion=$encriptar->encriptar('procesar','');
					$url_detalle_pedido='pedidos.php?accion='.$accion.'&id_pres='.$id_pres_encriptado;
					include_once('../../../funciones/poner_link.php');
					$link=new link();
					
				?>
                <tr>
                  <td><?php echo  $link->poner_link($url_detalle_pedido,$fecha_presup);?></td>
                  <td><?php echo  $link->poner_link($url_detalle_pedido,$id_pres_formato);?></td>
                  <td><?php echo  $link->poner_link($url_detalle_pedido,$cliente)?></td>
                  <td><p style="<?php echo ($dias_solicitud>3)? 'color: #C83C47;font-weight: bold;' : '';?>"><?php echo $dias_solicitud;?></p></td>
					<td><a href="<?php echo $url_detalle_pedido?>" title="Procesar"> <p style="font-size: 18px;<?php echo ($dias_solicitud>3)? 'color: #C83C47;font-weight: bold;' : '';?>"><i class="fa fa-wpforms" aria-hidden="true"></i></p></a></td>
                </tr>
				<?php
				endforeach;
				?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Fecha</th>
                  <th>Código pedido</th>
                  <th>Cliente</th>
                  <th>Nº días</th>
                  <th></th>                  
                </tr>
                </tfoot>
              </table>
            </div>
			<?PHP 
			endif;
			?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		<!-- /div .col-xs-8 -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->