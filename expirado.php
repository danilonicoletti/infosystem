<?
require __DIR__ . '/inc/config.php';
require __DIR__ . '/login/conn.php';
include __DIR__ . '/inc/url.php';
include __DIR__ . '/inc/functions.php';

session_destroy();
?>

<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>InfoSystem</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="assets/media/logos/favicon.png" />
</head>
<script>
    // Código AJAX para atualização rápida

    var counter = 5;
    var cinterval;
    setInterval(function() {
        counter--;
        if (counter >= 1) {
            document.getElementById('count').innerHTML = '&nbsp;Sua sessão foi expirada, voce redirecionado para a tela de login em ' + counter + ' segundos';
        }
        // Display 'counter' wherever you want to display it.
        if (counter === 0) {
            location.href = '<?= $url ?>';
            clearInterval(cinterval);
        }
    }, 1000);
</script>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading index-login">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Error-->
        <div class="error error-6 d-flex flex-row-fluid bgi-size-cover bgi-position-center">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-row-fluid text-center m-auto">
                <img src="assets/media/logos/infoaquila.png" alt="infoSystem" class="max-w-200px " style="margin: 0 auto;">
                <h1 class="error-title font-weight-boldest mb-6" style="margin-top: 6rem; color:#FFF;">Oops...</h1>
                <p class="font-weight-bold" style="font-size: 1.8rem !important;color:#FFF;" id="count">Sua sessão foi expirada, voce redirecionado para a tela de login em 5 segundos</p>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Error-->
    </div>
    <!--end::Main-->
    <!--begin::Footer-->
    <? include 'footer.php'; ?>
    <!--end::Footer-->
    </div>
    <!--end::Wrapper-->
    </div>
    <!--end::Page-->
    </div>
    <!--end::Main-->
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            }
        };
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->
</body>
<!--end::Body-->

</html>