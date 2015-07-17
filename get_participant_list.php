<?
	/* Filename   : get_participant_list.php
	   Date       : 2015/04/09
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 필요한 데이터(room_num)를 POST로 수신받은 후 해당 DB에서 검색 후 전송 @@
	*/

	include("DataBase.php");
	
	/* DB 접속 */
	$host = "54.64.94.4";
	$user = "feople";
	$pword = "1234";
	$db_name = "feople";

	$db = new DataBase($host,$user,$pword,$db_name);
	$db1 = new DataBase($host,$user,$pword,$db_name);


	/* 필요 변수 선언 */
	$result = FALSE;
	$temp = array();
	$json_result = array();
	$key = 417616116;


	/* POST로 데이터 수신 */
	$room_num = $_POST['room_num'];


	/* 수신 데이터에 해당하는 record를 검색 */
	if($room_num != NULL){

		$select_query = "SELECT AES_DECRYPT(UNHEX(host_id),'$key') host_id, imgURL, nickName, user_kakaotalk_id
						 FROM User_info
						 WHERE host_id IN (SELECT host_id
										   FROM Room_main
										   WHERE room_num='$room_num')";
		
		$query_result = $db->query_select($select_query);

		if($query_result){
			$record = array();
			$record['client_id'] = urlencode($db->getData('host_id'));
			$record['imgURL'] = urlencode($db->getData('imgURL'));
			$record['nickName'] = urlencode($db->getData('nickName'));
			$record['user_kakaotalk_id'] = urlencode($db->getData('user_kakaotalk_id'));
			
			$host_id = $db->getData('host_id');
			
			$sub_query = "SELECT SUM(p.payment) total_payment
						  FROM Category_info c, Payment_info p
						  WHERE c.room_num='$room_num' AND c.category_num=p.category_num
						  GROUP BY p.client_id
						  HAVING p.client_id=HEX(AES_ENCRYPT('$host_id',$key))";

			$sub_result = $db1->query_select($sub_query);

			if($sub_result){
				$record['total_payment'] = $db1->getData('total_payment');
			}else{
				$record['total_payment'] = NULL;
			}

			array_push($temp,$record);
			$result = TRUE;
		}
		
		if($query_result){
			$select_query = "SELECT AES_DECRYPT(UNHEX(host_id),'$key') host_id, imgURL, nickName, user_kakaotalk_id
							 FROM User_info
							 WHERE host_id IN (SELECT client_id
											   FROM Room_member
											   WHERE room_num='$room_num') ";

			$query_result = $db->query_select($select_query);
			
			if($query_result){
				$result = TRUE;
			  
			  do{
					$record = array();
					$record['client_id'] = urlencode($db->getData('host_id'));
					$record['imgURL'] = urlencode($db->getData('imgURL'));
					$record['nickName'] = urlencode($db->getData('nickName'));
					$record['user_kakaotalk_id'] = urlencode($db->getData('user_kakaotalk_id'));
					
					$host_id = $db->getData('host_id');
			
					$sub_query = "SELECT SUM(p.payment) total_payment
								  FROM Category_info c, Payment_info p
								  WHERE c.room_num='$room_num' AND c.category_num=p.category_num
								  GROUP BY p.client_id
								  HAVING p.client_id=HEX(AES_ENCRYPT('$host_id',$key))";

					$sub_result = $db1->query_select($sub_query);

					if($sub_result){
						$record['total_payment'] = $db1->getData('total_payment');
					}else{
						$record['total_payment'] = NULL;
					}

					$sub_query = "SELECT request_payment, paid_payment
								  FROM Room_member
								  WHERE room_num='$room_num' AND client_id=HEX(AES_ENCRYPT('$host_id',$key))";

					$sub_result = $db1->query_select($sub_query);

					if($sub_result){
						$record['request_payment']=$db1->getData('request_payment');
						$record['paid_payment']=$db1->getData('paid_payment');
					}

					array_push($temp,$record);

				} while($db->setRecordNext());
			}
		}

	}
		
	$json_result['result'] = $result;
	$json_result['data'] = $temp;

	echo urldecode(json_encode($json_result));

	$db->close();
?>
