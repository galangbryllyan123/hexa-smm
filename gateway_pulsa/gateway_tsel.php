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

require_once "config.php";
require_once "EnvayaSMS.php";
require_once "../mainconfig.php";

$request = EnvayaSMS::get_request();
header("Content-Type: {$request->get_response_type()}");

if (!$request->is_validated($PASSWORD)) {
    header("HTTP/1.1 403 Forbidden");
    error_log("Invalid password");    
    echo $request->render_error_response("Invalid password");
    return;
}

$action = $request->get_action();
switch ($action->type) {
    case EnvayaSMS::ACTION_INCOMING:        
        $type = strtoupper($action->message_type);
        $message = $action->message;

    if ($action->from == '858' AND preg_match("/Anda mendapatkan penambahan pulsa/i", $message)) {
        $message = $action->message;
        $insert  = $db->query("INSERT INTO pesan_tsel (isi, status, date) VALUES ('$message', 'UNREAD', '$date')");
        $check   = $db->query("SELECT * FROM deposit WHERE status = 'Pending' AND method = 'Telkomsel - AUTO' AND date = '$date'");
        if ($check->num_rows == 0) {
            error_log("History TopUp Not Found");
        } else {
             while ($data_deposit = mysqli_fetch_assoc($check)) {

                $deposit_id = $data_deposit['code'];
                $sender     = $data_deposit['sender'];
                $username   = $data_deposit['username'];
                $amount     = $data_deposit['balance'];
                $date_req   = $data_deposit['date'];
                $quantity   = $data_deposit['quantity'];
                $cekpesan   = preg_match("/Anda mendapatkan penambahan pulsa Rp $quantity dari nomor $sender tgl $date_req/i", $message);

                if ($cekpesan == TRUE) {
                    $update = $db->query("UPDATE deposit SET status = 'Success' WHERE code = '$deposit_id'");
                    $update = $db->query("UPDATE users SET balance = balance+$amount WHERE username = '$username'");
                    if ($data_deposit == TRUE) {
                        $update_depo = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$username', 'Plus', 'Deposit', '$amount', 'Deposit #$deposit_id', '$date', '$time')");
                        error_log("Saldo $username Telah Ditambahkan Sebesar $amount");
                    } else {
                        error_log("System Error");
                    }
                } else {
                    error_log("data Transfer Pulsa Tidak Ada");
            }
        }
    }
} else {
    error_log("Received $type from {$action->from}");
    error_log("Message: {$action->message}");
}
return;
}