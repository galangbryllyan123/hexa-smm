<?php

require("../mainconfig.php");

if (isset($_POST['level'])) {
    
    $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
    $data_website = $check_website->fetch_array(MYSQLI_ASSOC);

    if (mysqli_real_escape_string($db, $_POST['level'] == "Member")) {
	    $post_price = "0";
	    
	} else if (mysqli_real_escape_string($db, $_POST['level'] == "Agen")) {
    	$post_price = "50000";
    	    
    } 
}
	?>
	<div class="form-group row">
		<label class="col-md-2 control-label">Informasi</label>
	    <div class="col-md-10">
	    	<div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                - Saldo Anda Akan Terpotong Rp <?php echo number_format($post_price,0,',','.'); ?> Untuk Melakukan Upgrade Ke Level <?php echo $_POST['level']; ?>.<br />
			</div>
		</div>
	</div>