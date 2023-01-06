<?php

session_start();
require("../mainconfig.php");
$page_type = "Riwayat Pesanan";

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
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-refresh"></i> Riwayat Pesanan</h4><hr>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
											        <tr>
														<th>Pesan</th>
														<th>Tujuan</th>
														<th>Tanggal</th>
														<th>Status</th>
														
													</tr>
												</thead>
												<tbody>
												<?php
												$check_order = $db->query("SELECT * FROM orders_sms WHERE user = '$sess_username' ORDER BY date DESC"); // edit
												while ($data_order = mysqli_fetch_assoc($check_order)) {
													if($data_order['status'] == "Pending") {
														$label = "warning";
													} else if($data_order['status'] == "Processing") {
														$label = "info";
													} else if($data_order['status'] == "Error") {
														$label = "danger";
													} else if($data_order['status'] == "Partial") {
														$label = "danger";
													} else if($data_order['status'] == "Success") {
														$label = "success";
													}
												?>
													<tr>
																					<td><?php echo $data_order['pesan']; ?></td>						
														<td><?php echo $data_order['nomer']; ?></td>
														<td><?php echo $data_order['date']; ?></td>												
																												<td align="center"><span class="badge badge-<?php echo $label; ?>"><?php echo $data_order['status']; ?></span></td>		
														
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
			    </div>
						<!-- end row -->
						
			
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>