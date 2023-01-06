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
$page_type = "Pengaturan ";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
	if ($check_user->num_rows == 0) {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$site_config['base_url']."user/logout.php");
	}

	include("../lib/header.php");
	$msg_type = "nothing";

	if (isset($_POST['change_pswd'])) {

		$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
		$post_npassword = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['npassword'], ENT_QUOTES))));
		$post_cnpassword = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['cnpassword'], ENT_QUOTES))));
		if (empty($post_password) || empty($post_npassword) || empty($post_cnpassword)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
		} else if ($post_pin <> $data_user['pin']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Pin Rahasia Anda Salah.";
		} else if ($post_password <> $data_user['password']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Password Lama Salah.";
		} else if (strlen($post_npassword) < 5) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Password baru telalu pendek, minimal 5 karakter.";
		} else if ($post_cnpassword <> $post_npassword) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Konfirmasi password baru tidak sesuai.";	
		} else {
			$update_user = $db->query("UPDATE users SET password = '$post_npassword' WHERE username = '$sess_username'");
			if ($update_user == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Success!</b> Password telah diubah.";
			} else {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Error system.";
			}
		}
	} else if (isset($_POST['change_pin'])) {

		$post_pin = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pin'], ENT_QUOTES))));
		$post_npin = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['npin'], ENT_QUOTES))));
		$post_cnpin = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['cnpin'], ENT_QUOTES))));
		if (empty($post_pin) || empty($post_npin) || empty($post_cnpin)) {
			$msg_type = "pingagal";
			$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
		} else if ($post_pin <> $data_user['pin']) {
			$msg_type = "pingagal";
			$msg_content = "<b>Gagal!</b> Pin Anda Salah.";
		} else if (strlen($post_npin) < 5) {
			$msg_type = "pingagal";
			$msg_content = "<b>Gagal!</b> Pin baru telalu pendek, minimal 5 karakter.";
		} else if ($post_cnpin <> $post_npin) {
			$msg_type = "pingagal";
			$msg_content = "<b>Gagal!</b> Konfirmasi Pin baru tidak sesuai.";	
		} else {
			$update_user = $db->query("UPDATE users SET pin = '$post_npin' WHERE username = '$sess_username'");
			if ($update_user == TRUE) {
				$msg_type = "pinsukses";
				$msg_content = "<b>Success!</b> Pin telah diubah.";
			} else {
				$msg_type = "pingagal";
				$msg_content = "<b>Gagal!</b> Error system.";
			}
		}
	} else if (isset($_POST['change_profile'])) {
		$post_email = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['email'], ENT_QUOTES))));
		$post_nama = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['nama'], ENT_QUOTES))));
		$post_pin = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pin'], ENT_QUOTES))));
        $post_hp = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['hp'], ENT_QUOTES))));
        if (empty($post_pin)) {
			$msg_type = "salah";
			$msg_content = "<b>Gagal!</b> Mohon Masukkan Pin Rahasia Anda";
		} else if ($post_pin <> $data_user['pin']) {
			$msg_type = "salah";
			$msg_content = "<b>Gagal!</b> Pin Rahasia Anda Salah.";
		} else {
    	$update_user = $db->query("UPDATE users SET nama = '$post_nama', email = '$post_email', hp = '$post_hp' WHERE username = '$sess_username'");
		if ($update_user == TRUE) {
			$msg_type = "sukses";
			$msg_content = "<b>Berhasil!</b> Data Berhasil Di Update.";
		} else {
			$msg_type = "salah";
			$msg_content = "<b>Gagal!</b> Data Tidak Sesuai.";
		}
	    }
	} else if (isset($_POST['change_api'])) {
		$set_api_key = random(20);
		$update_user = $db->query("UPDATE users SET api_key = '$set_api_key' WHERE username = '$sess_username'");
		if ($update_user == TRUE) {
			$msg_type = "successs";
			$msg_content = "<b>Berhasil!</b> API Key telah diubah.";
		} else {
			$msg_type = "errorr";
			$msg_content = "<b>Gagal!</b> Error system.";
		}
	} 
	
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
?>  

