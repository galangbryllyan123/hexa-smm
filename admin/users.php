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
$page_type = "Kelola Pengguna";

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
	} else {

	    if (isset($_POST['add'])) {
			$post_username = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['username'], ENT_QUOTES))));
			$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
			$post_balance = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['balance'], ENT_QUOTES))));
			$post_level = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['level'], ENT_QUOTES))));
			$post_hp = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['hp'], ENT_QUOTES))));
			$post_email = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['email'], ENT_QUOTES))));
			
			

			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = $checkdb_user->fetch_array(MYSQLI_ASSOC);
			if (empty($post_username) || empty($post_balance) || empty($post_level)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Mohon mengisi semua input.";
			} else if ($post_level != "Member" AND $post_level != "Reseller" AND $post_level != "Admin" AND $post_level != "Agen") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Input tidak sesuai.";
			} else if ($checkdb_user->num_rows > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Username $post_username sudah terdaftar dalam database.";
			} else {
				$post_api = random(20);
				$insert_user = $db->query("INSERT INTO users (username, password, balance, level, registered, status, api_key, uplink) VALUES ('$post_username', '$post_password', '$post_balance', '$post_level', '$date', 'Active', '$post_api', '$sess_username')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Pengguna berhasil ditambahkan.<br /><b>Username:</b> $post_username<br /><b>Password:</b> $post_password<br /><b>Level:</b> $post_level<br /><b>Saldo:</b> Rp ".number_format($post_balance,0,',','.');
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
				}
			}
	    } else if (isset($_POST['edit'])) {
	        $post_username = $db->real_escape_string($_GET['username']);
			$post_password = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['password'], ENT_QUOTES))));
			$post_balance = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['balance'], ENT_QUOTES))));
			$post_level = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['level'], ENT_QUOTES))));
			$post_hp = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['hp'], ENT_QUOTES))));
			$post_email = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['email'], ENT_QUOTES))));
			$post_point = $db->real_escape_string(stripslashes(strip_tags(htmlspecialchars($_POST['tabungan'], ENT_QUOTES))));
			
			
			 if ($post_level != "Developers" AND $post_level != "Member" AND $post_level != "Reseller" AND $post_level != "Admin" AND $post_level != "Agen") {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Input tidak sesuai (1).";
			} else {
				$update_user = $db->query("UPDATE users SET balance = '$post_balance', password = '$post_password', level = '$post_level', email = '$post_email', hp = '$post_hp', tabungan = '$post_point' WHERE username = '$post_username'");
			if ($update_user == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Berhasil!</b> Pengguna berhasil diubah.<br /><b>Username :</b> $post_username<br /><b>Email :</b> $post_email<br /><b>No Hp :</b> $post_hp<br /><b>Point :</b> $post_point<br /><b>Level :</b> $post_level<br /><b>Saldo :</b> Rp ".number_format($post_balance,0,',','.');
			} else {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Error system.";
			}
		}  

		} else if (isset($_POST['delete'])) {
			$post_username = $db->real_escape_string($_GET['username']);
			$checkdb_user = $db->query("SELECT * FROM users WHERE username = '$post_username'");
			if ($checkdb_user->num_rows == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal!</b> Pengguna tidak ditemukan.";
			} else {
				$delete_user = $db->query("DELETE FROM users WHERE username = '$post_username'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil!</b> Pengguna <b>$post_username</b> dihapus.";
				}
			}
		}

	include("../lib/headeradmin.php");
	$check_wuser = mysqli_query($db, "SELECT SUM(balance) AS total FROM users");
	$data_wuser = mysqli_fetch_assoc($check_wuser);
	$check_wuser = mysqli_query($db, "SELECT * FROM users");
	$count_wuser = mysqli_num_rows($check_wuser);
	
	$check_user_active = mysqli_query($db, "SELECT * FROM users WHERE status = 'Active'");
	$check_user_unactive = mysqli_query($db, "SELECT * FROM users WHERE status = 'Suspended'");
	
