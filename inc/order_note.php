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

require("../mainconfig.php");

if (isset($_POST['service'])) {
	$post_sid = $db->real_escape_string($_POST['service']);
	$check_service = $db->query("SELECT * FROM services WHERE sid = '$post_sid' AND status = 'Active'");
	if ($check_service->num_rows == 1) {
		$data_service = $check_service->fetch_array(MYSQLI_ASSOC);
	?>
		<div class="form-group row">
			<label class="col-md-2 control-label">Informasi</label>
		    <div class="col-md-3">
			    <label>Minimal</label>
			    <input type="number" class="form-control" value="<?php echo number_format($data_service['min'],0,',','.'); ?>" disabled>
		    </div>
		    <div class="col-md-3">
			    <label>Maksimal</label>
			    <input type="number" class="form-control" value="<?php echo $data_service['max']; ?>" disabled>
		    </div>
		    <div class="col-md-4">
			    <label>Harga/1000</label>
			    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="price" value="<?php echo number_format($data_service['price'],0,',','.'); ?>" disabled>
                </div>
            </div>
		    <label class="col-md-2 control-label"></label>
		        <div class="col-md-10">
			        <label></label>
			        <div class="input-group">
			            <td><textarea rows="5" cols="130" name="content" class="form-control" readonly="true"><?php echo $data_service['note']; ?></textarea></td>
			        </div>
			    </div>
			</div>
	<?php
	} else {
	?>
												<div class="alert alert-icon alert-danger" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
													<i class="mdi mdi-block-helper"></i>
													<b>Error:</b> Service not found.
												</div>
	<?php
	}
} else {
?>
												<div class="alert alert-icon alert-danger" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
													<i class="mdi mdi-block-helper"></i>
													<b>Error:</b> Something went wrong.
												</div>
<?php
}