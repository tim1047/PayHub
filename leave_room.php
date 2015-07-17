<?
	/* Filename   : leave_room.php
	   Date       : 2015/02/25
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 방 퇴장시 필요한 데이터들(client_id, room_num)을 POST로 수신받은 후 해당 DB에서 삭제 @@
	*/

	include("DataBase.php");
	
	/* DB 접속 */
	$host = "54.64.94.4";
	$user = "feople";
	$pword = "1234";
	$db_name = "feople";

	$db = new DataBase($host,$user,$pword,$db_name);
	//$db1 = new DataBase($host,$user,$pword,$db_name);


	/* 필요 변수 선언 */
	$result = FALSE;
	$temp = array();
	$json_result = array();
	$key = 417616116;


	/* POST로 데이터 수신 */
	$client_id = $_POST['client_id'];
	$room_num = $_POST['room_num'];


	/* 수신 데이터에 해당하는 record DB에서 삭제 */
	if($client_id != NULL && $room_num != NULL){
	/*
		$select_query = "SELECT p.category_num
						 FROM Category_info c, Payment_info p
						 WHERE c.category_num = p.category_num AND c.room_num = '$room_num' AND p.client_id=HEX(AES_ENCRYPT('$client_id','$key'))";
			*/			
		$delete_query = "DELETE 
						 FROM Room_member
						 WHERE room_num='$room_num' AND AES_DECRYPT(UNHEX(client_id),'$key')='$client_id'";
		
		$query_result = $db->query_insert($delete_query);
		
		if($query_result){
			$result = TRUE;
		}

	}
		
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
?>

