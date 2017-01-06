var linkWithTimeStamp = function(href){
	var timeStamp = getUnixTime();
	/*$("body").append("<form method='post' name='auto_form' id='auto_form' action='"
		+ href
		+ "'>"
		+"<input type='hidden' name='time' value='"
		+ timeStamp  
	 	+ "'></form>");
	document.auto_form.submit();
	*/
	window.location.href = href + "?time=" + timeStamp;
}

var backWithTimeStamp = function(){
	//var ref = document.referrer;
	//linkWithTimeStamp(ref);
	history.back();
}


var getUnixTime = function(){
	var date = new Date() ;
	return Math.floor( date.getTime() / 1000 ) ;
}

// ユーザエージェント識別
var _ua = (function(u){
  return {
    Tablet:(u.indexOf("windows") != -1 && u.indexOf("touch") != -1 && u.indexOf("tablet pc") == -1) 
      || u.indexOf("ipad") != -1
      || (u.indexOf("android") != -1 && u.indexOf("mobile") == -1)
      || (u.indexOf("firefox") != -1 && u.indexOf("tablet") != -1)
      || u.indexOf("kindle") != -1
      || u.indexOf("silk") != -1
      || u.indexOf("playbook") != -1,
    Mobile:(u.indexOf("windows") != -1 && u.indexOf("phone") != -1)
      || u.indexOf("iphone") != -1
      || u.indexOf("ipod") != -1
      || (u.indexOf("android") != -1 && u.indexOf("mobile") != -1)
      || (u.indexOf("firefox") != -1 && u.indexOf("mobile") != -1)
      || u.indexOf("blackberry") != -1
  }
})(window.navigator.userAgent.toLowerCase());

function getWindowSize() {
	var sW,sH,s;
	sW = window.innerWidth;
	sH = window.innerHeight;

	return [sW, sH]
}
