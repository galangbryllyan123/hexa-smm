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
require("../../mainconfig.php");

if (isset($_POST['provider'])) {
	$post_cat = mysqli_real_escape_string($db, $_POST['provider']);
	$check_service = mysqli_query($db, "SELECT * FROM services_pulsa WHERE category = '$post_cat' AND status = 'Active'");
	?>
	<option value="0">Select one...</option>
	<?php
	while ($data_service = mysqli_fetch_assoc($check_service)) {
	?>
	<option value="<?php echo $data_service['sid'];?>"><?php echo $data_service['service'];?></option>
	<?php
	}
} else {
?>
<option value="0">Error.</option>
<?php
}
?>