<?
require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <title>InfoSystem</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://infoaquila-horizontal.com.br" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="<?= $url ?>assets/css/infosystem.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?= $url ?>assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= $url ?>assets/css/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= $url ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="<?= $url ?>assets/media/logos/favicon.png" />

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed subheader-enabled page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header Mobile-->
                <div id="kt_header_mobile" class="header-mobile">
                    <!--begin::Logo-->
                    <a href="<?= $url ?>home">
                        <img alt="Logo" src="<?= $url ?>assets/media/logos/infoaquila-horizontal.png" class="max-h-30px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center">
                        <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
                            <span></span>
                        </button>
                        <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                            <span class="svg-icon svg-icon-xl">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </button>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header Mobile-->
                <!--begin::Header-->
                <div id="kt_header" class="header header-fixed">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Left-->
                        <div class="d-none d-lg-flex align-items-center mr-3">
                            <!--begin::Logo-->
                            <a href="<?= $url ?>home" class="mr-20">
                                <img alt="Logo" src="<?= $url ?>assets/media/logos/infoaquila-horizontal.png" class="logo-default max-h-35px" />
                            </a>
                            <!--end::Logo-->
                        </div>
                        <!--end::Left-->

                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Header Menu Wrapper-->
                <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                    <div class="container">
                        <!--begin::Header Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default header-menu-root-arrow">
                            <!--begin::Header Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item  menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">

                                        <span class="menu-text">

                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Chat-check.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        <path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>Cadastros</span>

                                    </a>

                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                                    </g>
                                                </svg>
                                            </span>Empresas</span>
                                    </a>

                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z" fill="#000000" opacity="0.3" />
                                                        <polygon fill="#000000" points="14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912" />
                                                    </g>
                                                </svg>
                                            </span>
                                            Estoque</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                        <path d="M3.52270623,14.028695 C2.82576459,13.3275941 2.82576459,12.19529 3.52270623,11.4941891 L11.6127629,3.54050571 C11.9489429,3.20999263 12.401513,3.0247814 12.8729533,3.0247814 L19.3274172,3.0247814 C20.3201611,3.0247814 21.124939,3.82955935 21.124939,4.82230326 L21.124939,11.2583059 C21.124939,11.7406659 20.9310733,12.2027862 20.5869271,12.5407722 L12.5103155,20.4728108 C12.1731575,20.8103442 11.7156477,21 11.2385688,21 C10.7614899,21 10.3039801,20.8103442 9.9668221,20.4728108 L3.52270623,14.028695 Z M16.9307214,9.01652093 C17.9234653,9.01652093 18.7282432,8.21174298 18.7282432,7.21899907 C18.7282432,6.22625516 17.9234653,5.42147721 16.9307214,5.42147721 C15.9379775,5.42147721 15.1331995,6.22625516 15.1331995,7.21899907 C15.1331995,8.21174298 15.9379775,9.01652093 16.9307214,9.01652093 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                            Vendas</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <rect fill="#000000" opacity="0.3" x="11.5" y="2" width="2" height="4" rx="1" />
                                                        <rect fill="#000000" opacity="0.3" x="11.5" y="16" width="2" height="5" rx="1" />
                                                        <path d="M15.493,8.044 C15.2143319,7.68933156 14.8501689,7.40750104 14.4005,7.1985 C13.9508311,6.98949895 13.5170021,6.885 13.099,6.885 C12.8836656,6.885 12.6651678,6.90399981 12.4435,6.942 C12.2218322,6.98000019 12.0223342,7.05283279 11.845,7.1605 C11.6676658,7.2681672 11.5188339,7.40749914 11.3985,7.5785 C11.2781661,7.74950085 11.218,7.96799867 11.218,8.234 C11.218,8.46200114 11.2654995,8.65199924 11.3605,8.804 C11.4555005,8.95600076 11.5948324,9.08899943 11.7785,9.203 C11.9621676,9.31700057 12.1806654,9.42149952 12.434,9.5165 C12.6873346,9.61150047 12.9723317,9.70966616 13.289,9.811 C13.7450023,9.96300076 14.2199975,10.1308324 14.714,10.3145 C15.2080025,10.4981676 15.6576646,10.7419985 16.063,11.046 C16.4683354,11.3500015 16.8039987,11.7268311 17.07,12.1765 C17.3360013,12.6261689 17.469,13.1866633 17.469,13.858 C17.469,14.6306705 17.3265014,15.2988305 17.0415,15.8625 C16.7564986,16.4261695 16.3733357,16.8916648 15.892,17.259 C15.4106643,17.6263352 14.8596698,17.8986658 14.239,18.076 C13.6183302,18.2533342 12.97867,18.342 12.32,18.342 C11.3573285,18.342 10.4263378,18.1741683 9.527,17.8385 C8.62766217,17.5028317 7.88033631,17.0246698 7.285,16.404 L9.413,14.238 C9.74233498,14.6433354 10.176164,14.9821653 10.7145,15.2545 C11.252836,15.5268347 11.7879973,15.663 12.32,15.663 C12.5606679,15.663 12.7949989,15.6376669 13.023,15.587 C13.2510011,15.5363331 13.4504991,15.4540006 13.6215,15.34 C13.7925009,15.2259994 13.9286662,15.0740009 14.03,14.884 C14.1313338,14.693999 14.182,14.4660013 14.182,14.2 C14.182,13.9466654 14.1186673,13.7313342 13.992,13.554 C13.8653327,13.3766658 13.6848345,13.2151674 13.4505,13.0695 C13.2161655,12.9238326 12.9248351,12.7908339 12.5765,12.6705 C12.2281649,12.5501661 11.8323355,12.420334 11.389,12.281 C10.9583312,12.141666 10.5371687,11.9770009 10.1255,11.787 C9.71383127,11.596999 9.34650161,11.3531682 9.0235,11.0555 C8.70049838,10.7578318 8.44083431,10.3968355 8.2445,9.9725 C8.04816568,9.54816454 7.95,9.03200304 7.95,8.424 C7.95,7.67666293 8.10199848,7.03700266 8.406,6.505 C8.71000152,5.97299734 9.10899753,5.53600171 9.603,5.194 C10.0970025,4.85199829 10.6543302,4.60183412 11.275,4.4435 C11.8956698,4.28516587 12.5226635,4.206 13.156,4.206 C13.9160038,4.206 14.6918294,4.34533194 15.4835,4.624 C16.2751706,4.90266806 16.9686637,5.31433061 17.564,5.859 L15.493,8.044 Z" fill="#000000" />
                                                    </g>
                                                </svg>
                                            </span>
                                            Financeiro</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M13,5 L15,5 L15,20 L13,20 L13,5 Z M5,5 L5,20 L3,20 C2.44771525,20 2,19.5522847 2,19 L2,6 C2,5.44771525 2.44771525,5 3,5 L5,5 Z M16,5 L18,5 L18,20 L16,20 L16,5 Z M20,5 L21,5 C21.5522847,5 22,5.44771525 22,6 L22,19 C22,19.5522847 21.5522847,20 21,20 L20,20 L20,5 Z" fill="#000000" />
                                                        <polygon fill="#000000" opacity="0.3" points="9 5 9 20 7 20 7 5" />
                                                    </g>
                                                </svg>
                                            </span>
                                            Nota Fiscal</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">
                                            <span class="svg-icon menu-icon mr-3" style="margin-top: -5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <rect fill="#000000" opacity="0.3" x="17" y="4" width="3" height="13" rx="1.5" />
                                                        <rect fill="#000000" opacity="0.3" x="12" y="9" width="3" height="8" rx="1.5" />
                                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero" />
                                                        <rect fill="#000000" opacity="0.3" x="7" y="11" width="3" height="6" rx="1.5" />
                                                    </g>
                                                </svg>
                                            </span>
                                            Relatórios</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                </li>

                            </ul>
                            <!--end::Header Nav-->
                        </div>
                        <!--end::Header Menu-->
                    </div>
                </div>
                <!--end::Header Menu Wrapper-->
                <!--begin::Container-->
                <div class="d-flex flex-row flex-column-fluid container">
                    <!--begin::Content Wrapper-->
                    <div class="main d-flex flex-column flex-row-fluid">
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
                                                <a href="javascript:;" class="text-muted">Empresa Parceira</a>
                                            </li>
                                            <li class="breadcrumb-item text-muted">
                                                <a href="javascript:;" class="text-muted">Adicionar</a>
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
                                    <a href="javascript:;" class="btn btn-light-primary btn-sm font-weight-bold ml-2" data-toggle="tooltip" data-placement="left">
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
                        <!--begin::Content-->
                        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                            <!--begin::Entry-->
                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container">
                                    <!--begin::Card-->
                                    <div class="card card-custom">
                                        <div class="card-body">
                                            <div class="" id="empresa_add" data-wizard-state="step-first" data-wizard-clickable="true">

                                                <!--begin::Wizard Body-->
                                                <div class="row justify-content-center">
                                                    <div class="col-12">
                                                        <!--begin::Form Wizard-->
                                                        <form class="form" id="empresa_add_form">
                                                            <input name="type" type="hidden" value="empresa_add" />

                                                            <div class="form-group row">

                                                                <div class="col-12 col-lg-2">
                                                                    <label>Pessoa:<span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="pessoa">
                                                                        <option value="juridica" selected>Jurídica</option>
                                                                        <option value="fisica">Física</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 col-lg-5">
                                                                    <label class="alter-name-1">Nome Fantasia:</label><span class="text-danger">*</span>
                                                                    <input type="text" class="form-control alter-name-1" name="fantasia" placeholder="Nome Fantasia">
                                                                </div>
                                                                <div class="col-12 col-lg-5">
                                                                    <label class="alter-name-2">Razão Social:</label><span class="text-danger">*</span>
                                                                    <input type="text" class="form-control alter-name-2" name="social" placeholder="Razão Social">
                                                                </div>

                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Documento:<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control cpfCnpjMask" name="documento" placeholder="Documento">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>E-mail:<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="email" placeholder="E-mail">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Senha:<span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" name="senha" placeholder="Senha">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Confirmar Senha:<span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" name="confirmar" placeholder="confirmar">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-12 col-lg-3 remove-required">
                                                                    <label>Nome Responsável:</label><span class="text-danger">*</span>
                                                                    <input type="text" class="form-control" name="nome_responsavel" placeholder="Nome Responsável">
                                                                </div>
                                                                <div class="col-12 col-lg-3 remove-required">
                                                                    <label>Sobrenome Responsável:</label><span class="text-danger">*</span>
                                                                    <input type="text" class="form-control" name="sobrenome_responsavel" placeholder="Sobrenome Responsável">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Telefone:<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control celMask" name="telefone" placeholder="Telefone">
                                                                </div>
                                                                <div class="col-12 col-lg-2">
                                                                    <label>Regime Tributário:<span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="crt">
                                                                        <option value="">...</option>
                                                                        <option value="1">Simples Nacional</option>
                                                                        <option value="2">Simples Nacional - excesso de sublimite da receita bruta</option>
                                                                        <option value="3">Regime Normal</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="separator separator-dashed my-10"></div>
                                                            <div class="form-group row mb-0">
                                                                <div class="col-12 col-lg-12">
                                                                    <label>Logotipo Empresa:<span class="text-danger">*</span></label>
                                                                    <div></div>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="logotipo" id="logotipo">
                                                                        <label class="custom-file-label" for="customFile">Escolher</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="separator separator-dashed my-10"></div>
                                                            <div class="form-group row">
                                                                <div class="col-12 col-lg-2">
                                                                    <label>CEP:</label>
                                                                    <input type="text" class="form-control cepMask" name="cep" placeholder="CEP" required>
                                                                    <span class="form-text text-muted text-cep-validator text-danger"></span>
                                                                </div>
                                                                <div class="col-12 col-lg-8">
                                                                    <label>Endereço:</label>
                                                                    <input type="text" class="form-control endereco" name="endereco" placeholder="Endereço">
                                                                </div>
                                                                <div class="col-12 col-lg-2">
                                                                    <label>Numero:</label>
                                                                    <input type="text" class="form-control numero" name="numero" placeholder="Numero">
                                                                    <input type="hidden" class="form-control ibge" name="ibge">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-12 col-lg-4">
                                                                    <label>Complemento:</label>
                                                                    <input type="text" class="form-control" name="complemento" value="" placeholder="Complemento">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Bairro:</label>
                                                                    <input type="text" class="form-control bairro" name="bairro" placeholder="Bairro">
                                                                </div>
                                                                <div class="col-12 col-lg-3">
                                                                    <label>Cidade:</label>
                                                                    <input type="text" class="form-control cidade" name="cidade" placeholder="Cidade">
                                                                </div>
                                                                <div class="col-12 col-lg-2">
                                                                    <label>Estado:</label>
                                                                    <select class="form-control uf" name="estado">
                                                                        <option value="">...</option>
                                                                        <option value="AC">AC</option>
                                                                        <option value="AL">AL</option>
                                                                        <option value="AP">AP</option>
                                                                        <option value="AM">AM</option>
                                                                        <option value="BA">BA</option>
                                                                        <option value="CE">CE</option>
                                                                        <option value="DF">DF</option>
                                                                        <option value="ES">ES</option>
                                                                        <option value="GO">GO</option>
                                                                        <option value="MA">MA</option>
                                                                        <option value="MT">MT</option>
                                                                        <option value="MS">MS</option>
                                                                        <option value="MG">MG</option>
                                                                        <option value="PA">PA</option>
                                                                        <option value="PB">PB</option>
                                                                        <option value="PR">PR</option>
                                                                        <option value="PE">PE</option>
                                                                        <option value="PI">PI</option>
                                                                        <option value="RJ">RJ</option>
                                                                        <option value="RN">RN</option>
                                                                        <option value="RS">RS</option>
                                                                        <option value="RO">RO</option>
                                                                        <option value="RR">RR</option>
                                                                        <option value="SC">SC</option>
                                                                        <option value="SP">SP</option>
                                                                        <option value="SE">SE</option>
                                                                        <option value="TO">TO</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!--begin::Actions-->
                                                            <div class="d-flex justify-content-between ">
                                                                <div class="mr-2">

                                                                </div>
                                                                <div>
                                                                    <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Gravar</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                        <!--end::Form Wizard-->
                                                    </div>
                                                </div>
                                                <!--end::Wizard Body-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Entry-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--begin::Content Wrapper-->
                </div>
                <!--end::Content-->
                <div id="theModal" class="modal fade text-center">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
                <!--end::Container-->
                <!--begin::Footer-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2023©</span>
                            <a href="javascript:;" target="_blank" class="text-white text-hover-primary">InfoSystem</a>
                        </div>
                        <!--end::Copyright-->
                        <!--begin::Nav-->
                        <div class="nav nav-dark order-1 order-md-2">
                            <a href="javascript:;" target="_blank" class="nav-link pr-3 pl-0 text-muted text-hover-primary">Sobre</a>
                            <a href="javascript:;" target="_blank" class="nav-link px-3 text-muted text-hover-primary">Time</a>
                            <a href="javascript:;" target="_blank" class="nav-link pl-3 pr-0 text-muted text-hover-primary">Contato</a>
                        </div>
                        <!--end::Nav-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                    <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->

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
    <script src="<?= $url ?>assets/js/plugins.bundle.js"></script>
    <script src="<?= $url ?>assets/js/prismjs.bundle.js"></script>
    <script src="<?= $url ?>assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="<?= $url ?>assets/js/widgets.js"></script>
    <script src="<?= $url ?>assets/js/jquery-mask.js"></script>

    <script src="<?= $url ?>assets/js/create-company.js"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
<!--end::Footer-->