<?
	/* Filename   : delete_category.php
	   Date       : 2015/05/22
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
	$file_name = "";


	/* POST로 데이터 수신 */
	$category_num = $_POST['category_num'];


	/* DELETE query 실행 */
	if($category_num != NULL){
		
		$select_query = "SELECT receipt
						 FROM Category_info
						 WHERE category_num = '$category_num'";
		
		$query_result = $db->query_select($select_query);

		if($query_result){
			$file_name = $db->getData('receipt');
			$file_name = str_replace("http://54.64.94.4/uploads/receipt/","",$file_name);
		}

		$update_query = "DELETE FROM Category_info WHERE category_num='$category_num'";
		
		$query_result = $db->query_insert($update_query);

		if($query_result){
			$result = TRUE;
		}
		
		$path = "../uploads/receipt/".$file_name;
		
		if(is_file($path)){
			unlink($path);
		}
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();


?>