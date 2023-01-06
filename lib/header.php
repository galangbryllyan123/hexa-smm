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
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-4108989395706957",
          enable_page_level_ads: true
     });
</script>

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
                                <span class="nav-link"><b>Saldo: Rp <?php echo number_format($data_user['balance'],0,',','.'); ?></b></span>
                            </li>

                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo $site_config['base_url']; ?>assets/images/profile.png" alt="user" class="rounded-circle"> 
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <?php
                        if ($data_user['level'] != "Agen" AND $data_user['level'] != "Developers") {
                        ?>
                                    <a href="<?php echo $site_config['base_url']; ?>user/settings" class="dropdown-item notify-item">
                                        <i class="ti-settings m-r-5"></i> Pengaturan Akun
                                    </a>
                                    
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>user/upgrade-akun" class="dropdown-item notify-item">                            
                                        <i class="fa fa-level-up m-r-5"></i> Upgrade Level
                                    </a>
                                   
                                    <a href="<?php echo $site_config['base_url']; ?>logs/login-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> History Login</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>logs/balance-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> Balance History</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>user/logout" class="dropdown-item notify-item">
                                        <i class="ti-power-off m-r-5"></i> Keluar
                      
                                    <a href="<?php echo $site_config['base_url']; ?>user/settings" class="dropdown-item notify-item">
                                        <i class="ti-settings m-r-5"></i> Pengaturan Akun
                                    </a>
                                    
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>user/upgrade-akun" class="dropdown-item notify-item">                            
                                        <i class="fa fa-level-up m-r-5"></i> Upgrade Level
                                    </a>
                                   
                                    <a href="<?php echo $site_config['base_url']; ?>logs/login-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> History Login</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>logs/balance-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> Balance History</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>user/logout" class="dropdown-item notify-item">
                                        <i class="ti-power-off m-r-5"></i> Keluar
                                    </a>
                                    <?php
                                    }
                                    ?>
                                    
                                    <?php
                        if ($data_user['level'] != "Member") {
                        ?>
                                    
                      
                                    <a href="<?php echo $site_config['base_url']; ?>user/settings" class="dropdown-item notify-item">
                                        <i class="ti-settings m-r-5"></i> Pengaturan Akun
                                    </a>
                                   
                                    <a href="<?php echo $site_config['base_url']; ?>logs/login-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> History Login</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>logs/balance-history" class="dropdown-item notify-item"><i class="ti-settings m-r-5"></i> Balance History</a>
                                    
                                    <a href="<?php echo $site_config['base_url']; ?>user/logout" class="dropdown-item notify-item">
                                        <i class="ti-power-off m-r-5"></i> Keluar
                                    </a>
                                    <?php
                                    }
                                    ?>

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
                        <?php
                        if (isset($_SESSION[ 'user'])) {
                        ?>
                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>"><i class="mdi mdi-home"></i> <span> Dashboard </span> </a>
                            </li>
                        <?php
                        if ($data_user['level'] != "Member" AND $data_user['level'] != "Agen") {
                        ?>
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-account"></i> <span> Staff Menu </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>staff/add_user">Tambah Pengguna</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>staff/transfer_balance">Transfer Saldo</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($data_user[ 'level'] == "Agen" ) {
                        ?>
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-account"></i> <span> Agen Menu </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>staff/add_user">Tambah Pengguna</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>staff/transfer_balance">Transfer Saldo</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($data_user[ 'level'] == "Developers") {
                        ?>    
                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>admin"><i class="mdi mdi-view-dashboard"></i> <span> Admin Page </span> </a>
                            </li>
                        <?php
                        }
                        ?>    

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-instagram"></i> <span> Sosial Media </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>order/new">Buat Pesanan</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>order/history">Riwayat Pesanan</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>services">List Harga</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>api/doc">Api Dokumentasi</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cart"></i> <span> Pulsa Dan PPOB </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>pulsa/order">Buat Pesanan</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>pulsa/order_history">Riwayat Pesanan</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>pulsa/services">List Harga</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>pulsa/apidoc">Api Dokumentasi</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cart"></i> <span> SMS Gateway </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>sms/index">Buat Pesanan</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>sms/history">Riwayat Pesanan</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>


                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-wallet"></i> <span> Tambah Saldo </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>deposit/create">Tambah</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>deposit/withdraw">Withdraw Point</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>deposit/history">Riwayat Deposit</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <?php
                            $check_ticket = mysqli_query($db, "SELECT * FROM tickets WHERE status = 'Responded' AND user = '$sess_username'");
                            ?>

                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>ticket/new"><i class="mdi mdi-comment-text-outline"></i> <span> Tiket </span><?php if (mysqli_num_rows($check_ticket) !== 0) { ?><span class="badge badge-primary"><?php echo mysqli_num_rows($check_ticket); ?></span><?php } ?> </a>
                            </li>

                            
                                            
                                        
                            
                        <?php
                        } else {
                        ?>    
                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>user/login"><i class="mdi mdi-login"></i> <span> Masuk </span> </a>
                            </li>

                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>user/register"><i class="mdi mdi-account-plus"></i> <span> Daftar </span> </a>
                            </li>
                            
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-format-list-bulleted"></i> <span> Daftar Harga </span> </a>
                                <ul class="submenu">
                                    <li>
                                        <ul>
                                            <li><a href="<?php echo $site_config['base_url']; ?>pulsa/services">Pulsa</a></li>
                                            <li><a href="<?php echo $site_config['base_url']; ?>services">Media Sosial</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>page/contact"><i class="mdi mdi-phone"></i> <span> Kontak </span> </a>
                            </li>

                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>page/faq"><i class="mdi mdi-help-circle-outline"></i> <span> Pertanyaan Umum </span> </a>
                            </li>

                            <li class="has-submenu">
                                <a href="<?php echo $site_config['base_url']; ?>page/tos"><i class="mdi mdi-information-outline"></i> <span> Ketentuan Layanan </span> </a>
                            </li>

                        <?php
                        }
                        ?>    
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    
    <div class="modal fade bs-example-modal-lg" id="Faq" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="fa fa-question"></i> Pertanyaan Umum</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	    1. Bagaimana cara mendaftar?<br>
                                Silahkan menghubungi Admin untuk mendaftar, silahkan Klik tombol KONTAK untuk melihat kontak Admin.<br><br><hr>
                                        
                            2. Bagaimana cara membuat pesanan?<br>
                                Silahkan menuju halaman PEMESANAN       untuk membuat pesanan, kemudian         bacalah informasi pada halaman          tersebut sebelum membuat pesanan        baru.<br><br><hr>
                                       
                            3. Bagaimana cara deposit/isi saldo?<br>
                                Silahkan menuju halaman deposit baru    untuk membuat permintaan deposit,       kemudian bacalah informasi pada         halaman tersebut sebelum membuat        deposit baru. Kami menyediakan dua      jenis pilihan untuk melakukan deposit    yaitu: Deposit manual dan deposit       otomatis.<br>

									<div class="modal-footer">
                                        <button type="reset" class="btn btn-secondary btn-bordred waves-effect" data-dismiss="modal"> Cancel</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade bs-example-modal-lg" id="Kontak" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="fa fa-phone"></i> Kontak Admin</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	    <p>
                            	    Anda dapat mengajukan pertanyaan, meminta bantuan, memberikan saran, dan komplain transaksi kepada CS kami setiap hari pada jam jam kerja. Kami akan berusaha dengan segera menjawab pertanyaan dari Anda di jam kerja. Pertanyaan diluar jam kerja tetap akan dijawab di jam kerja. Berikut jam kerja CS <b><?php echo $data_website['title']; ?></b>:
                            	</p><br>
                            	<ul>
                                    <li>Senin - Jumat : 8.00 - 22.00 WIB</li>
                                    <li>Sabtu - Minggu : 8.00 - 20.00 WIB</li>
                                    <li>Istirahat : 12.00 - 12.45 WIB</li>
                                </ul><hr>
                                <b>Berikut kontak yang dapat Anda hubungi jika ada kendala:</b><br>
                                <ul>
                                	<li>Line: <a href="http://line.me/ti/p/~<?php echo $data_website['line']; ?>" target="_blank"><b>Line</b></a></li>
                                	<li>Instagram: <a href="https://instagram.com/rifqi.071"><b>Instagram</b></a></li>
                                	<li>Facebook: <a href="<?php echo $data_website['facebook']; ?>"><b>Facebook (Rifqi Resfether)</b></a></li>
                              
                                	
                                	<li>WhatsApp: <a href="https://api.whatsapp.com/send?phone=<?php echo $data_website['whatsapp']; ?>" target="_blank"><b>Whatsapp (Rifqi Resfether)</b></a></li>
                                	
                                	Anda dapat melakukan Komplain/Deposit/Pertanyaan/Info Teknis API/Keluhan atas kinerja CS dengan menghubungi kontak kami diatas!
                                </ul><hr>
                                <b>Prosedur jika ingin melakukan komplain kepada CS kami:</b>
                                <ul>
                                	<li>Jika komplain transaksi pesanan belum masuk. Silakan sebutkan id pesanan, data target, dan tipe pesanan (semisal: followers, like, dsb.).</li>
                                    <li>Usahakan untuk komplain via Open Tiket, Facebook, atau LINE CS karena lebih cepat responnya daripada komplain via SMS.</li>
                                    <li>Jangan memberitahukan / menyebutkan Password Anda kepada siapapun termasuk CS. Karena kami tidak membutuhkan data tersebut.</li>
                                </ul>

									<div class="modal-footer">
                                        <button type="reset" class="btn btn-secondary btn-bordred waves-effect" data-dismiss="modal"> Cancel</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade bs-example-modal-lg" id="KetLay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="fa fa-info"></i> Ketentuan Layanan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	    <p>Layanan yang disediakan oleh <?php echo $cfg_webname; ?> telah ditetapkan kesepakatan-kesepakatan berikut.</p>
										<p><b>1. Umum</b>
										<br />Dengan mendaftar dan menggunakan layanan <?php echo $cfg_webname; ?>, Anda secara otomatis menyetujui semua ketentuan layanan kami. Kami berhak mengubah ketentuan layanan ini tanpa pemberitahuan terlebih dahulu. Anda diharapkan membaca semua ketentuan layanan kami sebelum membuat pesanan.
										<br />Penolakan: <?php echo $cfg_webname; ?> tidak akan bertanggung jawab jika Anda mengalami kerugian dalam bisnis Anda.
										<br />Kewajiban: <?php echo $cfg_webname; ?> tidak bertanggung jawab jika Anda mengalami suspensi akun atau penghapusan kiriman yang dilakukan oleh Instagram, Twitter, Facebook, Youtube, dan lain-lain.
										<br /><b>2. Layanan</b>
										<br /><?php echo $cfg_webname; ?> hanya digunakan untuk media promosi sosial media dan membantu meningkatkan penampilan akun Anda saja.
										<br /><?php echo $cfg_webname; ?> tidak menjamin pengikut baru Anda berinteraksi dengan Anda, kami hanya menjamin bahwa Anda mendapat pengikut yang Anda beli.
										<br /><?php echo $cfg_webname; ?> tidak menerima permintaan pembatalan/pengembalian dana setelah pesanan masuk ke sistem kami. Kami memberikan pengembalian dana yang sesuai jika pesanan tida dapat diselesaikan.</p>

									<div class="modal-footer">
                                        <button type="reset" class="btn btn-secondary btn-bordred waves-effect" data-dismiss="modal"> Cancel</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
