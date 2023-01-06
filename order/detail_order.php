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
$page_type = "Detail Pesanan";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    } else {
		if (isset($_GET['oid'])) {
			$post_oid = $db->real_escape_string($_GET['oid']);
			$checkdb_order = $db->query("SELECT * FROM orders WHERE oid = '$post_oid'");
			$datadb_order = $checkdb_order->fetch_array(MYSQLI_ASSOC);

			if ($datadb_order['refund'] == 1) {
			    $count_refund = $datadb_order['price'] / $datadb_order['quantity'];
		        $total_refund = $count_refund * $datadb_order['remains'];
			} 

			if($datadb_order['status'] == "Pending") {
				$label = "warning";
			} else if($datadb_order['status'] == "Processing") {
				$label = "info";
			} else if($datadb_order['status'] == "Error") {
				$label = "danger";
			} else if($datadb_order['status'] == "Partial") {
				$label = "danger";
			} else if($datadb_order['status'] == "Success") {
				$label = "success";
			}
			if (mysqli_num_rows($checkdb_order) == 0) {
				header("Location: ".$site_config['base_url']."order/history");
			} else {
?>
										
                                    <div class="col-md-12">
                	                    <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <tr>
													<td><b>ID Pesanan</b></td>
													<td><b><?php echo $datadb_order['oid']; ?></b></td>
												</tr>
												<tr>
													<td><b>Layanan</b></td>
													<td><?php echo $datadb_order['service']; ?></td>
												</tr>
												<tr>
													<td><b>Data</b></td>
													<td><?php echo $datadb_order['link']; ?></td>
												</tr>
												<tr>
													<td><b>Harga</b></td>
													<td>Rp <?php echo number_format($datadb_order['price'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Jumlah Awal</b></td>
													<td><?php echo number_format($datadb_order['start_count'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Jumlah Sisa</b></td>
													<td><?php echo number_format($datadb_order['remains'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Status</b></td>
													<td><span class="badge badge-<?php echo $label; ?>"><?php echo $datadb_order['status']; ?></span></td>
												</tr>
												<tr>
													<td><b>Refund</b></td>
													<td><span class="badge badge-<?php if($datadb_order['refund'] == "false") { echo "danger"; } else { echo "primary"; } ?>"><?php if($datadb_order['refund'] == "false") { ?>Tidak<?php } else { ?> Ya (Refunded Rp <?php echo number_format($total_refund); ?>)<?php } ?></span> </td>
												</tr>
												<tr>
													<td><b>Tanggal Pembelian</b></td>
													<td><?php echo TanggalIndo($datadb_order['date']); ?></td>
						 						</tr>
						                    </table>
						                </div>
						            </div>
<?php
			}
		} else {
			header("Location: ".$site_config['base_url']."order/history");
		}
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>