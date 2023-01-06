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
$page_type = "Kelola Pesanan";

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
	
	if (isset($_POST['edit'])) {
	    $post_order_id = $db->real_escape_string($_GET['order_id']);
	    $post_status = $db->real_escape_string($_POST['status']);
	    
	    $check_order = $db->query("SELECT * FROM orders_pulsa WHERE oid = '$post_order_id'");
	    $data_order = $check_order->fetch_array(MYSQLI_ASSOC);
	    
	    if ($check_order->num_rows == 0) {
	        $msg_type = "error";
	        $msg_content = "<b>Gagal!</b> Pesanan yang dimaksud tidak ditemukan.";
	    } else {
	        $update_order = $db->query("UPDATE orders_pulsa SET status = '$post_status' WHERE oid = '$post_order_id'");
	        if ($update_order == TRUE) {
	            $msg_type = "success";
	            $msg_content = "<b>Berhasil!</b> Pesanan berhasil diedit.<br /><b>ID Pesanan:</b> $post_order_id<br /><b>Status:</b> $post_status";
	        } else {
	            $msg_type = "error";
	            $msg_content = "<b>Gagal!</b> Error database. (Update)";
	        }
	    }
	} else if (isset($_POST['delete'])) {
	    $post_oid = $db->real_escape_string($_GET['order_id']);
		$checkdb_service = $db->query("SELECT * FROM orders_pulsa WHERE oid = '$post_oid'");
		
		if ($checkdb_service->num_rows == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Pesanan tidak ditemukan.";
		} else {
			$delete_user = $db->query("DELETE FROM orders_pulsa WHERE oid = '$post_oid'");
			if ($delete_user == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Berhasil!</b> Pesanan <b>$post_oid</b> dihapus.";
			}
		}
	}
	
}
	// widget
	$check_worder = mysqli_query($db, "SELECT SUM(price) AS total FROM orders_pulsa");
	$data_worder = mysqli_fetch_assoc($check_worder);
	$check_worder = mysqli_query($db, "SELECT * FROM orders_pulsa");
	$count_worder = mysqli_num_rows($check_worder);
?>
                <div class="row">
                	<div class="col-lg-12">
                		<div class="card-box widget-box-three">
                			<div class="bg-icon pull-left">
                				<i class="ti-shopping-cart fa-4x"></i>
                			</div>
                			<div class="text-right">
                				<p class="text-muted m-t-5 text-uppercase font-600 font-secondary">Total Pesanan Pengguna</p>
                				<h2 class="m-b-10"><span>Rp <?php echo number_format($data_worder['total'],0,',','.'); ?> (Dari <?php echo number_format($count_worder,0,',','.'); ?> pesanan)</span></h2>
                			</div>
                		</div>
                	</div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-cart"></i> Kelola Pesanan</h4><hr>
							
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
											<th>OID</th>
											<th>POID</th>
											<th>User</th>
											<th colspan="2">Service</th>
											<th>Tujuan</th>
											<th>Price</th>
											<th>Status Pesanan</th>
											<th>Date</th>
											<th>Aksi</th>
											<th>API</th>
											<th>Refund</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$query_list = $db->query("SELECT * FROM orders_pulsa ORDER BY date DESC"); // edit
									while ($data_show = mysqli_fetch_assoc($query_list)) {
											
										if($data_show['status'] == "Pending") {
											$label = "warning";
										} else if($data_show['status'] == "Processing") {
											$label = "info";
										} else if($data_show['status'] == "Error") {
											$label = "danger";
										} else if($data_show['status'] == "Partial") {
											$label = "danger";
										} else if($data_show['status'] == "Success") {
											$label = "success";
										}
									?>
										<tr>
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>?order_id=<?php echo $data_show['oid']; ?>" class="form-inline" role="form" method="POST">
											    <td><?php echo $data_show['id']; ?></td>   
												<td><?php echo $data_show['oid']; ?></td>
												<td><?php echo $data_show['poid']; ?></td>
												<td><?php echo $data_show['user']; ?></td>
												<td colspan="2"><?php echo $data_show['service']; ?></td>
												<td><input type="text" class="form-control" value="<?php echo $data_show['link']; ?>" style="width: 100px;" readonly></td>
												<td>Rp <?php echo number_format($data_show['price'],0,',','.'); ?></td>
												<td>
												    <select class="form-control" name="status">
												        <?php if ($data_show['status'] == "Success") { ?>
												        <option value="<?php echo $data_show['status']; ?>"><?php echo $data_show['status']; ?></option>
												        <?php } else { ?>
												        <option value="<?php echo $data_show['status']; ?>"><?php echo $data_show['status']; ?></option>
													    <option value="Pending">Pending</option>
													    <option value="Processing">Processing</option>
													    <option value="Success">Success</option>
													    <option value="Error">Error</option>
													    <option value="Partial">Partial</option>
													    <?php
												        }
												        ?>
												    </select>
												</td>
												<td><?php echo $data_show['date']; ?></td>
                                                <td align="center">
												    <button type="submit" name="edit" class="btn btn-sm btn-bordred btn-info"><i class="fa fa-edit" title="Edit"></i></button>
													<button type="submit" name="delete" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-trash" title="Hapus"></i></button>
												</td>
												<td><?php if($data_show['place_from'] == "API") { ?><span class="badge badge-success"><i class="fa fa-check"></i></span><?php } else { ?><span class="badge badge-danger"><i class="fa fa-times"></i></span><?php } ?></td>
												<td><?php if($data_show['refund'] == "true") { ?><span class="badge badge-success"><i class="fa fa-check"></i></span><?php } else { ?><span class="badge badge-danger"><i class="fa fa-times"></i></span><?php } ?></td>
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
} else {
	header("Location: ".$site_config['base_url']);
}
?>