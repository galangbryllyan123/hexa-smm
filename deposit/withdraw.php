<?php

session_start();
require("../mainconfig.php");
$page_type = "Withdraw Point";

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
        $post_point = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['tabungan'], ENT_QUOTES))));
        $post_quantity = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['quantity'], ENT_QUOTES))));
        
        $code = "WD"."-".random_number(4);
        $min_with = "1000"; // edit min withdraw

        if (empty($post_quantity)) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Mohon mengisi input.";
        } else if ($post_quantity < $min_with) {
            $msg_type = "error";
            $msg_content = "<b>Gagal!</b> Jumlah minimal adalah ".$min_with.".";
        } else if ($data_user['tabungan'] < $post_quantity) {
			$msg_type = "error";
			$msg_content = "<b>Gagal!</b> Point Anda tidak mencukupi.";
		} else {
        $update_user = $db->query("UPDATE users SET tabungan = tabungan-$post_quantity, balance = balance+$post_quantity  WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
            $insert_with = $db->query("INSERT INTO withdraw (code, username, point, balance, date) VALUES ('$code', '$sess_username', '$post_quantity', '$post_quantity', '$date')");
            $insert_with = mysqli_query($db, "INSERT INTO balance_history (username, type, category, quantity, message, date, time) VALUES ('$sess_username', 'Plus', 'Withdraw', '$post_quantity', 'Withdraw Point #$code', '$date', '$time')");
            if ($insert_with == TRUE) {
                $msg_type = "success";
                $msg_content = "<b>Sukses $post_quantity Point Anda Telah Di Tukarkan, Saldo Yang Di Dapat Rp. $post_quantity</b>";
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
    
    $check_point = mysqli_query($db, "SELECT * FROM tabungan WHERE user = '$sess_username'");
    $rows_point = mysqli_query($db, "SELECT * FROM tabungan WHERE user = '$sess_username'");
    $data_point = mysqli_fetch_assoc($check_point);
    
    $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
?>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-wallet text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Point Anda : <?php echo number_format($data_user['tabungan'],0,',','.'); ?></b></h3>
                        <p class="text-muted mb-0">Yang Di Dapatkan Dari Setiap Transaksi Berstatus Sukses</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            </div>
                        <div class="card-box">
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-scale-balance"></i> Withdraw Point</h4><hr>
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
                                    <label class="col-md-2 control-label">Jumlah Withdraw</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="quantity" class="form-control" placeholder="Jumlah">
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
                            <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-book"></i> Petunjuk Withdraw</h4><hr>
                            <p><b>Petunjuk Withdraw:</b></p>
                            <ul>
                                <li>Point Yang Di Dapat Hanya Untuk Transaksi Pulsa & PPOB Saja.</li>
                                <li>Minimal Withdraw Point Adalah <b>1000</b> Point.</li>
                                <li>Point Yang Di Dapat Adalah Jika Anda Melakukan Transaksi & Berstatus Sukses Maka Dari 1 Transaksi Anda Akan Mendapatkan <b>10</b> Point.</li>
                                <li>Dari <b>10</b> Point Yang Anda Dapatkan Maka Anda Akan Mendapatkan <b>10</b> Saldo.</li>
                                <li><font color="red"><b>PROSES INI INSTANT</b></font> Langsung Mengurangi Point Anda Dan Anda Akan Mendapatkan Saldo Dari Point Tersebut Yang Anda Tukarkan.</li>
                                </ul>
                        </div>
                    </div>
                </div>                     
                
                <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-refresh"></i> Riwayat Withdraw</h4><hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Code</th>
                                        <th>Point</th>
                                        <th>Saldo didapat</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $check_deposit = mysqli_query($db, "SELECT * FROM withdraw WHERE username = '$sess_username' ORDER BY date DESC");
                                $no = 1;
                                while ($data_deposit = mysqli_fetch_assoc($check_deposit)) {

                                                                                           
                                ?>                                                  
                                    <tr class="table-<?php echo $label; ?>">
                                        
                                        <td><?php echo $data_deposit['date']; ?></td>
                                        <td><?php echo $data_deposit['code']; ?></td>
                                        <td><?php echo $data_deposit['point']; ?></td>
                                        <td><?php echo $data_deposit['balance']; ?></td>
                                        
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>     
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
    
<?php
    include("../lib/footer.php");
} else {
    header("Location: ".$site_config['base_url']);
}
?>