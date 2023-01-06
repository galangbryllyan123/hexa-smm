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
	$post_key    = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['key'], ENT_QUOTES))));
	$post_action = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['action'], ENT_QUOTES))));
	if (empty($post_key) || empty($post_action)) {
		$array = array("error" => "Incorrect request");
	} else {
		$check_user = $db->query("SELECT * FROM users WHERE api_key = '$post_key'");
		$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
		if ($check_user->num_rows == 1) {
			$username = $data_user['username'];
			if ($post_action == "add") {
				if (isset($_POST['service']) AND isset($_POST['link']) AND isset($_POST['quantity'])) {
					$post_service = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['service'], ENT_QUOTES))));
					$post_link = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['link'], ENT_QUOTES))));
					$post_quantity = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['quantity'], ENT_QUOTES))));
					if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
						$array = array("error" => "Incorrect request");
					} else {
						$check_service = $db->query("SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
						$data_service = $check_service->fetch_array(MYSQLI_ASSOC);
						if ($check_service->num_rows == 0) {
							$array = array("error" => "Service not found");
						} else {
							$oid = random_number(7);
							$rate = $data_service['price'] / 1000;
							$price = $rate*$post_quantity;
							$service = $data_service['service'];
							$provider = $data_service['provider'];
							$pid = $data_service['pid'];
							if ($post_quantity < $data_service['min']) {
								$array = array("error" => "Quantity inccorect");
							} else if ($post_quantity > $data_service['max']) {
								$array = array("error" => "Quantity inccorect");
							} else if ($data_user['balance'] < $price) {
								$array = array("error" => "Low balance");
							} else {
								$check_provider = $db->query("SELECT * FROM provider WHERE code = '$provider'");
								$data_provider = $check_provider->fetch_array(MYSQLI_ASSOC);
								$provider_key = $data_provider['api_key'];
								$provider_link = $data_provider['link'];

								if ($provider == "MANUAL") {
									$api_postdata = "";
									$poid = $oid;  
								} else if ($provider == "IRVANKEDE") {
									$order_postdata = "api_id=$provider_link&api_key=$provider_key&service=$pid&target=$post_link&quantity=$post_quantity";	
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, "https://irvankede-smm.co.id/api/order");
									curl_setopt($ch, CURLOPT_POST, 1);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									$chresult = curl_exec($ch);
									curl_close($ch);

									$order_data = json_decode($chresult, true);
									$poid = $order_data['data']['id'];
									$error = $order_data['status'];
									$msg = $order_data['data']['msg'];
								} else if ($provider == "PRINCEPEDIA") {
									$order_postdata = "key=$provider_key&action=add&service=$pid&link=$post_link&quantity=$post_quantity";	
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, "https://prince-pedia.web.id/api.php");
									curl_setopt($ch, CURLOPT_POST, 1);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									$chresult = curl_exec($ch);
									curl_close($ch);

									$order_data = json_decode($chresult, true);
									$poid = $order_data['order_id'];
									$error = $order_data['error'];
									$msg = $order_data['error'];	 	           
								} else {
									die("System Error");  
								}

								if ($provider == "IRVANKEDE" AND $order_data['status'] == FALSE) {
									$array = array("error" => "$msg");
								} else if ($provider == "PRINCEPEDIA" AND $order_data['error'] == TRUE) {
									$array = array("error" => "$msg");
								} else if ($provider == "JUSTANOTHER" AND $order_data['error'] == TRUE) {
									$array = array("error" => "$msg");
								} else if ($provider == "FOLLOWIZ" AND $order_data['error'] == TRUE) {
									$array = array("error" => "$msg");		
								} else {
									$update_user = $db->query("UPDATE users SET balance = balance-$price WHERE username = '$username'");
									if ($update_user == TRUE) {
										$insert_order = $db->query("INSERT INTO orders (oid, poid, user, service, link, quantity, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$username', '$service', '$post_link', '$post_quantity', '$price', 'Pending', '$date', '$provider', 'API')");
										$insert_order = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$username', 'Minus', 'Place Order', '$price', 'Membuat pesanan #$oid', '$date', '$time')");
										if ($insert_order == TRUE) {
											$array = array("order_id" => "$oid");
										} else {
											$array = array("error" => "System error");
										}
									} else {
										$array = array("error" => "System error");
									}
								}
							}
						}
					}
				} else {
					$array = array("error" => "Incorrect request");
				}
			} else if ($post_action == "status") {
				if (isset($_POST['order_id'])) {
					$post_oid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['order_id'], ENT_QUOTES))));
					$post_oid = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['order_id'], ENT_QUOTES))));
					$check_order = $db->query("SELECT * FROM orders WHERE oid = '$post_oid' AND user = '$username'");
					$data_order = $check_order->fetch_array(MYSQLI_ASSOC);
					if ($check_order->num_rows == 0) {
						$array = array("error" => "Order not found");
					} else {
						$array = array("charge" => $data_order['price'], "start_count" => $data_order['start_count'], "status" => $data_order['status'], "remains" => $data_order['remains']);
					}
				} else {
					$array = array("error" => "Incorrect request");
				}	
			} else if ($post_action == "services") {
			    $check_service = $db->query("SELECT * FROM services");
			    while($data_service = $check_service->fetch_array(MYSQLI_ASSOC)) {
			        $array[] = array("sid" => $data_service['sid'], "category" => $data_service['category'], "service" => $data_service['service'], "note" => $data_service['note'], "min" => $data_service['min'], "max" => $data_service['max'], "price" => $data_service['price']);
			}
			} else {
				$array = array("error" => "Wrong action");
			}
		} else {
			$array = array("error" => "Invalid API key");
		}
	}
} else {
	$array = array("error" => "Incorrect request");
}

$print = json_encode($array);
print_r($print);