<?php
session_start();
require("../mainconfig.php");
$page_type = "Admin Oper";

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

include("../lib/headeradmin.php");
?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h3 class="text-center"> Oper Disini Gan</h3><br>
                            <form method="POST">
                                <div class="form-group row">
    								<label class="col-md-2 control-label">Insert Query</label>
    								<div class="col-md-10">
    									<select class="form-control" id="category" name="opsi">
											<option value="0">Pilih salah satu...</option>
											<option value="1">Get Service Sosmed Irvankede</option>
											<option value="2">Get Service Sosmed Diamondpedia</option>
											<option value="3">Get Service Pulsa Diamondpedia</option>
											<option value="6">Get Kategori Pulsa Diamondpedia</option>
											<option value="7">Get Kategori Sosmed Irvan Kede</option>
											<option value="4">Hapus Service Sosmed</option>
											<option value="5">Hapus Service Pulsa</option>
											<option value="8">Hapus Kategori Sosmed</option>
											<option value="9">Hapus Kategori Pulsa</option>
										</select>
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
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <h3 class="text-center">Result : </h3>
                                <?php
                Class Ez{
    
    public static $api_id = 8206;
    
    public static $apikey_irvan = '35253f-859313-5edcb8-b5c75b-aeb263';
    
    public static $apikey_diaomond  = 'bgTUOkfpyMHmCGN9Ishl';
    
    public function get_service_irvan(){
        
        $data = ['api_id'=>self::$api_id,'api_key'=>self::$apikey_irvan];
        $ariez = Ez::curl('https://irvankede-smm.co.id/api/services',$data);
        $hasil = $ariez['exec'];
        
        return $hasil->data;    
    }
    
    public function get_service_cat_irvan(){
        
        $data = ['api_id'=>self::$api_id,'api_key'=>self::$apikey_irvan];
        $ariez = Ez::curl('https://irvankede-smm.co.id/api/services',$data);
        $hasil = $ariez['exec'];
        
        return $hasil->data;    
    }
    
    public function get_service_diamond(){
            
        $data = ['api_key'=>self::$apikey_diaomond];
        $ariez = Ez::curl('https://serverh2h.net/service/social',$data);
        
        return $ariez['exec'];  
    }
    
    public function get_service_pulsa_diamond(){
         $data = ['api_key'=>self::$apikey_diaomond];
        $ariez = Ez::curl('https://serverh2h.net/service/pulsa',$data);
        
        return $ariez['exec'];  
    }
    
     public function get_service_pulsa_cat_diamond(){
         $data = ['api_key'=>self::$apikey_diaomond];
        $ariez = Ez::curl('https://serverh2h.net/service/pulsa',$data);
        
        return $ariez['exec'];  
    }
    
    private static function curl($end_point = null ,$postdata = null){
        $ch = curl_init($end_point);
        curl_setopt($ch ,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch ,CURLOPT_FOLLOWLOCATION,TRUE);
        curl_setopt($ch ,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch ,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch ,CURLOPT_POSTFIELDS,http_build_query($postdata));
        $exe = json_decode(curl_exec($ch));
        $info = json_decode(curl_getinfo($ch));
        $error = json_decode(curl_error($ch));
        curl_close($ch);
        return [
            'exec'=>$exe,
            'info'=>$info,
            'error'=>$error,
            ];
        
    }
    
    
}

