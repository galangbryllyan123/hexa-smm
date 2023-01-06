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
$page_type = "Dokumentasi API";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
    if ($check_user->num_rows == 0) {
        header("Location: ".$site_config['base_url']."user/logout");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$site_config['base_url']."user/logout");
    }
}

include("../lib/header.php");
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-box">
                                       <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-fire"></i> Dokumentasi API</h4><hr>
									   <h4 class="text-bold text-info">Informasi Dasar</h4>
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td>Metode HTTP</td>
														<td>POST</td>
													</tr>
													<tr>
														<td>API URL</td>
														<td><?php echo $site_config['base_url']; ?>pulsa/api.php</td>
													</tr>
													<tr>
														<td>Response format</td>
														<td>JSON</td>
													</tr>
												</tbody>
											</table>
										<h4 class="text-bold text-info">Membuat Pesanan</h4>
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Parameter</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
												<tr>
							                        <td><code>key</code></td>
							                        <td>API Key Anda, dapat dilihat dibagian <a href="<?php echo $site_config['base_url']; ?>user/settings">profil akun</a>.</td>
						                        </tr>
						                        <tr>
						                           	<td><code>action</code></td>
						                        	<td>Untuk membuat pesanan dapat mengisi <i>action</i> dengan <i>add</i>.</td>
						                        </tr>
						                        <tr>
							                        <td><code>service</code></td>
							                        <td>Diisi dengan id layanan, silahkan lihat <a href="<?php echo $site_config['base_url']; ?>pulsa/services">daftar harga</a>.</td>
						                        </tr>
						                        <tr>
							                        <td><code>phone</code></td>
							                        <td>Diisi dengan nomer telepon yang ditargetkan pesanan.</td>
					                            </tr>

												</tbody>
											</table>
<b>Contoh respon sukses</b>
				<pre>{
	"order_id":"12345"
}</pre>
				<br />
				<b>Contoh respon gagal</b>
				<pre>{
	"error":"Incorrect request"
}</pre>
<br />
											<h4 class="text-bold text-info">Mengecek Status Pesanan</h4>
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Parameter</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
													<tr>
													<td><code>key</code></td>
							                        <td>API Key Anda, dapat dilihat dibagian <a href="<?php echo $site_config['base_url']; ?>user/settings">profil akun</a>.</td>
													</tr>
													<tr>
														<td><code>action</code></td>
														<td>Untuk mengecek pesanan dapat mengisi <i>action</i> dengan <i>status</i>.</td>
													</tr>
													<tr>
														<td><code>order_id</code></td>
														<td>Diisi dengan ID Pesanan yang ingin dicek.</td>
													</tr>
												</tbody>
											</table>
											
<b>Contoh respon sukses</b>
<pre>{
	"status":"Success"
}
</pre>
				<br />
				<b>Contoh respon gagal</b>
				<pre>{
	"error":"Incorrect request"
}</pre>
										</div>
									</div>
								</div>
<?php
include("../lib/footer.php");
?>