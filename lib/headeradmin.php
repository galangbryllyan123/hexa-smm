<?php

/**
 * Code By : Mahiruddin a.k.a Mhrdpy.NET
 * Date Edit : 16 - 12 - 2018
 * Dont Edit Anything If You Don't Know About Script
 * SMM Panel Script - Mhrdpy.NET
 * Demo => https://scriptsmm.web.id/ ( User & Pass : admin )
 * Contact Person :
                => Whatsapp  : 0895 3378 26740
                => Facebook  : Mahir Depay (https://facebook.com/hirpayzzz)
                => Instagram : mahirdpy_   (https://instagram.com/mahirdpy_) 
                => Email     : mahirdpy@gmail.com             
  __  __ _             _               _   _ ______ _______ 
 |  \/  | |           | |             | \ | |  ____|__   __|
 | \  / | |__  _ __ __| |_ __  _   _  |  \| | |__     | |   
 | |\/| | '_ \| '__/ _` | '_ \| | | | | . ` |  __|    | |   
 | |  | | | | | | | (_| | |_) | |_| |_| |\  | |____   | |   
 |_|  |_|_| |_|_|  \__,_| .__/ \__, (_)_| \_|______|  |_|   
                        | |     __/ |                       
                        |_|    |___/                         
**/


$check_website = $db->query("SELECT * FROM website WHERE id ='1'");
$data_website = $check_website->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $data_website['title']; ?> | <?php echo $page_type; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="<?php echo $data_website['description']; ?>" name="description" />
        <meta content="<?php echo $data_website['keyword']; ?>" name="keyword" />
        <meta content="MHRDPY.NET" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="<?php echo $site_config['base_url']; ?>assets/images/favicon.ico">
        <link rel="stylesheet" href="<?php echo $site_config['base_url']; ?>plugins/morris/morris.css">
        <link href="<?php echo $site_config['base_url']; ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $site_config['base_url']; ?>plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $site_config['base_url']; ?>plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $site_config['base_url']; ?>plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo $site_config['base_url']; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $site_config['base_url']; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $site_config['base_url']; ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/modernizr.min.js"></script>

    </head>

    <body>

        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <div class="logo">
                        <a href="<?php echo $site_config['base_url']; ?>" class="logo">
                            <span class="logo-small"><i class="fa fa-globe"></i></span>
                          <span class="logo-large"><i class="fa fa-globe"></i> <?php echo $data_website['logo_text']; ?></span>
                        </a>

                    </div>

                    <div class="menu-extras topbar-custom">
                        <ul class="list-inline float-right mb-0">
                            <li class="menu-item list-inline-item">
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </li>

                            <?php
                            if (isset($_SESSION[ 'user'])) {
                            ?>

                            <li style="padding: 0 10px;">
                                <span class="nav-link"><a href="<?php echo $site_config['base_url']; ?>" class="btn btn-secondary btn-bordred btn-sm"><i class="fa fa-home"></i> Home</a></span>
                            </li>

                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo $site_config['base_url']; ?>assets/images/profile.png" alt="user" class="rounded-circle"> 
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <a href="<?php echo $site_config['base_url']; ?>user/settings" class="dropdown-item notify-item">
                                        <i class="ti-settings m-r-5"></i> Pengaturan Akun
                                    </a>
                                    <a href="<?php echo $site_config['base_url']; ?>user/logout" class="dropdown-item notify-item">
                                        <i class="ti-power-off m-r-5"></i> Keluar
                                    </a>

                                </div>
                            </li>
                            <?php
                            }
                            ?>

                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>admin"><i class="mdi mdi-view-dashboard"></i> <span> Dashboard </span> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>


        <div class="wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                    <li class="breadcrumb-item"><a href="<?php echo $site_config['base_url']; ?>"><?php echo $data_website['title']; ?></a></li>
                                    <li class="breadcrumb-item active"><?php echo $page_type; ?></li>
                                </ol>
                            </div>
                            <h4 class="page-title"><?php echo $page_type; ?></h4>
                        </div>
                    </div>
                </div>   
