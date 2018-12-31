<?php
include("../../../../wp-config.php");

$current_lsi_id=$_REQUEST['lsi_id'];
$current_user_id=$_REQUEST['current_user_id'];
$lsi_complurlpath=get_post_meta($current_lsi_id,'lsi_complete_url_path',true);
$lsi_filename=get_post_meta($current_lsi_id,'lsi_filename',true);
$usermetakey='lsi_count_no_downloads_for_'.$current_lsi_id;
$getuserprelsidata=get_user_meta($current_user_id, $usermetakey, true); 
if($getuserprelsidata===false)
{
	$usermetakeyval=1;
	add_user_meta( $current_user_id, $usermetakey, $usermetakeyval); 
}else{
	$usermetakeyval=$getuserprelsidata+1;
	update_user_meta( $current_user_id, $usermetakey, $usermetakeyval);
}
/******************Download Audio Code******************/
$ch = curl_init($lsi_complurlpath);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_NOBODY, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
$output = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($status == 200) {
    header("Content-type: application/octet-stream"); 
    header("Content-Disposition: attachment; filename=".$lsi_filename); 
    echo $output;
    die();
}
?>