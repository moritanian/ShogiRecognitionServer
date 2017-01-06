<?php
function setting(){
	
	require_once 'Dispatcher.php';

	require_once 'util.php';

	require_once 'Config/database.php';
	
	$dispatcher = new Dispatcher();

	IncludePathSetting($dispatcher);

	require_once 'Model/ModelBase.php';

	

	// DB接続
	$database_config = new DATABASE_CONFIG();

	ModelBase::setConnectionInfo($database_config->connInfo );

	return $dispatcher;
}
?>