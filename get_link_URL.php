<?
  
	/* Filename   : get_link_URL.php
	   Date       : 2015/05/26
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	    @@ 안드로이드에서 room_num을 전송받아 image의 URL을 안드로이드에 전송 @@
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


	/* POST로 데이터 수신 */
	$room_num = $_POST['room_num'];


	/* 수신 데이터에 해당하는 record를 검색 */
	if($room_num != NULL){
	
		$select_query = "SELECT linkURL
						 FROM Room_main
						 WHERE room_num='$room_num'";
		
		$query_result = $db->query_select($select_query);
		
		if($query_result){
			$link_url = $db->getData('linkURL');

			if($link_url != NULL){
				$result = TRUE;
			}
		}
	}

	$json_result['result'] = $result;
	$json_result['link'] = $link_url;
	
	/* JSON String return */
	echo urldecode(json_encode($json_result));

	$db->close();
 ?>