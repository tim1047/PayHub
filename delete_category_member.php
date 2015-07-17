<?
	/* Filename   : delete_category_member.php
	   Date       : 2015/05/13
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 추가시 필요한 데이터들(category_num)을 전송받아 DB에 저장 @@
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
	$key = 417616116;


	/* POST로 데이터 수신 */
	$category_num = $_POST['category_num'];
	$client_id = $_POST['client_id'];


	/* DELETE query 실행 */
	if($category_num != NULL && $client_id != NULL){
		
		$update_query = "DELETE FROM Payment_info WHERE category_num='$category_num' AND client_id=HEX(AES_ENCRYPT('$client_id','$key'))";
		
		$query_result = $db->query_insert($update_query);

		if($query_result){
			$result = TRUE;
		}
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>