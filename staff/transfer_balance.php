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
$page_type = "Transfer Saldo";

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
			$post_balance = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['balance'], ENT_QUOTES))));

			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = $checkdb_user->fetch_array(MYSQLI_ASSOC);
			if (empty($post_username) || empty($post_balance)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> User $post_username tidak ditemukan.";
			} else if ($post_balance < 1000) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Minimal transfer adalah Rp 1000.";
			} else if ($data_user['balance'] < $post_balance) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Saldo Anda tidak mencukupi untuk melakukan transfer dengan jumlah tersebut.";
			} else {
				$update_user = $db->query("UPDATE users SET balance = balance-$post_balance WHERE username = '$sess_username'"); // cut sender
				$update_user = $db->query("UPDATE users SET balance = balance+$post_balance WHERE username = '$post_username'"); // send receiver
				$insert_tf   = $db->query("INSERT INTO transfer_balance (sender, receiver, quantity, date) VALUES ('$sess_username', '$post_username', '$post_balance', '$date')");
				$update_user = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$post_username', 'Plus', 'Deposit', '$post_balance', 'Deposit (Transfer Saldo)', '$date', '$time')");
				$update_user = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '$post_balance', 'Deposit (Transfer Saldo)', '$date', '$time')");
				if ($insert_tf == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Transfer saldo berhasil.<br /><b>Pengirim:</b> $sess_username<br /><b>Penerima:</b> $post_username<br /><b>Jumlah Transfer:</b> Rp ".number_format($post_balance,0,',','.')." Saldo";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
				}
			}
		}

	include("../lib/header.php");
?>
							
                <div class="row">
                    <div class="offset-lg-2 col-lg-8">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Transfer Saldo
                            </h4><hr>
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
									<label class="col-md-2 control-label">Username Penerima</label>
									<div class="col-md-10">
										<input type="text" name="username" class="form-control" placeholder="Username">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Jumlah Transfer</label>
									<div class="col-md-10">
										<input type="number" name="balance" min="5000" class="form-control" placeholder="Jumlah Transfer">
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