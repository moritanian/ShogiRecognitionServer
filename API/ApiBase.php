<?php 

require_once( "request.php" );

require_once( "util.php");

require_once( 'Model/ModelBase.php');

function ApiRoute($params){

    $controller = $params[0]; 
    $className = ucfirst($controller) . 'Api';
    require_once("API/" . $className . ".php");
    
    new $className($params);
}

class ApiBase {

    protected $request;

    public function __construct()
    {
         $this->request = new Request();
    }

    public function SendData($info){
        header('Content-type: application/json');
        echo json_encode($info);
    }
}

?>