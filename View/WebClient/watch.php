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
<title> watch </title>
</head>

<script type="text/javascript">

 $(function(){
    "use strict";

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
                var k = 3.1415 * 2/360.0;
                compassdir.y =- event.alpha * k;
                compassdir.x =  event.beta * k;
                compassdir.z = -event.gamma * k;
                //$(".com_value").text("else \n" + Math.round(compassdir.x) + "   " + Math.round(compassdir.y) + "   " + Math.round(compassdir.z));
            }
        });
    }

    var game_board = {
        HU : 1,
        KYO : 2,
        KEI : 3,
        GIN : 4,
        KIN : 5,
        KAK : 6,
        HIS : 7,
        OHO : 8,

        TO : 9,
        NKYO : 10,
        NKEI : 11,
        NGIN : 12,
        UMA : 13,
        RYU : 14,

        first_player_name : "先手",
        second_player_name : "後手",
        first_player_own : [],
        second_player_own : [],

        epoch : 0,
        teban : 1,
        game_id : <?= $v->game_id?>,

        board : [],
        board_mesh : [],
        _catch_first : [],
        _catch_second : [],
        
        _piece_str : ["歩", "香", "桂", "銀", "金", "角", "飛", "王", "と", "杏", "圭", "全", "馬", "竜"],
        _piece_colors : [0xd0d0d0, 0xd03333, 0xc07040, 0xc0c0c0, 0xd0d044, 0x3322d0, 0x44a0f0, 0xffffff, 0xd03344, 0xd03344, 0xd03344, 0xd03344, 0xd03344, 0xd03344 ],
        init : function(){
            console.log("game_id = " + this.game_id);
            for(var i=0;i<9;i++){
                var row = [];
                var row_mesh = [];
                for(var j=0; j<9; j++){
                    if(i==2){
                        row.push(-this.HU);
                    }else if(i==6){
                        row.push(this.HU);
                    }else{
                        row.push(0);
                    }
                    row_mesh.push([]);
                }
                this.board.push(row);
                this.board_mesh.push(row_mesh);
            }
            this.board[0][0] = -this.KYO;
            this.board[0][1] = -this.KEI;
            this.board[0][2] = -this.GIN;
            this.board[0][3] = -this.KIN;
            this.board[0][4] = -this.OHO;
            this.board[0][5] = -this.KIN;
            this.board[0][6] = -this.GIN;
            this.board[0][7] = -this.KEI;
            this.board[0][8] = -this.KYO;

            this.board[1][1] = -this.HIS;
            this.board[1][7] = -this.KAK;

            this.board[8][0] = this.KYO;
            this.board[8][1] = this.KEI;
            this.board[8][2] = this.GIN;
            this.board[8][3] = this.KIN;
            this.board[8][4] = this.OHO;
            this.board[8][5] = this.KIN;
            this.board[8][6] = this.GIN;
            this.board[8][7] = this.KEI;
            this.board[8][8] = this.KYO;

            this.board[7][1] = this.KAK;
            this.board[7][7] = this.HIS;


        },

        show : function(){
            var wid = 300;
            var height = 350;
            // line
            var start = [];
            var end = []; 
            var color = 0x00ff00;
            var mul = 1.0;
            for(var i=0; i<10; i++){
                start = [wid*mul*(i - 4.5), 0 , height * mul * (- 4.5)];
                end =   [wid*mul*(i - 4.5), 0 , height * mul *(4.5)]; 
                createLinePoints(start, end, color, 300);
                start = [wid * mul * (- 4.5), 0 , height * mul * (i - 4.5)];
                end =   [wid * mul * (4.5), 0 , height * mul * (i - 4.5)]; 
                createLinePoints(start, end, color, 300);
            }

            // koma
            for (var i=0; i<9; i++){
                for(var j=0; j<9; j++){
                    var piece = this.board[i][j];
                    if(piece == 0){
                        continue;
                    }
                    this._create_mesh(piece, [i, j]);
                }
            }
        },



        advance : function(record){
            var pos_num = record['position'];
            var pos_arr = String(pos_num).split('');
            var start = [pos_arr[0], pos_arr[1]];
            var goal = [pos_arr[2], pos_arr[3]];

            var is_catch = false;
            var is_promotion = record['is_promotion'] == 0 ? false : true;
            var revival = record['revival'] ==  0 ? false : true ;
            // 駒取か
            if (this.board[goal[0]][goal[1]] != 0){
                is_catch = true;
            }
            var target = record['target'];



            if(is_catch == 1){
                this._regist_catch(this.board[goal[0]][goal[1]], -self.teban);
                deleteMesh(this.board_mesh[goal[0]][goal[1]]);
            }

            if(revival){
                
                this._regist_revival(target, this.teban);
                this.board[goal[0]][goal[1]] = target;

                var mesh = this._create_mesh(target, goal);
                this.board_mesh[goal[0]][goal[1]] = mesh;
                this._set_mesh(mesh, target, goal);
                
            }else{

                this.board[start[0]][start[1]] = 0;
                if(is_promotion){
                    this.board[goal[0]][goal[1]] = this._get_promoted_target(target, self.teban);
                    deleteMesh(this.board_mesh[start[0]][start[1]]);
                    this._create_mesh( this.board[goal[0]][goal[1]], goal);
                }else{
                    this.board[goal[0]][goal[1]] = target;
                    this._set_mesh(this.board_mesh[start[0]][start[1]], target, goal);
                    this.board_mesh[goal[0]][goal[1]] =  this.board_mesh[start[0]][start[1]];
                    this.board_mesh[start[0]][start[1]] = [];
                }
            }

            this.epoch ++;
            this.teban *= -1;
      
          
        },

        receive_record : function(data){
            console.log("receive" + data.length);
            if(data.length > 0){
                for (var i=0; i<data.length; i++){
                    this.advance(data[i]);
                    //this.show();
                }
            }
        },

        exec : function(){
            var url = '../api/game/' + (game_board.game_id) + '/game_record/show/' + (game_board.epoch + 1) + '/' +  (game_board.epoch + 1);
            console.log(url );
            $.ajax({
                url: url ,
                    type: 'GET',
                    contentType : "application/json",
                    cache: 'false',
                    dataType: 'json',
                })
                .done(function (data, textStatus, jqXHR) {
                    console.log(data);
                    if(data['result']){
                        game_board.receive_record(data['records']);
                    }
                  setTimeout(game_board.exec, 1000);
                })
                .fail(function(jqXHR, testStatus, errorThrown){
                  alert("failed \n" + testStatus + "\n" + errorThrown);
                });
        },

        _create_mesh : function(target, end_point){
          
            var _str = this._get_str_from_piece(target);
           
            var mesh = createFontPoints(_str, this._get_color_from_piece(target));
            this.board_mesh[end_point[0]][end_point[1]] = mesh;
            this._set_mesh(mesh, target , end_point);
            
        },

        _set_mesh : function(mesh, target, end_point){
            var wid = 300;
            var height = 350;
            var pos = [wid * (end_point[1] - 4), 40, height * (end_point[0] - 4)];
            var rot = [Math.PI/2 ,0, Math.PI/20000];
            if(target > 0){
                rot[2] = Math.PI;
                rot[1] = Math.PI;
            }
            setMesh(mesh, pos, rot);

        },

        _regist_catch : function(target, teban){
            var origin_target = target;
            if(this._is_promoted(target)){
                origin_target = this._get_origin_target(target, - teban);
            }
            if(teban == 1){
                this._catch_first.push(-origin_target);
            }else{
                this._catch_second.push(-origin_target);
            }
        },

        _regist_revival : function(target, teban){
            if(teban == 1){
                var index =  this._catch_first.indexOf(target);
                this._catch_first.splice(index, 1);
            }else{
                var index =  this._catch_second.indexOf(target);
                this._catch_second.splice(index, 1);
            }
        },

        _is_promoted :function(target){
            var abs_target = Math.abs(target);
            if(abs_target == this.TO || abs_target == this.NKYO || abs_target == this.NKEI || abs_target == this.NGIN || abs_target == this.UMA || abs_target == this.RYU){
                return true;
            }
            return false;
        },

        _get_promoted_target: function(target, teban){
            var abs_target = target * teban;
            var abs_promoted_target = 0;
            switch (abs_target){
                case this.HU:
                    abs_promoted_target = this.TO;
                    break;
                case this.KYO:
                    abs_promoted_target = this.NKYO;
                    break;
                case this.KEI:
                    abs_promoted_target = this.NKEI;
                    break;
                case this.GIN:
                    abs_promoted_target = this.NGIN;
                    break;
               case this.HIS:
                    abs_promoted_target = this.RYU;
                    break;
                case this.KAK:
                    abs_promoted_target = this.UMA;
                    break;
            }
            return abs_promoted_target * teban;
        },

        _get_origin_target: function(target, teban){
            var abs_target = target * teban;
            var abs_origin_target = 0;
            switch (abs_target){
                case this.TO:
                    abs_origin_target = this.HU;
                    break;
                case this.NKYO:
                    abs_origin_target = this.KYO;
                    break;
                case this.NKEI:
                    abs_origin_target = this.KEI;
                    break;
                case this.NGIN:
                    abs_origin_target = this.GIN;
                    break;
               case this.UMA:
                    abs_origin_target = this.KAK;
                    break;
                case this.RYU:
                    abs_origin_target = this.HIS;
                    break;
            }
            return abs_origin_target * teban;
        },

        _get_str_from_piece : function(piece){
            return this._piece_str[Math.abs(piece) - 1];
        },

        _get_color_from_piece : function(piece){
            return this._piece_colors[Math.abs(piece) - 1];
        }
    }

    var str = "Am I 3D?";
    var fontSize = 18;
    var fontName ="'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'メイリオ', Meiryo, Osaka, 'ＭＳ Ｐゴシック', 'MS PGothic'";

    var width = 1920;
    var height = 1080;

    var table;

    var scene,renderer,camera,controls;

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
    });

   

    function initRender() {
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

        // render
        generateLogo(str,fontSize)
    }

    function generateLogo() {
        //createCubes(scene,cubes);
        //createFontPoints(str);
        game_board.init();
        game_board.show();
        game_board.exec();
        render();
    }

    function createLinePoints(start, end, color, div = 100){
        var vect = [end[0] -start[0], end[1] -start[1], end[2] -start[2]];
        var geometry = new THREE.Geometry();
        for(var i=0; i<div; i++){
            geometry.vertices[ i ] = new THREE.Vector3(start[0] + vect[0]*i/div, start[1] + vect[1]*i/div, start[2] + vect[2]*i/div); 
        }
        var mesh = new THREE.Points( geometry, new THREE.PointsMaterial( { size: 1.0, color: color } ) );
        scene.add(mesh);
    }

    function createFontPoints(_str, color = 0xff33ff){
        var table = getAsciiBlocks(_str,fontSize);
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
        return mesh;
    }

    function setMesh(mesh, pos, rot){
        mesh.position.set(pos[0], pos[1], pos[2]);
        mesh.rotation.set(rot[0], rot[1], rot[2]);
    }

    function deleteMesh(mesh){
        scene.remove( mesh );
        //geometry.dispose();
        //material.dispose();
        //texture.dispose();
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


    function calcPosition(array,index) {
        return index * 15 - (array.length / 2 * 15);
    }

    function render() {
        requestAnimationFrame(render);
        camera.rotation.set(compassdir.x, compassdir.y, compassdir.z)
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
    <div id="stage"></div>
</body>
</html>