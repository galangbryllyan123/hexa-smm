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
$page_type = "Upgrade Akun";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
	} else {
	    
	    if (isset($_POST['upgrade'])) {
            
        	$post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
			$post_level = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['level'], ENT_QUOTES))));
          
            $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
            $data_website = $check_website->fetch_array(MYSQLI_ASSOC);
            
            // Check Price
            if (mysqli_real_escape_string($db, $_POST['level'] == "Agen")) {
            	$post_price = "50000";
            }

			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = $checkdb_user->fetch_array(MYSQLI_ASSOC);
			 if ($post_level != "Agen") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Input tidak sesuai (1).";
			} else if ($data_user['balance'] < $post_price) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Saldo Anda tidak mencukupi untuk melakukan upgrade.";
			} else {
				$update_user = $db->query("UPDATE users SET balance = balance-$post_price, level = '$post_level' WHERE username = '$sess_username'");
				$update_user = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Upgrade Akun', '$post_price', 'Upgrade Akun Ke Level $post_level', '$date', '$time')");
				if ($update_user == TRUE) {
					$msg_type = "success";
					$msg_content = "
					<b>Berhasil!</b> Akun Anda Telah Di Upgrade Ke Level $post_level. <br />";
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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="fa fa-level-up"></i> Upgrade Level</h4><hr>
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
                                    <label class="col-md-2 control-label">Level Sekarang</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Lvl</span>
                                            </div>
                                           <input type="text" class="form-control" value="<?php echo $data_user['level']; ?>" readonly>
                                            </div>
                                    </div>                                    
                                </div>
							    <div class="form-group row">
		                            <label class="col-md-2 control-label">Level</label>
		                            <div class="col-md-10">
				                        <select class="form-control" name="level" id="level" required>
				                            <option value="default">Silahkan Pilih Salah Satu</option>
				                            <?php if ($data_user['level'] == "Member"){ ?>
				                            <option value="Agen">Agen</option>
				                         <?php } ?>
				                        </select>
		                            </div>
		                        </div>
		                        <div id="note_up"></div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8">
                                        <button type="submit" class="btn btn-custom btn-bordred" name="upgrade"><i class="fa fa-send"></i> Upgrade </button>   
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