<div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-account-card-details"></i> Profile</h4><hr>
                            <?php 
                            if ($msg_type == "sukses") {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            } else if ($msg_type == "salah") {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            }
                            ?>
							<form class="form-horizontal" role="form" method="POST">
								<div class="form-group row">
									<label class="col-md-2 control-label">Nama Lengkap</label>
									<div class="col-md-10">
										<input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="<?php echo $data_user['nama']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Nomer Handphone</label>
									<div class="col-md-10">
										<input type="text" name="hp" class="form-control" placeholder="Nomer Handphone Aktiv" value="<?php echo $data_user['hp']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Alamat Email</label>
									<div class="col-md-10">
										<input type="email" name="email" class="form-control" placeholder="Alamat Email" value="<?php echo $data_user['email']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Pin Rahasia Anda</label>
									<div class="col-md-10">
										<input type="password" name="pin" class="form-control" placeholder="Pin Rahasia Anda">
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-12">
                                        <button type="submit" class="btn btn-custom btn-bordred" name="change_profile"><i class="fa fa-send"></i> Update Profile </button>   
                                    </div>
                                </div> 
							</form>
						</div>
					</div>
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-account-key"></i> Password</h4><hr>
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
							<form class="form-horizontal" role="form" method="POST">
								<div class="form-group row">
									<label class="col-md-2 control-label">Password Lama</label>
									<div class="col-md-10">
										<input type="password" name="password" class="form-control" placeholder="Password">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Password Baru</label>
									<div class="col-md-10">
										<input type="password" name="npassword" class="form-control" placeholder="Password Baru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Konfirmasi Password Baru</label>
									<div class="col-md-10">
										<input type="password" name="cnpassword" class="form-control" placeholder="Konfirmasi Password Baru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Pin Rahasia Anda</label>
									<div class="col-md-10">
										<input type="password" name="pin" class="form-control" placeholder="Pin Rahasia Anda">
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8"> 
                                        <button type="submit" class="btn btn-custom btn-bordred" name="change_pswd"><i class="fa fa-send"></i> Update Password </button>   
                                    </div>
                                </div> 
							</form>
						</div>
					</div>
					</div>
                <div class="row">
                	<div class="col-md-6">
                		<div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-lock"></i> Pin Keamanan</h4><hr>
							<form class="form-horizontal" role="form" method="POST">
						    <?php 
                            if ($msg_type == "pinsukses") {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            } else if ($msg_type == "pingagal") {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            }
                            ?>
							<div class="form-group row">
									<label class="col-md-2 control-label">Pin Lama</label>
									<div class="col-md-10">
										<input type="password" name="pin" class="form-control" placeholder="Pin Lama">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Pin Baru</label>
									<div class="col-md-10">
										<input type="password" name="npin" class="form-control" placeholder="Pin Baru">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Konfirmasi Pin Baru</label>
									<div class="col-md-10">
										<input type="password" name="cnpin" class="form-control" placeholder="Konfirmasi Pin Baru">
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8"> 
                                        <button type="submit" class="btn btn-custom btn-bordred" name="change_pin"><i class="fa fa-send"></i> Update Pin </button>   
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                		<div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-fire"></i> API</h4><hr>
							<form class="form-horizontal" role="form" method="POST">
						    <?php 
                            if ($msg_type == "successs") {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            } else if ($msg_type == "errorr") {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            }
                            ?>
								<div class="form-group row">
									<label class="col-md-2 control-label">API Key</label>
									<div class="col-md-10">
										<input type="text" class="form-control" value="<?php echo $data_user['api_key']; ?>" readonly>
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8"> 
                                        <button type="submit" class="btn btn-custom btn-bordred" name="change_api"><i class="fa fa-send"></i> Ubah API Key </button>   
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>