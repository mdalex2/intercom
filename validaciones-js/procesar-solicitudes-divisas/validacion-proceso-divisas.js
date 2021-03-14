  $(document).ready(function() {
	  $('#frm_procesar').bootstrapValidator({
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
                        min: 2,
						message:'Debe escribir mínimo 2 dígitos'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },
			txt_fecha: {                
                validators: {
					notEmpty: {
                        message: 'Indíque la fecha de transferencia'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Formato de fecha incorrecta dd-mm-yyyy'
                    }
				}
            },
			arch_soporte: {
                validators: {
					file: {
                        extension: 'jpeg,jpg,png,pdf',
                        type: 'image/jpeg,image/jpg,image/png,application/pdf',
                        maxSize: 120000,
                        message: 'Tamaño de imagen o archivo inválido'
                    },
                    notEmpty: {
                        message: 'Archivo del soporte de transferencia requerido'
                    }
                }
            }	
        	}
        });	  
}); // fin docuemtn ready
