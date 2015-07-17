<?
	/* Filename   : get_friend_list.php
	   Date       : 2015/04/05
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 리스트 검색에 필요한 데이터(nickName)를 POST로 수신받은 후 해당 DB에서 검색 후 전송 @@
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


	/* 수신 데이터에 해당하는 record를 검색 */
	if($host_id != NULL){
	
		$select_query = "SELECT AES_DECRYPT(UNHEX(User_info.host_id),'$key') host_id, User_info.imgURL, User_info.nickName
						 FROM User_info INNER JOIN Friend_info ON User_info.host_id = Friend_info.friend_id
						 WHERE AES_DECRYPT(UNHEX(Friend_info.host_id),'$key') ='$host_id'";
		
		$query_result = $db->query_select($select_query);
		
		if($query_result){
			$result = TRUE;
		}

	}

	/* 결과 레코드들을 JSON 형식으로 변환 
	   데이터들을 배열에 저장 -> json_encode() 이용해서 JSON형식으로 변환 */
	do{
		$record = array();
		$record['host_id'] = urlencode($db->getData('host_id'));
		$record['imgURL'] = urlencode($db->getData('imgURL'));
		$record['nickName'] = urlencode($db->getData('nickName'));

		array_push($temp,$record);

	} while($db->setRecordNext());
		
	$json_result['result'] = $result;
	$json_result['data'] = $temp;

	echo urldecode(json_encode($json_result));

	$db->close();
?>
