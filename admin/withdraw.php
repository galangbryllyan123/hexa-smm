<?php

session_start();
require("../mainconfig.php");
$page_type = "Kelola Withdraw";

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
	
	 if (isset($_POST['delete'])) {
	    $post_oid = $db->real_escape_string($_GET['code']);
		$checkdb_service = $db->query("SELECT * FROM withdraw WHERE code = '$post_oid'");
		
		if ($checkdb_service->num_rows == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Withdraw tidak ditemukan.";
		} else {
			$delete_user = $db->query("DELETE FROM withdraw WHERE code = '$post_oid'");
			if ($delete_user == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Berhasil!</b> Withdraw Dengan Code <b>$post_oid</b> dihapus.";
			}
		}
	}
	
}
	// widget
	$check_worder = mysqli_query($db, "SELECT SUM(balance) AS total FROM withdraw");
	$data_worder = mysqli_fetch_assoc($check_worder);
	$check_worder = mysqli_query($db, "SELECT * FROM withdraw");
	$count_worder = mysqli_num_rows($check_worder);
?>
                <div class="row">
                	<div class="col-lg-12">
                		<div class="card-box widget-box-three">
                			<div class="bg-icon pull-left">
                				<i class="ti-shopping-cart fa-4x"></i>
                			</div>
                			<div class="text-right">
                				<p class="text-muted m-t-5 text-uppercase font-600 font-secondary">Total Withdraw Pengguna</p>
                				<h2 class="m-b-10"><span>Rp <?php echo number_format($data_worder['total'],0,',','.'); ?> (Dari <?php echo number_format($count_worder,0,',','.'); ?> Withdraw)</span></h2>
                			</div>
                		</div>
                	</div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-cart"></i> Kelola Withdraw</h4><hr>
							
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
											<th>Code</th>
											<th>User</th>
											<th>Point</th>
											<th>Balance</th>
											<th>Date</th>
									    	<th>Aksi</th>
											
										</tr>
									</thead>
									<tbody>
									<?php
									$query_list = $db->query("SELECT * FROM withdraw ORDER BY date DESC"); // edit
									while ($data_show = mysqli_fetch_assoc($query_list)) {
											
										
									?>
										<tr>
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $data_show['code']; ?>" class="form-inline" role="form" method="POST">
											    <td><?php echo $data_show['code']; ?></td>   
												<td><?php echo $data_show['username']; ?></td>
												<td><?php echo $data_show['point']; ?></td>
												<td><?php echo $data_show['balance']; ?></td>
												
												<td><?php echo $data_show['date']; ?></td>
                                                <td align="center">
												    					<button type="submit" name="delete" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-trash" title="Hapus"></i></button>
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
} else {
	header("Location: ".$site_config['base_url']);
}
?>