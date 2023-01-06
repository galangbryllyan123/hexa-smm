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
$page_type = "Buat Tiket";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
	if ($check_user->num_rows == 0) {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$site_config['base_url']."user/logout.php");
	}

	include("../lib/header.php");
	$msg_type = "nothing";

	if (isset($_POST['submit'])) {
		$post_subject = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['subject'], ENT_QUOTES))));
		$post_message = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['message'], ENT_QUOTES))));
		if (empty($post_subject) || empty($post_message)) {
			$msg_type = "error";
			$msg_content = '<b>Gagal!</b> Mohon mengisi semua input.>';
		} else if (strlen($post_subject) > 50) {
			$msg_type = "error";
			$msg_content = '<b>Gagal!</b> subjek maks. 50 karakter.';
		} else if (strlen($post_message) > 200) {
			$msg_type = "error";
			$msg_content = '<b>Gagal!</b> Pesan maks. 200 karakter.';
		} else {
			$insert_ticket = $db->query("INSERT INTO tickets (user, subject, message, datetime, last_update, status) VALUES ('$sess_username', '$post_subject', '$post_message', '$date $time', '$date $time', 'Pending')");
			if ($insert_ticket == TRUE) {
				$msg_type = "success";
				$msg_content = '<b>Berhasil!</b> Tiket Berhasil Dibuat.';
			} else {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> System error.";
			}
		}
	}
?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-comment-text-outline"></i> Buat Tiket</h4><hr>
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
                            } else {
                            ?>
							<div class="alert alert-info">
                                Tata cara pengisian form subjek:
                                <ul>
                                    <li>ORDER : Masalah mengenai dengan pemesanan.</li>
				                    <li>DEPOSIT : Masalah mengenai dengan deposito.</li>
					                <li>OTHER : Masalah mengenai dengan hal lainnya.</li>
							    </ul>
							</div>
							<?php
							}
							?>
							<form class="form-horizontal" role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<div class="form-group row">
									<label class="col-md-2 control-label">Subjek</label>
									<div class="col-md-10">
										<input type="text" name="subject" class="form-control" placeholder="Masukan subjek" maxlength="50">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Pesan</label>
									<div class="col-md-10">
										<textarea name="message" class="form-control" placeholder="Masukan pesan dari tiket." rows="3" maxlength="200"></textarea>
									</div>
								</div>
								<div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8">
                                        <button type="reset" class="btn btn-secondary btn-bordred"><i class="fa fa-refresh"></i> Reset </button>  
                                        <button type="submit" class="btn btn-custom btn-bordred" name="submit"><i class="fa fa-send"></i> Submit </button>   
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-refresh"></i> Riwayat</h4><hr>
							<div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Subjek</th>
											<th>Status</th>
											<th>Terakhir diperbarui</th>
										</tr>
									</thead>
									<tbody>
									<?php
								    $check_ticket = $db->query("SELECT * FROM tickets WHERE user = '$sess_username' ORDER BY id DESC"); // edit
							        while ($data_ticket = $check_ticket->fetch_array(MYSQLI_ASSOC)) {

							            if ($data_ticket['status'] == "Closed") {
							                $label = "danger";
							            } else if ($data_ticket['status'] == "Responded") {
							                $label = "success";
							            } else if($data_ticket['status'] == "Waiting") {
							                $label = "info";
							            } else {
							                $label = "warning";
							            }
							        ?>
										<tr>
										    <td><?php if($data_ticket['seen_user'] == 0) { ?><span class="badge badge-warning">NEW!</span><?php } ?> <a href="<?php echo $site_config['base_url']; ?>ticket/open?id=<?php echo $data_ticket['id']; ?>"><?php echo $data_ticket['subject']; ?></a></td>
										    <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_ticket['status']; ?></span></td>
											<td><?php echo TanggalIndo($data_ticket['last_update']); ?></td>
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
} else {
	header("Location: ".$site_config['base_url']);
}
?>