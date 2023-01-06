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

require("../../mainconfig.php");

$check_order = $db->query("SELECT * FROM orders WHERE status IN ('Error','Partial') AND refund = 'false'");

if ($check_order->num_rows == 0) {
	die("Order Error or Partial not found.");
} else {

	while($data_order = $check_order->fetch_array(MYSQLI_ASSOC)) {

		$o_oid = $data_order['oid'];
		$u_remains = $data_order['remains'];
		$priceone = $data_order['price'] / $data_order['quantity'];
		$refund = $priceone * $u_remains;
		$buyer = $data_order['user'];

		if ($u_remains == 0) {
		    $refund = $data_order['price'];
		}
		    
		$update_user = mysqli_query($db, "UPDATE users SET balance = balance+$refund, tabungan = tabungan+10 WHERE username = '$buyer'");
    	$update_order = mysqli_query($db, "UPDATE orders SET refund = 'true'  WHERE oid = '$o_oid'");
   		$update_order = mysqli_query($db, "INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$buyer', 'Plus', 'Refund Saldo', '$refund', 'Refund #$o_oid', '$date', '$time')");
    	if ($update_order == TRUE) {
    		echo "Refunded Rp $refund - $o_oid<br />";
   		} else {
    		echo "Error database.";
    	}
	}
}
?>