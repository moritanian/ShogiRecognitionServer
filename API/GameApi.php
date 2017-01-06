<?php 

require_once( 'Controller/ControllerBase.php' );

require_once( 'Model/Game.php');

require_once( 'Model/GameRecord.php');

class GameApi extends ApiBase {
  
  	private $game_id;

  	private $gameModel;

  	private $gameRecordModel;

   	public function __construct($params)
    {
    	parent::__construct();
    	$this->game_id = 0;
    	if(isset($params[1])){
    		$this->game_id = $params[1];
    	}
    	$method = "index";
    	
    	if(isset($params[2])){
    		$method =  strtr(ucwords(strtr($params[2], ['_' => ' '])), [' ' => '']);
    	}

    	$this->gameModel = new Game();
    	$this->gameRecordModel = new GameRecord();

    	$this->$method($params);

    	

    }

    public function index($params){
    	$this->Show($params);
    }


	public function Start($params)
	{
		$description = $this->request->description;
		$first_player_name = $this->request->first_player_name;
		$second_player_name = $this->request->second_player_name;
		$start_time = $this->request->start_time ? $this->request->start_time : date("Y-m-d H:i:s");

		$game_data = array("description" => $description, "first_player_name" => $first_player_name, "second_player_name" => $second_player_name, "start_time" => $start_time);
		$id = $this->gameModel->createGame($game_data);
		$info = array("result" =>  True, "game_id" => $id);
		$this->SendData($info);
	}	

	public function End($params){
		$winner = $this->request->winner;
		$end_time = $this->request->end_time ? $this->request->end_time : date("Y-m-d H:i:s");
		$game_data = array("game_id" => $game_id, "winner" => $winner, "end_time" => $end_time);
		$this->gameModel->EndGame($game_data);
		$this->SendData(array("result" => True));
	}

	public function Show($params){
		if(isset($params[1])){
			$list = $this->gameModel->GetById($this->game_id);
		}else{
			$list = $this->gameModel->GetAll();
		}
		$this->SendData(array("result" => True, "list" => $list));
	}


	public function GameRecord($params){
		$action = $params[3];
		if($action == "show"){
			$from = $params[4];
			$to = isset($params[5]) ? $params[5] : $from;
			if($to == -1){
				$records = $this->gameRecordModel->GetByGameid($this->game_id);
			}else{
				$records = $this->gameRecordModel->GetByGameidAndEpoch($this->game_id, $from, $to);
			}
			$result = $records ? True : False;
			$this->SendData(array("result" => $result, "records" => $records));

		}elseif ($action == "add") {
			$epoch =  $this->request->epoch;
			$position = $this->request->position;
			$target =  $this->request->target;
			$is_promotion =  $this->request->is_promotion;
			$revival = $this->request->revival;
			$kihu =  $this->request->kihu;
			$create_time = $this->request->create_time ? $this->request->create_time : date("Y-m-d H:i:s");
			$game_data = array("game_id" => $this->game_id, "epoch" => $epoch, "target" => $target, "position" => $position, "is_promotion" => $is_promotion, "revival" => $revival, "kihu" => $kihu, "create_time" => $create_time);
			$this->gameRecordModel->insertData($game_data);
			$this->gameModel->updateGame(array("game_id" => $this->game_id, "epoch" => $epoch));
			$info = array("result" =>  True, "add" => True, "is_promotion" => $is_promotion);
 			$this->SendData($info);
		}

	}

	public function GameBoard($params){
		$epoch = $params[3];

	}
}