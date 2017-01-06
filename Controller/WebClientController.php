<?php
require_once( 'Controller/ControllerBase.php' );
require_once('Model/Game.php');
class WebClientController extends ControllerBase
{
	
	// ゲームリスト	
	public function indexAction(){
		//$this->view->show_only("Demo/test1");
		$gameModel = new Game();
		$game_list = $gameModel->GetAll();
		$this->view->game_list = $game_list;
		$this->view->show_only("WebClient/list");
	}

	public function WatchAction(){
		$game_id = $this->request->game_id;
		$this->view->game_id = $game_id;
		$this->view->show_only("WebClient/watch");
	}
}
?>