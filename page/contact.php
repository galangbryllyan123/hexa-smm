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
$page_type = "Kontak";

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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-cellphone-setting"></i> Kontak Kami</h4><hr>
                            	<p>
                            	    Anda dapat mengajukan pertanyaan, meminta bantuan, memberikan saran, dan komplain transaksi kepada CS kami setiap hari pada jam jam kerja. Kami akan berusaha dengan segera menjawab pertanyaan dari Anda di jam kerja. Pertanyaan diluar jam kerja tetap akan dijawab di jam kerja. Berikut jam kerja CS <b><?php echo $data_website['title']; ?></b>:
                            	</p><br>
                            	<ul>
                                    <li>Senin - Jumat : 8.00 - 22.00 WIB</li>
                                    <li>Sabtu - Minggu : 8.00 - 20.00 WIB</li>
                                    <li>Istirahat : 12.00 - 12.45 WIB</li>
                                </ul><hr>
                                <b>Berikut kontak yang dapat Anda hubungi jika ada kendala:</b><br>
                                <ul>
                                	<li>Line: <a href="http://line.me/ti/p/~<?php echo $data_website['line']; ?>" target="_blank"><b>Line</b></a></li>
                                	<li>Instagram: <a href="https://instagram.com/rifqi_resfether"><b>Instagram</b></a></li>
                                	<li>Facebook: <a href="<?php echo $data_website['facebook']; ?>"><b>Facebook (Rifqi Resfether)</b></a></li>
                              
                                	
                                	<li>WhatsApp: <a href="https://api.whatsapp.com/send?phone=<?php echo $data_website['whatsapp']; ?>" target="_blank"><b>Whatsapp (Rifqi Resfether)</b></a></li>
                                	
                                	Anda dapat melakukan Komplain/Deposit/Pertanyaan/Info Teknis API/Keluhan atas kinerja CS dengan menghubungi kontak kami diatas!
                                </ul><hr>
                                <b>Prosedur jika ingin melakukan komplain kepada CS kami:</b>
                                <ul>
                                	<li>Jika komplain transaksi pesanan belum masuk. Silakan sebutkan id pesanan, data target, dan tipe pesanan (semisal: followers, like, dsb.).</li>
                                    <li>Usahakan untuk komplain via Open Tiket, Facebook, atau LINE CS karena lebih cepat responnya daripada komplain via SMS.</li>
                                    <li>Jangan memberitahukan / menyebutkan Password Anda kepada siapapun termasuk CS. Karena kami tidak membutuhkan data tersebut.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
						<!-- end row -->
<?php
include("../lib/footer.php");
?>