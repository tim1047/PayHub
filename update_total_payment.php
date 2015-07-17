<?
	/* Filename   : update_payment.php
	   Date       : 2015/05/13
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
	$json_result = array();

	/* POST로 데이터 수신 */
	$category_num = $_POST['category_num'];
	$total_payment = $_POST['total_payment'];


	/* query 실행 */
	if($category_num != NULL && $total_payment != NULL){

		$update_query = "UPDATE Category_info SET total_payment='$total_payment' WHERE category_num='$category_num'";

		$query_result = $db->query_update($update_query);

		if($query_result){
			$result = TRUE;
		}		
	}
	
	$json_result['result'] = $result;         

	/* JSON String return */
	echo urldecode(json_encode($json_result));

	$db->close();
?>