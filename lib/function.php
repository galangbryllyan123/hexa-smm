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

function random($length) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function format_date($date) {
	$split = explode("-", $date);
	$month = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	if (in_array($split[1], array_keys($month)) == false) {
		return "Something went wrong.";
	}
	return $split[2].' '.$month[$split[1]].' '.$split[0];
}

function encrypt( $q ) {
	$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
}

function check_empty($input) {
	$result = true;
	foreach ($input as $key => $value) {
		$result = false;
		if (empty($value) == true) {
			$result = true;
			break;
		}
	}
	return $result;
}

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP')) {
		$ipaddress = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
		$ipaddress = getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
		$ipaddress = getenv('HTTP_FORWARDED');
	} elseif (getenv('REMOTE_ADDR')) {
		$ipaddress = getenv('REMOTE_ADDR');
	} else {
		$ipaddress = 'UNKNOWN';
	}
	return $ipaddress;
}

function TanggalIndo($date)
{
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
 
	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
	return($result);
}

function random_number($length) {
	$str = "";
	$characters = array_merge(range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function random_code($length) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}