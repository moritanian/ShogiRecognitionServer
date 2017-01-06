<?php

class DATABASE_CONFIG
{
	public $connInfo = array(); 

	private $local = array(
	    'host'     => 'localhost',
	    'dbname'   => 'flyby',
	    'dbuser'   => 'root',
	    'password' => 'MyPro'
	);

	private $production = array(
		'host'     => 'us-cdbr-iron-east-04.cleardb.net',
	    'dbname'   => 'heroku_ba9c285d0d5e3d9',
	    'dbuser'   => 'b34c46a4b29cfc',
	    'password' => '42ab9ee3'
	    );

	public function __construct()
	{
		if ($_SERVER['SERVER_NAME']=='localhost'   || preg_match('/^192.[0-9]+.[0-9]+.[0-9]+$/', $_SERVER['SERVER_NAME'] ) == 1){
			$this->connInfo = $this->local;
		}else{
			$this->connInfo = $this->production;
		}
	}
}
?>