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
$page_type = "Riwayat Deposit";

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
?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-refresh"></i> Riwayat Deposit</h4><hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Faktur</th>
                                        <th>Metode</th>
                                        <th>Jumlah Transfer</th>
                                        <th>Saldo didapat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $check_deposit = mysqli_query($db, "SELECT * FROM deposit WHERE username = '$sess_username' ORDER BY id DESC");
                                $no = 1;
                                while ($data_deposit = mysqli_fetch_assoc($check_deposit)) {

                                    if ($data_deposit['status'] == "Pending") {
                                        $label = "warning";
                                    } else if($data_deposit['status'] == "Error") {
                                        $label = "danger";
                                    } else if($data_deposit['status'] == "Success") {
                                        $label = "success";
                                    }                                                       
                                ?>                                                  
                                    <tr class="table-<?php echo $label; ?>">
                                        <td><?php echo $data_deposit['id']; ?></td>
                                        <td><?php echo $data_deposit['date']; ?></td>
                                        <td><?php echo $data_deposit['code']; ?></td>
                                        <td><?php echo $data_deposit['method']; ?></td>
                                        <td><?php echo $data_deposit['quantity']; ?></td>
                                        <td><?php echo $data_deposit['balance']; ?></td>
                                        <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_deposit['status']; ?></span></td>
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
	header("Location: ".$cfg_baseurl);
}
?>