<?
	/* Filename   : send_push_data.php
	   Date       : 2015/05/25
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 필요한 데이터들(uuids, push_message)을 받아서 kakaotalk server에 전송 @@
	*/
	
	include("DataBase.php");
	
	/* DB 접속 */
	$host = "54.64.94.4";
	$user = "feople";
	$pword = "1234";
	$db_name = "feople";

	$db = new DataBase($host,$user,$pword,$db_name);
	
	$result = FALSE;

	/* admin_key 가져오기 */
	$admin_key = "";

	$query_select = "SELECT admin_key
					 FROM Push_data";

	$query_result = $db->query_select($query_select);

	if($query_result){
		$admin_key = urlencode($db->getData('admin_key'));	
	}

    /* 해당 uuid로 push message 전송 */
	$url = "https://kapi.kakao.com/v1/push/send";
	
	$uuids = $_POST['uuids'];
	$push_message = $_POST['push_message'];

	$header = array("Authorization: KakaoAK $admin_key");

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "uuids=$uuids&push_message=$push_message");
	
	if(curl_exec($curl)){
		$result = TRUE;
	}
	
	$json_result = array();
	$json_result['result'] = $result;	

	echo urldecode(json_encode($json_result));

	curl_close($curl);

?>