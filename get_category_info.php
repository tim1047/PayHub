<?
	/* Filename   : get_category_info.php
	   Date       : 2015/05/14
	   Server URL : http://54.64.94.4 or http://feople.adultdns.net

	   @@ 안드로이드에서 필요한 데이터(room_num)를 POST로 수신받은 후 해당 DB에서 검색 후 전송 @@
	*/

	include("DataBase.php");
	
	/* DB 접속 */
	$host = "54.64.94.4";
	$user = "feople";
	$pword = "1234";
	$db_name = "feople";

	$db1 = new DataBase($host,$user,$pword,$db_name);
	$db2 = new DataBase($host,$user,$pword,$db_name);
	$db3 = new DataBase($host,$user,$pword,$db_name);


	/* 필요 변수 선언 */
	$result = FALSE;
	$temp1 = array();
	$temp2 = array();
	$category = array();
	$json_result = array();
	$key = 417616116;


	/* POST로 데이터 수신 */
	$room_num = $_POST['room_num'];


	/* 수신 데이터에 해당하는 record를 검색 */
	if($room_num != NULL){
		
		$select_query = "SELECT c.category_num, category, receipt, total_payment, COUNT(c.category_num) count 
						 FROM Category_info c, Payment_info p
						 WHERE (c.room_num = '$room_num' AND p.category_num = c.category_num) OR c.room_num = '$room_num'
						 GROUP BY c.category_num";
		
		$query_result = $db1->query_select($select_query);

		if($query_result){
			
			do{
				$member_number = $db1->getData('count');
				$category_num = $db1->getData('category_num');
				
				$category['category_num'] = urlencode($db1->getData('category_num'));
				$category['category'] = urlencode($db1->getData('category'));
				$category['receipt'] = urlencode($db1->getData('receipt'));
				$category['total_payment'] = urlencode($db1->getData('total_payment'));
				
				$select_query = "SELECT *
								 FROM Payment_info
								 WHERE category_num = '$category_num'";
				
				$query_result3 = $db3->query_select($select_query);

				if($query_result3){
					
					$select_query = "SELECT u.nickName, AES_DECRYPT(UNHEX(u.host_id),'$key') client_id, u.imgURL, p.payment
									 FROM User_info u, Payment_info p
									 WHERE u.host_id=p.client_id AND p.category_num='$category_num' AND u.host_id IN (SELECT p.client_id
																													  FROM Payment_info
																													  WHERE category_num = '$category_num')";
						
					$query_result2 = $db2->query_select($select_query);

					if($query_result2){
							
						do{
							$member = array();
							$member['nickName'] = urlencode($db2->getData('nickName'));
							$member['client_id'] = urlencode($db2->getData('client_id'));
							$member['imgURL'] = urlencode($db2->getData('imgURL'));
							$member['payment'] = urlencode($db2->getData('payment'));
								
							array_push($temp1,$member);
								
						} while($db2->setRecordNext());
							
						$result = TRUE;
					} 
				}
				
				$category['member'] = $temp1;	
				$temp1 = array();
				
				array_push($temp2,$category);
					
		   } while($db1->setRecordNext());
			
		   $result = TRUE;
		}				
		
	}
	
	$json_result['result'] = $result;
	$json_result['data'] = $temp2;

	echo urldecode(json_encode($json_result));

	$db1->close();
	$db2->close();
	$db3->close();
?>
