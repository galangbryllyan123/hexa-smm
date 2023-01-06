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
$page_type = "Kelola Tiket";

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

		if (isset($_POST['delete'])) {
			$post_id = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['id'], ENT_QUOTES))));
			$checkdb_user = $db->query("SELECT * FROM tickets WHERE id = '$post_id'");
			if ($checkdb_user->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Tiket tidak ditemukan di database.";
			} else {
				$delete_ticket = $db->query("DELETE FROM tickets WHERE id = '$post_id'");
				$delete_ticket = $db->query("DELETE FROM tickets_message WHERE ticket_id = '$post_id'");
			if ($delete_ticket == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Berhasil!</b> Ticket dihapus.";
			}
		}
	}

	include("../lib/headeradmin.php");
?>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-comment-text-outline"></i> Kelola Tiket</h4><hr>
				
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
									<th>Status</th>
									<th>Subjek</th>
									<th>Pengguna</th>
									<th>Tanggal diterima</th>
									<th>Terakhir Update</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$data_ticket = $db->query("SELECT * FROM tickets ORDER BY id DESC"); // edit
								while ($data_show = $data_ticket->fetch_array(MYSQLI_ASSOC)) {

									if ($data_show['status'] == "Closed") {
										$label = "danger";
									} else if ($data_show['status'] == "Responded") {
										$label = "success";
									} else if ($data_show['status'] == "Waiting") {
										$label = "info";
									} else {
										$label = "warning";
									}
								?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_show['status']; ?></span></td>
									<td><?php if($data_show['seen_admin'] == 0) { ?><span class="badge badge-warning">NEW!</span><?php } ?> <?php echo $data_show['subject']; ?></td>
									<td><?php echo $data_show['user']; ?></td>
									<td><?php echo $data_show['datetime']; ?></td>
									<td><?php echo $data_show['last_update']; ?></td>
									<td align="center">
										<a href="<?php echo $site_config['base_url']; ?>admin/ticket/reply.php?id=<?php echo $data_show['id']; ?>" class="btn btn-sm btn-bordred btn-success"><i class="fa fa-reply"></i></a>
										<a href="<?php echo $site_config['base_url']; ?>admin/ticket/close.php?id=<?php echo $data_show['id']; ?>" class="btn btn-sm btn-bordred btn-info"><i class="fa fa-times"></i></a>
										<a href="<?php echo $site_config['base_url']; ?>admin/ticket/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-trash"></i></a>
									</td>
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