<?php

require __DIR__ . '/inc/config.php';
include __DIR__ . '/inc/url.php';

if (isset($_COOKIE['id_is'])) {

	echo "<script>location.href='home';</script>";
}

?>
<!DOCTYPE html>

<html lang="br">
<!--begin::Head-->

<head>
	<meta charset="utf-8" />
	<title>InfoSystem</title>
	<meta name="description" content="Login page example" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="canonical" href="https://infoaquila.com.br" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Custom Styles(used by this page)-->
	<link href="assets/css/login.css" rel="stylesheet" type="text/css" />
	<!--end::Page Custom Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/prismjs.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="assets/media/logos/favicon.png" />

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading index-login">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
		<div class="login login-5 login-signin-on d-flex flex-row-fluid" id="kt_login">
			<div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid">
				<div class="login-form text-center text-white p-7 position-relative overflow-hidden">
					<!--begin::Login Header-->
					<div class="d-flex flex-center mb-15">
						<a href="#">
							<img src="assets/media/logos/infoaquila.png" class="max-h-150px" alt="" />
						</a>
					</div>
					<!--end::Login Header-->
					<!--begin::Login Sign in form-->
					<div class="login-signin">
						<div class="mb-20">
							<h3 class="opacity-40 font-weight-normal">Acesse o InfoSystem</h3>
							<p class="opacity-40">Seu melhor APP para Marmoraria</p>
						</div>
						<form class="form" id="kt_login_signin_form">
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="password" placeholder="Senha" name="senha" />
							</div>
							<div class="form-group d-flex flex-wrap justify-content-between align-items-center px-8 opacity-60">
								<div class="checkbox-inline">
									<label class="checkbox checkbox-outline checkbox-white text-white m-0">
										<input type="checkbox" name="remember" />
										<span></span>Lembre de mim</label>
								</div>
								<p class="text-right m-0" style="height: 40px;">
									<span class="carregandoIndex" style="display: none;">Carregando...<img src="assets/media/svg/loading.svg" width="40" /></span>
								</p>
								<a href="javascript:;" id="kt_login_forgot" class="text-white font-weight-bold">Esqueceu a senha ?</a>
							</div>
							<div class="form-group text-center mt-10">
								<button id="kt_login_signin_submit" class="btn btn-pill btn-primary opacity-90 px-15 py-3">Entrar</button>
							</div>
						</form>
						<div class="mt-10">
							<span class="opacity-40 mr-4">Não tem uma conta ainda?</span>
							<a href="javascript:;" id="kt_login_signup" class="text-white opacity-30 font-weight-normal">Inscrever-se</a>
						</div>
					</div>
					<!--end::Login Sign in form-->
					<!--begin::Login Sign up form-->
					<div class="login-signup">
						<div class="mb-20">
							<h3 class="opacity-40 font-weight-normal">Inscrever-se</h3>
							<p class="opacity-40">Insira seus dados para criar sua conta</p>
						</div>
						<form class="form text-center" id="kt_login_signup_form">
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="text" placeholder="Nome" name="fullname" />
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="password" placeholder="Senha" name="password" />
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="password" placeholder="Confirmar Senha" name="cpassword" />
							</div>
							<div class="form-group text-left px-8">
								<div class="checkbox-inline">
									<label class="checkbox checkbox-outline checkbox-white opacity-60 text-white m-0">
										<input type="checkbox" name="agree" />
										<span></span>Eu concordo com
										<a href="#" class="text-white font-weight-bold ml-1">os termos e Condições</a>.</label>
								</div>
								<div class="form-text text-muted text-center"></div>
							</div>
							<div class="form-group">
								<button id="kt_login_signup_submit" class="btn btn-pill btn-primary opacity-90 px-15 py-3 m-2">Inscrever-se</button>
								<button id="kt_login_signup_cancel" class="btn btn-pill btn-outline-white opacity-70 px-15 py-3 m-2">Cancelar</button>
							</div>
						</form>
					</div>
					<!--end::Login Sign up form-->
					<!--begin::Login forgot password form-->
					<div class="login-forgot">
						<div class="mb-20">
							<h3 class="opacity-40 font-weight-normal">Esqueceu a senha ?</h3>
							<p class="opacity-40">Insira seu e-mail para redefinir sua senha</p>
						</div>
						<form class="form" id="kt_login_forgot_form">
							<div class="form-group mb-10">
								<input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
							</div>
							<div class="form-group">
								<button id="kt_login_forgot_submit" class="btn btn-pill btn-primary opacity-90 px-15 py-3 m-2">Solicitar Senha</button>
								<button id="kt_login_forgot_cancel" class="btn btn-pill btn-outline-white opacity-70 px-15 py-3 m-2">Cancelar</button>
							</div>
						</form>
					</div>
					<!--end::Login forgot password form-->
				</div>
			</div>
		</div>
		<!--end::Login-->
	</div>
	<!--end::Main-->
	<script>
		var HOST_URL = "<?= $url ?>";
	</script>
	<!--begin::Global Config(global config for global JS scripts)-->
	<script>
		var KTAppSettings = {
			"breakpoints": {
				"sm": 576,
				"md": 768,
				"lg": 992,
				"xl": 1200,
				"xxl": 1200
			},
			"font-family": "Poppins"
		};
	</script>
	<!--end::Global Config-->
	<!--begin::Global Theme Bundle(used by all pages)-->
	<script src="assets/js/plugins.bundle.js"></script>
	<script src="assets/js/prismjs.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>
	<!--end::Global Theme Bundle-->
	<!--begin::Page Scripts(used by this page)-->
	<script src="assets/js/login.js"></script>
	<!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>