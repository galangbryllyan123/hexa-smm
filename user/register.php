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

session_start();
require("../mainconfig.php");
$page_type = "Daftar";

function dapetin($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $data = curl_exec($ch);
        curl_close($ch);
                return json_decode($data, true);
}

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
	
	if ($check_user->num_rows !== 0) {
		header("Location: ".$site_config['base_url']);
	}
}
	if (isset($_POST['daftar'])) {
	    $post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
		$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
		$post_repassword = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['repassword'], ENT_QUOTES))));
		$post_email = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['email'], ENT_QUOTES))));
		$post_nama = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['nama'], ENT_QUOTES))));
        $post_hp = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['hp'], ENT_QUOTES))));

		$secret_key = '6Lf9wbIUAAAAALkSlSnxHHMyfBfPmjNEKLBrLiEq'; //masukkan secret key-nya berdasarkan secret key masig-masing saat create api key nya
		$captcha = $_POST['g-recaptcha-response'];
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $captcha;
		$recaptcha = dapetin($url);		
		
		$check_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
		$check_email = $db->query("SELECT * FROM users WHERE email = '$post_email'");
	    $check_hp = $db->query("SELECT * FROM users WHERE hp = '$post_hp'");
			
		if (empty($post_username) || empty($post_password) || empty($post_repassword) || empty($post_email) || empty($post_hp)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
		} else if (mysqli_num_rows($check_user) > 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Username telah terdaftar.";
		} else if (mysqli_num_rows($check_email) > 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Email telah terdaftar.";
		} else if (mysqli_num_rows($check_hp) > 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Nomer Handphone telah terdaftar.";
		} else if ($recaptcha['success'] == false) {
			$msg_type = "error";
			$msg_content = '<b>Gagal!</b> Mohon mengisi captcha.';	
		} else if (strlen($post_username) > 20) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Username Maksimal 20 karakter.";
		} else if (strlen($post_password) > 20) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Password Maksimal 20 karakter.";
		} else if (strlen($post_username) < 5) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Username Minimal 5 karakter.";
		} else if (strlen($post_password) < 5) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Password Minimal 5 karakter.";
		} else if ($post_password <> $post_repassword) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Password tidak sesuai.";
		} else if ($_POST['accept_terms'] !== "true") {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Silahkan setujui ketentuan layanan kami sebelum mendaftar.";
		} else {
			    $pin=random_number(6);
    		    $pesannya="Hexazor Pedia : $post_nama Terimakasih Telah Mendaftar | Security Pin Anda Adalah: $pin";
    $postdata = "api_key=hsAfNOPkRBjeFcwc7Zmv&pesan=$pesannya&nomer=$post_hp";
    $apibase= "https://serverh2h.net/sms_gateway";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://serverh2h.net/sms_gateway");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $json_result = json_decode($chresult, true);

			    $post_api = random(20);
				$insert_user = mysqli_query($db, "INSERT INTO users (nama, username, email, password, pin, balance, level, registered, status, api_key, uplink, hp) VALUES ('$post_nama', '$post_username', '$post_email', '$post_password', '$pin', '0', 'Member', '$date', 'Active', '$post_api', 'Server', '$post_hp')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Pengguna telah didaftarkan.<br />";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> System Error.";
				}
			}
		}



include_once("../lib/header.php");
?>

                <div class="row">
                    <div class="offset-lg-4 col-lg-4">
                        <div class="card-box">
                            <div class="text-center">
                                <h4 class="text-uppercase font-bold">Daftar</h4>
                            </div>
                            <?php 
                            if ($msg_type == "success") {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            } else if ($msg_type == "error") {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            }
                            ?>
						    <form class="form-horizontal m-t-20" role="form" method="POST">
								<div class="form-group row">
									<div class="col-md-12">
									    <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap">
									</div>
								</div>
								<div class="form-group row">
									
									<div class="col-md-12">
										<input type="text" name="username" class="form-control" placeholder="Username">
									</div>
								</div>
								<div class="form-group row">
									
									<div class="col-md-12">
										<input type="text" name="email" class="form-control" placeholder="Email">
									</div>
								</div>
								<div class="form-group row">
									
									<div class="col-md-12">
										<input type="text" name="hp" class="form-control" placeholder="Nomor Handphone Aktiv">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-12">
										<input type="password" name="password" class="form-control" placeholder="Password">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-12">
										<input type="password" name="repassword" class="form-control" placeholder="Ulangi Password">
									</div>
								</div>
								
								<div class="form-group row">
									<div class="col-md-12">
										<div class="g-recaptcha" data-sitekey="6Lf9wbIUAAAAAE9VSNWYpo_sumhovQncS7fEo3Da"></div>
										<small>Memastikan bahwa anda bukan robot.</small>
									</div>
								</div>								
								<div class="form-group row">
									<div class="col-md-12">
										<div class="checkbox checkbox-custom">
                                            <input id="checkbox1" name="accept_terms" value="true" type="checkbox">
                                            <label for="checkbox1">
                                             	Saya setuju dengan <a href="<?php echo $site_config['base_url']; ?>page/tos"> syarat dan ketentuan </a>
                                            </label>
                                        </div>
									</div>
								</div><br>
								<div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-custom btn-bordred btn-block" name="daftar">Daftar </button>   
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="text-muted"><i class="fa fa-user m-r-5"></i> Sudah memiliki akun?<a href="<?php echo $site_config['base_url']; ?>user/login" class="text-primary m-l-5"><b>Masuk</b></a></p>
                            </div>
                        </div>
                    </div>
                </div>
						
<?php
include("../lib/footer.php");
?>