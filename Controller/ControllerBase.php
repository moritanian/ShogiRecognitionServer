<?php 

require_once( "request.php" );

require_once( "util.php");

require_once( 'Model/ModelBase.php');

require_once( 'MyTemplate.php' );

class ControllerBase {

	protected $view;

	protected $request;

    protected $userData;

    protected $app_pos;

	public function __construct($url="", $app_pos="")
    {
        $this->app_pos = $app_pos;
        //user data
        $userName = isset($_SESSION['username']) ? $_SESSION['username'] : "";

        // リクエスト

        $this->request = new Request();

        //ビューインスタンス化

        $this->view = new MyTemplate();

        $this->view->app_pos = $app_pos;
  
        if($userName){
        
            $userDataModel = new UserData();
            
            $this->userData = $userDataModel->getByUserName($userName);         

            $this->view->authority = $this->userData['authority'];

            $this->view->user_name = $this->userData['user_name'];
        }
    }

    public function notFoundErrorAction(){
        header("HTTP/1.0 404 Not Found");
        $this->view->show("404Error");
    }

    public function authorityErrorAction(){
        header("HTTP/1.1 403 Forbidden");
        $this->view->show("403Error");
    }

    // 権限を満たしているか
    public function checkAuthority($authority){
        if($authority > $this->userData['authority']){
            // 権限をみたしていない場合はその旨を表示して終了
            $this->authorityErrorAction();
            exit();
        }
    }
    public static function checkSession(){
        @session_start();

        return isset($_SESSION['username']);
    } 

    public function user_id(){
        return $this->userData['user_id'];
    }  
    
    public function request(){
        return $this->request;
    }

    public function redirectURL($path, $get_params, $timeStamp){
        $url = $this->app_pos . "/" . $path;
        foreach ($get_params as $key => $value) {
            if($value == reset($get_params)){
                $url .= "?";
            }else{
                $url .= "&";
            }
            $url .= $key . "=" . $value;
        }
        if($timeStamp){
            if(count($get_params)){
                $url .= "&";
            }else{
                $url .= "?";
            }    
            $url .= "time=" . time();
        }
        header("Location: " . $url);
        exit;
    }
}
?>