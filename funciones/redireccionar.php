<?php

function redireccionar_js($pag,$tiempoMS){
echo '<script type="text/javascript">  
function redireccionar(){  
  window.location="'.$pag.'";  
}   
setTimeout ("redireccionar()", '.$tiempoMS.'); //tiempo expresado en milisegundos  
</script>';
exit();
}

function redireccionar_js_par($pag,$tiempoMS){
echo '<script language="JavaScript" type="text/javascript">
var pagina="'.$pag.'"
function redireccionar() 
{
window.parent.location.href=pagina
} 
setTimeout ("redireccionar()",'.$tiempoMS.');
</script>';
exit();}
?>