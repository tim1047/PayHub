<?
	/* Filename   : delete_room.php
	   Date       : 2015/03/06
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 방 삭제시 필요한 데이터들(host_id, room_num)을 POST로 수신받은 후 해당 DB에서 삭제 @@
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
	$temp = array();
	$json_result = array();
	$key = 417616116;
	$receipt = array();
	$i = 0;

	
	/* POST로 데이터 수신 */
	$host_id = $_POST['host_id'];
	$room_num = $_POST['room_num'];


	/* 수신 데이터에 해당하는 record DB에서 삭제 */
	if($host_id != NULL && $room_num != NULL){

		$select_query = "SELECT receipt
						 FROM Category_info
						 WHERE room_num='$room_num'";
		
		$query_result = $db->query_select($select_query);

		do{
			$receipt[$i] = $db->getData('receipt');
			$receipt[$i] = str_replace("http://54.64.94.4/uploads/receipt/","",$receipt[$i]);
			$i++;
			
		} while($db->setRecordNext());
		

		$delete_query = "DELETE FROM Room_main WHERE AES_DECRYPT(UNHEX(host_id),'$key')='$host_id' AND room_num='$room_num'";

		$query_result = $db->query_insert($delete_query);

		if($query_result){
		
			for($i=0;$i<count($receipt);$i++){
				
				$path = "../uploads/receipt/".$receipt[$i];

				if(is_file($path)){
					unlink($path);
				}
			}

			$result = TRUE;
		}

	}
		
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
?>


