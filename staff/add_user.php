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
$page_type = "Tambah Pengguna";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['level'] == "Member") {
		header("Location: ".$site_config['base_url']."user/logout");
	} else {
	    
	    if (isset($_POST['add'])) {

			$post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
			$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
            $post_level = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['level'], ENT_QUOTES))));
            $post_hp = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['hp'], ENT_QUOTES))));


            $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
            $data_website = $check_website->fetch_array(MYSQLI_ASSOC);
            
            // Check Price
            if (mysqli_real_escape_string($db, $_POST['level'] == "Member")) {
            	$post_price = "0";
            	$post_balance = "0";
            } else if (mysqli_real_escape_string($db, $_POST['level'] == "Agen")) {
            	$post_price = "30000";
            	$post_balance = "10000";
            } else if (mysqli_real_escape_string($db, $_POST['level'] == "Reseller")) {
            	$post_price = "100000";
            	$post_balance = "50000";
	        } else if (mysqli_real_escape_string($db, $_POST['level'] == "")) {
	        	$post_price = "150000";
	        	$post_balance = "75000";
	        }

			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = $checkdb_user->fetch_array(MYSQLI_ASSOC);
			if (empty($post_username) || empty($post_password) || empty($post_level)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if ($checkdb_user->num_rows > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Username $post_username sudah terdaftar dalam database.";
			} else if ($data_user['balance'] < $post_price) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Saldo Anda tidak mencukupi untuk melakukan pendaftaran.";
			} else {
				$post_api = random(20);
				$update_user = $db->query("UPDATE users SET balance = balance-$post_price WHERE username = '$sess_username'");
				$update_user = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '$post_price', 'Tambah Pengguna', '$date', '$time')");
				$insert_user = $db->query("INSERT INTO users (username, password, balance, level, registered, status, api_key, uplink, hp) VALUES ('$post_username', '$post_password', '$post_balance', '$post_level', '$date', 'Active', '$post_api', '$sess_username', '$post_hp')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "
					<b>Berhasil!</b> Pengguna telah ditambahkan.<br /><b>Username :</b> $post_username<br /><b>Password :</b> $post_password<br /><b>Level :</b> $post_level<br /><b>Saldo :</b> Rp ".number_format($post_balance,0,',','.')." <br><b>Nomor Hp</b> : $post_hp <br><b>Link : </b> https://hexazor-smm.com. <br />";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		}

	include("../lib/header.php");
?>
							
                <div class="row">
                    <div class="offset-lg-2 col-lg-8">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-account-plus"></i> Tambah Pengguna</h4><hr>
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
		                            <label class="col-md-2 control-label">Level</label>
		                            <div class="col-md-10">
				                        <select class="form-control" name="level" id="level" required>
				                            <option value="default">Silahkan Pilih Salah Satu</option>
                                            <?php if ($data_user['level'] == "Member"){ ?>
                                            <?php } else if ($data_user['level'] == "Agen"){ ?>
				                            <option value="Member">Member</option>
                                            <?php } else if ($data_user['level'] == "Reseller") { ?>
                                            <option value="Member">Member</option>
				                            <option value="Agen">Agen</option>
                                            <?php } else if ($data_user['level'] == "Admin") { ?>
                                            <option value="Member">Member</option>
				                            <option value="Agen">Agen</option>
				                            <option value="Reseller">Reseller</option>
                                            <?php } else if ($data_user['level'] == "Developers") { ?>
                                            <option value="Member">Member</option>
				                            <option value="Agen">Agen</option>
				                            <option value="Reseller">Reseller</option> 
				                            <option value="Admin">Admin</option> 
				                            <?php } ?>
				                        </select>
		                            </div>
		                        </div>
		                        <div id="note"></div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Username</label>
									<div class="col-md-10">
										<input type="text" name="username" class="form-control" placeholder="Username">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Password</label>
									<div class="col-md-10">
										<input type="text" name="password" class="form-control" placeholder="Password">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Nomor HP</label>
									<div class="col-md-10">
										<input type="text" name="hp" class="form-control" placeholder="Nomor HP">
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8">
                                        <button type="reset" class="btn btn-secondary btn-bordred"><i class="fa fa-refresh"></i> Reset </button>  
                                        <button type="submit" class="btn btn-custom btn-bordred" name="add"><i class="fa fa-send"></i> Submit </button>   
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
<?php
	include("../lib/footer.php");
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>