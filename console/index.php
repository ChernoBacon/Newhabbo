<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';

if (isset($_SESSION['id_painel'])) {
    header("Location: /principal");
}

if (empty($_SESSION['count_login_error'])) {
    $_SESSION['count_login_error'] = 0;
}

function LoginError($mensagem)
{
    global $lingua;
    echo '<div class="alert alert-solid-danger mg-b-0" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
    <span aria-hidden="true">&times;</span></button>
    <strong>'.$lingua['generico-erro'].'</strong> ' . $mensagem . '
    </div>
    <br>';
}

if (isset($_POST['login'])) {

    if ($_SESSION['count_login_error'] >= 10) {
        $segundosblock = time() - $_SESSION['last_login_error'];
        if ($segundosblock >= 600) {
            $continua = true;
        } else {
            $faltasegundos = $segundosblock - 600;
            $mensagem_erro = str_replace("%segundos%",  str_replace("-", "", $faltasegundos), $lingua['index-erro-cooldown']);
            $continua = false;
        }
    } else {
        $continua = true;
    }
    if ($continua) {
        if (empty($_POST['email']) && $config['TipoLogin'] == "email")
            $mensagem_erro = $lingua['index-erro-email'];
        else if (empty($_POST['usuario']) && $config['TipoLogin'] == "usuario")
            $mensagem_erro = $lingua['index-erro-usuario'];
        else if (empty($_POST['senha']))
            $mensagem_erro = $lingua['index-erro-senha'];
        else {

            $senha = $_POST['senha'];

            if ($config['TipoLogin'] == "email") {
                $stmt = $db->prepare("SELECT * FROM users WHERE mail = :mail");
                $stmt->bindValue(':mail', $_POST['email']);
                $stmt->execute();
            } else {
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindValue(':username', $_POST['usuario']);
                $stmt->execute();
            }

            if ($stmt->RowCount() == 1) {

                $userdb = $stmt->fetch();

                $checkPermission = $db->prepare("SELECT login FROM phb_hk_permissoes WHERE id = :rankid");
                $checkPermission->bindValue(':rankid', $userdb['rank']);
                $checkPermission->execute();
                $permission = $checkPermission->fetch();

                $passwordDb = $userdb['password'];
                if (PHBTools::check_pass($senha, $passwordDb)) {
                    if ($checkPermission->rowCount() != 1) {
                        $mensagem_erro = $lingua['index-erro-rank'];
                    } else if ($permission['login'] == '0') {
                        $mensagem_erro = $lingua['index-erro-rank'];
                    } else {
                        $_SESSION['id_painel'] = $userdb['id'];
                        PHBLogger::AddLog($lingua['index-login-log']);
                        $_SESSION['count_login_error'] = 0;
                        $_SESSION['last_login_error'] = 0;
                        header("Location: /principal");
                    }
                } else {
                    $_SESSION['last_login_error'] = time();
                    $_SESSION['count_login_error'] = $_SESSION['count_login_error'] + 1;
                    $mensagem_erro = $lingua['index-erro-dados'] . " (" . $_SESSION['count_login_error'] . ")";
                }
            } else {
                $_SESSION['last_login_error'] = time();
                $_SESSION['count_login_error'] = $_SESSION['count_login_error'] + 1;
                $mensagem_erro = $lingua['index-erro-dados'] . " (" . $_SESSION['count_login_error'] . ")";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="<?= $config['NomeHotel']; ?> - <?= $lingua['index-titulo']; ?>">
    <meta name="author" content="PHB">
    <meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
    <!-- Favicon -->
    <link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
    <!-- Title -->
    <title><?= $config['NomeHotel']; ?> - <?= $lingua['index-titulo']; ?></title>
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
    <div class="page main-signin-wrapper">
        <!-- Row -->
        <div class="row signpages text-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row row-sm">
                        <div class="col-lg-12 col-xl-12 col-xs-12 col-sm-12 login_form">
                            <div class="container-fluid">
                                <div class="row row-sm">
                                    <div class="card-body mt-2 mb-2">
                                        <div class="clearfix"></div>
                                        <form method="post">
                                            <h5 class="text-left mb-2"><?= $lingua['index-texto1']; ?></h5>
                                            <p class="mb-4 text-muted tx-13 ml-0 text-left"><?= $lingua['index-texto2']; ?></p>
                                            <?php if (isset($mensagem_erro)) {
                                                LoginError($mensagem_erro);
                                            } ?>
                                            <?php
                                            if ($config['TipoLogin'] == "email") { ?>
                                                <div class="form-group text-left">
                                                    <label><?= $lingua['index-email']; ?></label>
                                                    <input class="form-control" placeholder="<?= $lingua['index-email-placeholder']; ?>" name="email" type="mail">
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-group text-left">
                                                    <label><?= $lingua['index-usuario']; ?></label>
                                                    <input class="form-control" placeholder="<?= $lingua['index-usuario-placeholder']; ?>" name="usuario" type="text">
                                                </div>
                                            <?php } ?>
                                            <div class="form-group text-left">
                                                <label><?= $lingua['index-senha']; ?></label>
                                                <input class="form-control" placeholder="<?= $lingua['index-senha-placeholder']; ?>" name="senha" type="password">
                                            </div>
                                            <button class="btn ripple btn-main-primary btn-block" name="login"><?= $lingua['index-botao-login']; ?></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page -->
    <!-- Jquery js-->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Select2 js-->
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <!-- Custom js -->
    <script src="/assets/js/custom.js"></script>
</body>

</html>