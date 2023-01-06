<?php

session_start();
require("../mainconfig.php");
$page_type = "Kelola Kategori";

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
			$post_id = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['id'], ENT_QUOTES))));
			$post_name = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
			$post_code = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['code'], ENT_QUOTES))));
			$post_type = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['type'], ENT_QUOTES))));

			$checkdb_service = $db->query("SELECT * FROM service_cat WHERE id = '$post_id'");
			$datadb_service = $checkdb_service->fetch_array(MYSQLI_ASSOC);
			if (empty($post_id) || empty($post_name) || empty($post_code)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if ($checkdb_service->num_rows > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Category ID $post_id telah terdaftar di database.";
			} else {
				$insert_provider = $db->query("INSERT INTO service_cat (id, name, code, type) VALUES ('$post_id', '$post_name', '$post_code', '$post_type')");
				if ($insert_provider == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Success!</b> Kategori berhasil ditambahkan.<br /><b>Provider ID:</b> $post_id<br /><b>Name:</b>$post_name<br /><b>Type:</b>$post_type";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> System Error.";
				}
			}
		} else if (isset($_POST['edit'])) {
			$post_id = $db->real_escape_string($_GET['service_pulsa_cat_id']);
			$post_name = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
			$post_code = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['code'], ENT_QUOTES))));

			if (empty($post_name) || empty($post_code)) {
				$msg_type = "error";
				$msg_content = "<b>Failed:</b> Please input all fills.";
			} else {
				$update_category = $db->query("UPDATE service_pulsa_cat SET name = '$post_name', code = '$post_code' WHERE id = '$post_id'");
			if ($update_category == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Berhasil!</b> Kategori berhasil diubah.<br /><b>Kategori ID:</b> $post_id<br /><b>Name:</b> $post_name<br />";
			} else {
				$msg_type = "error";
				$msg_content = "<b>Failed:</b> Error system.";
			}
		}

		} else if (isset($_POST['delete'])) {
			$post_id = $db->real_escape_string($_GET['service_pulsa_cat_id']);
			$checkdb_news = $db->query("SELECT * FROM service_pulsa_cat WHERE id = '$post_id'");
			if ($checkdb_news->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Kategori tidak ditemukan.";
			} else {
				$delete_news = $db->query("DELETE FROM service_pulsa_cat WHERE id = '$post_id'");
				if ($delete_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Kategori dihapus.";
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
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-format-list-bulleted"></i> Tambah Kategori</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	<form class="form-horizontal" role="form" method="POST">
								    <div class="form-group row">
										<label class="col-md-2 control-label">Kategori ID</label>
										<div class="col-md-10">
											<input type="number" name="id" class="form-control" placeholder="example : 1">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Type Kategori</label>
										<div class="col-md-10">
											<select class="form-control" name="type">
												<option value="PULSA">Pulsa Isi Ulang</option>
											<option value="PKIN">Paket Internet</option>
											<option value="VGAME">Voucher Game Online</option>
											<option value="SALGO">Saldo E-Money</option>		
											<option value="PKSMS">Paket SMS Dan Telpon</option>
											<option value="TOKENPLN">Token PLN</option>
											<option value="PT">Pulsa Transfer</option>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-2 control-label">Code</label>
										<div class="col-md-10">
											<input type="text" name="code" class="form-control" placeholder="example : IGF, IGL, IGC">
										</div>
									</div>
											
									<div class="form-group row">
										<label class="col-md-2 control-label">Name</label>
										<div class="col-md-10">
											<input type="text" name="name" class="form-control" placeholder="example : Instagram Followers">
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
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Kategori</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-format-list-bulleted"></i> Kelola Kategori</h4><hr>
				
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
									<th>Nama</th>
									<th>Kode</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$data_category = $db->query("SELECT * FROM service_pulsa_cat ORDER BY id DESC");
							while ($data_show = $data_category->fetch_array(MYSQLI_ASSOC)) {
							?>
								<tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?servicecat_id=<?php echo $data_show['id']; ?>" class="form-inline" role="form" method="POST">
										<td><input type="text" class="form-control" style="width: 50px;" name="id" value="<?php echo $data_show['id']; ?>"></td>								
										<td><input type="text" class="form-control" style="width: 300px;" name="name" value="<?php echo $data_show['name']; ?>"></td>
										<td><input type="text" class="form-control" style="width: 100px;" name="code" value="<?php echo $data_show['code']; ?>"></td>
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