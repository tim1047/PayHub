<?
  
	/* Filename   : get_receipt_URL.php
	   Date       : 2015/05/18
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	    @@ 안드로이드에서 category_num을 전송받아 image의 URL을 안드로이드에 전송 @@
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


	/* POST로 데이터 수신 */
	$category_num = $_POST['category_num'];


	/* 수신 데이터에 해당하는 record를 검색 */
	if($category_num != NULL){
	
		$select_query = "SELECT receipt
						 FROM Category_info
						 WHERE category_num='$category_num'";
		
		$query_result = $db->query_select($select_query);
		
		if($query_result){
			$receipt_url = $db->getData('receipt');

			if($receipt_url != NULL){
				$result = TRUE;
			}
		}
	}

	$json_result['result'] = $result;
	$json_result['receipt'] = $receipt_url;
	
	/* JSON String return */
	echo urldecode(json_encode($json_result));

	$db->close();
 ?>