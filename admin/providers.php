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
$page_type = "Kelola Provider";

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
			$post_code = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['code'], ENT_QUOTES))));
			$post_link = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['link'], ENT_QUOTES))));
			$post_api = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['api'], ENT_QUOTES))));

			$checkdb_provider = $db->query("SELECT * FROM provider WHERE id = '$post_id'");
			$datadb_provider = $checkdb_provider->fetch_array(MYSQLI_ASSOC);
			if (empty($post_id) || empty($post_code) || empty($post_link) || empty($post_api)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if ($checkdb_provider->num_rows > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Provider ID $post_si telah terdaftar di database.";
			} else {
				$insert_provider = $db->query("INSERT INTO provider (id, code, link, api_key) VALUES ('$post_id', '$post_code', '$post_link', '$post_api')");
				if ($insert_provider == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Provider berhasil ditambahkan.<br /><b>Provider ID:</b> $post_id<br /><b>Code:</b> $post_code<br /><b>Link:</b> $post_link<br /><b>Api Key:</b> $post_api";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> System Error.";
				}
			}
		} else if (isset($_POST['edit'])) {
	        $post_id = $db->real_escape_string($_GET['provider_id']);
			$post_code = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['code'], ENT_QUOTES))));
			$post_link = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['link'], ENT_QUOTES))));
			$post_api = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['api'], ENT_QUOTES))));
					
			if (empty($post_code)) {
				$msg_type = "error";
				$msg_content = "<b>Failed:</b> Please input all fills.";
			} else {
				$update_provider = $db->query("UPDATE provider SET code = '$post_code', link = '$post_link', api_key = '$post_api' WHERE id = '$post_id'");
				if ($update_provider == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Provider berhasil diubah.<br /><b>Provider ID:</b> $post_id<br /><b>Code:</b> $post_code<br /><b>Link:</b> $post_link<br /><b>Api Key:</b> $post_api";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Failed:</b> Error system.";
				}
			}
		} else if (isset($_POST['delete'])) {
			$post_id = $db->real_escape_string($_GET['provider_id']);
			$checkdb_prov = $db->query("SELECT * FROM provider WHERE id = '$post_id'");
			if ($checkdb_prov->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Provider tidak ditemukan.";
			} else {
				$delete_news = $db->query("DELETE FROM provider WHERE id = '$post_id'");
				if ($delete_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Provider dihapus.";
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
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-code-tags"></i> Tambah Provider</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	<form class="form-horizontal" role="form" method="POST">
								    <div class="form-group row">
										<label class="col-md-2 control-label">Provider ID</label>
										<div class="col-md-10">
											<input type="number" name="id" class="form-control" placeholder="example : 1">
										</div>
									</div>
											
									<div class="form-group row">
										<label class="col-md-2 control-label">Code</label>
										<div class="col-md-10">
											<input type="text" name="code" class="form-control" placeholder="example : MANUAL">
										</div>
									</div>
											
									<div class="form-group row">
										<label class="col-md-2 control-label">Link</label>
										<div class="col-md-10">
											<input type="text" name="link" class="form-control" placeholder="example : <?php echo $cfg_baseurl; ?>api.php">
										</div>
									</div>
											
									<div class="form-group row">
										<label class="col-md-2 control-label">Api Key</label>
										<div class="col-md-10">
											<input type="text" name="api" class="form-control" placeholder="example : 12345ABCDE67890">
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
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Providers</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-code-tags"></i> Kelola Provider</h4><hr>
				
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
									<th>Nama Provider</th>
									<th>Link</th>
									<th>Api Key</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$data_prov = $db->query("SELECT * FROM provider ORDER BY id ASC"); // edit
							$no = 1;
							while ($data_show = $data_prov->fetch_array(MYSQLI_ASSOC)) {
							?>
								<tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?provider_id=<?php echo $data_show['id']; ?>" class="form-inline" role="form" method="POST">
										<td><?php echo $no; ?></td>
										<td><input type="text" class="form-control" style="width: 150px;" name="code" value="<?php echo $data_show['code']; ?>"></td>
										<td><input type="text" class="form-control" style="width: 300px;" name="link" value="<?php echo $data_show['link']; ?>"></td>
										<td><input type="text" class="form-control" style="width: 300px;" name="api" value="<?php echo $data_show['api_key']; ?>"></td>
										<td align="center">
											<button type="submit" name="edit" class="btn btn-sm btn-bordred btn-info"><i class="fa fa-edit" title="Edit"></i></button>
											<button type="submit" name="delete" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-trash" title="Hapus"></i></button>
										</td>
									</form>
								</tr>
								<?php
								$no++;
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