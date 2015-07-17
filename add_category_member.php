<?
	/* Filename   : add_category_member.php
	   Date       : 2015/05/17
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 추가시 필요한 데이터들(category_num, client_id)을 전송받아 DB에 저장 @@
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
	$exist_count = 0;


	/* POST로 데이터 수신 */
	$category_num = $_POST['category_num'];
	$client_id = $_POST['client_id'];


	/* INSERT query 실행 */
	if($category_num != NULL && $client_id != NULL){

		$client = explode(";",$client_id);
		$client_count = count($client);

		mysqli_autocommit($db->getConnect(), FALSE);

		for($i=0;$i<$client_count;$i++){
			
			$update_query = "INSERT INTO Payment_info(category_num,client_id) VALUES('$category_num', HEX(AES_ENCRYPT('$client[$i]','$key')))";
		
			$query_result = $db->query_insert($update_query);

			if($query_result){
				$result = TRUE;
			}
			else{
				$error_num = mysqli_errno($db->getConnect());

				if($error_num == 1062){
					$exist_count++;
					$result = TRUE;
					
					if($exist_count == $client_count){
						$result = FALSE;
					}
				}else{
					mysqli_rollback($db->getConnect());
					$result = FALSE;
					break;
				}
			}
		}

		mysqli_commit($db->getConnect());
		mysqli_autocommit($db->getConnect(), TRUE);
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
	
?>