<?
	/* Filename   : update_member_payment.php
	   Date       : 2015/05/13
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 추가시 필요한 데이터들(category_num, client_id, payment)을 전송받아 DB에 저장 @@
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
	$client_payment =  $_POST['client_payment'];


	/* UPDATE query 실행 */
	if($category_num != NULL && $client_id != NULL && $client_payment != NULL){
		
		mysqli_autocommit($db->getConnect(), FALSE);

		$client = explode(';',$client_id);
		$number_client = count($client);

		$payment = explode(';',$client_payment);
		$number_payment = count($payment);

		for($i=0;$i<$number_client;$i++){
		
			$update_query = "UPDATE Payment_info SET payment='$payment[$i]' WHERE category_num='$category_num' AND client_id=HEX(AES_ENCRYPT('$client[$i]','$key'))";
	
			$query_result = $db->query_update($update_query);

			if($query_result){
				$result = TRUE;
			} 
			else{
				$result = FALSE;
				mysqli_rollback($db->getConnect());
				break;
			}
		}

		mysqli_commit($db->getConnect());
		mysqli_autocommit($db->getConnect(), TRUE);
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>