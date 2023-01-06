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
header("Content-Type: application/json");

if (isset($_POST['key']) AND isset($_POST['action'])) {
	$post_key = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['key'], ENT_QUOTES))));
	$post_action = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['action'], ENT_QUOTES))));
	if (empty($post_key) || empty($post_action)) {
		$array = array("error" => "Bad Request (1)");
	} else {
		$check_user = $db->query("SELECT * FROM users WHERE api_key = '$post_key'");
		$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
		if ($check_user->num_rows == 1) {
			$username = $data_user['username'];
			if ($post_action == "add") {
				if (isset($_POST['service']) AND isset($_POST['phone'])) {
					$post_service = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['service'], ENT_QUOTES))));
					$post_phone = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['phone'], ENT_QUOTES))));
					if (empty($post_service) || empty($post_phone)) {
						$array = array("error" => "Bad Request (2)");
					} else {
						$check_service = $db->query("SELECT * FROM services_pulsa WHERE sid = '$post_service' AND status = 'Active'");
						$data_service = $check_service->fetch_array(MYSQLI_ASSOC);
						if ($check_service->num_rows == 0) {
							$array = array("error" => "Service Not Found");
						} else {
							$oid = random_number(3).random_number(4);
							$check_price = $data_service['price'];
							$rate = $data_rate['rate'];
							$price = $check_price;
							$service = $data_service['service'];
							$provider = $data_service['provider'];
							$pid = $data_service['pid'];
							if ($data_user['balance'] < $price) {
								$array = array("error" => "Low balance");
							} else {
								$check_provider = $db->query("SELECT * FROM provider WHERE code = '$provider'");
								$data_provider = $check_provider->fetch_array(MYSQLI_ASSOC);
								$provider_key = $data_provider['api_key'];
								$provider_link = $data_provider['link'];
	
								$random_trxid = random_number(1).random_number(2);

								if ($data_service['category'] == "PLN") {
                    		        $post_listrik = $_POST['no_listrik'];
                    		    
                                    $data = array( 
                                    'inquiry' => 'PLN', // konstan
                                    'code' => $pid, // kode produk
                                    'phone' => $post_phone, // nohp pembeli
                                    'idcust' => $post_listrik, // nomor meter atau id pln
                                    'trxid_api' => $random_trxid, // Trxid / Reffid dari sisi client
                                    'no' => $count_orders+1, // untuk isi lebih dari 1x dlm sehari, isi urutan 2,3,4,dst
                                    );
                                
                    		    } else if ($data_service['category'] !== "PLN") {
                    		        $data = array(
                                    'inquiry' => 'I', // konstan
                                    'code' => $pid, // kode produk
                                    'phone' => $post_phone, // nohp pembeli
                                    'trxid_api' => $random_trxid, // Trxid / Reffid dari sisi client
                                    'no' => $count_orders+1, // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
                                    );
                    		    } else {
                                    $array = array("error" => "Provider not found");
							    }
							    
							    if ($provider == "MANUAL") {
                    		        $api_postdata = "";
                    				$poid = $oid;
                    		    } else if ($provider == "DP") {
                                	$postdata = "api_key=$api_key&service=$pid&phone=$post_phone";	
                                	$ch = curl_init();
                                	curl_setopt($ch, CURLOPT_URL, "https://serverh2h.id/order/pulsa");
                                	curl_setopt($ch, CURLOPT_POST, 1);
                                	curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                                	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                	$chresult = curl_exec($ch);
                                	curl_close($ch);
                                	$order_data = json_decode($chresult, true);
                                	$poid = $order_data['code_trx'];
                                	$error = $order_data['error'];
                                	$msg = $order_data['error'];       
                                } else {
                                    $array = array("error" => "Provider not found");
                                }
                
								
								if ($provider == "DP" AND $order_data['error'] == TRUE) {
                    			    error_log("Pesan : ".$order_data['error']);
                    				$array = array("error" => "Please Contact Admin.");
                    			} else {
									$poid = $oid;
									$update_user = $db->query("UPDATE users SET balance = balance-$price WHERE username = '$username'");
									if ($update_user == TRUE) {
										$insert_order = $db->query("INSERT INTO orders_pulsa (oid, poid, user, service, link, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$username', '$service', '$post_phone', '$price', 'Pending', '$date', '$provider', 'API')");
										$insert_order = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$username', 'Minus', 'Place Order', '$price', 'Membuat pesanan #$oid', '$date', '$time')");
										if ($insert_order == TRUE) {
											$array = array("order_id" => "$oid",
											"numberphone" => "$post_phone");
										} else {
											$array = array("error" => "System error (1)");
										}
									} else {
										$array = array("error" => "System error (2)");
									}
								}
							}
						}
					}
					
				} else {
					$array = array("error" => "Bad Request (3)");
				}
			} else if ($post_action == "status") {
				if (isset($_POST['order_id'])) {
					$post_oid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['order_id'], ENT_QUOTES))));
					$post_oid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['order_id'], ENT_QUOTES))));
					$check_order = $db->query("SELECT * FROM orders_pulsa WHERE oid = '$post_oid'");
					$data_order = $check_order->fetch_array(MYSQLI_ASSOC);
					if ($check_order->num_rows == 0) {
						$array = array("error" => "Order not found");
					} else {
						$array = array("charge" => $data_order['price'], "service" => $data_order['service'], "status" => $data_order['status']);
					}
				} else {
					$array = array("error" => "Incorrect request 4");
				} else if ($post_action == "services"){
			    $check_service = $db->query("SELECT * FROM services_pulsa");
			    while($data_service = mysqli_fetch_assoc($check_service)){
			        $array[] = array("sid" => $data_service['sid'], "service" => $data_service['service'], "category" => $data_service['category'], "harga" => $data_service['price'], "status" => $data_service['status']);
			
			} else {
				$array = array("error" => "Wrong action");
			}
		} else {
			$array = array("error" => "Invalid API key");
		}
	}
} else {
	$array = array("error" => "Incorrect request 5");
}

$print = json_encode($array);
print_r($print);