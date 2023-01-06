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
$page_type = "Berita & Informasi";

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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-newspaper"></i> Berita & Informasi</h4><hr>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th data-toggle="true">Konten</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $check_news = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC");
                                $no = 1;
                                while ($data_news = mysqli_fetch_assoc($check_news)) {
                                    if ($data_news['category'] == "Info") {
                                        $label = "success";
                                    } else if ($data_news['category'] == "Event") {
                                        $label = "primary";
                                    } else if ($data_news['category'] == "Update") {
                                        $label = "info"; 
                                    } else if ($data_news['category'] == "Service") {
                                        $label = "warning";       
                                    }
                                ?>
                                        <tr class="table-<?php echo $label; ?>">
                                            <td><?php echo TanggalIndo($data_news['date']); ?> <?php echo $data_news['time']; ?></td>
                                            <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_news['category']; ?></span></td>
                                            <td><?php echo $data_news['content']; ?></td>
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