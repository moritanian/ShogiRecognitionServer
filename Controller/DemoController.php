<?php
require_once( 'Controller/ControllerBase.php' );

class DemoController extends ControllerBase
{
	
	public function indexAction(){
		//$this->view->show_only("Demo/test1");
		$this->view->show_only("Demo/3d_logo");
	}

	public function CompassAction(){
		$this->view->show_only("Demo/magnet");
	}

	public function ThreeAction(){
		$this->view->show_only("Demo/three");
	}
	public function PointAction(){
		$this->view->show_only("Demo/point");
	}

	public function WebCamAction(){
		$this->view->show_only("Demo/webcam");
	}

	public function MaterialVideoAction(){
		$this->view->show_only("Demo/material_video");
	}
}
?>