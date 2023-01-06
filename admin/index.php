<?php

session_start();
require("../mainconfig.php");
$page_type = "Halaman Admin";

if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = $check_user->fetch_array(MYSQLI_ASSOC);
  if ($check_user->num_rows == 0) {
    header("Location: ".$site_config['base_url']."user/logout.php");
  } else if ($data_user['status'] == "Suspended") {
    header("Location: ".$site_config['base_url']."user/logout.php");
  } else if ($data_user['level'] != "Developers") {
    header("Location: ".$site_config['base_url']);
  } 

  $check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders");
  $rows_order = mysqli_query($db, "SELECT * FROM orders");
  $data_order = mysqli_fetch_assoc($check_order);

  $check_order_pulsa = mysqli_query($db, "SELECT SUM(price) AS total FROM orders_pulsa");
  $rows_order_pulsa = mysqli_query($db, "SELECT * FROM orders_pulsa");
  $data_order_pulsa = mysqli_fetch_assoc($check_order_pulsa);

  $check_depo = mysqli_query($db, "SELECT SUM(balance) AS total FROM deposit");
  $data_depo = mysqli_fetch_assoc($check_depo);
  $rows_depo = mysqli_query($db, "SELECT * FROM deposit");
  

} else {
  header("Location: ".$site_config['base_url']."user/login");
}

include("../lib/headeradmin.php");
?>  
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-instagram text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_order['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_order); ?>)</b></h3>
                        <p class="text-muted mb-0">Pesanan (Medsos)</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-phone text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_order_pulsa['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_order_pulsa); ?>)</b></h3>
                        <p class="text-muted mb-0">Pesanan (Pulsa)</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-wallet text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_depo['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_depo); ?>)</b></h3>
                        <p class="text-muted mb-0">Deposit</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-chart-bubble"></i> Grafik Pembelian & Deposit Selama 30 Hari</h4><hr>
                    <div id="line-bulanan" style="height: 250px;"></div>
                </div>
            </div>
            
            <div class="col-12">
	                <div class="card text-center">
	                    <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/orders_pulsa" class="btn-loading"><i class="mdi mdi-cart fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/orders_pulsa" class="btn-loading"><h5  class="text-primary">Pesanan Pulsa</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/orders" class="btn-loading"><i class="mdi mdi-cart fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/orders" class="btn-loading"><h5  class="text-primary">Pesanan Sosmed</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/services_pulsa" class="btn-loading"><i class="mdi mdi-format-list-bulleted fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/services_pulsa" class="btn-loading"><h5  class="text-primary">Layanan Pulsa</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/services" class="btn-loading"><i class="mdi mdi-format-list-bulleted fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/services" class="btn-loading"><h5  class="text-primary">Layanan Sosmed</h5>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/kategori-pulsa" class="btn-loading"><i class="mdi mdi-format-list-bulleted fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/kategori-pulsa" class="btn-loading"><h5  class="text-primary">Kategori Pulsa</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/kategori" class="btn-loading"><i class="mdi mdi-format-list-bulleted fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/kategori" class="btn-loading"><h5  class="text-primary">Kategori Sosmed</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/deposits" class="btn-loading"><i class="mdi mdi-wallet fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/deposits" class="btn-loading"><h5  class="text-primary">Data Deposit</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/deposit_method" class="btn-loading"><i class="mdi mdi-credit-card-plus fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/deposit_method" class="btn-loading"><h5  class="text-primary">Metode Deposit</h5>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/withdraw" class="btn-loading"><i class="mdi mdi-scale-balance fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/withdraw" class="btn-loading"><h5  class="text-primary">Data Withdraw</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/users" class="btn-loading"><i class="mdi mdi-clipboard-account fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/users" class="btn-loading"><h5  class="text-primary">Data Pengguna</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/providers" class="btn-loading"><i class="mdi mdi-cart-plus fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/providers" class="btn-loading"><h5  class="text-primary">Data Provider</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/operan" class="btn-loading"><i class="mdi mdi-settings fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/operan" class="btn-loading"><h5  class="text-primary">Oper Service</h5>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/website" class="btn-loading"><i class="mdi mdi-web fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/website" class="btn-loading"><h5  class="text-primary">Data Website</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/news" class="btn-loading"><i class="mdi mdi-newspaper fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/news" class="btn-loading"><h5  class="text-primary">Data Berita</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/tickets" class="btn-loading"><i class="mdi mdi-message-bulleted fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/tickets" class="btn-loading"><h5  class="text-primary">Data Tiket</h5>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="" class="text-primary">
                                            <a href="<?php echo $site_config['base_url']; ?>admin/transfer_history" class="btn-loading"><i class="mdi mdi-history fa-3x"></i>
                                            <a href="<?php echo $site_config['base_url']; ?>admin/transfer_history" class="btn-loading"><h5  class="text-primary">Riwayat Transfer</h5>
                                        </a>
                                    </th>
                                </tr>
        </tbody>
                        </table>
	                </div>
	            </div>
	        </div>
	    </div>
	    </div>

<?php
include("../lib/footer.php");
?>
<?php if (isset($_SESSION['user'])) { ?>
<script type="text/javascript">
<?php
$lastweek = date('Y-m-d', strtotime('-7 days', strtotime(date("Y-m-d"))));

    function ambil($tabel, $limit) {
        global $db;
        $check_data = mysqli_query($db, "SELECT * FROM ".$tabel." WHERE ".$limit);
        $count_data = mysqli_num_rows($check_data);
        return $count_data;
        }
?>
$(function(){
  new Morris.Area({
    element: 'line-bulanan',
    data: [
  <?php for ($i=1; $i<=30; $i++){
          $orderdate = date('Y-m-d', strtotime('+'.$i.'days', strtotime($lastweek)));
          $line = $orderdate;
          $convert = format_date($line);
          $sosmed = ambil('orders', "DATE(date) = '$orderdate'");
          $pulsa = ambil('orders_pulsa', "DATE(date) = '$orderdate'");
          $deposit = ambil('deposit', "DATE(date) = '$orderdate'");
          ?>
    {y: '<?php echo $convert; ?>', MediaSosial: <?php echo $sosmed; ?>, Pulsa: <?php echo $pulsa; ?>, Deposit: <?php echo $deposit; ?>},
  <?php
  }
  ?>    ],
      xkey: 'y',
      ykeys: ['MediaSosial', 'Pulsa', 'Deposit'],
      labels: ['MediaSosial', 'Pulsa', 'Deposit'],
      lineColors: ['#2196f3', 'Purple', '#727b84'],
      resize: true,
      lineWidth: 4,
      pointSize: 5,
      parseTime: false
    });
  });
</script>
<?php } ?>