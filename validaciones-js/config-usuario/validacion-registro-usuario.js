  $(document).ready(function() {
    $('#form_nuevo').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			txt_cedula: {
                validators: {
                        stringLength: {
                        min: 5,
						message:'Debe escribir mínimo 5 caracteres'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },			
            txt_nom_ape_raz_soc: {
                validators: {
                        stringLength: {
                        min: 3,
						message:'Debe escribir mas de 3 caracteres'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },
			cmb_tip_cuenta: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione el tipo de cuenta'
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
            phone: {
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
            address: {
                validators: {
                     stringLength: {
                        min: 8,
						message: 'Debe escribir al menos 08 caracteres'
                    },
                    notEmpty: {
                        message: 'Debe suministrar la dirección de facturación / habitación'
                    }
                }
            },
            city: {
                validators: {
                     stringLength: {
                        min: 4,
                    },
                    notEmpty: {
                        message: 'Escriba la ciudad'
                    }
                }
            },
			password: {
				validators: {
                    notEmpty: {
                        message: 'Debe escribir la contraseña'
                    },
					identical: {
						field: 'confirmPassword',
						message: 'La contraseña y su confirmación no coinciden'
					},
					regexp: {
						regexp:'^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[/()=¿?{}_^+!@#\$%\^&\*])(?=.{8,})',
						message: '<p style="text-align:justify;">La contraseña debe tener al menos 01 carácter alfabético en minúscula, al menos 01 carácter alfabético en mayúsculas, al menos 1 carácter numérico, al menos un carácter especial y debe tener ocho caracteres o más.</p>'
					}
				}
        	},
        	confirmPassword: {
				validators: {
                    notEmpty: {
                        message: 'Debe escribir la confirmación de la contraseña'
                    },
					identical: {
						field: 'password',
						message: 'La contraseña y su confirmación no coinciden'
					}
				}
        	}
            }		
        });
	  $('#form_editar').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			txt_cedula: {
                validators: {
                        stringLength: {
                        min: 5,
						message:'Debe escribir mínimo 5 caracteres'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },			
            txt_nom_ape_raz_soc: {
                validators: {
                        stringLength: {
                        min: 3,
						message:'Debe escribir mas de 3 caracteres'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },
			cmb_tip_cuenta: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione el tipo de cuenta'
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
            phone: {
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
            address: {
                validators: {
                     stringLength: {
                        min: 8,
						message: 'Debe escribir al menos 08 caracteres'
                    },
                    notEmpty: {
                        message: 'Debe suministrar la dirección de facturación / habitación'
                    }
                }
            },
            city: {
                validators: {
                     stringLength: {
                        min: 4,
                    },
                    notEmpty: {
                        message: 'Escriba la ciudad'
                    }
                }
            },
			password: {
				validators: {
                    notEmpty: {
                        message: 'Debe escribir la contraseña'
                    },
					identical: {
						field: 'confirmPassword',
						message: 'La contraseña y su confirmación no coinciden'
					},
					regexp: {
						regexp:'^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[/()=¿?{}_^+!@#\$%\^&\*])(?=.{8,})',
						message: '<p style="text-align:justify;">La contraseña debe tener al menos 01 carácter alfabético en minúscula, al menos 01 carácter alfabético en mayúsculas, al menos 1 carácter numérico, al menos un carácter especial y debe tener ocho caracteres o más.</p>'
					}
				}
        	},
        	confirmPassword: {
				validators: {
                    notEmpty: {
                        message: 'Debe escribir la confirmación de la contraseña'
                    },
					identical: {
						field: 'password',
						message: 'La contraseña y su confirmación no coinciden'
					}
				}
        	}
            }		
        });
	  $('#form_reg_cuenta_banco').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			num_cuenta: {
                validators: {
                        stringLength: {
                        min: 3,
						message:'Debe escribir mínimo 3 caracteres'
                    },
                        notEmpty: {
                        message: 'Este campo es requerido'
                    }
                }
            },			
			cmb_tip_cuenta: {
                validators: {
                     notEmpty: {
                        message: 'Seleccione el tipo de cuenta'
                    }
                }
            }	
        	}
        });	  
}); // fin docuemtn ready
