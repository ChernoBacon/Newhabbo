<!DOCTYPE html>
<html lang="en">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php'; ?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="404 - Não encontrado">
    <meta name="author" content="PHB">
    <meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
    <!-- Favicon -->
    <link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
    <!-- Title -->
    <title>404 - Não encontrado</title>
    <!-- Bootstrap css-->
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons css-->
    <link href="/assets/plugins/web-fonts/icons.css" rel="stylesheet" />
    <link href="/assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/web-fonts/plugin.css" rel="stylesheet" />
    <!-- Style css-->
    <link href="/assets/css/style.css" rel="stylesheet" />
    <link href="/assets/css/skins.css" rel="stylesheet" />
    <link href="/assets/css/dark-style.css" rel="stylesheet" />
    <link href="/assets/css/colors/default.css" rel="stylesheet" />
    <!-- Color css-->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="/assets/css/colors/color.css" />
    <!-- Select2 css-->
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <!-- Sidemenu css-->
    <link href="/assets/css/sidemenu/sidemenu.css" rel="stylesheet">
    <!-- Switcher css-->
    <link href="/assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="/assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="main-body leftmenu">
    <!-- Loader -->
    <div id="global-loader">
        <img src="/assets/img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->
    <!-- Page -->
    <div class="page main-signin-wrapper bg-primary construction">
        <div class="container ">
            <div class="construction1 text-center details text-white">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <h1 class="tx-140 mb-0">404</h1>
                    </div>
                    <div class="col-lg-12 ">
                        <h1>Ops, a página que você tentou acessar não existe.</h1>
                        <h6 class="tx-15 mt-3 mb-4 text-white-50">Você pode ter digitado incorretamente o endereço ou a página pode ter sido movida.</h6>
                        <a class="btn ripple btn-success text-center" href="/index">Ir para o início</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page -->
    <!-- Back-to-top -->
    <a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>
    <!-- Jquery js-->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Perfect-scrollbar js -->
    <script src="/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!-- Sidemenu js -->
    <script src="/assets/plugins/sidemenu/sidemenu.js"></script>
    <!-- Sidebar js -->
    <script src="/assets/plugins/sidebar/sidebar.js"></script>
    <!-- Select2 js-->
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <!-- Sticky js -->
    <script src="/assets/js/sticky.js"></script>
    <!-- Custom js -->
    <script src="/assets/js/custom.js"></script>
    <!-- Switcher js -->
    <script src="/assets/switcher/js/switcher.js"></script>
</body>

</html>