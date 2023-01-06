<?php
/*
Coding By Rifqi Ganteng
**/
session_start();
require("../mainconfig.php");
$page_type = "Forgot Password";
function dapetin($url) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
$data = curl_exec($ch);
curl_close($ch);
return json_decode($data, true);
}
if (isset($_SESSION['user'])) {
$sess_username = $_SESSION['user']['username'];
$check_userd = $db->query("SELECT * FROM users WHERE username = '$sess_username'");
$data_userd = $check_userd->fetch_array(MYSQLI_ASSOC);
if ($check_userd->num_rows !== 0) {
header("Location: ".$site_config['base_url']);
}
} else {
if (isset($_POST['reset'])) {
$post_username = mysqli_real_escape_string($db, trim($_POST['username']));
$post_email = mysqli_real_escape_string($db, trim($_POST['email']));
$post_method = mysqli_real_escape_string($db, trim($_POST['method']));
if (empty($post_email)) {
$msg_type = "error";
$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
} else {
$check_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
$check_user = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
if (mysqli_num_rows($check_user) == 0) {
$msg_type = "error";
$msg_content = "<b>Gagal!</b> Email Tidak Terdaftar.";
} else {
$link = "https://hexazor-smm.com";
$newpassword = "Hexa-".random_number(4);		    
$to = $data_user['email'];
$subject = "New Password";
$messages = '<html><body>';
$messages .="
This Is Your New Password : $newpassword <br />
Thanks For Use our Service <br />
Regards <br />
<a href='$link'>Rifqi Resfether</a>";
$messages .= '</body></html>';    
$headers = 'From: admin@hexazor-smm.com'."\r\n";
$headers .= 'Reply-To: admin@hexazor-smm.com'."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$statusnya=@mail($to, $subject, $messages, $headers);
if($statusnya == TRUE)
{
$msg_type = "success";
$msg_content = "×</span></button> <b>Success:</b> Password Baru Anda Sudah Dikirim Ke Email Anda.";
$update_user = mysqli_query($db, "UPDATE users SET password = '$newpassword' WHERE email = '$post_email'");
} else { 
$msg_type = "error";
$msg_content = "×</span></button> <b>Gagal:</b> Password Gagal Terkirim Ke Email Anda.";
}
}
}
} else if (isset($_POST['reset_nope'])) {
$post_usernames = mysqli_real_escape_string($db, trim($_POST['username']));
$post_hp = mysqli_real_escape_string($db, trim($_POST['hp']));
$post_method = mysqli_real_escape_string($db, trim($_POST['method']));
if (empty($post_hp)) {
$msg_type = "errorr";
$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
} else {
$check_user = $db->query("SELECT * FROM users WHERE username = '$post_usernames'");
$data_user = $check_user->fetch_array(MYSQLI_ASSOC);
$check_user = mysqli_query($db, "SELECT * FROM users WHERE hp = '$post_hp'");
if (mysqli_num_rows($check_user) == 0) {
$msg_type = "errorr";
$msg_content = "<b>Gagal!</b> Nomer Handphone Tidak Terdaftar.";
} else {
$newpassword = "Hexa-".random_number(4);
$pesannya="Hexazor Pedia : Your Account has been Success to reset! Your new password is $newpassword";
$postdata = "api_key=hsAfNOPkRBjeFcwc7Zmv&pesan=$pesannya&nomer=$post_hp";
$apibase= "https://serverh2h.id/sms_gateway";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apibase);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$chresult = curl_exec($ch);
curl_close($ch);
$json_result = json_decode($chresult, true);
$statusnya=$json_result['status'] == TRUE;
if($statusnya == TRUE)
{
$msg_type = "successs";
$msg_content = "×</span></button> <b>Success:</b> Password Baru Anda Sudah Dikirim Ke Nomer Hp Anda.";
$update_user = mysqli_query($db, "UPDATE users SET password = '$newpassword' WHERE hp = '$post_hp'");
} else { 
$msg_type = "errorr";
$msg_content = "×</span></button> <b>Gagal:</b> Password Gagal Terkirim Ke Nomer Anda.";
}
}
}
}
include("../lib/header.php");
?>
<div class="row">
  <div class="offset-lg-4 col-lg-4">
    <div class="card-box">
      <div class="text-center">
        <h4 class="text-uppercase font-bold">Forgot Password
        </h4>
      </div>
      <div class="card-box">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a href="#email" data-toggle="tab" aria-expanded="false" class="nav-link active">
              <span class="d-block d-sm-none">
                <i class="mdi mdi-account-card-details">
                </i>
              </span>
              <span class="d-none d-sm-block">Reset Via Email
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#nohp" data-toggle="tab" aria-expanded="false" class="nav-link">
              <span class="d-block d-sm-none">
                <i class="mdi mdi-account-key">
                </i>
              </span>
              <span class="d-none d-sm-block">Reset Via No HP
              </span>
            </a>
          </li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade active show" id="email">
            <p class="mb-0">
            <form class="form-horizontal" role="form" method="POST">
              <?php 
if ($msg_type == "success") {
?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                </button>
                <?php echo $msg_content; ?>
              </div>
              <?php
} else if ($msg_type == "error") {
?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                </button>
                <?php echo $msg_content; ?>
              </div>
              <?php
}
?>
              <label class="control-label">
                <b>Reset Via Email
                </b>
              </label>
              <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username">
              </div>
              <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Masukan Email">
              </div>
              <div class="text-center">
                <button type="submit" class="pull-right btn btn-primary btn-block waves-effect w-md waves-light" name="reset">
                  <i class="mdi mdi-check-circle-outline">
                  </i> Reset Password
                </button>
              </div>
            </form>
            </p>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="nohp">
          <p class="mb-0">
          <form class="form-horizontal" role="form" method="POST">
            <?php 
if ($msg_type == "successs") {
?>
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
              </button>
              <?php echo $msg_content; ?>
            </div>
            <?php
} else if ($msg_type == "errorr") {
?>
            <div class="alert alert-danger alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
              </button>
              <?php echo $msg_content; ?>
            </div>
            <?php
}
?>
            <label class="control-label">
              <b>Reset Via Nomer HP
              </b>
            </label>
            <div class="form-group">
              <input type="text" name="username" class="form-control" placeholder="Username">
            </div>
            <div class="form-group">
              <input type="text" name="hp" class="form-control" placeholder="Masukan No HP">
            </div>
            <div class="text-center">
              <button type="submit" class="pull-right btn btn-primary btn-block waves-effect w-md waves-light" name="reset_nope">
                <i class="mdi mdi-check-circle-outline">
                </i> Reset Password
              </button>
            </div>
          </form>
          </p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 text-center">
    <p class="text-muted">
      <i class="fa fa-user m-r-5">
      </i> Belum memiliki akun?
      <a href="<?php echo $site_config['base_url']; ?>user/register" class="text-primary m-l-5">
        <b>Daftar
        </b>
      </a>
    </p>
  </div>
</div>
<script src='https://code.responsivevoice.org/responsivevoice.js'>
</script>
</div>
</div>
<?php
include("../lib/footer.php");
}
?>
