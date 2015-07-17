<?
	/* Filename   : delete_participant.php
	   Date       : 2015/05/15
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 참가자 삭제시 필요한 데이터들(client_id, room_num)을 전송받아 DB에 저장 @@
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
	$temp = -1;
	$key = 417616116;


	/* POST로 데이터 수신 */
	$client_id = $_POST['client_id'];
	$room_num = $_POST['room_num'];


	/* DELETE query 실행 */
	if($client_id != NULL && $room_num != NULL){

		$client = explode(';',$client_id);      // delimeter로 구분
		$number_client = count($client); 

		mysqli_autocommit($db->getConnect(), FALSE);
		
		for($i=0;$i<$number_client;$i++){
			$delete_query = "DELETE FROM Room_member WHERE room_num='$room_num' AND client_id=HEX(AES_ENCRYPT('$client[$i]','$key'))";

			$query_result = $db->query_insert($delete_query);
	
			if($query_result){
				$result = TRUE;
			}
			else{
				mysqli_rollback($db->getConnect());
				$result = FALSE;
				break;
			}
		}

		mysqli_commit($db->getConnect());
		mysqli_autocommit($db->getConnect(), TRUE);
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>