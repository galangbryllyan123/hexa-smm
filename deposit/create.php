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
$page_type = "Deposit Baru";

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

    if (isset($_POST['submit'])) {
        $post_method = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['method'], ENT_QUOTES))));
        $post_quantity = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['quantity'], ENT_QUOTES))));
        $post_sender = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['sender'], ENT_QUOTES))));


        $check_depo = $db->query("SELECT * FROM deposit WHERE username = '$sess_username' AND status = 'Pending'");

        $check_service = $db->query("SELECT * FROM deposit_method WHERE id = '$post_method'");
        $data_service = $check_service->fetch_array(MYSQLI_ASSOC);

        $rate = $data_service['rate'];
        $balance = $rate*$post_quantity;
        $code = random_number(15);
        $note = $data_service['payment'];
        $method = $data_service['name'];
        $match = preg_match("/Bank/", $method);

        if($match == true) {
            $random_angka = rand(1,999);
            $post_quantity = $post_quantity+$random_angka;
            $balance = $post_quantity;
        }

        $min_depo = "10000"; // edit min depo

        if (empty($post_method) || empty($post_quantity) || empty($post_sender)) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Mohon mengisi input.";
        } else if (mysqli_num_rows($check_depo) > 3) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Terdeteksi spam, Anda memiliki lebih dari 3 deposito Pending, segera lunasi.";
        } else if (mysqli_num_rows($check_service) == 0) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Metode tidak ditemukan.";
        } else if ($post_quantity < $min_depo) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Jumlah minimal adalah ".$min_depo.".";
        } else {
            $insert_depo = $db->query("INSERT INTO deposit (code, username, sender, method, quantity, balance, status, date) VALUES ('$code', '$sess_username', '$post_sender', '$method', '$post_quantity', '$balance', 'Pending', '$date')");
            if ($insert_depo == TRUE) {
                $msg_type = "success";
                $msg_content = "<b>Permintaan telah dikirim!</b><br /><b>Metode:</b> $method<br /><b>Kode Faktur:</b> ".$code."<br /><hr />Silahkan kirim pembayaran ke <b>".$note."</b> sebesar <b>Rp ".number_format($post_quantity,0,',','.')."</b> ( Jumlah Wajib Sama jika ada kode unik dan dikonfirmasi oleh sistem secara Otomatis Jika ada Kode Uniknya), Jika tidak ada kode unik harap konfirmasi secara manual ke <b>Admin</b>";
            } else {
                $msg_type = "error";
                $msg_content = "<b>Gagal:</b> Error system (2).";
            }
        }
    }
    
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Deposit Baru</h4><hr>
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
                                        <select class="form-control" name="method" id="depomethod">
                                            <option value="0">-- Pilih Metode --</option>
                                            <?php
                                            $check_cat = mysqli_query($db, "SELECT * FROM deposit_method ORDER BY name ASC");
                                            while ($data_cat = mysqli_fetch_assoc($check_cat)) {
                                            ?>
                                            <option value="<?php echo $data_cat['id']; ?>"><?php echo $data_cat['name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Pengirim</label>
                                    <div class="col-md-10">
                                        <input type="text" name="sender" class="form-control" placeholder="Contoh : 123456789 A.N Pemilik Rekening Atau No HP : 6282297387348 ( Wajib 62 )">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Jumlah Deposit</label>
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
                                    <label class="col-md-2 control-label">Saldo Didapat</label>
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
                                        <button type="submit" class="btn btn-custom btn-bordred" name="submit"><i class="fa fa-send"></i> Submit </button>   
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-book"></i> Petunjuk Deposit</h4><hr>
                            <p><b>Petunjuk deposit:</b></p>
                            <ul>
                                <li>Deposito Manual diverifikasi secara manual oleh Admin.</li>
                                <li>Deposito Automatis diverifikasi secara otomatis oleh sistem.</li>
                                <li>Untuk permintaan deposit via transfer Bank, Jumlah Deposit akan ditambahkan 3 digit angka verifikasi pembayaran (Contoh: Jumlah Deposit 100.000 akan menjadi 100.321 atau 3 digit acak lainnya), nominal yang harus dibayar akan ditampilkan setelah Submit form.</li>
                                <li>Masukkan Nomor HP yang digunakan untuk transfer pulsa, gunakan awalan kode 62 bukan 0 (Contoh: 6281311020950).</li>
                                <li>Anda diwajibkan mengirikan bukti pembayaran berupa ID Deposito dan Bukti Transfer ke kontak Admin yang tersedia dibawah setelah melakukan pembayaran.</li>
                                <li>Silahkan konfirmasi melalui ticket jika sudah melakukan pembayaran.</li>
                            </ul>
                        </div>
                    </div>
                </div>                          
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#depomethod").change(function() {
                var method = $("#depomethod").val();
                $.ajax({
                    url: '<?php echo $site_config['base_url']; ?>inc/depo_rate.php',
                    data: 'method=' + method,
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