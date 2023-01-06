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
$page_type = "Masuk";

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
    
    if (isset($_POST['login'])) {
		$post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
		$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
		if (empty($post_username) || empty($post_password)) {
			$msg_type = "error";
			$msg_content = '<b>Gagal!</b> Mohon mengisi semua input.';
		} else {
			$check_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			if ($check_user->num_rows == 0) {
				$msg_type = "error";
				$msg_content = '<b>Gagal!</b> Username atau password salah.';
			} else {
				$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
				if ($post_password <> $data_user['password']) {
					$msg_type = "error";
					$msg_content = '<b>Gagal:</b> Username atau password salah.';
				} else if ($data_user['status'] == "Suspended") {
					$msg_type = "error";
					$msg_content = '<b>Gagal:</b> Akun Suspended.';
					
				} else {
					$_SESSION['user'] = $data_user;
					$username = $data_user['username'];
					$addres = $_SERVER['REMOTE_ADDR'];
					$update = $db->query("INSERT INTO login_history (username, adress, date, time) VALUES ('$username', '$addres', '$date', '$time')");
					$update = $db->query("UPDATE users SET read_news = 'False' WHERE username = '$username'");
					header("Location: ".$site_config['base_url']);
				}
			}
		}
	}

include("../lib/header.php");
?> 
                <div class="row">
                    <div class="offset-lg-4 col-lg-4">
                        <div class="card-box">
                            <div class="text-center">
                                <h4 class="text-uppercase font-bold">Masuk</h4>
                            </div>
                            <?php
                            if ($msg_type == "error") {
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
									    <input type="text" name="username" class="form-control" placeholder="Username">
								    </div>
							    </div>
							    <div class="form-group row">
								    <div class="col-md-12">
									    <input type="password" name="password" class="form-control" placeholder="Password">
								    </div>
							    </div><br>
								<div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-custom btn-bordred btn-block" name="login"> <i class="fa fa-send"></i> Masuk </button>   
                                    </div>
                                </div>
                                <div class="form-group m-t-30 m-b-0">
                                    <div class="col-sm-12">
                                        <a href="<?php echo $site_config['base_url']; ?>user/forgot-password" class="text-muted"><center><i class="fa fa-lock m-r-5"></i> Lupa kata sandi anda?</center></a>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="text-muted"><i class="fa fa-user m-r-5"></i> Belum memiliki akun?<a href="<?php echo $site_config['base_url']; ?>user/register" class="text-primary m-l-5"><b>Daftar</b></a></p>
                            </div>
                        </div>
                        
      	<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
						
                    </div>
                </div>
                        
                    
						
<?php
include("../lib/footer.php");
?>