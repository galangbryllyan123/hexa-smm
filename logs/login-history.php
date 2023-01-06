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
$page_type = "Log Masuk";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    }

    include("../lib/header.php");
    $msg_type = "nothing";

    
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);

?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-login"></i> Log Masuk</h4><hr>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                    			    <thead>
									    <tr>
										    <th>Tanggal/Waktu</th>
										    <th>Alamat IP</th>
									    </tr>
								    </thead>
								    <tbody>
                                    <?php
                                    $check_logs = $db->query("SELECT * FROM login_history WHERE username = '$sess_username' ORDER BY id DESC"); // edit
                                    while ($data_logs = $check_logs->fetch_assoc()) {
                                    ?>

                                        <tr>
                                	       <td><?php echo TanggalIndo($data_logs['date']); ?> (<?php echo $data_logs['time']; ?>)</td>
                                	       <td><?php echo $data_logs['adress']; ?></td>
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
