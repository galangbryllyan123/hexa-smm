<?php

// Status
// 1 : Pending
// 2 : Gagal / Error
// 3 : Refund
// 4 : Sukses

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
 
$a = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE status = 'Pending' AND provider = 'PORTALPULSA'");
while($b = mysqli_fetch_assoc($a)) {
    $required = "STATUS";
    $oid = $b['oid'];
    $trxid_api = $b['poid'];
   
            $api_link = "https://portalpulsa.com/api/connect/";
            $api_header = array(
                            'portal-userid: P95132',
                            'portal-key: d4044dc3992b037d625f00d5729ac574', // lihat hasil autogenerate di member area
                            'portal-secret: 49e6dc118fc9992efb784abad34a764a4466079b7d3b2ff11b39e17b8a927123', // lihat hasil autogenerate di member area
                        );
            $api_postdata = array(
                                  'inquiry' => $required, // konstan
                                  'trxid_api' => $trxid_api,
                                 );
                             
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $api_link);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
           curl_setopt($ch, CURLOPT_HTTPHEADER, $api_header);
           curl_setopt($ch, CURLOPT_POST, 1);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
           $result = curl_exec($ch);    
           $json_result = json_decode($result, true);
           $messages = $json_result['message'];  
           $status = $json_result['message'][0]['status'];
           $sn = $json_result['message'][0]['sn'];
 
            if($status == "1") {
             $sets = "Pending";
            } else if($status == "2") {
             $sets = "Error";
            } else if($status == "3") {
             $sets = "Error";
            } else if($status == "4") {
             $sets = "Success";
           }    
           $kodok = mysqli_query($db, "UPDATE orders_pulsa set status = '$sets', sn = '$sn' WHERE oid = '$oid'");
           if($kodok == TRUE) {
               $array = array("oid" => $oid,
                              "status" => $sets,
                              "serial_number" => $sn,
                              "update" => "ok");
           } else {
               $array = array("oid" => $oid,
                              "status" => $sets,
                              "serial_number" => $sn,
                              "update" => "error");
           }
           
           $array = json_encode($array);
           $array = json_decode($array);
           
           print '<pre> '.print_r($array, true).' </pre>';
}