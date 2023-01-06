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
require("../../mainconfig.php");
$page_type = "Balas Tiket";


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
		if (isset($_GET['id'])) {
			$post_target = $db->real_escape_string($_GET['id']);
			$check_ticket = $db->query("SELECT * FROM tickets WHERE id = '$post_target'");
			$data_ticket = $check_ticket->fetch_array(MYSQLI_ASSOC);
			if ($check_ticket->num_rows == 0) {
				header("Location: ".$site_config['base_url']."admin/tickets.php");
			} else {
				$db->query("UPDATE tickets SET seen_admin = '1' WHERE id = '$post_target'");
				if (isset($_POST['submit'])) {
					$post_message = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['message'],ENT_QUOTES))));
					if ($data_ticket['status'] == "Closed") {
						$msg_type = "error";
						$msg_content = "<b>Gagal!</b> Ticket ditutup.";
					} else if (empty($post_message)) {
						$msg_type = "error";
						$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
					} else if (strlen($post_message) > 200) {
						$msg_type = "error";
						$msg_content = "<b>Gagal!</b> Pesan maksimal 200 kata.";
					} else {
						$last_update = "$date $time";
						$insert_ticket = $db->query("INSERT INTO tickets_message (ticket_id, sender, user, message, datetime) VALUES ('$post_target', 'Admin', '$data_ticket[user]', '$post_message', '$last_update')");
						$update_ticket = $db->query("UPDATE tickets SET last_update = '$last_update', seen_user = '0', status = 'Responded' WHERE id = '$post_target'");
						if ($insert_ticket == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Berhasil:</b> Pesan dikirim.";
						} else {
							$msg_type = "error";
							$msg_content = "<b>Gagal:</b> System error.";
						}
					}
				}
				$check_ticket = $db->query("SELECT * FROM tickets WHERE id = '$post_target'");
				$data_ticket = $check_ticket->fetch_array(MYSQLI_ASSOC);
				include("../../lib/headeradmin.php");
?>
						

                <div class="row">
                    <div class="offset-lg-2 col-lg-8">
                    	<a href="<?php echo $site_config['base_url']; ?>admin/tickets.php" class="btn btn-secondary btn-bordred m-b-20"><i class="fa fa-reply"></i> Kembali</a>
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="fa fa-reply"></i> Balas Tiket</h4><hr>
							
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
							
							<div style="max-height: 400px; overflow: auto;">
								<div class="alert alert-info alert-white">
									<b><?php echo $data_ticket['user']; ?></b><br /><?php echo nl2br($data_ticket['message']); ?><br /><i class="text-muted" style="font-size: 10px;"><?php echo $data_ticket['datetime']; ?></i>
								</div>
<?php
$check_message = $db->query("SELECT * FROM tickets_message WHERE ticket_id = '$post_target'");
while ($data_message = $check_message->fetch_array(MYSQLI_ASSOC)) {
	if ($data_message['sender'] == "Admin") {
		$msg_alert = "success";
		$msg_text = "text-right";
		$msg_sender = "Admin";
	} else {
		$msg_alert = "info";
		$msg_text = "";
		$msg_sender = $data_message['user'];
	}
?>
								<div class="alert alert-<?php echo $msg_alert; ?> alert-white <?php echo $msg_text; ?>">
									<b><?php echo $msg_sender; ?></b><br /><?php echo nl2br($data_message['message']); ?><br /><i class="text-muted" style="font-size: 10px;"><?php echo $data_message['datetime']; ?></i>
								</div>
<?php
}
?>
							</div>
						</div>
						<div class="card-box">
							<form class="form-horizontal" role="form" method="POST">
								<div class="form-group row">
									<div class="col-md-12">
										<textarea name="message" class="form-control" placeholder="Pesan" rows="3" maxlength="200"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-12">
										<button type="cancel" class="btn btn-block btn-custom btn-bordred" name="submit"><i class="fa fa-send"></i> Balas </button>	
						     	    </div>
								</div>
							</form>
						</div>
					</div>
				</div>
<?php
				include("../../lib/footer.php");
			}
		} else {
			header("Location: ".$site_config['base_url']."admin/tickets.php");
		}
	}
} else {
	header("Location: ".$site_config['base_url']);
}
?>