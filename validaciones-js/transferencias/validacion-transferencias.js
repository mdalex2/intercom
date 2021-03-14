  $(document).ready(function() {
	  $('#frm_gestion_cuenta').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			txt_num_ref: {
                validators: {
                        stringLength: {
                        min: 5,
						message:'Debe escribir mínimo 5 dígitos'
                    },
                        notEmpty: {
                        message: 'Ingrese el número de referencia del banco'
                    }
                }
            },	
			txt_monto: {
                validators: {
                     notEmpty: {
                        message: 'Ingrese el monto a transferir'
                    }
                }
            },			
			txt_fecha: {                
                validators: {
					notEmpty: {
                        message: 'Indíque la fecha de la transacción'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Formato de fecha incorrecta dd-mm-yyyy'
                    }
				}
            },
			cmb_cuenta: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione una cuenta'
                    }
                }
            },
			cmb_cuenta_dest: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione una cuenta destino'
                    }
                }
            },
			arch_soporte: {
                validators: {
                     notEmpty: {
                        message: 'Debe adjuntar el capture del baucher'
                    },
					file: {
                        extension: 'jpeg,jpg,png,pdf',
                        type: 'image/jpeg,image/png,application/pdf',
                        maxSize: 2097152,   // 2048 * 1024
                        message: 'Archivo inválido, debe ser un PDF o imagen (jpeg,jpg,png) no mayor a 2MB'
                    }
                }
            }
        	}
        });	  
}); // fin docuemtn ready
