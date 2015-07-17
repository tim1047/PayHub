<?
	/* Filename   : create_room.php
	   Date       : 2015/02/15
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 방 생성시 필요한 데이터들(host_id, room_title)을 POST로 수신받은 후 해당 DB에 저장 @@
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
	$room_num = -1;
	$temp = array();
	$json_result = array();
	$key = 417616116;


	/* POST로 안드로이드로 부터 데이터 수신 */
	$room_title = $_POST['room_title'];
	$host_id = $_POST['host_id'];
	$imgURL = $_POST['imgURL'];
	$client_id = $_POST['client_id'];    // 111111;22222;33333;
	

	/* 수신 데이터 DB 입력 */
	if($room_title != NULL && $host_id != NULL && $imgURL != NULL){

		mysqli_autocommit($db->getConnect(), FALSE);

		$insert_query = "INSERT INTO Room_main(room_title,host_id,imgURL,time) VALUES('$room_title', HEX(AES_ENCRYPT('$host_id','$key')),'$imgURL',now())";

		$query_result = $db->query_insert($insert_query);
		
		if($query_result){
			$result = TRUE;
			$room_num = mysqli_insert_id($db->getConnect());

		} else {
			mysqli_rollback($db->getConnect());
		}

		if($query_result && $client_id != NULL){

				$client = explode(';',$client_id);
				$number_client = count($client);
				
				for($i=0;$i<$number_client;$i++){
					$insert_query = "INSERT INTO Room_member(room_num,client_id) VALUES('$room_num', HEX(AES_ENCRYPT('$client[$i]','$key')))";
					
					$query_result = $db->query_insert($insert_query);

					if($query_result){
						$result = TRUE;
					}
					else{
						mysqli_rollback($db->getConnect());
						$room_num = -1;
						break;
					}
				}
		}
		
		$temp['room_num'] = $room_num;
		
		mysqli_commit($db->getConnect());
		mysqli_autocommit($db->getConnect(), TRUE);
	}

	$json_result['result'] = $result; 
	$json_result['data'] = $temp;

	echo json_encode($json_result);

	$db->close();
?>

