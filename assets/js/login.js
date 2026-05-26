"use strict";

// Class Definition
var KTLogin = function () {
	var _login;

	var _showForm = function (form) {
		var cls = 'login-' + form + '-on';
		var form = 'kt_login_' + form + '_form';

		_login.removeClass('login-forgot-on');
		_login.removeClass('login-signin-on');
		_login.removeClass('login-signup-on');

		_login.addClass(cls);

		KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
	}

	var _handleSignInForm = function () {
		var validation;

		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_signin_form'), {
			fields: {
				email: {
					validators: {
						notEmpty: {
							message: 'Email é Necessário'
						}
					}
				},
				senha: {
					validators: {
						notEmpty: {
							message: 'Senha é Necessário'
						}
					}
				}
			},
			plugins: {
				trigger: new FormValidation.plugins.Trigger(),
				submitButton: new FormValidation.plugins.SubmitButton(),
				//defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
				bootstrap: new FormValidation.plugins.Bootstrap()
			}
		}
		);
		$('.input-group').keypress(function (e) {
			if (e.which == 13) { //Enter key pressed
				$('#kt_login_signin_submit').click(); //Trigger search button click event
			}
		});
		$('#kt_login_signin_submit').on('click', function (e) {
			e.preventDefault();

			validation.validate().then(function (status) {
				if (status == 'Valid') {
					$('.carregandoIndex').show();

					var data = new FormData();

					//Form data
					var form_data = $('#kt_login_signin_form').serializeArray();
					$.each(form_data, function (key, input) {
						data.append(input.name, input.value);
					});

					$.ajax({
						url: 'login/logar.php',
						type: 'POST',
						data: data,
						processData: false,
						contentType: false,
						success: function (data, status) {
							//console.log(data);
							var resposta = data.split("||");
							if (resposta[0] == 'success') {
								window.location.href = HOST_URL + 'home';
							} else {
								Swal.fire({
									text: resposta[1],
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "OK, entendi!",
									customClass: {
										confirmButton: "btn font-weight-bold btn-primary",
									}
								}).then(function () {
									$('.carregandoIndex').hide();
								});

							}
						},
						error: function (xhr, desc, err) {
							swal.fire({
								text: "Parece que alguns erros foram detectados, tente novamente.",
								icon: "error",
								buttonsStyling: false,
								confirmButtonText: "OK, entendi!",
								customClass: {
									confirmButton: "btn font-weight-bold btn-light-primary"
								}
							}).then(function () {
								KTUtil.scrollTop();
							});
						}
					});
				} else {
					swal.fire({
						text: "Parece que alguns erros foram detectados, tente novamente.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "OK, entendi!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-light-primary"
						}
					}).then(function () {
						KTUtil.scrollTop();
					});
				}
			});
		});

		// Handle forgot button
		$('#kt_login_forgot').on('click', function (e) {
			e.preventDefault();
			_showForm('forgot');
		});

	}

	var _handleSignUpForm = function (e) {
		var validation;
		var form = KTUtil.getById('kt_login_signup_form');

		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validation = FormValidation.formValidation(
			form, {
			fields: {
				password: {
					validators: {
						notEmpty: {
							message: 'A senha é obrigatória e não pode estar vazia'
						}
					}
				},
				cpassword: {
					validators: {
						notEmpty: {
							message: 'Confirmar senha é obrigatória e não pode estar vazio'
						},
						identical: {
							compare: function () {
								return form.querySelector('[name="password"]').value;
							},
							message: 'A senha e sua confirmação devem ser as mesmas'
						}
					}
				},
			},
			plugins: {
				trigger: new FormValidation.plugins.Trigger(),
				bootstrap: new FormValidation.plugins.Bootstrap()
			}
		}
		);

		$('#kt_login_signup_submit').on('click', function (e) {
			e.preventDefault();

			validation.validate().then(function (status) {
				if (status == 'Valid') {
					var id_resetar = $('#id_resetar').val();
					if (id_resetar != '') {
						var data = new FormData();

						//Form data
						var form_data = $('#kt_login_signup_form').serializeArray();
						$.each(form_data, function (key, input) {
							data.append(input.name, input.value);
						});

						$.ajax({
							url: 'sql.php',
							type: 'POST',
							data: data,
							processData: false,
							contentType: false,
							success: function (data, status) {
								//console.log(data);
								var resposta = data.split("||");
								if (resposta[0] == 'success') {
									swal.fire({
										text: "Sua senha foi atualizada, você será redirecionado para acessar o ASSOCIAR",
										icon: "success",
										buttonsStyling: false,
										confirmButtonText: "OK, entendi!",
										customClass: {
											confirmButton: "btn font-weight-bold btn-light-primary"
										}
									}).then(function () {
										window.location.href = HOST_URL;
									});

								} else {
									Swal.fire({
										text: resposta[1],
										icon: "error",
										buttonsStyling: false,
										confirmButtonText: "OK, entendi!",
										customClass: {
											confirmButton: "btn font-weight-bold btn-primary",
										}
									}).then(function () {
										$('.carregandoIndex').hide();
									});

								}
							},
							error: function (xhr, desc, err) {
								swal.fire({
									text: "Parece que alguns erros foram detectados, tente novamente.",
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "OK, entendi!",
									customClass: {
										confirmButton: "btn font-weight-bold btn-light-primary"
									}
								}).then(function () {
									KTUtil.scrollTop();
								});
							}
						});

					} else {
						swal.fire({
							text: "Parece que foram detectados alguns erros, tente novamente.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "OK, entendi!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function () {
							_showForm('signin');
						});
					}

				} else {
					swal.fire({
						text: "Parece que foram detectados alguns erros, tente novamente.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "OK, entendi!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-light-primary"
						}
					}).then(function () {
						KTUtil.scrollTop();
					});
				}
			});
		});

		// Handle cancel button
		$('#kt_login_signup_cancel').on('click', function (e) {
			e.preventDefault();

			_showForm('signin');
		});
	}

	var _handleForgotForm = function (e) {
		var validation;

		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_forgot_form'), {
			fields: {
				email: {
					validators: {
						notEmpty: {
							message: 'Email é necessário'
						},
						emailAddress: {
							message: 'O valor não é um endereço de email válido'
						},
						remote: {
							data: {
								type: 'email',
							},
							message: 'O email não existe na base do InfoSystem',
							method: 'POST',
							url: 'remote.php',
						}
					}
				}
			},
			plugins: {
				trigger: new FormValidation.plugins.Trigger(),
				bootstrap: new FormValidation.plugins.Bootstrap(),
				submitButton: new FormValidation.plugins.SubmitButton()
			}
		}
		);
		// Handle submit button
		$('#kt_login_forgot_submit').on('click', function (e) {
			e.preventDefault();

			validation.validate().then(function (status) {
				if (status == 'Valid') {

					var data = new FormData();

					//Form data
					var form_data = $('#kt_login_forgot_form').serializeArray();
					$.each(form_data, function (key, input) {
						data.append(input.name, input.value);
					});

					$.ajax({
						url: 'sql.php',
						type: 'POST',
						data: data,
						processData: false,
						contentType: false,
						success: function (data, status) {
							//console.log(data);
							var resposta = data.split("||");
							if (resposta[0] == 'success') {
								//$('.carregandoIndex').hide();
								_showForm('token');

							} else {
								Swal.fire({
									text: resposta[1],
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "OK, entendi!",
									customClass: {
										confirmButton: "btn font-weight-bold btn-primary",
									}
								}).then(function () {
									$('.carregandoIndex').hide();
								});

							}
						},
						error: function (xhr, desc, err) {
							swal.fire({
								text: "Parece que alguns erros foram detectados, tente novamente.",
								icon: "error",
								buttonsStyling: false,
								confirmButtonText: "OK, entendi!",
								customClass: {
									confirmButton: "btn font-weight-bold btn-light-primary"
								}
							}).then(function () {
								KTUtil.scrollTop();
							});
						}
					});

				} else {
					swal.fire({
						text: "Parece que foram detectados alguns erros, tente novamente.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "OK, entendi!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-light-primary"
						}
					}).then(function () {
						KTUtil.scrollTop();
					});
				}
			});
		});

		// Handle cancel button
		$('#kt_login_forgot_cancel').on('click', function (e) {
			e.preventDefault();

			_showForm('signin');
		});
	}

	// Public Functions
	return {
		// public functions
		init: function () {
			_login = $('#kt_login');

			_handleSignInForm();
			_handleSignUpForm();
			_handleForgotForm();
		}
	};
}();

// Class Initialization
jQuery(document).ready(function () {
	KTLogin.init();
});
