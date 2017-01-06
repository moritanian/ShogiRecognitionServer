<!DOCTYPE html> 
<html>
<!--<html lang="ja" manifest="<?=$v->app_pos?>/no-cache.appcache"> -->
<head>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<link rel="icon" href="<?= $v->app_pos?>/Images/icon.jpg">


<title>
ComicMyAdmin
</title>


<script src="<?=$v->app_pos?>/Scripts/util.js"></script>

<script type="text/javascript">


if(_ua.Mobile){

  document.write( '<link rel="stylesheet"'+
        'href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />' + 
    '<script src="http://code.jquery.com/jquery-1.10.2.min.js"><\/script>' +
    '<script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"><\/script>');
} else {
  document.write( '<link rel=stylesheet href="<?= $v->app_pos?>/StyleSheet/style1.css" type="text/css">' + 
    '<script src="<?= $v->app_pos?>/Plugins/jq.js"><\/script>' +
    '<link href="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.css" rel="stylesheet" />'+
    '<script src="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.js"><\/script>');

}

</script>
</head>

<script>
(function () {
  var bu;
  var ru;
  var tsu;

  var fps = 60;

  var setIntervalId;

  $(function () {
    bu = $("#font_1");
    ru = $("#font_2");
    tsu = $("#font_3");
    $(".font").css("position", "relative");
    $(window).on("touchend", touchendHandler);
  });

  function touchendHandler() {
    var mSec = 2000;
    // バイブレーション
    navigator.vibrate(mSec);
    startTxtAnime();
    setTimeout(stopTxtAnime, mSec);
  }

  function startTxtAnime() {
    stopTxtAnime();

    setIntervalId = setInterval(txtUpdate, fps / 1000);
  }

  function stopTxtAnime() {
    if (setIntervalId) clearInterval(setIntervalId);

    bu.css({
      top: 0,
      left: 0
    });

    ru.css({
      top: 0,
      left: 0
    });

    tsu.css({
      top: 0,
      left: 0
    });
  }

  function txtUpdate() {
    var l = 40;
    bu.css({
      top: Math.floor(Math.random() * l) - l / 2,
      left: Math.floor(Math.random() * l) - l / 2
    });

    ru.css({
      top: Math.floor(Math.random() * l) - l / 2,
      left: Math.floor(Math.random() * l) - l / 2
    });

    tsu.css({
      top: Math.floor(Math.random() * l) - l / 2,
      left: Math.floor(Math.random() * l) - l / 2
    });
  }
})();

</script>

<div id="txt" class="clearfix" style= "font-size: 90px">
  <div id="font_1" class="font">B</div>
  <div id="font_2" class="font">L</div>
  <div id="font_3" class="font">U</div>
</div>