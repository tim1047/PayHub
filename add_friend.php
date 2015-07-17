<?
	/* Filename   : add_friend.php
	   Date       : 2015/03/28
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 추가시 필요한 데이터들(host_id)을 전송받아 DB에 저장 @@
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
	$friend_id = $_POST['friend_id'];  
	$name = $_POST['name'];
	$flag = $_POST['flag'];

/*
	$friend = explode(';',$friend_id);      // delimeter로 구분
	$number_friend = count($friend);        // 추가 할 친구 수 저장
	
*/

	/* INSERT query 실행 */
	if($host_id != NULL){
		
		if($flag == "1"){
			$update_query = "INSERT INTO Friend_info(host_id,friend_id) VALUES(HEX(AES_ENCRYPT('$host_id','$key')),HEX(AES_ENCRYPT('$friend_id','$key')))";

			$query_result = $db->query_insert($update_query);

			if($query_result){
				$result = TRUE;
			}

		} else {
			mysqli_autocommit($db->getConnect(), FALSE);

			$update_query = "INSERT INTO Friend_info(host_id,friend_id) VALUES(HEX(AES_ENCRYPT('$host_id','$key')),HEX(AES_ENCRYPT('$friend_id','$key')))";

			$query_result = $db->query_insert($update_query);

			if($query_result){

				$update_query = "INSERT INTO User_info(host_id,imgURL,nickName,member) VALUES(HEX(AES_ENCRYPT('$friend_id','$key')),'http://54.64.94.4/default.jpg','$name',0)";

				$query_result = $db->query_update($update_query);

				if($query_result){
					$result = TRUE;
				}
				else{
					$error_num = mysqli_errno($db->getConnect());
					
					if($error_num != 1062){
						mysqli_rollback($db->getConnect()); 
					}else{
						$result = TRUE;
					}
				}
			}

			else{
				mysqli_rollback($db->getConnect());
			}

			mysqli_commit($db->getConnect());
			mysqli_autocommit($db->getConnect(), TRUE);
		}
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>