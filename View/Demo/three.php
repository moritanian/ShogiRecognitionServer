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
          compassdir.y =- event.alpha * k;
          compassdir.x =  event.beta * k;
          compassdir.z = -event.gamma * k;
          $(".com_value").text("else \n" + Math.round(compassdir.x) + "   " + Math.round(compassdir.y) + "   " + Math.round(compassdir.z));
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

  if(isControl){
    var controls = new THREE.OrbitControls(camera);
  }
  // set renderrer
  camera.position.set(0, 10, 100);

  camera.lookAt(new THREE.Vector3(0,10, -50));
  var renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize( width, height );
  $("body").append( renderer.domElement );
    
  // add light
  var directionalLight = new THREE.DirectionalLight( 0x7777777 );
  directionalLight.position.set( 0, 100, 0 );
  scene.add( directionalLight );
  light2 = new THREE.AmbientLight(0xaaaaaa);
  scene.add(light2);  

  var RubicCube = new THREE.Group();

  var size = { 'x':11, 'y':16.0, 'z':1.6 };
    // create box 
  function createBox(pos, rot, size, pos_id = 0, book_data = {}){
      
      var book = new THREE.Group();

      var geometry = new THREE.BoxBufferGeometry( size.x, size.y, size.z);

      var material = new THREE.MeshPhongMaterial( { color : Math.random() * 0xffffff } );
    
      var material = new THREE.MeshNormalMaterial();
      var mesh = new THREE.Mesh( geometry, material );
    
      book.add(mesh);

      var mesh = getImageMesh(book_data.img_url, size);
     

      book.add(mesh);
      applyPos(book, pos, rot);

      book.book_data = book_data;
      book.pos_id = pos_id;

      return book;
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
      plane.position.y = 0;
      plane.rotation.x = -Math.PI/2.0;
      plane.receiveShadow = true;
      scene.add(plane);
    }
  }

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
    camera.rotation.set(compassdir.x, compassdir.y, compassdir.z);
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


