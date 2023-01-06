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
$page_type = "Pertanyaan Umum";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    }
}

include("../lib/header.php");
?>
				<div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-comment-question-outline"></i> Pertanyaan Umum</h4><hr>
							1. Bagaimana cara mendaftar?<br>
                                Silahkan menghubungi Admin untuk mendaftar, silahkan menuju halaman <a href="<?php echo $site_config['base_url']; ?>page/contact">KONTAK</a> untuk melihat kontak Admin.<br><br><hr>
                                        
                            2. Bagaimana cara membuat pesanan?<br>
                                Silahkan menuju halaman PEMESANAN       untuk membuat pesanan, kemudian         bacalah informasi pada halaman          tersebut sebelum membuat pesanan        baru.<br><br><hr>
                                       
                            3. Bagaimana cara deposit/isi saldo?<br>
                                Silahkan menuju halaman deposit baru    untuk membuat permintaan deposit,       kemudian bacalah informasi pada         halaman tersebut sebelum membuat        deposit baru. Kami menyediakan dua      jenis pilihan untuk melakukan deposit    yaitu: Deposit manual dan deposit       otomatis.<br>

						</div>
					</div>
				</div>
<?php
include("../lib/footer.php");
?>