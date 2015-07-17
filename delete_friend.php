<?
	/* Filename   : delete_friend.php
	   Date       : 2015/04/11
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 친구 삭제시 필요한 데이터들(host_id, friend_id)을 POST로 수신받은 후 해당 DB에서 삭제 @@
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
	$friend_id = $_POST['friend_id'];


	/* 수신 데이터에 해당하는 record DB에서 삭제 */
	if($host_id != NULL && $friend_id != NULL){
		
		$delete_query = "DELETE FROM Friend_info WHERE AES_DECRYPT(UNHEX(host_id),'$key')='$host_id' AND AES_DECRYPT(UNHEX(friend_id),'$key')='$friend_id'";
		
		$query_result = $db->query_insert($delete_query);
		
		if($query_result){
			$result = TRUE;
		}

	}
		
	$json_result['result'] = $result;

	echo json_encode($json_result);

	$db->close();
?>


