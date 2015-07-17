<?
	/* Filename   : update_list.php
	   Date       : 2015/02/15
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 방 목록 화면에서 host_id를 POST로 전송받아 해당 host_id의 방 목록을 return @@
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

	/* POST로 데이터 수신 */
	$host_id = $_POST['host_id'];


	/* SELECT query 실행 */
	if($host_id != NULL){
		/*$select_query = "SELECT Room_main.room_num,Room_main.room_title,Room_main.host_id,Room_main.imgURL,Room_member.client_id 
						FROM Room_main LEFT OUTER JOIN Room_member 
						ON Room_main.room_num=Room_member.room_num 
						WHERE Room_main.host_id='$host_id' or Room_member.client_id='$host_id'";*/
		
		$select_query = "SELECT room_num, room_title, AES_DECRYPT(UNHEX(host_id),'$key') host_id, imgURL     
						 FROM Room_main 
						 WHERE AES_DECRYPT(UNHEX(Room_main.host_id),'$key')='$host_id' or room_num IN ( SELECT room_num
																									    FROM Room_member
																									    WHERE AES_DECRYPT(UNHEX(client_id),'$key')='$host_id')";

		$query_result = $db->query_select($select_query);

		if($query_result){
			$result = TRUE;
		}
	}


	/* 결과 레코드들을 JSON 형식으로 변환 
	   데이터들을 배열에 저장 -> json_encode() 이용해서 JSON형식으로 변환 */
	do{
		$record = array();
		$record['room_num'] = $db->getData('room_num');
		$record['room_title'] = urlencode($db->getData('room_title'));
		$record['host_id'] = urlencode($db->getData('host_id'));
		$record['imgURL'] = urlencode($db->getData('imgURL'));
		$record['client_id'] = urlencode($db->getData('client_id'));

		array_push($temp,$record);

	} while($db->setRecordNext());
	
	$json_result['result'] = $result;         
	$json_result['data'] = $temp;


	/* JSON String return */
	echo urldecode(json_encode($json_result));

	$db->close();
?>