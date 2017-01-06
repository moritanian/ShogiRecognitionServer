<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - materials - video</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				background-color: #000;
				color: #fff;
				margin: 0px;
				overflow: hidden;
				font-family:Monospace;
				font-size:13px;
				text-align:center;
				font-weight: bold;
				text-align:center;
			}
			a {
				color:#0078ff;
			}
			#info {
				color:#fff;
				position: absolute;
				top: 5px; width: 100%;
				z-index:100;
			}
		</style>
	</head>
	<body>

		<div id="info">
			<a href="http://threejs.org" target="_blank">three.js</a> - webgl video demo. playing <a href="http://durian.blender.org/" target="_blank">sintel</a> trailer
		</div>

		<script src="<?= $v->app_pos?>/Plugins/Three/three.min.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/OrbitControls.js"></script> 

		<script src="<?= $v->app_pos?>/Plugins/Three/shaders/ConvolutionShader.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/shaders/CopyShader.js"></script>

		<script src="<?= $v->app_pos?>/Plugins/Three/postprocessing/EffectComposer.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/postprocessing/RenderPass.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/postprocessing/MaskPass.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/postprocessing/BloomPass.js"></script>
		<script src="<?= $v->app_pos?>/Plugins/Three/postprocessing/ShaderPass.js"></script>

		<script src="<?= $v->app_pos?>/Plugins/Three/Detector.js"></script>

		<!--<video id="video" autoplay loop webkit-playsinline style="display:none">
			<source src="textures/sintel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
			<source src="textures/sintel.ogv" type='video/ogg; codecs="theora, vorbis"'>
		</video> -->

		<script>
			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();
			var container;
			var camera, scene, renderer;
			var video, texture, material, mesh;
			var composer;
			var mouseX = 0;
			var mouseY = 0;
			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;
			var cube_count,
				meshes = [],
				materials = [],
				xgrid = 20,
				ygrid = 10;

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
									init();
									animate();
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

			function init() {
				console.log("init");
				container = document.createElement( 'div' );
				document.body.appendChild( container );
				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 500;
				scene = new THREE.Scene();
				var light = new THREE.DirectionalLight( 0xffffff );
				light.position.set( 0.5, 1, 1 ).normalize();
				scene.add( light );
				renderer = new THREE.WebGLRenderer( { antialias: false } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );
				//video = document.getElementById( 'video' );
				console.log("init");
				
				texture = new THREE.VideoTexture( video );
				console.log("init");
				
				texture.minFilter = THREE.LinearFilter;
				texture.magFilter = THREE.LinearFilter;
				texture.format = THREE.RGBFormat;
				//


				var i, j, ux, uy, ox, oy,
					geometry,
					xsize, ysize;
				ux = 1 / xgrid;
				uy = 1 / ygrid;
				xsize = 480 / xgrid;
				ysize = 204 / ygrid;
				var parameters = { color: 0xffffff, map: texture };
				cube_count = 0;
				for ( i = 0; i < xgrid; i ++ )
				for ( j = 0; j < ygrid; j ++ ) {
					ox = i;
					oy = j;
					geometry = new THREE.BoxGeometry( xsize, ysize, xsize );
					change_uvs( geometry, ux, uy, ox, oy );
					materials[ cube_count ] = new THREE.MeshLambertMaterial( parameters );
					material = materials[ cube_count ];
					material.hue = i/xgrid;
					material.saturation = 1 - j/ygrid;
					material.color.setHSL( material.hue, material.saturation, 0.5 );
					mesh = new THREE.Mesh( geometry, material );
					mesh.position.x =   ( i - xgrid/2 ) * xsize;
					mesh.position.y =   ( j - ygrid/2 ) * ysize;
					mesh.position.z = 0;
					mesh.scale.x = mesh.scale.y = mesh.scale.z = 1;
					scene.add( mesh );
					mesh.dx = 0.001 * ( 0.5 - Math.random() );
					mesh.dy = 0.001 * ( 0.5 - Math.random() );
					meshes[ cube_count ] = mesh;
					cube_count += 1;
				}
				renderer.autoClear = false;
				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				// postprocessing
				var renderModel = new THREE.RenderPass( scene, camera );
				var effectBloom = new THREE.BloomPass( 1.3 );
				var effectCopy = new THREE.ShaderPass( THREE.CopyShader );
				effectCopy.renderToScreen = true;
				composer = new THREE.EffectComposer( renderer );
				composer.addPass( renderModel );
				composer.addPass( effectBloom );
				composer.addPass( effectCopy );
				//
				window.addEventListener( 'resize', onWindowResize, false );
			}
			function onWindowResize() {
				windowHalfX = window.innerWidth / 2;
				windowHalfY = window.innerHeight / 2;
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				renderer.setSize( window.innerWidth, window.innerHeight );
				composer.reset();
			}
			function change_uvs( geometry, unitx, unity, offsetx, offsety ) {
				var faceVertexUvs = geometry.faceVertexUvs[ 0 ];
				for ( var i = 0; i < faceVertexUvs.length; i ++ ) {
					var uvs = faceVertexUvs[ i ];
					for ( var j = 0; j < uvs.length; j ++ ) {
						var uv = uvs[ j ];
						uv.x = ( uv.x + offsetx ) * unitx;
						uv.y = ( uv.y + offsety ) * unity;
					}
				}
			}
			function onDocumentMouseMove(event) {
				mouseX = ( event.clientX - windowHalfX );
				mouseY = ( event.clientY - windowHalfY ) * 0.3;
			}
			//
			function animate() {
				requestAnimationFrame( animate );
				render();
			}
			var h, counter = 1;
			function render() {
				var time = Date.now() * 0.00005;
				camera.position.x += ( mouseX - camera.position.x ) * 0.05;
				camera.position.y += ( - mouseY - camera.position.y ) * 0.05;
				camera.lookAt( scene.position );
				for ( i = 0; i < cube_count; i ++ ) {
					material = materials[ i ];
					h = ( 360 * ( material.hue + time ) % 360 ) / 360;
					material.color.setHSL( h, material.saturation, 0.5 );
				}
				if ( counter % 1000 > 200 ) {
					for ( i = 0; i < cube_count; i ++ ) {
						mesh = meshes[ i ];
						mesh.rotation.x += 10 * mesh.dx;
						mesh.rotation.y += 10 * mesh.dy;
						mesh.position.x += 200 * mesh.dx;
						mesh.position.y += 200 * mesh.dy;
						mesh.position.z += 400 * mesh.dx;
					}
				}
				if ( counter % 1000 === 0 ) {
					for ( i = 0; i < cube_count; i ++ ) {
						mesh = meshes[ i ];
						mesh.dx *= -1;
						mesh.dy *= -1;
					}
				}
				counter ++;
				renderer.clear();
				composer.render();
			}
		</script>

	</body>
</html>