<?
	/* Filename   : add_request_payment.php
	   Date       : 2015/05/21
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 필요한 데이터들(room_num, client_id, request_payment, paid_payment)을 전송받아 DB에 저장 @@
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
	$key = 417616116;


	/* POST로 데이터 수신 */
	$room_num = $_POST['room_num'];
	$client_id = $_POST['client_id'];
	$request_payment = $_POST['request_payment'];
	$paid_payment = $_POST['paid_payment'];


	/* INSERT query 실행 */
	if($room_num != NULL && $client_id != NULL){
		
		$update_query = "UPDATE Room_member SET request_payment='$request_payment', paid_payment='$paid_payment' WHERE room_num='$room_num' AND client_id=HEX(AES_ENCRYPT('$client_id','$key'))";
		
		$query_result = $db->query_update($update_query);

		if($query_result){
			$result = TRUE;
		}
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>