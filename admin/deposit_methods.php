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
$page_type = "Kelola Metode Deposit";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['level'] != "Developers") {
		header("Location: ".$site_config['base_url']);
	} else {

		if (isset($_POST['add'])) {
			$post_name = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
			$post_type = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['type'], ENT_QUOTES))));
			$post_note = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['note'], ENT_QUOTES))));
			$post_rate = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['rate'], ENT_QUOTES))));

			if (empty($post_name) OR empty($post_note)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else {
				$insert_news = $db->query("INSERT INTO deposit_method (name, payment, type, rate) VALUES ('$post_name', '$post_note', '$post_type', '$post_rate')");
				if ($insert_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Metode berhasil ditambah.";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system.";
				}
			}
		}

		if (isset($_POST['delete'])) {
			$post_id = $db->real_escape_string($_GET['id']);
			$checkdb_news = $db->query("SELECT * FROM deposit_method WHERE id = '$post_id'");
			if ($checkdb_news->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Metode tidak ditemukan.";
			} else {
				$delete_news = $db->query("DELETE FROM deposit_method WHERE id = '$post_id'");
				if ($delete_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Metode deleted.";
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
										<label class="col-md-2 control-label">Type Deposit</label>
										<div class="col-md-10">
											<select class="form-control" name="type">
												<option value="Auto">Automatis</option>
												<option value="Manual">Manual</option>
											</select>
										</div>
									</div>                        		
								    <div class="form-group row">
									    <label class="col-md-2 control-label">Provider</label>
									    <div class="col-md-10">
										    <input type="text" name="name" class="form-control" placeholder="BANK BCA">
									    </div>
								    </div>
								    <div class="form-group row">
									    <label class="col-md-2 control-label">Catatan</label>
									    <div class="col-md-10">
										    <input type="text" name="note" class="form-control" placeholder="BCA 123456789 A/N Pemilik Rekening">
									    </div>
								    </div>
								    <div class="form-group row">
									    <label class="col-md-2 control-label">Rate / Kurs</label>
									    <div class="col-md-10">
										    <input type="text" name="rate" class="form-control" placeholder="0.8">
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
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Metode </button>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Kelola Metode Deposit</h4><hr>

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
									<th>Type</th>
									<th>Nama</th>
									<th>Catatan</th>
									<th>Rate</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$data_deposit = $db->query("SELECT * FROM deposit_method ORDER BY id DESC");
							while ($data_show = $data_deposit->fetch_array(MYSQLI_ASSOC)) {
							?>
							    <tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $data_show['id']; ?>" class="form-inline" role="form" method="POST">
										<td><?php echo $data_show['type']; ?></td>
									    <td><?php echo $data_show['name']; ?></td>
										<td><?php echo $data_show['payment']; ?></td>
										<td><?php echo $data_show['rate']; ?></td>
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
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>