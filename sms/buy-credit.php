<?php

session_start();
require("../mainconfig.php");
$page_type = "Isi Credit SMS";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
	} else {
	    
	    if (isset($_POST['buycre'])) {
            
        	$post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
			$post_credit = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['credit'], ENT_QUOTES))));
          
            $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
            $data_website = $check_website->fetch_array(MYSQLI_ASSOC);
            
            // Check Price
            if (mysqli_real_escape_string($db, $_POST['credit'] == "100")) {
            	$post_price = "7500";
            } else if (mysqli_real_escape_string($db, $_POST['credit'] == "500")) {
    	        $post_price = "25000";  
            } else if (mysqli_real_escape_string($db, $_POST['credit'] == "1000")) {
    	        $post_price = "40000";
    	    } else if (mysqli_real_escape_string($db, $_POST['credit'] == "2000")) {
    	        $post_price = "75000";    
            } 

			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = $checkdb_user->fetch_array(MYSQLI_ASSOC);
			 if ($post_credit != "100" AND $post_credit != "500" AND $post_credit != "1000" AND $post_credit != "2000") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Input tidak sesuai (1).";
			} else if ($data_user['balance'] < $post_price) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Saldo Anda tidak mencukupi untuk melakukan pembelian credit SMS.";
			} else {
				$update_user = $db->query("UPDATE users SET balance = balance-$post_price, credit = credit+$post_credit WHERE username = '$sess_username'");
				$update_user = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '$post_price', 'Pembelian Credit SMS', '$date', '$time')");
				if ($update_user == TRUE) {
					$msg_type = "success";
					$msg_content = "
					<b>Berhasil!</b> Credit Anda Telah Di Tambahkan $post_credit. <br />";
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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="fa fa-level-up"></i> Isi Credit Sms</h4><hr>
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
                                    <label class="col-md-2 control-label">Credit Sekarang</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Credit Saya</span>
                                            </div>
                                           <input type="text" class="form-control" value="<?php echo $data_user['credit']; ?>" readonly>
                                            </div>
                                    </div>                                    
                                </div>
							    <div class="form-group row">
		                            <label class="col-md-2 control-label">Pilih Credit</label>
		                            <div class="col-md-10">
				                        <select class="form-control" name="credit" id="credit" required>
				                            <option value="default">Silahkan Pilih Salah Satu</option>
				                            
				                            <option value="100">100 Credit (Rp. 7500)</option>
				                            <option value="500">500 Credit (Rp. 25000)</option>
				                            <option value="1000">1000 Credit (Rp. 40000)</option>
				                            <option value="2000">2000 Credit (Rp. 75000)</option>
				                         
				                        </select>
		                            </div>
		                        </div>
		                        <div id="note_cre"></div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8">
                                        <button type="submit" class="btn btn-custom btn-bordred" name="buycre"><i class="fa fa-send"></i> Buy Credit </button>   
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