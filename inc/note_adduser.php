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

require("../mainconfig.php");

if (isset($_POST['level'])) {
    
    $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
    $data_website = $check_website->fetch_array(MYSQLI_ASSOC);

    if (mysqli_real_escape_string($db, $_POST['level'] == "Member")) {
	    $post_price = "0";
	    $post_balance = "0";
	
	} else if (mysqli_real_escape_string($db, $_POST['level'] == "Agen")) {
    	$post_price = "30000";
    	$post_balance = "10000";
    	    
    } else if (mysqli_real_escape_string($db, $_POST['level'] == "Reseller")) {
	    $post_price = "100000";
	    $post_balance = "50000";
	    
    } else if (mysqli_real_escape_string($db, $_POST['level'] == "Admin")) {
	    $post_price = "150000";
	    $post_balance = "75000";
	}
}
	?>
	<div class="form-group row">
		<label class="col-md-2 control-label">Informasi</label>
	    <div class="col-md-10">
	    	<div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                - Saldo Anda Akan Terpotong Rp <?php echo number_format($post_price,0,',','.'); ?> Untuk Menambahkan Level <?php echo $_POST['level']; ?>.<br />
			</div>
		</div>
	</div>