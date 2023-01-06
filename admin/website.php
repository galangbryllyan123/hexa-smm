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
$page_type = "Kelola Website";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout.php");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout.php");
    } else if ($data_user['level'] != "Developers") {
        header("Location: ".$site_config['base_url']);
    } else {
		if (isset($_POST['setting'])) { 

		    $post_title = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['title'], ENT_QUOTES))));
            $post_description = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['description'], ENT_QUOTES))));
            $post_keyword = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['keyword'], ENT_QUOTES))));
            $post_logotext = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['logo_text'], ENT_QUOTES))));

            $wa = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['wa'], ENT_QUOTES))));
            $fb = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['fb'], ENT_QUOTES))));
            $line = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['line'], ENT_QUOTES))));

		    if(empty($post_title) || empty($post_description) || empty($post_keyword) || empty($post_logotext)) {
		           $msg_type = "error";
				   $msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
		        } else {
		             $update_status = mysqli_query($db, "UPDATE website SET title = '$post_title', description = '$post_description', keyword = '$post_keyword', logo_text = '$post_logotext', whatsapp = '$wa', facebook = '$fb', line = '$line' WHERE id = '1'");
		             if($update_status == TRUE){
		                $msg_type = "success";
					    $msg_content = "<b>Berhasil:</b> Website berhasil di konfigurasi.";
				     } else {
					    $msg_type = "error";
					    $msg_content = "<b>Gagal:</b> Error system.";
		        }
		    }
		}
		$checkdb_service = mysqli_query($db, "SELECT * FROM website WHERE id = '1'");
		$datadb_service = mysqli_fetch_assoc($checkdb_service);
		include("../lib/headeradmin.php");
?>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Kelola Website</h4><hr>
                
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
                    
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <form class="" role="form" method="POST">
                                    <div class="form-group">
                                        <label>Nama Website</label>
                                        <input type="text" class="form-control" name="title" placeholder="Service Name" value="<?php echo $datadb_service['title']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Logo Text</label>
                                        <input type="text" class="form-control" name="logo_text" placeholder="Service Name" value="<?php echo $datadb_service['logo_text']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Deksripsi Website</label>
                                        <textarea rows="6" cols="200" name="description" class="form-control"><?php echo $datadb_service['description']; ?></textarea>
                                    </div>
                                    

                            </div>
                        </div>        

                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-20">
                                <div class="form-group">
                                    <label>Kontak</label>
                                    <input type="text" class="form-control" name="wa" placeholder="082297387348" value="<?php echo $datadb_service['whatsapp']; ?>">
                                </div>
                                <div class="m-t-10">
                                    <input type="text" class="form-control" name="fb" placeholder="http://fb.com/hirpayzzz" value="<?php echo $datadb_service['facebook']; ?>">
                                </div>
                                <div class="m-t-10">
                                    <input type="text" class="form-control" name="line" placeholder="mahirdepay29" value="<?php echo $datadb_service['line']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Keyword Website</label>
                                    <textarea rows="6" cols="200" name="keyword" class="form-control"><?php echo $datadb_service['keyword']; ?></textarea>
                                </div>
                            </div>
                            <br />
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-custom btn-bordred btn-block waves-effect w-md waves-light" name="setting">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php
include("../lib/footer.php");
}
} else {
	header("Location: ".$site_config['base_url']);
}
?>