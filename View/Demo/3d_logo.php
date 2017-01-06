<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="<?= $v->app_pos?>/Plugins/Three/three.min.js"></script>
<script src="<?= $v->app_pos?>/Plugins/Three/OrbitControls.js"></script> 

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-58056432-2', 'auto');
  ga('send', 'pageview');
</script>
<title>3D Logo Generator</title>
<link rel="stylesheet" type="text/css" href="3D_Logo.css">
</head>

<script type="text/javascript">

 $(function(){
    "use strict";

    var str = "Am I 3D?";
    var fontSize = 18;
    var fontName ="'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'メイリオ', Meiryo, Osaka, 'ＭＳ Ｐゴシック', 'MS PGothic'";

    var width = 1400;
    var height = 700;

    var cubes = [];
    var table;

    var scene,renderer,camera,controls,wall;

    initRender();

    // regenerate
    $("#generate").click(function(){
        $("canvas").remove();

        var inputStr = $("#str").val();
        if (inputStr !== "") {
            str = inputStr;
        }

//        var inputFontSize = $("#fontSize").val();
//        if (inputFontSize !== "") {
//            fontSize = inputFontSize;
//        }

        initRender();
        showWall();
    });

    // regenerate
    $("#wall").change(function(){
        showWall();
    });

    function showWall() {
        if ($("#wall").prop("checked") === true) {
            scene.add(wall);
        } else {
            scene.remove(wall);
        }
    }

    function initRender() {
        // scene
        scene = new THREE.Scene()

        // wall
        createWall();

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

        // render
        generateLogo(str,fontSize)
    }

    function generateLogo() {
        //createCubes(scene,cubes);
        createFontPoints(scene);
        render();
    }

    function createCubes(scene,cubes){
        var table = getAsciiBlocks(str,fontSize);

        table.reverse();
        table.forEach(function(row,rowIndex) {
            row.forEach(function(cell,colIndex) {
                if (cell === 1) {
                    var cube = createCube(colIndex,rowIndex,table,row)
                    cubes.push(cube);
                    scene.add(cube);
                }
            })
        })
    }

    function createFontPoints(scene, color = 0xff33ff){
        var table = getAsciiBlocks(str,fontSize);
        var geometry = new THREE.Geometry();
        table.reverse();
        var cnt = 0;
        table.forEach(function(row,rowIndex) {
            row.forEach(function(cell,colIndex) {
                if (cell === 1){
                    geometry.vertices[ cnt ] = new THREE.Vector3(calcPosition(row,colIndex), calcPosition(table,rowIndex), 0);
                    cnt ++;
                }
            })
        })
        var mesh = new THREE.Points( geometry, new THREE.PointsMaterial( { size: 3, color: color } ) );
        scene.add(mesh);
    }

    function createWall() {
        // plane
        var geometry = new THREE.PlaneGeometry(3000,800);
        var material = new THREE.MeshLambertMaterial({color: "#bbbbbb", side: THREE.DoubleSide});
        wall = new THREE.Mesh(geometry, material);
        wall.position.set(0,0,-100);
        wall.receiveShadow = true;
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


    function createCube(colIndex,rowIndex,table,row) {
        var cubeSize = 12;
        var geometry = new THREE.BoxGeometry(cubeSize,cubeSize,cubeSize);
        var material = new THREE.MeshLambertMaterial({color: "#ffffff"});
        var cube;
        cube = new THREE.Mesh(geometry, material);
        cube.castShadow = true;
        cube.position.set(calcPosition(row,colIndex),calcPosition(table,rowIndex),0)

        return cube;
    }

    function calcPosition(array,index) {
        return index * 15 - (array.length / 2 * 15);
    }

    function render() {
        requestAnimationFrame(render);

        cubes.forEach(function(c){
            c.rotation.x += 0.01;
            c.rotation.y += 0.01;
            c.rotation.z += 0.01;

        });
        renderer.render(scene, camera);
        controls.update();
    }

    function getAsciiBlocks(str,fontSize,fontName) {
        // init
        var i, j;
        var canvasTmp = $("<canvas>")[0];
        if(!canvasTmp.getContext) return;
        var contextTmp = canvasTmp.getContext('2d');
        var fontStyle = fontSize + "px " + fontName;
        var strWidth, strHeight;
        var table = [];

        // measure text
        contextTmp.font  = fontStyle;
        canvasTmp.width  = strWidth  = Math.ceil(contextTmp.measureText(str).width);
        canvasTmp.height = strHeight = Math.ceil(fontSize * 1.5);

        // render text
        contextTmp.font = fontStyle;
        contextTmp.textBaseline = "top";
        contextTmp.fillText(str, 0, 0);

        // get image data
        var imgdata = contextTmp.getImageData(0, 0, strWidth, strHeight);
        var exist = false;
        var cnt = 0;
        for(i = 0; i < strHeight; i++){
            for(j = 0; j < strWidth; j++){
                var alpha = imgdata.data[(strWidth * i + j) * 4 + 3];
                if(alpha >= 128){
                    if(!exist) exist = true;
                    if(!table[i + cnt]) table[i + cnt] = [];
                    table[i + cnt][j] = 1;
                }
            }
            if(table[i + cnt]){
                for(j = 0; j < strWidth; j++){
                    if(!table[i + cnt][j]) table[i + cnt][j] = 0;
                }
            }
            if(!exist) cnt--;
        }

        return table;
    }
 });

</script>
<body>
    <form>
        <fieldset>
            <div>
                <input type="text" id="str" placeholder="text you wanna 3D-ize!" size="30" maxlength="30"/>
                <!--
                <input type="text" id="fontSize" placeholder="font-size" pattern="^[0-9]+$" />
                -->
                <input type="button" id="generate" value="generate Logo"/>
                <input type="checkbox" id="wall" />
                <label for="wall">show WALL</label>
            </div>
            <div>
            </div>
            <div class="annotaion">
                You can change the viewpoint by mouse dragging and the distance by mouse wheel.
                <div id="copyright">copyright 2015 <a href="https://github.com/ohbarye/3D-logo-generator">@ohbarye</a></div>
            </div>
        </fieldset>
    </form>

    <div id="stage"></div>
</body>
</html>