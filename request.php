<?php 
class Request
{
	public function __construct()
    {
	}
	public function __get($name){
		if($name == "_all"){	// キケン!!!
			$ret = array_merge($_POST, $_GET);
			return $ret;
		}
		$post = filter_input(INPUT_POST, $name);
		if($post==null)$post = filter_input(INPUT_POST, $name, FILTER_DEFAULT ,FILTER_REQUIRE_ARRAY);
		$get = filter_input(INPUT_GET, $name);
		return $post!=null ? $post : $get;
	}

	public function get_json(){
		$json_string = file_get_contents('php://input');
		$obj = json_decode($json_string);
		return $obj;
	}	
}

?>