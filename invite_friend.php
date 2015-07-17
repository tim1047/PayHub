<?
	/* Filename   : invite_friend.php
	   Date       : 2015/04/12
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 초대시 필요한 데이터들(room_num,host_id,friend_id)을 전송받아 DB에 저장 @@
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
	$host_id = $_POST['host_id'];
	$friend_id = $_POST['friend_id'];  // 1111;2222;3333
	$room_num = $_POST['room_num'];


	/* INSERT query 실행 */
	if($host_id != NULL){

		$friend = explode(';',$friend_id);      // delimeter로 구분
		$number_friend = count($friend); 

		mysqli_autocommit($db->getConnect(), FALSE);
		
		for($i=0;$i<$number_friend;$i++){
			$update_query = "INSERT INTO Room_member(room_num,client_id) VALUES('$room_num',HEX(AES_ENCRYPT('$friend[$i]','$key')))";

			$query_result = $db->query_insert($update_query);
	
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