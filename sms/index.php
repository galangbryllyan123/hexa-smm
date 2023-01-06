<?php
session_start();
require("../mainconfig.php");
$page_type = "SMS Gateway";

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
	$post_pesan = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pesan'], ENT_QUOTES))));
	$post_nomer = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['nomer'], ENT_QUOTES))));
	$post_pin = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['pin'], ENT_QUOTES))));
		

		if (empty($post_pesan) || empty($post_nomer) || empty($post_pin)) {
			$msg_type = "error";
			$msg_content = '<b>Gagal:</b> Mohon mengisi semua input.<script>swal("Error!", "Mohon mengisi semua input.", "error");</script>';
		} else if ($post_pin <> $data_user['pin']) {
			$msg_type = "error";
			$msg_content = '<b>Gagal:</b> PIN keamanan salah.<script>swal("Error!", "PIN keamanan salah.", "error");</script>';
		} else if (strlen($post_pesan) > 200) {
			$msg_type = "error";
			$msg_content = '<b>Gagal:</b> Isi Pesan Maksimal 200.<script>swal("Error!", "Isi Pesan Maksimal 200.", "error");</script>';
		} else if ($data_user['credit'] < 1) {
			$msg_type = "error";
			$msg_content = '<b>Gagal:</b> Credit Anda tidak mencukupi untuk melakukan pengiriman SMS ini.<script>swal("Error!", "Credit Anda tidak mencukupi untuk melakukan pengiriman SMS ini.", "error");</script>';
		} else {
		
                        $postdata = "api_key=hsAfNOPkRBjeFcwc7Zmv&pesan=$post_pesan&nomer=$post_nomer";
                        $apibase= "https://serverh2h.net/sms_gateway";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $apibase);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $chresult = curl_exec($ch);
                        curl_close($ch);
                        $json_result = json_decode($chresult, true);
                        
                        if ($json_result['error'] == TRUE) {
                            $msg_type = "error";
                            $msg_content = "<b>Error:</b> Disebabkan ".$json_result['error'];
			}

				$update_user = mysqli_query($db, "UPDATE users SET credit = credit-1 WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
				    $insert_order = mysqli_query($db, "INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Minus', 'Place Order', '1', 'Membuat Pengiriman Sms Ke Nomer $post_nomer', '$date', '$time')");
					$insert_order = mysqli_query($db, "INSERT INTO orders_sms (user, pesan, nomer, date) VALUES ('$sess_username', '$post_pesan', '$post_nomer', '$date')");
					if ($insert_order == TRUE) {
						$msg_type = "success";
						$msg_content = "<b>Sukses!</b><br /><b>Isi Pesan :</b> $post_pesan<br /><b>Nomer :</b> $post_nomer<br /><b>Tanggal & Waktu :</b> $date ($time)";
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
	
	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
?>
<div class="row">
 <div class="col-lg-12 col-md-12">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-wallet text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b><?php echo ($data_user['credit']); ?> Credit </b></h3>
                        <p class="text-muted mb-0"><a href="<?php echo $site_config['base_url']; ?>sms/buy-credit" type="button" class="btn btn-custom btn-bordred"><i class="fa fa-send"></i> Isi Credit? </a>   </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div> 
                
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
								    <label class="col-md-2 control-label">Pesan</label>
									<div class="col-md-10">
										<textarea name="pesan" class="form-control" placeholder="Isi Pesan"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 control-label">Nomer Tujuan</label>
									<div class="col-md-10">
										<input type="number" name="nomer"class="form-control" placeholder="Nomer HP">
													<span style="color: red;"><i>*Isi Nomer HP Dengan Benar.*</i></span>
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-md-2 control-label">Security Pin</label>
									<div class="col-md-10">
										<input type="password" name="pin" class="form-control" placeholder="Security PIN">
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
						            <li>Pastikan nomer tujuan benar,</li>
											<li>Pastikan jumlah karakter tidak melebihi 200 karakter,</li>
											<li>Setiap pengiriman SMS, credit terpotong sebesar Rp <b>1</b>.</li>
					            </ul>
					    </div>	
					    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
			 
					    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
						
					</div>
		       </div>						
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>

<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>