if(isset($_POST['submit'])){
$x = new Ez;
if($_POST['opsi'] == '1'){
    $service_irvan = $x->get_service_irvan();
    #insert_irv
    foreach($service_irvan as $data ){
        $id = $data->id;
        $category = $data->category;
        $name = $data->name;
        $price = $data->price;
        $min = $data->min;
        $max = $data->max;
        $note = $data->note;
        $rate = $price+0;
        
        $cek = $db->query("SELECT * FROM services WHERE sid = '$id' AND provider = 'IRVANKEDE'");
        if(mysqli_num_rows($cek) == 1){
            echo 'DATA ID '.$id.' SUDAH DIMASUKKAN <br \>';
        }else{
        
            $insert = $db->query("INSERT INTO services (sid,category,service,note,min,max,price,status,pid,provider) VALUES ('$id','$category','$name','$note','$min','$max','$rate','Active','$id','IRVANKEDE')");
            if($insert == TRUE){
            echo "<font color='green'><b>SUKSES INSERT</b></font> >  ID = ".$id." , SERVICE = ".$name." <br \>";
            }else{
            echo "GAGAL MEMASUKAN DATA <br \>";
            }
        }
    }
}else if($_POST['opsi'] == '2'){
    $service_diamond = $x->get_service_diamond();
    
    #insert_diamond
    foreach($service_diamond as $data ){
        $id = $data->id;  
        $category = $data->category;
        $name = $data->service;
        $price = $data->price;
        $min = $data->min;
        $max = $data->max;
        $note = $data->note;
        
        $cek = $db->query("SELECT * FROM services WHERE sid = '$id' AND provider='DIAMOND'");
        if(mysqli_num_rows($cek) == 1){
            echo 'DATA ID '.$id.' SUDAH DIMASUKKAN <br \>';
        }else{
            $insert = $db->query("INSERT INTO services (sid,category,service,note,min,max,price,status,pid,provider) VALUES ('$id','$category','$name','$note','$min','$max','$price','Active','$id','DIAMOND')");
            if($insert == TRUE){
            echo "<font color='green'><b>(+) SUKSES INSERT</b></font> >  ID = ".$id." , SERVICE = ".$name." PRICE = ".$price." <br \>";
            }else{
            echo "GAGAL MEMASUKAN DATA <br \>";
            }
        }
    }
}else if($_POST['opsi'] == '8'){
    $delete_service_all = $db->query("DELETE FROM service_cat");
    if($delete_service_all){
        echo "<font color='green'><b>(-) SUKSES DELETE CATEGORY SOSMED";
    }    
}else if($_POST['opsi'] == '4'){
    $delete_service_all = $db->query("DELETE FROM services");
    if($delete_service_all){
        echo "<font color='green'><b>(-) SUKSES DELETE SERVICE SOSMED";
    }
}else if($_POST['opsi'] == '9'){
    $delete_service_all = $db->query("DELETE FROM service_pulsa_cat");
    if($delete_service_all){
        echo "<font color='green'><b>(-) SUKSES DELETE CATEGORY PULSA";
    }    
}else if($_POST['opsi'] == '5'){
    $delete_service_all = $db->query("DELETE FROM services_pulsa");
    if($delete_service_all){
        echo "<font color='green'><b>(-) SUKSES DELETE SERVICE PULSA";
    }
}else if($_POST['opsi'] == '3'){
      $service_diamond = $x->get_service_pulsa_diamond();
    
    #insert_diamond
    foreach($service_diamond as $data ){
        $id = $data->id;  
        $oprator = $data->oprator;
        $type = $data->tipe;
        $price = $data->price;
        $name = $data->name;
        $status = $data->status;
        $rate = $price+200;
        $agen = $price+20;
        
        $cek = $db->query("SELECT * FROM services_pulsa WHERE sid = '$id' AND provider='DP'");
        if(mysqli_num_rows($cek) == 1){
            echo 'DATA ID '.$id.' SUDAH DIMASUKKAN <br \>';
        }else{
            $insert = $db->query("INSERT INTO services_pulsa (sid,type,category,service,price,price_agen,status,pid,provider) VALUES ('$id','$type','$oprator','$name','$rate','$agen','Active','$id','DP')");
            if($insert == TRUE){
            echo "<font color='green'><b>(+) SUKSES INSERT</b></font> >  ID = ".$id." <br> SERVICE = ".$name." <br> PRICE = ".$rate." <br>PRICE AGEN = ".$agen." <br \>";
            }else{
            echo "GAGAL MEMASUKAN DATA <br \>";
            }
        }
    }
}else if($_POST['opsi'] == '6'){
      $service_diamond = $x->get_service_pulsa_cat_diamond();
    
    #insert_diamond
    foreach($service_diamond as $data ){
        $id = $data->id;  
        $oprator = $data->oprator;
        $type = $data->tipe;
        $price = $data->price;
        $name = $data->name;
        $status = $data->status;
        $rate = $price+251;
        
        $cek = $db->query("SELECT * FROM service_pulsa_cat WHERE name = '$oprator'");
        if(mysqli_num_rows($cek) == 1){
            echo 'DATA ID '.$oprator.' SUDAH DIMASUKKAN <br \>';
        }else{
            $insert = $db->query("INSERT INTO service_pulsa_cat (id,name,code,type) VALUES (NULL,'$oprator','$oprator','$type')");
            if($insert == TRUE){
            echo "<font color='green'><b>(+) SUKSES INSERT</b></font> >  Name = ".$oprator." , Code = ".$oprator." Type = ".$type." <br \>";
            }else{
            echo "GAGAL MEMASUKAN DATA <br \>";
            }
        }
    }    
}else if($_POST['opsi'] == '7'){
    $service_irvan = $x->get_service_cat_irvan();
    #insert_irv
    foreach($service_irvan as $data ){
        $id = $data->id;
        $category = $data->category;
        $name = $data->name;
        $price = $data->price;
        $min = $data->min;
        $max = $data->max;
        $note = $data->note;
        $rate = $price+200;
        
        $cek = $db->query("SELECT * FROM service_cat WHERE name = '$category'");
        if(mysqli_num_rows($cek) == 1){
            echo 'DATA ID '.$id.' SUDAH DIMASUKKAN <br \>';
        }else{
        
            $insert = $db->query("INSERT INTO service_cat (id,name,code) VALUES (NULL,'$category','$category')");
            if($insert == TRUE){
            echo "<font color='green'><b>(+) SUKSES INSERT</b></font> >  Name = ".$category." , Code = ".$category." <br \>";
            }else{
            echo "GAGAL MEMASUKAN DATA <br \>";
            }
        }
    }    
}

}
?>

                        </div>
                    </div>
                </div>
                
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$site_config['base_url']);
}
?>