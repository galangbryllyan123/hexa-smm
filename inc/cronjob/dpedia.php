<?php
require("../../mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE status IN ('','Pending','Processing') AND provider = 'DP'");

if (mysqli_num_rows($check_order) == 0) {
  die("Order Pending not found.");
} else {
  while($data_order = mysqli_fetch_assoc($check_order)) {
    $o_oid = $data_order['oid'];
    $o_poid = $data_order['oid'];
  if ($o_provider == "MANUAL") {
    echo "Order manual<br />";
  } else {
    $api_postdata = "api_key=hsAfNOPkRBjeFcwc7Zmv&code=$o_poid";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://serverh2h.net/status/pulsa");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $json_result = json_decode($chresult, true);
    $u_status = $json_result['status'];
    $u_catatan = $json_result['catatan'];
    
    $update_order = mysqli_query($db, "UPDATE orders_pulsa SET status = '$u_status' WHERE oid = '$o_oid'");
    $update_order = mysqli_query($db, "UPDATE orders_pulsa SET sn = '$u_catatan' WHERE oid = '$o_oid'");
    if ($update_order == TRUE) {
      echo "$o_oid - status : $u_status - Catatan : $u_catatan<br /> $chresult";
    } else {
      echo "Error database.";
    }
  }
  }
}