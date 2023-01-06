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
$page_type = "Kelola Deposit";

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
		if (isset($_POST['accept'])) {
			$post_code = $db->real_escape_string($_GET['code']);
			$checkdb = $db->query("SELECT * FROM deposit WHERE code = '$post_code'");
			$datadb = $checkdb->fetch_array(MYSQLI_ASSOC);
			if ($checkdb->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Deposito tidak ditemukan.";
			} elseif ($datadb['status'] != "Pending") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Deposito berstatus ".$datadb['status'].".";
			} else {	
				$post_user = $datadb['username'];
				$post_balance = $datadb['balance'];
				$update_depo = $db->query("UPDATE users SET balance = balance+$post_balance WHERE username = '$post_user'");	
				$update_depo = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$post_user', 'Plus', 'Deposit', '$post_balance', 'Deposit #$post_code', '$date', '$time')");
				$update_depo = $db->query("UPDATE deposit SET status = 'Success' WHERE code = '$post_code'");
				if ($update_depo == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Permintaan deposito diterima.<br />Kode: $post_code<br />Penerima: $post_user<br />Jumlah Saldo: Rp $post_balance";
				} else { 
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Kesalahan sistem";
				}
			}
		} else if (isset($_POST['cancel'])) {
			$post_code = $db->real_escape_string($_GET['code']);
			$checkdb = $db->query("SELECT * FROM deposit WHERE code = '$post_code'");
			$datadb = $checkdb->fetch_array(MYSQLI_ASSOC);
			if ($checkdb->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Deposito tidak ditemukan.";
			} elseif ($datadb['status'] != "Pending") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Deposito berstatus ".$datadb['status'].".";
			} else {
				$post_user = $datadb['user'];
				$update_depo = $db->query("UPDATE deposit SET status = 'Error' WHERE code = '$post_code'");
				if ($update_depo == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Permintaan deposito dibatalkan.<br />Kode: $post_code";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Kesalahan sistem";
				}
			}
		}

	include("../lib/headeradmin.php");
	
	$check_wuser = mysqli_query($db, "SELECT SUM(balance) AS total FROM deposit");
	$data_wuser = mysqli_fetch_assoc($check_wuser);
	$check_wuser = mysqli_query($db, "SELECT * FROM deposit");
	$count_wuser = mysqli_num_rows($check_wuser);
	
	$check_user_active = mysqli_query($db, "SELECT * FROM users WHERE status = 'Active'");
	$check_user_unactive = mysqli_query($db, "SELECT * FROM users WHERE status = 'Suspended'");
	
?>
                <div class="row">
                	<div class="col-lg-12">
                		<div class="card-box widget-box-three">
                			<div class="bg-icon pull-left">
                				<i class="ti-wallet fa-5x"></i>
                			</div>
                			<div class="text-right">
                				<p class="text-muted m-t-5 text-uppercase font-600 font-secondary">Total Deposit Pengguna</p>
                				<h2 class="m-b-10"><span>Rp <?php echo number_format($data_wuser['total'],0,',','.'); ?> (Dari <?php echo number_format($count_wuser,0,',','.'); ?> deposit)</span></h2>
                			</div>
                		</div>
                	</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Kelola Deposit</h4><hr>
							
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
							<div class="table-responsive">
								<table id="datatable" class="table table-bordered table-striped">
								    <thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Kode Deposit</th>
											<th>Pengguna</th>
											<th>Metode</th>
											<th>Jumlah</th>
											<th>Saldo Didapat</th>
											<th>Status</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$k = $db->query("SELECT * FROM deposit ORDER BY id DESC");
									while ($data_show = $k->fetch_array(MYSQLI_ASSOC)) {

										if ($data_show['status'] == "Pending") {
											$label = "warning";
										} else if ($data_show['status'] == "Error") {
											$label = "danger";
										} else if ($data_show['status'] == "Success") {
											$label = "success";
										}


									?>
										<tr class="table-<?php echo $label; ?>">
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $data_show['code']; ?>" class="form-inline" role="form" method="POST">
												<td><?php echo $data_show['id']; ?></td>
											    <td><?php echo $data_show['date']; ?></td>
											    <td><?php echo $data_show['code']; ?></td>
											    <td><?php echo $data_show['username']; ?></td>
											    <td><?php echo $data_show['method']; ?></td>
											    <td>Rp <?php echo number_format($data_show['quantity'],0,',','.'); ?></td>
											    <td>Rp <?php echo number_format($data_show['balance'],0,',','.'); ?></td>
											    <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_show['status']; ?></span></td>
											    <td align="center">
												    <button type="submit" name="accept" class="btn btn-sm btn-bordred btn-success"><i class="fa fa-check" title="Terima"></i></button>
												    <button type="submit" name="cancel" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-times" title="Tolak"></i></button>
											    </td>
											</form>
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