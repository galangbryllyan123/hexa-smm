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
$page_type = "Kelola Layanan";

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

	    if (isset($_POST['add'])) {

			$post_sid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['sid'], ENT_QUOTES))));
			$post_type = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['type'], ENT_QUOTES))));
			$post_cat = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['category'], ENT_QUOTES))));
			$post_name = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
			$post_price = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['price'], ENT_QUOTES))));
			$post_pid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pid'], ENT_QUOTES))));
			$post_prov = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['provider'], ENT_QUOTES))));

			$checkdb_service = $db->query("SELECT * FROM services_pulsa WHERE sid = '$post_sid'");
			$datadb_service = $checkdb_service->fetch_array(MYSQLI_ASSOC);
			if (empty($post_sid) || empty($post_cat) || empty($post_name) || empty($post_price) || empty($post_pid) || empty($post_prov)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if ($checkdb_service->num_rows > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Service ID $post_sid sudah terdaftar di database.";
			} else {
				$insert_service = $db->query("INSERT INTO services_pulsa (sid, type, category, service, price, status, pid, provider) VALUES ('$post_sid', '$post_type', '$post_cat', '$post_name', '$post_price', 'Active', '$post_pid', '$post_prov')");
				if ($insert_service == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Layanan berhasil ditambahkan.<br /><b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_name<br /><b>Category:</b> $post_cat<br /><b>Price:</b> Rp ".number_format($post_price,0,',','.')."<br /><b>Provider ID:</b> $post_pid<br /><b>Provider Code:</b> $post_prov";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		} else if (isset($_POST['edit'])) {
			$post_sid = $db->real_escape_string($_GET['service_id']);
	        $post_cat = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['category'], ENT_QUOTES))));
			$post_name = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
			$post_status = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['status'], ENT_QUOTES))));
			$post_price = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['price'], ENT_QUOTES))));
			$post_pid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pid'], ENT_QUOTES))));
		    $post_prov = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['provider'], ENT_QUOTES))));

		if (empty($post_sid) || empty($post_cat) || empty($post_name) || empty($post_price) || empty($post_pid) || empty($post_prov)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi input.";
		} else if ($post_status != "Active" AND $post_status != "Not active") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Input tidak sesuai.";
		} else {
				$update_service = $db->query("UPDATE services_pulsa SET category = '$post_cat', service = '$post_name', price = '$post_price', status = '$post_status', pid = '$post_pid', provider = '$post_prov' WHERE sid = '$post_sid'");
			    if ($update_service == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Layanan berhasil diubah.<br /><b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_name<br /><b>Category:</b> $post_cat<br /><b>Price:</b> Rp ".number_format($post_price,0,',','.')."<br /><b>Provider ID:</b> $post_pid<br /><b>Provider Code:</b> $post_prov";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		} else if (isset($_POST['delete'])) {
			$post_sid = $db->real_escape_string($_GET['service_id']);
			$checkdb_service = $db->query("SELECT * FROM services_pulsa WHERE sid = '$post_sid'");
			if ($checkdb_service->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Layanan tidak ditemukan.";
			} else {
				$delete_user = $db->query("DELETE FROM services_pulsa WHERE sid = '$post_sid'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Layanan <b>$post_sid</b> dihapus.";
				}
			}
		}

	include("../lib/headeradmin.php");
?>
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-format-list-bulleted"></i> Tambah Layanan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
								<form class="form-horizontal" role="form" method="POST">
								    <div class="form-group row">
										<label class="col-md-2 control-label">Type</label>
										<div class="col-md-10">
											<select class="form-control" name="type">
												<option value="0">Pilih salah satu...</option>
											    <option value="PULSA">Pulsa Isi Ulang</option>
											<option value="PKIN">Paket Internet</option>
											<option value="VGAME">Voucher Game</option>
											<option value="SALGO">Saldo Gojek, Grab, Dll</option>		
											<option value="PKSMS">Paket SMS</option>
											<option value="TOKENPLN">Token Pln</option>
											<option value="PT">Pulsa Transfer</option>
											<option value="LAYPRO">Layanan Promo</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Category</label>
										<div class="col-md-10">
											<select class="form-control" name="category">
												<?php
												$check_cat = $db->query("SELECT * FROM service_pulsa_cat WHERE type != 'Sosmed' ORDER BY name ASC");
												while ($data_cat = $check_cat->fetch_array(MYSQLI_ASSOC)) {
												?>
												<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>
											
									<div class="form-group row">
										<label class="col-md-2 control-label">Service ID</label>
										<div class="col-md-10">
											<input type="number" name="sid" class="form-control" placeholder="Service ID">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Service Name</label>
										<div class="col-md-10">
											<input type="text" name="name" class="form-control" placeholder="Service Name">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Price</label>
										<div class="col-md-10">
											<input type="number" name="price" class="form-control" placeholder="Etc: 30000">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Provider ID</label>
										<div class="col-md-10">
											<input type="text" name="pid" class="form-control" placeholder="Provider ID">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Provider Code</label>
										<div class="col-md-10">
											<select class="form-control" name="provider">
												<?php
												$check_prov = mysqli_query($db, "SELECT * FROM provider");
												while ($data_prov = mysqli_fetch_assoc($check_prov)) {
												?>
												<option value="<?php echo $data_prov['code']; ?>"><?php echo $data_prov['code']; ?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="modal-footer">
                                        <button type="reset" class="btn btn-secondary btn-bordred waves-effect" data-dismiss="modal"><i class="fa fa-refresh"></i> Reset</button>
                                        <button type="submit" class="btn btn-custom btn-bordred waves-effect w-md waves-light" name="add"><i class="fa fa-send"></i> Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Layanan</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-format-list-bulleted"></i> Kelola Layanan</h4><hr>
				
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
									<th>ID</th>
									<th>Kategori</th>
									<th>Layanan</th>
									<th>Harga</th>
									<th>Status</th>
									<th>PID</th>
									<th>Provider</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$query_list = $db->query("SELECT * FROM services_pulsa ORDER BY id ASC"); // edit
							while ($data_show = $query_list->fetch_array(MYSQLI_ASSOC)) {
							?>
								<tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?service_id=<?php echo $data_show['sid']; ?>" class="form-inline" role="form" method="POST">
										<td><input type="text" class="form-control" name="name" value="<?php echo $data_show['sid']; ?>" style="width: 50px;"></td>
										<td>
											<select class="form-control" name="category">
											    <option value="<?php echo $data_show['category']; ?>"><?php echo $data_show['category']; ?></option>
													<?php
													$check_cat = $db->query("SELECT * FROM service_pulsa_cat ORDER BY name ASC");
													while ($data_cat = $check_cat->fetch_array(MYSQLI_ASSOC)) {
													?>
												<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
													<?php
													}
													?>
											</select>
										</td>
										<td><input type="text" class="form-control" name="name" value="<?php echo $data_show['service']; ?>"></td>
										<td><input type="number" class="form-control" name="price" value="<?php echo $data_show['price']; ?>"></td>
										<td>
											<select class="form-control" name="status">
												<option value="<?php echo $data_show['status']; ?>"><?php echo $data_show['status']; ?></option>
												<option value="Active">Active</option>
												<option value="Not active">Not active</option>
											</select>
										</td>
										<td><input type="text" class="form-control" style="width: 100px;" name="pid" value="<?php echo $data_show['pid']; ?>"></td>
										<td>
											<select class="form-control" name="provider">
											    <option value="<?php echo $data_show['provider']; ?>"><?php echo $data_show['provider']; ?></option>
												<?php
												$check_prov = mysqli_query($db, "SELECT * FROM provider");
												while ($data_prov = mysqli_fetch_assoc($check_prov)) {
												?>
												<option value="<?php echo $data_prov['code']; ?>"><?php echo $data_prov['code']; ?></option>
												<?php
												}
												?>
											</select>
										</td>
										<td align="center">
											<button type="submit" name="edit" class="btn btn-sm btn-bordred btn-info"><i class="fa fa-edit" title="Edit"></i></button>
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
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>