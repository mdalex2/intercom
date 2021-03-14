<?PHP
    //INICIALIZO LAS RUTAS DE IMAGENES DEL CORREO PARA QUE SE MUESTREN CORRECTAMENTE
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
	$servidor=$_SERVER['SERVER_NAME'];
	$ruta_img_p = $protocol.$servidor.'/images/catalogo/p/';
	$ruta_logo=$protocol.$servidor.'/images/home/logo-email.png';
    //echo var_dump($_POST);

//INICIO GUARDADO DE LOS PRECIOS DEL PEDIDO Y REITO CORREO AL CLIENTE
try { 
	include_once("../../../controladores/class.usuarios-cliente.php");
	include_once("../../../clases/class.conexion.php");
	$conex= new bd_conexion();
    $formato_num=new formato_numero(); //para formatear numeros
	$conex=$conex->bd_conectarse();
	$conex->beginTransaction(); // inicio la transacción
    
	//PRIMERO INSERTO EL CLIENTE EN LA BD
	$error_transac=false; //para saber si hago o no un roolback
    
	//OBTENGO LOS DATOS DEL POST
	$id_pres = $_POST['txt_id_pres'];
	$id_pres_ceros = $formato_num-> pon_cero_izq($_POST['txt_id_pres'],6);
    $id_clie = $_POST['txt_id_cliente'];
	$desc_clie= $_POST['txt_desc_clie'];
	$email_cliente=$_POST['txt_email_clie'];
    $id_gene_por =$_SESSION['id_clie']; //EL USUARIO ACTUAL ES EL QUE GENERA EL PRESUPUESTO
	$gestor=$_POST['txt_gestor'];
    $pres_remi = 1;
    $fech_pres=$_POST['txt_fech_pres'];
    $fecha_actual_mysql=fecha_actual('mysql');
	$fecha_actual_esp=fecha_actual('normal');
	$fecha_pago=$_POST['txt_fech_pago'];
    $dias_vige = diferenciaDias($fech_pres, $fecha_actual_mysql);
    $porc_desc = $formato_num->convertir_esp_mysql($_POST['txt_iva']);
    $cost_flet= $formato_num->convertir_esp_mysql($_POST['txt_cost_flet']);
    $cost_flet_formato=($cost_flet>0)? $_POST['txt_cost_flet'].' Bs.' : 'Gratis!!!';
    $sub_total = $_POST['txt_sub_total_ocu'];
    $monto_iva = $_POST['txt_monto_iva_ocu'];
    $tot_gen = $_POST['txt_tot_gen_ocu'];
    $nota_obse= $_POST['txt_obs'];
    
    // OBTENGO LOS DATOS DE LOS ARRAY DE PRODUCTOS (ID, CANT Y PRECIO)
    $array_id_poductos = $_POST['txt_id_prod_serv'];
    $array_precios = $_POST['txt_prec_unit'];
    $array_cant=$_POST['txt_cant_prod'];
    $array_desc_prod=$_POST['txt_desc_prod_serv'];
    //esto lo esta haciendo 
    //$array_sub_total_prod = $_POST['txt_sub_total_prod'];
	//INICIO LA PRIMERA TRANSACCION PARA GUARDAR DATOS DEL CLIENTE
	$sql="UPDATE pres_vent set 
    id_gene_por = :id_gene_por,
    pres_remi = :pres_remi,
    fech_proc = :fech_proc,
    dias_vige = :dias_vige,
    porc_desc_impu = :porc_desc,
    cost_flet = :cost_flet,
    nota_obse = :nota_obse 
    WHERE id_pres = :id_pres";
	$query1 = $conex->prepare($sql); //preparo la conexion evitar sql injection
	//paso los parametros a la consulta para evitar sql injection
    $data = array(
        'id_pres'=> $id_pres,
        'id_gene_por'=> $id_gene_por,
        'pres_remi'=> $pres_remi,
        'fech_proc'=>$fecha_actual_mysql,
        'dias_vige'=>$dias_vige,
        'porc_desc'=>$porc_desc,
        'cost_flet'=>$cost_flet,
        'nota_obse'=>$nota_obse
    );
		if(!$query1->execute($data)) {
			throw new Exception('No se puede actualizar el presupuesto');
			$error_transac=true;
		}
    
    
	//ACTUALIZO LOS PRECIOS DEL PRODUCTO PARA EL PRESUPUESTO ACTUAL Y PARA EL PRECIO PREDETERMINADO
    //ASÍ EN FUTUROS PRESUPUESTOS APARECERÁ EL ULTIMO PRECIO DADO
    $fila_tabla_productos="";
	foreach ($array_id_poductos as $fila =>$valor){
		$sql="update pres_vent_deta SET 
            prec_prod = :prec_prod 
            WHERE id_pres = :id_pres AND id_prod_serv = :id_prod_serv";
		$query2 = $conex->prepare($sql); //preparo la conexion evitar sql injection
                
		//DECLARO LAS VARIABLES QUE USARÉ
        $prec_prod=$formato_num->convertir_esp_mysql($array_precios[$fila]);
        $id_prod_serv=$valor; //en el array el valor es el id del producto
        $cant_prod=$formato_num->convertir_mysql_esp($array_cant[$fila]);
        $desc_prod=$array_desc_prod[$fila];
        $sub_tot_prod=$_POST['txt_sub_tot_prod_ocu'.$id_prod_serv];
        
        //ACTUALIZO LOS PRECIOS DEL PRODUCTO EN LA TABLA DE PRODUCTO
        //PARA FUTUROS PRESUPUESTOS TENGAN EL ULTIMO PRECIO DE VENTA
        

		//paso los parametros a la consulta para evitar sql injection
        
		$data = array(
            'prec_prod'=> $prec_prod,
            'id_pres'=>$id_pres,
            'id_prod_serv'=> $id_prod_serv
        );
		if(!$query2->execute($data)) {
			throw new Exception('Guardado de los precios del presupuesto ha fallado');
			$error_transac=true;
		}
		
        // ASIGNO LA VARIABLE PARA CADA FILA ESTO ES PARA EL CORREO A ENVIAR
        $fila_tabla_productos.="<tr>
        <td class='innerpadding borderbottom'>
          <table width='115' align='left' border='0' cellpadding='0' cellspacing='0'>  
            <tbody><tr>
              <td height='115' style='padding: 0 20px 20px 0;'>
                <img class='fix' src='".$ruta_img_p.$id_prod_serv.".jpg' width='115' height='115' border='0' alt=''>
              </td>
            </tr>
          </tbody></table>
          <!--[if (gte mso 9)|(IE)]>
            <table width='380' align='left' cellpadding='0' cellspacing='0' border='0'>
              <tr>
                <td>
          <![endif]-->
          <table class='col380' align='left' border='0' cellpadding='0' cellspacing='0' style='width: 100%; max-width: 380px;'>  
            <tbody><tr>
              <td>
                <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                  <tbody>
                  <tr>
                    <td class='bodycopy'>
                        <strong>Producto:</strong> ".$desc_prod."<br>
                        <strong>ID:</strong> ".$formato_num->pon_cero_izq($id_prod_serv,6)."<br>
                        <strong>Precio unitario: ".$formato_num->convertir_mysql_esp($prec_prod)." Bs.</strong><br>
                        <strong>Cantidad:</strong> ".$cant_prod." / <strong>Total:</strong> ".$sub_tot_prod." Bs.
                    </td>                
                  </tr>
                  <tr>
                    <td style='padding: 20px 0 0 0;'>
                      <table class='buttonwrapper' bgcolor='#C83C47' border='0' cellspacing='0' cellpadding='0'>
                        <tbody><tr>
                          <td class='button' height='45'>
                            <a href='".$protocol.$servidor."/catalogo/detalle-producto.php?id_prod=$id_prod_serv' target='_blank'>Detalle del producto!</a>
                          </td>
                        </tr>
                      </tbody></table>
                    </td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
          </tbody></table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>";
        
        //ACTUALIZO LOS PRECIOS DEL PRODUCTO EN LA TABLA DE PRODUCTO
        //PARA FUTUROS PRESUPUESTOS TENGAN EL ULTIMO PRECIO DE VENTA
        $sql="update prod_serv SET 
            prec_unit = :prec_unit 
            WHERE id_prod_serv = :id_prod_serv";
		$query3 = $conex->prepare($sql); //preparo la conexion evitar sql injection
		//paso los parametros a la consulta para evitar sql injection
		$data = array(
            'prec_unit'=> $prec_prod,
            'id_prod_serv'=>$id_prod_serv
        );
		if(!$query3->execute($data)) {
			throw new Exception('Guardado de los precios del producto ha fallado');
			$error_transac=true;
		}        
	} // FIN FOREACH
    
	//ejecuto la transaccion si no hubo error
	

    $mensaje_html="
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  <title>Hogarísima C.A.</title>
  <style type='text/css'>
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .img_circular {
    border-radius: 50%;
  }
  .content {width: 100%; max-width: 600px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}
  .textoright {text-align: right;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #C83C47; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  .h11 {color: #153643; font-family: sans-serif;}
.h21 {color: #153643; font-family: sans-serif;}
  </style>
</head>

<body yahoo='' bgcolor='#f6f8f1' style=''>
<table width='100%' bgcolor='#f6f8f1' border='0' cellpadding='0' cellspacing='0'>
<tbody><tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width='600' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
      <tbody><tr>
        <td bgcolor='#C83C47' class='header'>
          <table width='70' align='left' border='0' cellpadding='0' cellspacing='0'>  
            <tbody>
            <tr>
              <td height='70' style='padding: 0 20px 20px 0;'>
                <img class='fix' src='".$ruta_logo."'  width='300px' border='0' alt=''>
              </td>
            </tr>
          </tbody></table>
          <!--[if (gte mso 9)|(IE)]>
            <table width='425' align='left' cellpadding='0' cellspacing='0' border='0'>
              <tr>
                <td>
          <![endif]-->
          
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class='innerpadding borderbottom'>
          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tbody><tr>
              <td class='h2'>
                Hola, $desc_clie!
              </td>
            </tr>
            <tr>
              <td class='bodycopy'>
                Nos complace en informarle que la <strong>solícitud de presupuesto en línea #$id_pres_ceros</strong> de fecha <strong>".date('d-m-Y',strtotime($fech_pres))."</strong>, fue atendida por nuestro gestor<strong> $gestor</strong>, en fecha ".date('d-m-Y')."; a contínuación, se detalla su pedido: </td>
                
            </tr>
          </tbody></table>
        </td>
      </tr>
      <!-- PONGO LA FILA CON TABLA PARA CADA PRODUCTO -->
      $fila_tabla_productos
      <!-- FIN DE LA FILA DE LA TABLA DE PRODUCTOS -->
      <tr>
          <td class='bodycopy textoright borderbottom innerpadding'>
            <strong>Costo envío:</strong> $cost_flet_formato<br>
            <strong>Sub-Total:</strong> $sub_total Bs.<br>
            <strong>IVA (12%):</strong> $monto_iva Bs.<br>
            <strong>Total General: $tot_gen Bs.</strong><br>
        </td>
      </tr>
          
      <tr style='display:none;'>
                 
        <td class='innerpadding borderbottom'>
          <img class='fix' src='".$protocol.$servidor."/images/home/wide-email.png' width='100%' border='0' alt=''>
        </td>
      </tr>          
      <tr>
        <td class='innerpadding bodycopy'>
          <strong>Nota:</strong> La validez de este presupuesto es de ($dias_vige) días continuos, a partir de la presente fecha; gracias por su solicitud...
        </td>
      </tr>
	  <tr>
        <td class='innerpadding bodycopy'>
          <p>Esta es una cuenta de correo no monitoreada, no responda a éste mensaje, para más informacion comuníquese con nosotros a través del correo electrónico: <a href='mailto:info@hogarisima.com'>info@hogarisima.com</a>.</p>
        </td>
      </tr>
      <tr>
        <td class='footer' bgcolor='#44525f'>
          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tbody><tr>
              <td align='center' class='footercopy'>
                &copy; Hogarísima C.A. / J-40639876-4<br>
                Av. Los Próceres, CC Plaza Los Próceres, Galpón Nº 7, Mérida- Venezuela. <br>
                (0274) 266.41.30 <br>
                <br> 
                <span class='hide'>Suscríbete a nuestras redes sociales</span>
              </td>
            </tr>
            <tr>
              <td align='center' style='padding: 20px 0 0 0;'>
                <table border='0' cellspacing='0' cellpadding='0'>
                  <tbody><tr>
                    <td width='37' style='text-align: center; padding: 0 10px 0 10px;'>
                      <a href='http://facebook.com/hogarisimacf'>
                        <img src='".$protocol.$servidor."/images/home/facebook-email.png' width='37' height='37' alt='Facebook' border='0'>
                      </a>
                    </td>
                    <td width='37' style='text-align: center; padding: 0 10px 0 10px;'>
                      <a href='http://twitter.com/hogarisima1'>
                        <img src='".$protocol.$servidor."/images/home/twitter-email.png' width='37' height='37' alt='Twitter' border='0'>
                      </a>
                    </td>
                    <td width='37' style='text-align: center; padding: 0 10px 0 10px;'>
                      <a href='http://instagram.com/hogarisima'>
                        <img src='".$protocol.$servidor."/images/home/instagram-email.png' width='37' height='37' alt='Instagram' border='0'>
                      </a>
                    </td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</tbody></table>

<!--analytics-->
<script type='text/javascript' async='' src='./A Simple Responsive HTML Email_files/ga.js.descarga'></script><script src='./A Simple Responsive HTML Email_files/jquery-1.10.1.min.js.descarga'></script>
<script src='./A Simple Responsive HTML Email_files/ga-tracking.min.js.descarga'></script>

</body></html>
";
//VERIFÍCO Y ENVIO EL CORREO AL CLIENTE
    
	//ENVÍO EL EMAIL AL CLIENTE:
    require_once '../../../librerias/PHPMailer5.5/src/PHPMailer.php';
    require_once '../../../librerias/PHPMailer5.5/language/phpmailer.lang-es.php';
    require_once '../../../librerias/PHPMailer5.5/src/SMTP.php';
    require_once '../../../librerias/PHPMailer5.5/src/Exception.php';
    include_once('../../../clases/class.enviar-email.php');

	$correo=new class_email();
    /* error no envia */ 
	$array_destinatarios=array(
        ['dire_email'=>$email_cliente,'desc_email'=>$desc_clie]
    );
			//['dire_email'=>'carlossanchezgeo@gmail.com','desc_email'=>'Carlos Sánchez'],
	$asunto='Presupuesto OnLine #'.$id_pres_ceros;
	
	/* cambiar email*/
	$resultado_email=$correo->enviar_ventas_online('ventas-online@hogarisima.com','Ventas On-Line Hogarísima',$array_destinatarios,$asunto,$mensaje_html);
	//FIN ENVIO INFORMACION AL CLIENTE
	$error_transac=($resultado_email==false) ? true : false; // si no se envio el correo pongo error y roldback
	
	if ($error_transac==false){
		$conex->commit();
    ?>
			 <div class="alert bg-gray alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                El presupuesto se registró correctamente y fué remitido por correo al cliente...
                 <br><br>
                <?php
                $accion=$encriptar->encriptar('listado','');
                $link='pedidos.php?accion='.$accion;
                ?>
                 <button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo $link;?>'">
                    Ir a procesar&nbsp;
                    <span class="glyphicon glyphicon-circle-arrow-right"></span>
                </button>
                 <br><br>                
              </div>
                
    <?php
		
	} else {
		$conex->rollback() ;
		?>
         <div class="alert bg-gray alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Notificación!</h4>
            No se pudo procesar el presupuesto, por favor, intente de nuevo, si el problema persiste, contacte al departamento técnico...
            <br><br>
            <button type="button" class="btn btn-danger" onclick="window.history.go(-1);">
                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                &nbsp;Regresar            
            </button>
          </div>
        
    <?php
	} 
    
} catch (PDOException $pdoExcetion) {
		echo $error= 'Error hacer la consulta: '.$pdoExcetion->getMessage();
		return false;
	} catch (Exception $e) {
		echo $error='Excepción capturada: '.  $e->getMessage(). '\n';
		return false;		}
?>