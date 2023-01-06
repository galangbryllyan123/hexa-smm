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
$page_type = "Kelola Berita";

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
			$post_content = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['content'], ENT_QUOTES))));
			$post_category = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['category'], ENT_QUOTES))));

			if (empty($post_content) || empty($post_category)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else {
				$insert_news = $db->query("INSERT INTO news (date, time, category, content) VALUES ('$date', '$time', '$post_category', '$post_content')");
				if ($insert_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Berita berhasil ditambahkan.<br /><b>Kategori:</b> $post_category<br /><b>Konten:</b> $post_content<br /><b>Tanggal:</b> $date";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		} else if (isset($_POST['edit'])) {
	        $post_id = $db->real_escape_string($_GET['news_id']);
			$post_content = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['content'], ENT_QUOTES))));
			$post_category = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['category'], ENT_QUOTES))));
		    if (empty($post_content)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else {
				$update_news = $db->query("UPDATE news SET content = '$post_content' WHERE id = '$post_id'");
				if ($update_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Berita berhasil diubah.";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		} else if (isset($_POST['delete'])) {
			$post_id = $db->real_escape_string($_GET['news_id']);
			$checkdb_news = $db->query("SELECT * FROM news WHERE id = '$post_id'");
			if ($checkdb_news->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Berita tidak ditemukan.";
			} else {
				$delete_news = $db->query("DELETE FROM news WHERE id = '$post_id'");
				if ($delete_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Berita dihapus.";
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
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-newspaper"></i> Tambah Berita</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	<form class="form-horizontal" role="form" method="POST">
									<div class="form-group row">
										<label class="col-md-2 control-label">Kategori</label>
										<div class="col-md-10">
											<select class="form-control" name="category">
												<option value="Event">Event</option>
												<option value="Info">Info</option>
												<option value="Update">Update</option>
												<option value="Service">Service</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Konten</label>
										<div class="col-md-10">
											<textarea name="content" class="form-control" placeholder="Konten"></textarea>
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
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Berita</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-newspaper"></i> Kelola Berita</h4><hr>
				
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
									<th>Tanggal</th>
									<th>Konten</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$data_news = $db->query("SELECT * FROM news ORDER BY id DESC"); // edit
							while ($data_show = $data_news->fetch_array(MYSQLI_ASSOC)) {
							?>
								<tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?news_id=<?php echo $data_show['id']; ?>" class="form-inline" role="form" method="POST">
										<td><?php echo $data_show['id']; ?></td>
										<td width="10%"><input type="text" class="form-control" name="date" value="<?php echo $data_show['date']; ?>"></td>
										<td><textarea rows="5" cols="100" name="content" class="form-control"><?php echo $data_show['content']; ?></textarea></td>
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