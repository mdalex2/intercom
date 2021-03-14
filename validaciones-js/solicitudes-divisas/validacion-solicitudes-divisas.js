  $(document).ready(function() {
	  $('#form_nuevo').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			num_ide: {
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
			desc_clie: {
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
			num_cuenta: {
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
            telf: {
                validators: {
                    notEmpty: {
                        message: 'Suministre su número telefónico'
                    },
					regexp: {
						regexp: '^[0-9]{3,4}-? ?[0-9]{6,7}$',
						//regexp: '/^([0-9]{4})(-)([0-9]{7})$/',
						message: 'Ingrese un número telefónico en formato (1111)-1111111'
					}
					/*
                    phone: {
                        country: 've',
                        message: 'Ingrese un correo número telefónico en formato 04121234567'
                    }*/
				}
            },
			monto_soli: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione una cuenta'
                    }
                }
            },						
			id_cuenta_orig: {
                validators: {
                     notEmpty: {
                        message: 'Debe seleccionar el saldo en caja disponible'
                    }
                }
            }	
        	}
        });	  
}); // fin docuemtn ready
