<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="<?= $v->app_pos?>/Plugins/Three/three.min.js"></script>
<script src="<?= $v->app_pos?>/Plugins/Three/OrbitControls.js"></script> 


</head>

<script type="text/javascript">

$(function(){

// WebGLでウェブカメラをテクスチャに適用する
var video;

var scene;

var camera;	

var videoTexture;

var width = 1800;
var height = 960;
// ウェブカメラが使えるかどうかチェック
canWebcam();


// ウェブカメラが利用できるかをチェックする
function canWebcam(){
	// ベンダープリフィックスを考慮して初期化
	navigator.getUserMedia = (
		navigator.getUserMedia ||
		navigator.webkitGetUserMedia ||
		navigator.mozGetUserMedia
	);
	
	if(navigator.getUserMedia){
		// user media に対する設定
		navigator.getUserMedia(
			// 有効化する user media
			{
				video: true,
				audio: false
			},
			
			// usre media の取得に成功した場合
			function(localMediaStream){
				var url = (
					window.URL ||
					window.webkitURL
				);
				
				// video エレメントの生成
				video = document.createElement('video');
				
				// video エレメントにイベントを設定
				video.addEventListener('canplay', function(){
					// 複数回呼ばれないようにイベントを削除
					video.removeEventListener('canplay', arguments.callee, true);
					
					// video 再生開始をコール
					video.play();
					
					// レンダリング関数を呼ぶ
					init_render();
				}, true);
				
				// video エレメントのソースにウェブカメラを渡す
				video.src = url.createObjectURL(localMediaStream);
			},
			
			// user media の取得に失敗した場合
			function(err){
				// 取得に失敗した原因を調査
				if(err.name === 'PermissionDeniedError'){
					// ユーザーによる利用の拒否
					alert('denied permission');
				}else{
					// デバイスが見つからない場合など
					alert('can not be used webcam');
				}
			}
		);
	}else{
		// ブラウザがサポートしていない
		alert('not supported getUserMedia');
	}
}



	function init_render(){
		console.log("init");
/*
		var videoImage = document.createElement('canvas');
		videoImage.width = 480;
		videoImage.height = 200;

		var videoImageContext = videoImage.getContext('2d');
		videoImageContext.fillStyle = '#000000';
		videoImageContext.fillRect(0, 0, videoImage.width, videoImage.height);
*/
		// scene
	    scene = new THREE.Scene()
	    // rendering
	    renderer = new THREE.WebGLRenderer();
	    renderer.setSize(width, height);
	    renderer.setClearColor("#000000", 1);
	    renderer.shadowMapEnabled = true;
	    document.getElementById('stage').appendChild(renderer.domElement);

	    // camera
	    camera = new THREE.PerspectiveCamera(100, width / height, 10, 10000);
	    camera.position.set(50,-50,500);

	    // light
	    setLight(scene);
	    // control
	    controls = new THREE.OrbitControls(camera, renderer.domElement);
		
		// video
		videoTexture = new THREE.VideoTexture( video );
		videoTexture.minFilter = THREE.LinearFilter;
		videoTexture.magFilter = THREE.LinearFilter;
		videoTexture.format = THREE.RGBFormat;
		var parameters = { color: 0xffffff, map: videoTexture, overdraw: true, side:THREE.DoubleSide};
		var movieMaterial = new THREE.MeshBasicMaterial(parameters);
		var geometry = new THREE.BoxGeometry( 100 , 100, 100 );
		var mesh = new THREE.Mesh(geometry, movieMaterial);

		scene.add(mesh);


		var size = { 'x':110, 'y':600, 'z':1100 };
		var pos = {'x':-100, 'y':0.0, 'z':0.0 };
		var android_obj = new THREE.Group();
		var obj1 = createBox(pos, pos, size);
		var size = { 'x':3, 'y':600.0, 'z':100 };
		var rot = {'x':-100, 'y':0.0, 'z':0.0 };
		var pos = { 'x':0.7, 'y':0, 'z':-5 };
		var obj2 = createBox(pos, rot, size, 0xff0000);
		android_obj.add(obj1);
		android_obj.add(obj2);
		//scene.add(android_obj);
		// loop
		render();
	}

	function render(){
		requestAnimationFrame(render);
        renderer.render(scene, camera);
        controls.update();
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
 		//   videoImageContext.drawImage(video, 0, 0);
    		if (videoTexture) {
        		videoTexture.needsUpdate = true;
    		}	
		}
	}

	function setLight(scene){
        // light
        var light = new THREE.DirectionalLight("#ffffff", 1);
        light.position.set(1500,0,1000);
        light.castShadow = true;
        scene.add(light);
        var ambient = new THREE.AmbientLight("#222222", 1);
        scene.add(ambient);
	}

	function createBox(pos, rot, size, color = 0x005588){

	    var geometry = new THREE.CubeGeometry(size.x, size.y, size.z);
	    var material = new THREE.MeshLambertMaterial( { color: color } )
	    //  var geometry = new THREE.BoxBufferGeometry( size.x, size.y, size.z);

	    // var material = new THREE.MeshNormalMaterial();
	    var mesh = new THREE.Mesh( geometry, material );
	    mesh.position.set(pos.x, pos.y, pos.z);
	    return mesh;
  	}


 });

</script>

<body>
	<div id="stage"></div>
	<canvas id="canvas"></canvas>
</body>
</html>

