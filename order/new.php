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

session_start();
require("../mainconfig.php");
$page_type = "Buat Pesanan";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    }

	include("../lib/header.php");
	$msg_type = "nothing";

	if (isset($_POST['order'])) {
		$post_service  = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['service'], ENT_QUOTES))));
		$post_quantity = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['quantity'], ENT_QUOTES))));
		$post_link = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['link'], ENT_QUOTES))));
		$post_category = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['category'], ENT_QUOTES))));
		$check_service = $db->query("SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
		$data_service = $check_service->fetch_array(MYSQLI_ASSOC);

        $check_orders = $db->query("SELECT * FROM orders WHERE link = '$post_link' AND status IN ('Pending','Processing')");
        $data_orders = $check_orders->fetch_array(MYSQLI_ASSOC);
		$rate = $data_service['price'] / 1000;
		$price = $rate*$post_quantity;
		$oid = random_number(3).random_number(4);
		$service = $data_service['service'];
		$provider = $data_service['provider'];
		$pid = $data_service['pid'];
		
		
		$data_users = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
		$data_show = $data_users->fetch_array(MYSQLI_ASSOC);

		$check_provider = $db->query("SELECT * FROM provider WHERE code = '$provider'");
		$data_provider = $check_provider->fetch_array(MYSQLI_ASSOC);
		
		$check_provider1 = $db->query("SELECT * FROM provider WHERE code = 'DP'");
		$data_provider1 = $check_provider1->fetch_array(MYSQLI_ASSOC);
		
		if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Mohon mengisi input.";
		} else if (mysqli_num_rows($check_service) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Layanan tidak ditemukan.";
		} else if (mysqli_num_rows($check_provider) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Server Maintenance.";
		} else if ($post_quantity < $data_service['min']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Jumlah minimal adalah ".$data_service['min'].".";
		} else if ($post_quantity > $data_service['max']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Jumlah maksimal adalah ".$data_service['max'].".";
		} else if ($data_user['balance'] < $price) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Saldo Anda tidak mencukupi untuk melakukan pembelian ini.";
		} else {

			$api_link = $data_provider['link'];
			$api_key = $data_provider['api_key'];

    		if ($provider == "MANUAL") {
				$api_postdata = "";
				$poid = $oid;  
            } else if ($provider == "IRVANKEDE") {
                $order_postdata = "api_id=8206&api_key=$api_key&service=$pid&target=$post_link&quantity=$post_quantity";	
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://irvankede-smm.co.id/api/order");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                // echo $chresult;
                curl_close($ch);
                $order_data = json_decode($chresult, true);
                $poid = $order_data['data']['id'];
                $error = $order_data['status'];
                $msg = $order_data['data'];  
            } else {
		        die("System Error");  
			}
			
			if ($provider == "IRVANKEDE" AND $order_data['status'] == FALSE) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Server Rusak. ( ".$msg." )";
			} else {
			if($data_provider1 == "DP"){			        
				   
			        $pesannya="Hexazor Pedia : $sess_username Terima Kasih Telah Melakukan Order $service Nomer Tujuan $post_link , Semoga Harimu Menyenangkan:)";
                    $nomerhp = $data_user['hp'];
                    $postdatas = "api_key=hsAfNOPkRBjeFcwc7Zmv&pesan=$pesannya&nomer=$nomerhp";
                    $apibase1 = "https://serverh2h.id/sms_gateway";
                    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apibase1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$chresult = curl_exec($ch);
curl_close($ch);
$json_result = json_decode($chresult, true);
			    }
				$update_user = $db->query("UPDATE users SET balance = balance-$price WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
					$insert_order = $db->query("INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '$price', 'Membuat pesanan #$oid', '$date', '$time')");
				    $insert_order = $db->query("INSERT INTO orders (oid, poid, user, service, link, quantity, remains, start_count, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_link', '$post_quantity', '0', '0', '$price', 'Pending', '$date', '$provider', 'WEB')");
					if ($insert_order == TRUE) {
						$msg_type = "success";
						$msg_content = "<b>Pesanan telah diterima.</b><br /><b>Kode Pesanan:</b> $oid<br /><b>Layanan:</b> $service<br /><b>Jumlah:</b> ".number_format($post_quantity,0,',','.')."<br /><b>Biaya:</b> Rp ".number_format($price,0,',','.');
					} else {
						$msg_type = "error";
						$msg_content = "<b>Gagal!</b> Error system (2).";
					}
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal!</b> Error system (1).";
				}
			}
		}
	}

	
	$check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-cart"></i> Pemesanan Baru</h4><hr>
                            <?php 
                            if ($msg_type == "success") {
                            ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            } else if ($msg_type == "error") {
                            ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $msg_content; ?>
                            </div>
                            <?php
                            }
                            ?>
							<form class="form-horizontal" role="form" method="POST">
								<div class="form-group row">
									<label class="col-md-2 control-label">Kategori</label>
									<div class="col-md-10">
										<select class="form-control" id="category" name="category">
											<option value="0">Pilih salah satu...</option>
											<?php
											$check_cat = mysqli_query($db, "SELECT * FROM service_cat");
											while ($data_cat = mysqli_fetch_assoc($check_cat)) {
											?>
											<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Layanan</label>
									<div class="col-md-10">
										<select class="form-control" name="service" id="service">
											<option value="0">Pilih kategori...</option>
										</select>
									</div>
								</div>
								<div id="note"></div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Data Target</label>
									<div class="col-md-10">
										<input type="text" name="link" class="form-control" placeholder="Link/Target">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Jumlah</label>
									<div class="col-md-10">
										<input type="number" name="quantity" class="form-control" placeholder="Jumlah" onkeyup="get_total(this.value).value;">
									</div>
								</div>	
								<input type="hidden" id="rate" value="0">
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Saldo Didapat</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" id="total" value="0" readonly>
                                        </div>
                                    </div>
                                </div>      
                                <div class="form-group row">
                                    <div class="offset-lg-2 col-lg-8">
                                        <button type="reset" class="btn btn-secondary btn-bordred"><i class="fa fa-refresh"></i> Reset </button>  
                                        <button type="submit" class="btn btn-custom btn-bordred" name="order"><i class="fa fa-send"></i> Submit </button>   
                                    </div>
                                </div>                                      
							</form>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-book"></i> Panduan Pemesanan</h4><hr>
                            Tata cara pengisian form pemesanan:
					        <ul>
						        <li>Pilih salah satu kategori pada <b>Kategori</b>, maka akan ditampilkan daftar layanan yang tersedia pada <b>Layanan</b>, silahkan pilih salah satu layanan.</li>
						        <li>Masukkan data berupa username atau link pada <b>Data</b> sesuai permintaan yang ditampilkan setelah memilih layanan.</li>
						        <li>Masukkan jumlah yang diinginkan pada <b>Jumlah</b>, maka akan ditampilkan total harga yang akan dibayar dengan saldo pada <b>Total harga</b>.</li>
						        <li>Jika semua input sudah terisi dengan benar, klik <b>Kirim</b>. Pesanan akan diproses jika hasil yang ditampilkan setelah submit sukses.</li>
						        <li>Jika pesanan <i>stuck</i>/tidak berubah status dari pending, Anda dapat menghubungi Admin melalui tiket.</li>
					            </ul>
					        Tata cara mengisi input <b>Data</b> yang sesuai:
					        <ul>
						        <li>Masukkan data berupa username atau link sesuai yang diminta.</li>
						        <li>Pastikan akun target tidak berstatus <i>private</i>.</li>
						        <li>Tidak ada pengembalian dana jika terjadi kesalahan pengisian data oleh pengguna.</li>
					        </ul>
                        </div>
                    </div>
                </div>
						<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$("#category").change(function() {
		var category = $("#category").val();
		$.ajax({
			url: '<?php echo $site_config['base_url']; ?>inc/order_service.php',
			data: 'category=' + category,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#service").html(msg);
			}
		});
	});
	$("#service").change(function() {
		var service = $("#service").val();
		$.ajax({
			url: '<?php echo $site_config['base_url']; ?>inc/order_note.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#note").html(msg);
			}
		});
		$.ajax({
			url: '<?php echo $site_config['base_url']; ?>inc/order_rate.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#rate").val(msg);
			}
		});
	});
});

function get_total(quantity) {
	var rate = $("#rate").val();
	var result = eval(quantity) * rate;
	$('#total').val(result);
}
	</script>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>