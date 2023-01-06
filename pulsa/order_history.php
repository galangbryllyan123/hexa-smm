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
														<th>No</th>
														<th>Detail</th>
														<th>ID Pesanan</th>
														<th>Layanan</th>
														<th>Data</th>
														<th>Harga</th>
														<th>Status</th>
														<th>SN/Catatan</th>
														<th>Tanggal</th>
														<th>API</th>
														<th>Refund</th>
													</tr>
												</thead>
												<tbody>
												<?php
												$check_order = $db->query("SELECT * FROM orders_pulsa WHERE user = '$sess_username' ORDER BY date DESC"); // edit
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
													<tr class="table-<?php echo $label; ?>">
														<td><?php echo $data_order['id']; ?></td>
														
														<td><a href="javascript:;" onclick="get_detail('<?php echo $site_config['base_url']; ?>pulsa/order_detail?oid=<?php echo $data_order['oid']; ?>')" class="btn btn-info btn-sm" title="Detail"><i class="fa fa-eye"></i></a></td>	
														<td><?php echo $data_order['oid']; ?></td>						
														<td><?php echo $data_order['service']; ?></td>
														<td><?php echo $data_order['link']; ?></td>
														<td>Rp <?php echo number_format($data_order['price'],0,',','.'); ?></td>
														<td align="center"><span class="badge badge-<?php echo $label; ?>"><?php echo $data_order['status']; ?></span></td>		
														<td><?php echo $data_order['sn']; ?></td>
														<td><?php echo $data_order['date']; ?></td>
														<td><?php if($data_order['place_from'] == "API") { ?><span class="badge badge-success"><i class="fa fa-check"></i></span><?php } else { ?><span class="badge badge-danger"><i class="fa fa-times"></i></span><?php } ?></td>
														<td><?php if($data_order['refund'] == 1) { ?><span class="badge badge-success"><i class="fa fa-check"></i></span><?php } else { ?><span class="badge badge-danger"><i class="fa fa-times"></i></span><?php } ?></td>
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
						
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				            	<div class="modal-dialog modal-lg">
				            		<div class="modal-content">
				            			<div class="modal-header">
				            			</div>
				            			<div class="modal-body" id="modal-DetailBody">

				            			</div>
				            			<div class="modal-footer">
				            				<button type="button" class="btn btn-secondary btn-bordred btn-block" data-dismiss="modal">Tutup</button>
				            			</div>
				            		</div>
				            	</div>
				            </div>

				                <script type="text/javascript">
				                	function get_detail(url) {
				                		$.ajax({
				                			type: "GET",
				                			url: url,
				                			beforeSend: function() {
				                				$('#modal-DetailBody').html('Sedang memuat...');
				                			},
				                			success: function(result) {
				                				$('#modal-DetailBody').html(result);
				                			},
				                			error: function() {
				                				$('#modal-DetailBody').html('Terjadi kesalahan.');
				                			}
				                		});
				                		$('#myModal').modal();
				                	}
				                </script>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>