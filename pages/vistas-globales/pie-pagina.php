
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- NOTIFICACIONES BOOTSTRAP -->
<script src="../../bower_components/bootstrap-notify-master/bootstrap-notify.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="../../bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- bootstrap datepicker -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../../bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>

<script>
//esta funcion cierra sesion al hacer clic en el enlace
	
		$('#btn_cerrar_sess').on('click', function () {
			//var Status = $(this).val();
			$.ajax({
				url: '../../../login/destruye_sesion.php',
				/*
				data: {
					text: $('textarea[name=Status]').val(),
					Status: Status
				},
				dataType : 'json'
				*/
				success:function(data){
					// now we have the response, so hide the loader
					window.parent.location.href='../../../home/';
				}
			});
		});
</script>
<?php
if ($total_solicitudes>$_SESSION['tot_sol']):
	$_SESSION['tot_sol']=$total_solicitudes;
?>
<script type="text/javascript">
	$.notify({
	// options
		title: "<strong>Notificación:</strong><br> ",
		icon: 'fa fa-info',
		message: ' Nueva asignación de solicitud, ha sido recibida en su buzón.',
		newest_on_top: true,
		
	},{
	// settings
		type: 'warning'
	});
	
</script>
<?php
endif;
?>