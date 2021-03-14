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
                        message: 'Este campo es requerido'
                    }
                }
            },	
			txt_monto: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione una cuenta'
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
            }	
        	}
        });	  
}); // fin docuemtn ready
