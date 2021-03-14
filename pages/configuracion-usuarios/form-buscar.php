<!-- Main content -->
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Buscar usuarios</h3>

          <div class="box-tools pull-right">
            <button id="btn_min_bus" type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
		  <form action="usuarios.php" id="frm_buscar" name="frm_buscar" method="post" autocomplete="off">
            <div class="col-md-3">
              <div class="form-group">
                <label>Tipo de búsqueda</label>
                <select id="cmb_tip_bus" name="cmb_tip_bus" class="form-control select2" style="width: 100%;">
                  <option selected="selected" value="1">Descripción del cliente / usuario</option>
                  <option value="2">Nº de cédula / Rif</option>
                  <option value="3">Login</option>
                </select>
              </div>
			
              <!-- /.form-group -->
              
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label id="lbl_tipo_busc">Descripción del cliente</label>
                <input type="text" name="txt_buscar" id="txt_buscar" value="" placeholder="Nombres / apellidos o razón social" class="form-control">
              </div>
              <!-- /.form-group -->
              
            </div>
			  <div class="col-md-3">
			  
              <div class="form-group">
				  <button id="btn_buscar" type="submit" class="btn btn-app">
                	<i class="fa fa-search-plus"></i> Buscar
              	  </button>
				  <?php 
				  	$accion_new_encrip=$encriptar->encriptar('nuevo','');
				  ?>
				  <a href="usuarios.php?accion=<?php echo $accion_new_encrip;?>" class="btn btn-app">
                	<i class="fa fa-user-plus"></i> Nuevo usuario
              	  </a>
                
              </div>
              <!-- /.form-group -->
              
            </div>
            <!-- /.col -->
			</form>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        
      </div>
      <!-- /.box -->
	  <div id="msg" hidden="hidden"></div>
	  <div id="procesando" hidden="hidden"><p><img src="../../images/sistema/loading-red.gif"> Procesando...</p></div>
	  	
      <div class="box" id="div_res_bus">
      	
            <!-- /.box-header -->            
      </div>
          <!-- /.box -->
      <!-- /.row -->

    <!-- /.content -->