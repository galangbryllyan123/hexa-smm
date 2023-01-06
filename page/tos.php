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
$page_type = "Ketentuan Layanan";

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
                                        <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-information-variant"></i> Ketentuan Layanan</h4><hr>
										<p>Layanan yang disediakan oleh <?php echo $cfg_webname; ?> telah ditetapkan kesepakatan-kesepakatan berikut.</p>
										<p><b>1. Umum</b>
										<br />Dengan mendaftar dan menggunakan layanan <?php echo $cfg_webname; ?>, Anda secara otomatis menyetujui semua ketentuan layanan kami. Kami berhak mengubah ketentuan layanan ini tanpa pemberitahuan terlebih dahulu. Anda diharapkan membaca semua ketentuan layanan kami sebelum membuat pesanan.
										<br />Penolakan: <?php echo $cfg_webname; ?> tidak akan bertanggung jawab jika Anda mengalami kerugian dalam bisnis Anda.
										<br />Kewajiban: <?php echo $cfg_webname; ?> tidak bertanggung jawab jika Anda mengalami suspensi akun atau penghapusan kiriman yang dilakukan oleh Instagram, Twitter, Facebook, Youtube, dan lain-lain.
										<br /><b>2. Layanan</b>
										<br /><?php echo $cfg_webname; ?> hanya digunakan untuk media promosi sosial media dan membantu meningkatkan penampilan akun Anda saja.
										<br /><?php echo $cfg_webname; ?> tidak menjamin pengikut baru Anda berinteraksi dengan Anda, kami hanya menjamin bahwa Anda mendapat pengikut yang Anda beli.
										<br /><?php echo $cfg_webname; ?> tidak menerima permintaan pembatalan/pengembalian dana setelah pesanan masuk ke sistem kami. Kami memberikan pengembalian dana yang sesuai jika pesanan tida dapat diselesaikan.</p>
									</div>
								</div>
							</div>
						<!-- end row -->
<?php
include("../lib/footer.php");
?>