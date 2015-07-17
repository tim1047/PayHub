<?
	/* FileName  : DataBase.php
	   data      : 2015/02/15

	   @@ PHP을 이용하여 MySQL 통신을 위해 생성한 library @@
	*/

class DataBase{
	private $connect;             // db접속 
	private $err_query;			  // error query
	private $err_msg;             // error message
	private $result;              // query 실행결과
	private $record_count;        // query 결과 record 개수
	private $row;                 // 결과 record 
	private $row_num;             // 결과 record 중 현재 row

	
	/* 해당 DB 접속 - constructor */
	function __construct($host,$user,$pw,$db_name){
		$this->connect = mysqli_connect($host,$user,$pw) or die("db connect fail");
		mysqli_select_db($this->connect,$db_name) or die("db select fail");
	}


    /* insert, delete 쿼리 실행 ( 결과 값이 없는 query ) */
	function query_insert($query){
		$this->err_msg = "";
		$this->result = mysqli_query($this->connect,$query);

		if(!$this->result){
			$this->err_query = $query;
			$this->err_msg = mysqli_error($this->connect);
			return false;
		}
		else{
			if(mysqli_affected_rows($this->connect) <= 0){
				return false;
			}
		}
		return true;
	}

    /* update 쿼리 실행 ( 결과 값이 없는 query ) */
	function query_update($query){
		$this->err_msg = "";
		$this->result = mysqli_query($this->connect,$query);

		if(!$this->result){
			$this->err_query = $query;
			$this->err_msg = mysqli_error($this->connect);
			return false;
		}

		return true;
	}


	/* select 쿼리 실행 ( 결과 값이 있는 query ) */
	function query_select($query){
		$this->err_msg = "";

		$this->result = mysqli_query($this->connect,$query);
		if(!$this->result){
			$this->err_query = $query;
			$this->err_msg = mysqli_error($this->connect);
			return false;
		}
		else{
			$this->record_count = mysqli_num_rows($this->result);
			$this->row = mysqli_fetch_array($this->result);
			$this->row_num=0;

			if($this->row == 0){
				return false;
			}
		}
		return true;
	}
	

	/* 첫 번째 결과 record로 이동 */
	function setRecordFirst(){

		$this->row_num = 0;
		mysqli_data_seek($this->result,$this->row_num);
		$this->row = mysqli_fetch_array($this->result);
	}
	
	/* 마지막 결과 record로 이동 */
	function setRecordLast(){

		$this->row_num = $this->record_count - 1;
		mysqli_data_seek($this->result,$this->row_num);
		$this->row = mysqli_fetch_array($this->result);
	}

	/* 이전 record로 이동 */
	function setRecordPrev(){

		if($this->row_num - 1 >= 0){
			$this->row_num = $this->row_num - 1;
			mysqli_data_seek($this->result,$this->row_num);
			$this->row = mysqli_fetch_array($this->result);
			return true;
		}
		else{
			return false;
		}
	}

	/* 다음 record로 이동 */
	function setRecordNext(){

		if($this->row_num + 1 < $this->record_count){
			$this->row_num = $this->row_num + 1;
			mysqli_data_seek($this->result,$this->row_num);
			$this->row = mysqli_fetch_array($this->result);
			return true;
		}
		else{
			return false;
		}
	}

	/* Query 실행 후 결과 record 개수 return */
	function getRecordCount(){
		return $this->record_count;
	}

	/* KEY 값을 이용한 데이터 return */
	function getData($key){
		return $this->row[$key];
	}

	/* DB connect 변수 return */
	function getConnect(){
		return $this->connect;
	}

	/* DB 접속 종료 */
	function close(){
		mysqli_close($this->connect);
	}
}
?>

