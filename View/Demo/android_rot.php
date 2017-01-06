<!--
  参考URL http://www.terabo.net/blog/3x3x3-solvers/
-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>


<script src="<?= $v->app_pos?>/Plugins/Three/three.min.js"></script>
<script src="<?= $v->app_pos?>/Plugins/Three/OrbitControls.js"></script> 




<script>
$(function(){
  var compassdir = {x:0, y:0, z:0};
  if (window.DeviceOrientationEvent) {



      // Listen for the deviceorientation event and handle the raw data
        window.addEventListener('deviceorientation', function(eventData) {
      

        if(event.webkitCompassHeading) {
          // Apple works only with this, alpha doesn't work
          compassdir = event.webkitCompassHeading;  
        $(".com_value").text("compass heading" + compassdir);
        }
        else
        { 
          k = 3.1415 * 2/360.0;
         // compassdir.y = event.alpha * k;
         // compassdir.z =  event.beta * k;
         // compassdir.x = event.gamma * k;
          compassdir.x = event.alpha * k ;
          compassdir.y =  -event.beta * k ;
          compassdir.z =- event.gamma * k ;

          $(".com_value").text(Math.round(compassdir.x) + "   " + Math.round(compassdir.y) + "   " + Math.round(compassdir.z) + " <br>" + (event.alpha) + "   " + (event.beta) + "   " + (event.gamma) );
        }
      });
    }
  var isControl = 1;

  var mouse = new THREE.Vector2(), INTERSECTED;
  // create scene
  var scene = new THREE.Scene();
  // create camera
  var width  = 1000;
  var height = 700;
  var fov    = 60;
  var aspect = width / height;
  var near   = 10;
  var far    = 1000;
  var camera = new THREE.PerspectiveCamera( fov, aspect, near, far );

  var PI = 3.1415926;
  cam_rot_rad = 20;

  if(isControl){
    var controls = new THREE.OrbitControls(camera);
  }
  // set renderrer
  camera.position.set(-cam_rot_rad, 10, 0);

  //camera.lookAt(new THREE.Vector3(0,10, -50));
  camera.lookAt(new THREE.Vector3(0,0, 0));
  var renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize( width, height );
  $("body").append( renderer.domElement );
    
  // add light
  var directionalLight = new THREE.DirectionalLight( 0x7777777 );
  directionalLight.position.set( 0, 100, 0 );
  scene.add( directionalLight );
  light2 = new THREE.AmbientLight(0xaaaaaa);
  scene.add(light2);  


  //var size = { 'x':11, 'y':1.0, 'z':6 };
  var size = { 'x':1.1, 'y':6.0, 'z':11 };
  var pos = {'x':0, 'y':0.0, 'z':0.0 };

  var android_obj = new THREE.Group();
  var obj1 = createBox(pos, pos, size);
  var size = { 'x':0.3, 'y':6.0, 'z':1 };
  var rot = {'x':0, 'y':0.0, 'z':0.0 };
  var pos = { 'x':0.7, 'y':0, 'z':-5 };
  var obj2 = createBox(pos, rot, size, 0xff0000);
  android_obj.add(obj1);
  android_obj.add(obj2);
   scene.add(android_obj);
    // create box 

  function createBox(pos, rot, size, color = 0x005588){

    var geometry = new THREE.CubeGeometry(size.x, size.y, size.z);
    var material = new THREE.MeshLambertMaterial( { color: color } )
    //  var geometry = new THREE.BoxBufferGeometry( size.x, size.y, size.z);

     // var material = new THREE.MeshNormalMaterial();
      var mesh = new THREE.Mesh( geometry, material );
      mesh.position.set(pos.x, pos.y, pos.z);
      return mesh;
  }

  function getMesh(size, color){
    var geometry = new THREE.PlaneGeometry ( size.x, size.y , 1, 1 );
     var material = new THREE.MeshPhongMaterial( { color : color } );
     var mesh = new THREE.Mesh( geometry, material );
     mesh.position.set(pos.x, pos.y, pos.z);
     return mesh;
  }

  function getRubicBox(){
    var size = {'x': 10, 'y': 10, 'z' : 10};

  }

  function applyPos(obj, pos, rot){
      obj.position.set(pos.x, pos.y, pos.z);
      obj.rotation.set(rot.x, rot.y, rot.z);
  } 
  
     
      //床の描画
  floor = new THREE.Group();
  var n_yuka = 10, yuka_w = 5; 
  var yuka_size = 1;
  for(var i=-n_yuka; i<=n_yuka ; i++){
    for(var j=-n_yuka; j<=n_yuka ; j++){
      if((i+j)%2==0) var plane = new THREE.Mesh(
        new THREE.PlaneGeometry(yuka_w, yuka_w, 1, 1), 
        new THREE.MeshLambertMaterial({color: 0x999999}));
      else var plane = new THREE.Mesh(
        new THREE.PlaneGeometry(yuka_w, yuka_w, 1,1), 
        new THREE.MeshLambertMaterial({color: 0x101010}));
      plane.position.x = j*yuka_w;
      plane.position.z = i*yuka_w + 30;
      plane.position.y = -6.0;
      plane.rotation.x = -Math.PI/2.0;
      //plane.receiveShadow = true;
      floor.add(plane);
    }
  }
  //console.log(floor.rotation.get());
  //floor.rotation.set({'x':0.0, 'y':0.0, 'z':0.0 });
  scene.add(floor);

  raycaster = new THREE.Raycaster();
  document.addEventListener( 'mousemove', onDocumentMouseMove, false );
  document.addEventListener('mousedown', onMouseDown, false);

  // rendering
  if(isControl)controls.update();　
  renderer.render( scene, camera );
    
  var bookPerSec = 0.5;
  var count = 0;
  setInterval(function(){
   
  }, 50);

  ( function renderLoop () {
    requestAnimationFrame( renderLoop );
    //camera.rotation.set(compassdir.x, compassdir.y, compassdir.z);
    //camera.position.set()
    android_obj.rotation.set(compassdir.x, compassdir.y, compassdir.z);
    renderer.render( scene, camera );

  } )();

  function onDocumentMouseMove( event ) {
       
    }

    function onMouseDown(){
     
    }


   
});
</script>

<div style="color:black">
マウスで動かせます
</div>

<div>
compass value = 
  <div class="com_value"></div>
</div>


