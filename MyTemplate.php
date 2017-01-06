<?php
class MyTemplate
{
    function show($view, $notLogin = false)
    {
        $v = $this;
        include("View/Header.php"); 
        if(!$notLogin){
           
            include("View/TopBar.php");
        }
        include("View/{$view}.php");
    	if(!$notLogin){
            include("View/Footer.php");
        }
    }

    function show_only($view){
        $v = $this;
        include("View/{$view}.php"); 
    }

    public function h($str) {
    	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}
?>