<?php

require("../../mainconfig.php");

$check_orders = $db->query("SELECT * FROM orders WHERE status IN ('Success') AND refund = 'true'");

if ($check_orders->num_rows == 0) {
	die("Order Error or Partial not found.");
} else {

	while($data_orders = $check_orders->fetch_array(MYSQLI_ASSOC)) {

		$buyers = $data_orders['user'];
		    
		$update_users = mysqli_query($db, "UPDATE users SET tabungan = tabungan+10 WHERE username = '$buyers'");
    	
	}
	}
?>