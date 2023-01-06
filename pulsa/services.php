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
$page_type = "Daftar Layanan";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
	}
}

include("../lib/header.php");
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Layanan</h4><hr>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
													<tr>
														<th>ID</th>
														<th>Kategori</th>
														<th>Layanan</th>
														<th>Harga</th>
																												<th>Harga Agen</th>
													</tr>
												</thead>
												<tbody>
												<?php
												$query_list = $db->query("SELECT * FROM services_pulsa ORDER BY id DESC"); // edit
												while ($data_service = $query_list->fetch_assoc()) {
												?>
													<tr>
														<td><?php echo $data_service['id']; ?></td>
														<td><?php echo $data_service['category']; ?></td>
														<td><?php echo $data_service['service']; ?></td>
														<td>Rp <?php echo number_format($data_service['price'],0,',','.'); ?></td>

<td>Rp <?php echo number_format($data_service['price_agen'],0,',','.'); ?></td>
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
?>