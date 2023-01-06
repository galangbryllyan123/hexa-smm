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
$page_type = "Log Penggunaan Saldo";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    }

	include("../lib/header.php");
	$msg_type = "nothing";

	
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);

?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-wallet"></i> Log Penggunaan Saldo</h4><hr>
                    	    <div class="table-responsive">
                    		    <table id="datatable" class="table table-bordered table-striped">
                    			    <thead>
									    <tr>
									    	<th>ID</th>
										    <th>Tanggal/Waktu</th>
										    <th>Tipe</th>
										    <th>Kategori</th>
										    <th>Jumlah</th>
										    <th>Deskripsi</th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php
								    	$check_logs = $db->query("SELECT * FROM balance_history WHERE username = '$sess_username' ORDER BY id DESC"); // edit 
								    	while ($data_logs = $check_logs->fetch_array(MYSQLI_ASSOC)) {
								    		if ($data_logs['category'] == "Deposit") {
										        $label = "success";
									            $pes = "DEPOSIT";
									            $icon = "plus-circle";
									        } else if($data_logs['category'] == "Place Order") {
										        $label = "danger";
										        $pes = "PLACE ORDER";
										        $icon = "minus-circle";
									        } else if($data_logs['category'] == "Withdraw") {
										        $label = "success";
										        $pes = "WITHDRAW";
										        $icon = "plus-circle";
									        } else if($data_logs['category'] == "Upgrade Akun") {
										        $label = "danger";
										        $pes = "Upgrade Akun";
										        $icon = "minus-circle";
									        } else if($data_logs['category'] == "Refund Saldo") {
										        $label = "success";
										        $pes = "REFUND";
										       $icon = "plus-circle";
									        }
								        ?>
								        <tr class="table-<?php echo $label; ?>">
                                	        <td><?php echo $data_logs['id']; ?></td>
                                	        <td><?php echo TanggalIndo($data_logs['date']); ?> (<?php echo $data_logs['time']; ?>)</td>
                                	        <td><?php echo $data_logs['type']; ?></td>
                                	        <td><span class="badge badge-<?php echo $label; ?>"><?php echo $pes; ?></span></td>
                                	        <td><span class="badge badge-<?php echo $label; ?>"><i class="fa fa-<?php echo $icon; ?>"></i> <?php echo number_format($data_logs['quantity'],0,',','.'); ?></span></td>
                                	        <td><?php echo $data_logs['message']; ?></td>
                                        </tr>
<?php
}
?>
                                    </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>