?>
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title m-t-0"><i class="mdi mdi-account"></i> Tambah Pengguna</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                            	<form class="form-horizontal" role="form" method="POST">
									<div class="form-group row">
										<label class="col-md-2 control-label">Level</label>
										<div class="col-md-10">
											<select class="form-control" name="level">
												<option value="Member">Member</option>
												<option value="Agen">Agen</option>
												<option value="Reseller">Reseller</option>
												<option value="Admin">Admin</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Username</label>
										<div class="col-md-10">
											<input type="text" name="username" class="form-control" placeholder="Username">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Password</label>
										<div class="col-md-10">
											<input type="text" name="password" class="form-control" placeholder="Password">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Password</label>
										<div class="col-md-10">
											<input type="text" name="hp" class="form-control" placeholder="Nomer Handphone">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Password</label>
										<div class="col-md-10">
											<input type="text" name="email" class="form-control" placeholder="Email">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 control-label">Saldo</label>
										<div class="col-md-10">
											<input type="number" name="balance" class="form-control" placeholder="Balance">
										</div>
									</div>

									<div class="modal-footer">
                                        <button type="reset" class="btn btn-secondary btn-bordred waves-effect" data-dismiss="modal"><i class="fa fa-refresh"></i> Reset</button>
                                        <button type="submit" class="btn btn-custom btn-bordred waves-effect w-md waves-light" name="add"><i class="fa fa-send"></i> Tambah</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
              	<div class="card-box widget-box-three">
                	<div class="bg-icon pull-left">
                		<i class="ti-user fa-4x"></i>
                	</div>
                	<div class="text-right">
               			<p class="text-muted m-t-5 text-uppercase font-600 font-secondary">Total Saldo Pengguna</p>
                		<h2 class="m-b-10"><span>Rp <?php echo number_format($data_wuser['total'],0,',','.'); ?> (Dari <?php echo number_format($count_wuser,0,',','.'); ?> pengguna)</span></h2>
                	</div>
               	</div>
           	</div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <button data-toggle="modal" data-target="#myModal" class="btn btn-custom btn-bordred waves-effect waves-light m-b-30"><i class="fa fa-plus"></i> Tambah Pengguna</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="m-t-0 text-uppercase header-title"><i class="mdi mdi-account"></i> Kelola Pengguna</h4><hr>
				
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
					<div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Username</th>
									<th>Password</th>
									<th>Level</th>
									<th>Saldo</th>
									<th>Point</th>
									<th>Email</th>
									<th>Nomer Handphone</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$data_users = $db->query("SELECT * FROM users ORDER BY id ASC"); // edit
							while ($data_show = mysqli_fetch_assoc($data_users)) {
							?>
								<tr>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?username=<?php echo $data_show['username']; ?>" class="form-inline" role="form" method="POST">
										<td><?php echo $data_show['id']; ?></td>
										<td><?php echo $data_show['username']; ?></td>
										<td><input type="text" class="form-control" name="password" value="<?php echo $data_show['password']; ?>"></td>
										<td>
											<select class="form-control" name="level">
												<option value="<?php echo $data_show['level']; ?>"><?php echo $data_show['level']; ?></option>
												<option value="Member">Member</option>
												<option value="Agen">Agen</option>
												<option value="Reseller">Reseller</option>
												<option value="Admin">Admin</option>
												<option value="Developers">Developers</option>		
											</select>
										</td>
										<td><input type="number" class="form-control" name="balance" value="<?php echo $data_show['balance']; ?>"></td>
										<td><input type="number" class="form-control" name="tabungan" value="<?php echo $data_show['tabungan']; ?>"></td>
										<td><input type="text" class="form-control" name="email" value="<?php echo $data_show['email']; ?>"></td>
										<td><input type="text" class="form-control" name="hp" value="<?php echo $data_show['hp']; ?>"></td>
										<td align="center">
											<button type="submit" name="edit" class="btn btn-sm btn-bordred btn-info"><i class="fa fa-edit" title="Edit"></i></button>
											<button type="submit" name="delete" class="btn btn-sm btn-bordred btn-danger"><i class="fa fa-trash" title="Hapus"></i></button>
										</td>
									</form>
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
<?php
	include("../lib/footer.php");
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>