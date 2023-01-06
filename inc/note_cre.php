<?php

require("../mainconfig.php");

if (isset($_POST['credit'])) {
    
    $check_website = $db->query("SELECT * FROM website WHERE id = '1'");
    $data_website = $check_website->fetch_array(MYSQLI_ASSOC);

    if (mysqli_real_escape_string($db, $_POST['credit'] == "100")) {
            	$post_price = "7500";
            } else if (mysqli_real_escape_string($db, $_POST['credit'] == "500")) {
    	        $post_price = "25000";  
            } else if (mysqli_real_escape_string($db, $_POST['credit'] == "1000")) {
    	        $post_price = "40000";
    	    } else if (mysqli_real_escape_string($db, $_POST['credit'] == "2000")) {
    	        $post_price = "75000";    
            } 
}
	?>
	<div class="form-group row">
		<label class="col-md-2 control-label">Informasi</label>
	    <div class="col-md-10">
	    	<div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                - Saldo Anda Akan Terpotong Rp <?php echo number_format($post_price,0,',','.'); ?> Untuk Melakukan Pembelian Credit SMS <?php echo $_POST['credit']; ?>.<br />
			</div>
		</div>
	</div>