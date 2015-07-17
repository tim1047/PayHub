<?
	/* Filename   : add_category.php
	   Date       : 2015/05/09
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 추가시 필요한 데이터들(room_num)을 전송받아 DB에 저장 @@
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
	$category_num=-1;


	/* POST로 데이터 수신 */
	$room_num = $_POST['room_num'];
	$category = $_POST['category'];


	/* INSERT query 실행 */
	if($room_num != NULL){
		
		$update_query = "INSERT INTO Category_info(room_num,category) VALUES('$room_num','$category')";
		
		$query_result = $db->query_insert($update_query);

		if($query_result){
			$result = TRUE;
			$category_num = mysqli_insert_id($db->getConnect());
		}
	}
	
	$json_result['result'] = $result;
	$json_result['category_num'] = $category_num;

	echo json_encode($json_result);

	$db->close();
	
?>