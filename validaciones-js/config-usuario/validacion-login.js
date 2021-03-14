  $(document).ready(function() {
    $('#frm_login').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'Debe escribir su correo electrónico'
                    },
                    emailAddress: {
                        message: 'Dirección de email incorrecta'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Debe ingresar la clave de acceso'
                    }
				}
            }
		}
	});
}); // fin docuemtn ready