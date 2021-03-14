<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/PHPMailer5.5/src/PHPMailer.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/PHPMailer5.5/language/phpmailer.lang-es.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/PHPMailer5.5/src/SMTP.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/PHPMailer5.5/src/Exception.php';

class class_email{
	//Método de conexión PDO
	//Atributos ------
	//Métodos
public function enviar_ventas_online($mail_remitente,$nombre_remitente,$array_destinatarios,$asunto,$mensaje){
    $mail = new PHPMailer();     
	$mail->IsSMTP();
	$mail->SMTPDebug = 0; //predeterminado 0 no mostrar errror y 2 mostrar todo
	$mail->CharSet = 'UTF-8';
	$mail->Mailer = "smtp";
	$mail->Host = "mx1.hostinger.com";
	$mail->Port = "587"; 
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = 'tls';
	$mail->Username = "ventas-online@hogarisima.com";
	$mail->Password = "Vo2018++";
	$mail->Priority = 3;
	$mail->setFrom('ventas-online@hogarisima.com', $nombre_remitente);
	$mail->addReplyTo($mail_remitente,$nombre_remitente);
	//destinatario
	$mail->Subject = $asunto;
	
	if (count($array_destinatarios)>0){
		foreach ($array_destinatarios as $correo){
			$mail->AddAddress($correo["dire_email"], $correo["desc_email"]);
		}
	}
	$mail->IsHTML(true);
	$mail->Body = $mensaje;
	$mail->AltBody = strip_tags($mensaje);
	$mail->WordWrap = 0; 	
	if (!$mail->Send()) {
		//echo 'Mensaje no enviado.';
		//echo 'E-mail no enviado, error: ' . $mail->ErrorInfo;
		return false;
	//exit;
	} else {
		//echo '<div class="container"><h2>Su pedido se envío, próximanente obtendrá una respuesta</h2></div>';
		return true;
		//unset($_SESSION["carrito"]);
	}
	} // fin funcin enviar	
public function enviar_no_responder($mail_remitente,$nombre_remitente,$array_destinatarios,$asunto,$mensaje){
    $mail = new PHPMailer();     
	$mail->IsSMTP();
	$mail->SMTPDebug = 0; //predeterminado 0 no mostrar errror y 2 mostrar todo
	$mail->CharSet = 'UTF-8';
	$mail->Mailer = "smtp";
	$mail->Host = "mx1.hostinger.com";
	$mail->Port = "587"; 
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = 'tls';
	$mail->Username = "no-responder@hogarisima.com";
	$mail->Password = "Nr2018++";
	$mail->Priority = 3;
	$mail->setFrom('no-responder@hogarisima.com', $nombre_remitente);
	$mail->addReplyTo($mail_remitente,$nombre_remitente);
	//destinatario
	$mail->Subject = $asunto;
	
	if (count($array_destinatarios)>0){
		foreach ($array_destinatarios as $correo){
			$mail->AddAddress($correo["dire_email"], $correo["desc_email"]);
		}
	}
	$mail->IsHTML(true);
	$mail->Body = $mensaje;
	$mail->AltBody = strip_tags($mensaje);
	$mail->WordWrap = 0; 
	if (!$mail->Send()) {
		//echo 'Mensaje no enviado.';
		//echo 'E-mail no enviado, error: ' . $mail->ErrorInfo;
		return false;
	//exit;
	} else {
		//echo '<div class="container"><h2>Su pedido se envío, próximanente obtendrá una respuesta</h2></div>';
		return true;
		//unset($_SESSION["carrito"]);
	}
	} // fin funcin enviar	
} // fin clase


/*
$correo=new class_email();
$email_cliente='soportehogarisima@gmail.com';
$desc_clie = 'Alexi mendoza';
$mensaje_html='Hola esta es una prueba del envio de email hogarisima';

	$array_destinatarios=array(
                    ['dire_email'=>$email_cliente,'desc_email'=>$desc_clie]
					
					);
			//['dire_email'=>'carlossanchezgeo@gmail.com','desc_email'=>'Carlos Sánchez'],
	$asunto='Presupuesto OnLine #';
	
	$resultado_email=$correo->enviar_ventas_online('ventas-online@hogarisima.com','Ventas On-Line Hogarísima',$array_destinatarios,$asunto,$mensaje_html);
	//FIN ENVIO INFORMACION AL CLIENTE
	$error_transac=($resultado_email==false) ? true : false; // si no se envio el correo pongo error y roldback
    echo 'Resultado del envío: '.$resultado_email;
    */

?>