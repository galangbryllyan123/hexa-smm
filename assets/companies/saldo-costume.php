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
		$post_service = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))));
		$post_quantity = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['quantity'], ENT_QUOTES))));
		$post_phone = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['phone'], ENT_QUOTES))));

		$check_service = $db->query("SELECT * FROM saldo WHERE name = '$post_service'");
		$data_service = $check_service->fetch_array(MYSQLI_ASSOC);

		$service = $data_service['name'];
		$method = $data_service['name'];
	
		
		$data_users = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
		$data_show = $data_users->fetch_array(MYSQLI_ASSOC);
		
		
		$check_orders = $db->query("SELECT * FROM orders_pulsa WHERE user = '$sess_username' AND service = '$service' AND date = '$date'");
		$data_orders = $check_orders->fetch_array(MYSQLI_ASSOC);
		$count_orders = $check_orders->num_rows;

		$check_provider = $db->query("SELECT * FROM provider WHERE code = '$provider'");
		$data_provider = $check_provider->fetch_array(MYSQLI_ASSOC);
        
		if (empty($post_phone)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Mohon mengisi input.";
		} else if (mysqli_num_rows($check_service) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Layanan tidak ditemukan.";
		} else if ($data_user['balance'] < $price) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Saldo Anda tidak mencukupi untuk melakukan pembelian ini.";
		} else {

			$api_link = "https://serverh2h.id/order/saldo";
			$api_key = $data_provider['api_key'];

		    $random_trxid = random_number(1).random_number(2);
		    $poid = $random_trxid;		    
		    $oid = random_number(7);
            
            
		   if ($provider == "MANUAL") {
				$api_postdata = "";
			} else if ($provider == "DP") {
                $postdata = "api_key=$api_key&service=$method&phone=$post_phone";
			} 
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://serverh2h.id/order/saldo");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$chresult = curl_exec($ch);
			$chresult;
			curl_close($ch);
			$json_result = json_decode($chresult, true);

			if ($json_result['error'] == TRUE) {
				$msg_type = "error";
				$msg_content = $json_result->error."<b>Gagal:</b> SERVER SEDANG GANGGUAN (1).";
			} else {
			    
			   if($provider == "DP"){
			        $oid = $json_result['code_trx'];
				    $catatan = $json_result['catatan'];
			        $pesannya="Hexazor Pedia : $sess_username Terima Kasih Telah Melakukan Order $service Data $post_phone , Semoga Harimu Menyenangkan:)";
                    $nomerhp = $data_show['hp'];
                    $postdatas = "api_key=hsAfNOPkRBjeFcwc7Zmv&pesan=$pesannya&nomer=$nomerhp";
                    $apibase= "https://serverh2h.id/sms_gateway";
                    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://serverh2h.id/sms_gateway");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$chresult = curl_exec($ch);
curl_close($ch);
$json_result = json_decode($chresult, true);
			    }
				$update_user = $db->query("UPDATE users SET balance = balance-$post_quantity WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
				    $insert_order = $db->query("INSERT INTO orders_pulsa (oid, poid, user, service, link, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_phone', '$post_quantity', 'Pending', '$date', '$provider', 'WEB')");
				    $insert_order = mysqli_query($db, "INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '$price', 'Membuat pesanan #$oid', '$date', '$time')");
					if ($insert_order == TRUE) {
						$msg_type = "success";
						$msg_content = "<b>Pesanan telah diterima.</b><br /><b>Layanan:</b> $service<br /><b>No. Telp:</b> $post_phone<br /><b>Biaya:</b> Rp ".number_format($post_quantity,0,',','.');
					} else {
						$msg_type = "error";
						$msg_content = "<b>Gagal:</b> Error system (2).";
					}
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system (1).";
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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Saldo Costume</h4><hr>
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
                                    <label class="col-md-2 control-label">Metode</label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="name" id="namethod">
                                            <option value="0">-- Pilih Metode --</option>
                                            <?php
                                            $check_cat = mysqli_query($db, "SELECT * FROM saldo ORDER BY name ASC");
                                            while ($data_cat = mysqli_fetch_assoc($check_cat)) {
                                            ?>
                                            <option value="<?php echo $data_cat['type']; ?>"><?php echo $data_cat['name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Nomor Tujuan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="phone" class="form-control" placeholder="081210110328">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Nominal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="quantity" class="form-control" placeholder="Jumlah" onkeyup="get_total(this.value).value;">
                                        </div>
                                    </div>                                    
                                </div>  
                                <input type="hidden" id="rate" value="0">
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Harga</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" id="total" readonly>
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
						            <li>Pilih salah satu kategori pada <b>Kategori</b>, lalu pilih <b>Tipe</b> yang sesuai, maka akan ditampilkan daftar layanan yang tersedia pada <b>Layanan</b>, silahkan pilih salah satu layanan.</li>
						            <li>Masukkan data berupa nomer handphone pada <b>Nomer Telepon</b> sesuai permintaan yang ditampilkan setelah memilih layanan.</li>
						            <li>Jika semua input sudah terisi dengan benar, klik <b>Kirim</b>. Pesanan akan diproses jika hasil yang ditampilkan setelah submit sukses.</li>
						            <li>Jika pesanan <i>stuck</i>/tidak berubah status dari pending, Anda dapat menghubungi Admin melalui tiket.</li>
					            </ul>
					            Tata cara mengisi input <b>Data</b> yang sesuai:
					            <ul>
						            <li>Masukkan data berupa nomer handphone sesuai yang diminta.</li>
						            <li>jika terjadi kesalahan pengisian data oleh pengguna, harap segera hubungi admin.</li>
					            </ul>
                        </div>
                    </div>
                </div>                          
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#namethod").change(function() {
                var name = $("#namethod").val();
                $.ajax({
                    url: '<?php echo $site_config['base_url']; ?>inc/saldo_rate.php',
                    data: 'name=' + name,
                    type: 'POST',
                    dataType: 'html',
                    success: function(msg) {
                        $("#rate").val(msg);
                    }
                });
            });
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
                    url: '<?php echo $site_config['base_url']; ?>inc/saldo_rate.php',
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
            var result = eval(quantity) + 750;
            $('#total').val(result);
        }
    </script>                                           
<?php
    include("../lib/footer.php");
} else {
    header("Location: ".$site_config['base_url']);
}
?>