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


$check_order = $db->query("SELECT * FROM orders WHERE status IN ('Pending','Processing')");

if ($check_order->num_rows == 0) {
  die("Order Pending not found.");

} else {

  while($data_order = $check_order->fetch_array(MYSQLI_ASSOC)) {

    $o_oid = $data_order['oid'];
    $o_poid = $data_order['poid'];
    $o_provider = $data_order['provider'];
    
    $check_provider = $db->query("SELECT * FROM provider WHERE code = '$o_provider'");
    $data_provider = $check_provider->fetch_array(MYSQLI_ASSOC);
    
    $p_apikey = $data_provider['api_key'];
    $p_link = $data_provider['link'];
    
    if ($o_provider == "MANUAL") {
        echo "Order manual<br />";
    } else if ($o_provider == "FOLLOWIZ") {
        $order_postdata = "key=$p_apikey&action=status&order=$o_poid";  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://followiz.com/api/v2/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);

        $order_data = json_decode($chresult, true);
        $status = $order_data['status'];
        $start = $order_data['start_count'];
        $remains = $order_data['remains'];
    } else if ($o_provider == "JUSTANOTHER") {
        $order_postdata = "key=$p_apikey&action=status&order=$o_poid";  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://justanotherpanel.com/api/v2/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);

        $order_data = json_decode($chresult, true);
        $status = $order_data['status'];
        $start = $order_data['start_count'];
        $remains = $order_data['remains'];
    } else if ($o_provider == "VIPMEMBER") {
        $order_postdata = "api_key=$p_apikey&action=status&order_id=$o_poid";  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://vip-member.id/api/json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);

        $order_data = json_decode($chresult, true);
        $status = $order_data['data']['status'];
        $start = $order_data['data']['start_count'];
        $remains = $order_data['data']['remains'];
    } else if ($o_provider == "SMMPANELID") {
        $order_postdata = "api_key=$p_apikey&code=$o_poid";  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.regrampanel.com/order/status");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);

        $order_data = json_decode($chresult, true);
        $status = $order_data['data']['status'];
        $start = $order_data['data']['start_count'];
        $remains = $order_data['data']['remains']; 

    } else if ($o_provider == "IRVANKEDE") {
        $order_postdata = "api_id=8206&api_key=$p_apikey&id=$o_poid";  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://irvankede-smm.co.id/api/status");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);

        $order_data = json_decode($chresult, true);
        $status = $order_data['data']['status'];
        $start = $order_data['data']['start_count'];
        $remains = $order_data['data']['remains'];                    

                
    } else {
        echo "Not Found Provider Code";
    }
    
    if ($o_provider == "FOLLOWIZ") {
        
        if ($status == "Pending") {
            $real_status = "Pending";

        } else if ($status == "Processing") {
            $real_status = "Processing";

        } else if ($status == "In Progress") {
            $real_status = "Processing";    

        } else if ($status == "Partial") {
            $real_status = "Partial";

        } else if ($status == "Canceled") {
            $real_status = "Error";

        } else if ($status == "Error") {
            $real_status = "Error";    

        } else if ($status == "Completed") {
            $real_status = "Success";

        } else if ($status == "Success") {
            $real_status = "Success";    
        } else {
            $real_status = "Pending";
        }     
    } else if ($o_provider == "JUSTANOTHER") {
        
        if ($status == "Pending") {
            $real_status = "Pending";

        } else if ($status == "Processing") {
            $real_status = "Processing";

        } else if ($status == "In Progress") {
            $real_status = "Processing";    

        } else if ($status == "Partial") {
            $real_status = "Partial";

        } else if ($status == "Canceled") {
            $real_status = "Error";

        } else if ($status == "Error") {
            $real_status = "Error";    

        } else if ($status == "Completed") {
            $real_status = "Success";

        } else if ($status == "Success") {
            $real_status = "Success";    
        } else {
            $real_status = "Pending";
        }        
    } else if ($o_provider == "VIPMEMBER") {
        
        if ($status == "Pending") {
            $real_status = "Pending";

        } else if ($status == "Processing") {
            $real_status = "Processing";

        } else if ($status == "In Progress") {
            $real_status = "Processing";    

        } else if ($status == "Partial") {
            $real_status = "Partial";

        } else if ($status == "Canceled") {
            $real_status = "Error";

        } else if ($status == "Error") {
            $real_status = "Error";    

        } else if ($status == "Completed") {
            $real_status = "Success";

        } else if ($status == "Success") {
            $real_status = "Success";    
        } else {
            $real_status = "Pending";
        }       
    } else if ($o_provider == "SMMPANELID") {
        
        if ($status == "Pending") {
            $real_status = "Pending";

        } else if ($status == "Processing") {
            $real_status = "Processing";

        } else if ($status == "In Progress") {
            $real_status = "Processing";    

        } else if ($status == "Partial") {
            $real_status = "Partial";

        } else if ($status == "Canceled") {
            $real_status = "Error";

        } else if ($status == "Error") {
            $real_status = "Error";    

        } else if ($status == "Completed") {
            $real_status = "Success";

        } else if ($status == "Success") {
            $real_status = "Success";    
        } else {
            $real_status = "Pending";
        }     
    } else if ($o_provider == "IRVANKEDE") {
        
        if ($status == "Pending") {
            $real_status = "Pending";

        } else if ($status == "Processing") {
            $real_status = "Processing";

        } else if ($status == "In Progress") {
            $real_status = "Processing";    

        } else if ($status == "Partial") {
            $real_status = "Partial";

        } else if ($status == "Canceled") {
            $real_status = "Error";

        } else if ($status == "Error") {
            $real_status = "Error";    

        } else if ($status == "Completed") {
            $real_status = "Success";

        } else if ($status == "Success") {
            $real_status = "Success";    
        } else {
            $real_status = "Pending";
        }     
    }
    if (empty($real_status)) {
        $real_status = "Pending";
    }
    
    
    $update_order = $db->query("UPDATE orders SET status = '$real_status', start_count = '$start', remains = '$remains' WHERE oid = '$o_oid'");
    if ($update_order == TRUE) {
      echo "Provider => $o_provider | Order ID => $o_oid | Status $real_status | Start Count => $start | Remains Count => $remains<br />";
    } else {
      echo "Error database.";
    }
  }
}