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
$page_type = "Hapus Tiket";

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
			if (mysqli_num_rows($check_ticket) == 0) {
				header("Location: ".$site_config['base_url']."admin/tickets.php");
			} else {
				include("../../lib/headeradmin.php");
?>
						
                <div class="row">
                    <div class="offset-lg-2 col-lg-8">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="fa fa-trash"></i> Hapus Tiket</h4><hr>
							<form class="form-horizontal" role="form" method="POST" action="<?php echo $site_config['base_url']; ?>admin/tickets.php">
								<input type="hidden" name="id" value="<?php echo $data_ticket['id']; ?>">
								<div class="form-group row">
									<label class="col-md-2 control-label">Subject</label>
									<div class="col-md-10">
										<input type="text" class="form-control" placeholder="Subject" value="<?php echo $data_ticket['subject']; ?>" readonly>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-8 offset-2">
										<a href="<?php echo $site_config['base_url']; ?>admin/tickets.php" class="btn btn-secondary btn-bordred"><i class="fa fa-refresh"></i> Kembali</a>
										<button type="cancel" class="btn btn-custom btn-bordred" name="delete"><i class="fa fa-send"></i> Hapus </button>	
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