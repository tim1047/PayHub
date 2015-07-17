<?
	/* Filename   : insert_user_info.php
	   Date       : 2015/05/20
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 로그인 시 필요한 데이터들(host_id, imgURL, nickName)을 전송받아 DB에 저장 @@
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
	$imgURL = $_POST['imgURL'];
	$nickName = $_POST['nickName'];
	$user_kakaotalk_id = $_POST['user_kakaotalk_id'];


	/* SELECT query 실행 */
	if($host_id != NULL && $imgURL != NULL && $nickName != NULL){
		$insert_query = "INSERT INTO User_info(host_id,imgURL,nickname,user_kakaotalk_id,member) VALUES(HEX(AES_ENCRYPT('$host_id',$key)),'$imgURL','$nickName','$user_kakaotalk_id',1)";

		$query_result = $db->query_insert($insert_query);

		if($query_result){
			$result = TRUE;
		}
		else {
			$error_num = mysqli_errno($db->getConnect());

			if($error_num == 1062){

				$update_query = "UPDATE User_info SET host_id=HEX(AES_ENCRYPT('$host_id','$key')), imgURL='$imgURL', nickName='$nickName', user_kakaotalk_id='$user_kakaotalk_id', member=1 
				                 WHERE host_id=HEX(AES_ENCRYPT('$host_id',$key))";

				$query_result = $db->query_update($update_query);
				
				echo mysqli_errno($db->getConnect());
				if($query_result){
					$result = TRUE;
				}
			}
		}
	}
	
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
?>
	
