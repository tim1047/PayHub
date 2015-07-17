<?
  
	/* Filename   : upload_link.php
	   Date       : 2015/05/26
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	    @@ 안드로이드에서 KakaotalkLink image를 전송받아 image의 URL을 DB에 저장 @@
	*/
	
	include("DataBase.php");
	
	/* DB 접속 */
	$host = "54.64.94.4";
	$user = "feople";
	$pword = "1234";
	$db_name = "feople";

	$db = new DataBase($host,$user,$pword,$db_name);

	/* 필요 변수 선언 */
	$result = FALSE;
	$json_result = array();

	$room_num = $_GET['room_num'];

    $file_path = "/var/www/uploads/link/";
	$file_url = "http://54.64.94.4/uploads/link/";
	$uploaded_file = "";
	$path = "";
	$date = date("YmdH:i:s");
    
	$file_name = $_FILES['uploaded_file']['name'];
	
	$temp = explode(".",$file_name);
	$temp_count = count($temp);
	
	$file_type = $temp[$temp_count - 1];

    $file_path = $file_path.$room_num."_$date".".".$file_type;
	$file_url = $file_url.$room_num."_$date".".".$file_type;
	
	$query_select = "SELECT linkURL
					 FROM Room_main
					 WHERE room_num = '$room_num'";
	
	$query_result = $db->query_select($query_select);

	if($query_result){

		$uploaded_url = $db->getData('linkURL');
		$uploaded_file = str_replace("http://54.64.94.4/uploads/link/","",$uploaded_url);

		$path = "../uploads/link/".$uploaded_file;

		if(is_file($path)){
			unlink($path);
		}
	}

	if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)){
       
		$query_update = "UPDATE Room_main SET linkURL='$file_url' WHERE room_num='$room_num'";

		$query_result = $db->query_update($query_update);

		if($query_result){
			$result = TRUE;
		}
    } 

	$json_result['result'] = $result;	

	/* JSON String return */
	echo urldecode(json_encode($json_result));

	$db->close();
 ?>