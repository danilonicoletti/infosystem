<?php

include __DIR__ . "/topo.php";

$consulta = $pdo->query("SELECT * FROM empresa WHERE id = " . $company);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

?>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6" id="kt_subheader">
	<div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Info-->
		<div class="d-flex align-items-center flex-wrap mr-1">
			<!--begin::Page Heading-->
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<!--begin::Page Title-->
				<h5 class="text-dark font-weight-bold my-1 mr-5">InfoSystem</h5>
				<!--end::Page Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a href="" class="text-muted">Dashboard</a>
					</li>

				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page Heading-->
		</div>
		<!--end::Info-->
		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">
			<!--begin::Daterange-->
			<a href="#" class="btn btn-light-primary btn-sm font-weight-bold ml-2" data-toggle="tooltip" data-placement="left">
				<span class="svg-icon svg-icon-md">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<rect x="0" y="0" width="24" height="24" />
							<path d="M12,22 C7.02943725,22 3,17.9705627 3,13 C3,8.02943725 7.02943725,4 12,4 C16.9705627,4 21,8.02943725 21,13 C21,17.9705627 16.9705627,22 12,22 Z" fill="#000000" opacity="0.3" />
							<path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000" />
						</g>
					</svg><!--end::Svg Icon--></span>
				<span class="font-weight-bold" id="kt_dashboard_daterangepicker_date"><?= date('d') ?> de <?= date('M') ?></span>
			</a>
			<!--end::Daterange-->

		</div>
		<!--end::Toolbar-->
	</div>
</div>
<!--end::Subheader-->
<div class="content flex-column-fluid" id="kt_content">
	<div class="row">
		<div class="col-xl-12">
			<div class="card card-custom gutter-b card-stretch">
				<div class="d-flex flex-row-fluid bgi-size-contain bgi-position-center" style="background-image: url('assets/media/bg/bg-<?= $linha['documento'] ?>.webp'); border-radius: 5px;background-size: cover; ">
					<div class=" container ">

						<div class="d-flex align-items-stretch text-center flex-column py-10">

							<div class="container px-0">

								<div class="card card-custom gutter-b mb-0" style="background-color: #ffffff00 !important;">
									<div class="card-body">

										<div class="d-flex mb-9">

											<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">

												<img src="assets/media/company/<?= $linha['logo'] ?>" alt="image" style="height: 96px;">


											</div>

											<div class="flex-grow-1">

												<div class="d-flex justify-content-between flex-wrap mt-1">
													<div class="d-flex mr-3">
														<a href="#" class="text-white font-size-h1 font-weight-bold mr-3"><?= $linha['fantasia'] ?></a>

													</div>
												</div>

												<div class="d-flex flex-wrap row justify-content-between mt-1">
													<div class="d-flex flex-column col-md-6 flex-grow-1 pr-8">
														<div class="d-flex flex-wrap mb-4">
															<a href="mailto:<?= $linha['email'] ?>" class="text-white text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
																<i class="flaticon2-envelope mr-2 font-size-lg"> <?= $linha['email'] ?></i></a>

														</div>
														<div class="d-flex flex-wrap mb-4">
															<a href="#" class="text-white text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
																<i class="flaticon2-phone mr-2 font-size-lg"> <?= $linha['telefone'] ?></i></a>

														</div>
													</div>

												</div>

											</div>

										</div>

										<div class="separator separator-solid"></div>

										<div class="d-flex align-items-center flex-wrap mt-8">
											<p class="text-white mb-0"><?= $linha['institucional'] ?></p>
										</div>

									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--begin::Dashboard-->

	<!--end::Dashboard-->
</div>
<!--end::Content-->
</div>
<!--begin::Content Wrapper-->
</div>
<!--end::Container-->
<?php include __DIR__ . "/footer.php"; ?>