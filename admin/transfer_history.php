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
$page_type = "Kelola Riwayat Transfer";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
	if ($check_user->num_rows == 0) {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['level'] != "Developers") {
		header("Location: ".$site_config['base_url']);
	} else {

	include("../lib/headeradmin.php");
	$check_wtransfer = mysqli_query($db, "SELECT SUM(quantity) AS total FROM transfer_balance");
	$data_wtransfer = mysqli_fetch_assoc($check_wtransfer);
?>
							
                <div class="row">
                	<div class="col-lg-12">
                		<div class="card-box widget-box-three">
                			<div class="bg-icon pull-left">
                				<i class="mdi mdi-wallet fa-4x"></i>
                			</div>
                			<div class="text-right">
                				<p class="text-muted m-t-5 text-uppercase font-600 font-secondary">Total Seluruh Transfer Saldo</p>
                				<h2 class="m-b-10"><span>Rp <?php echo number_format($data_wtransfer['total'],0,',','.'); ?></span></h2>
                			</div>
                		</div>
                	</div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-wallet"></i> Kelola Riwayat Transfer</h4><hr>
							<div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>Pengirim</th>
											<th>Penerima</th>
											<th>Jumlah</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$check_tf = $db->query("SELECT * FROM transfer_balance ORDER BY id DESC"); // edit
									while ($data_show = mysqli_fetch_assoc($check_tf)) {
									?>
										<tr>
											<td><?php echo $data_show['date']; ?></td>
											<td><?php echo $data_show['sender']; ?></td>
											<td><?php echo $data_show['receiver']; ?></td>
											<td>Rp <?php echo number_format($data_show['quantity'],0,',','.'); ?></td>
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
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>