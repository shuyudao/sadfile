<!-- '*':
  date:2019
  Copyright (c) 2019 by 术与道.
  All Rights Reserved. -->


<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include '../config.php';
include '../function.php';
if ($data[0]['tulang_state']!='0') { #如果图廊没有关闭
  if ($data[0]['tulang_open']=='0') { #是否公开
    islogin(); #判断是否登录
  }
}else{ #图廊关闭
  header('Location: ../');
  exit;
}


 ?>
<!DOCTYPE HTML>
<html lang="ch">
	<head>
		<title>图廊 - <?php echo $data[0]['site_name']?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="css/main.css" />
		  <link href="https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
		<style type="text/css">
			body{
				font-family: "微软雅黑","黑体";
			}
		</style>
	</head>

	<body>

		<!-- Wrapper -->
			<div id="wrapper">
			<!-- Header -->

					<div id="main">

					</div>
					<header id="header">
							<h1>
								<a href="#"><strong>图廊</strong> <?php echo $data[0]['site_name']?></a>
							</h1>
							<nav>
								<ul>
									<li id="pre"><span class="icon fa-arrow-circle-left">上一页</span></li>
									<b><b id="now">1</b>/<b id="all"></b></b>
                  <li id="next"><span class="icon fa-arrow-circle-right">下一页</span></li>
								</ul>
							</nav>
					</header>

				<!-- Main -->
				<!-- Footer -->


			</div>
		<!-- Scripts -->

<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.p.js"></script>
<script src="skel.js"></script>
<script src="util.js"></script>
<script src="main.js"></script>

</body>
</html>
<script type="text/javascript">
window.onload = function () {

  if (getCookie('start')==null) {
    setCookie('start','0');
    setCookie('yema','1');
  }
  if (getCookie('yema')!=null) {
    $('#now').html(parseInt(getCookie('yema')));
  }

  function getPages(){
    $.ajax({
          type: "GET",
          url: "getimgs.php",
          data: "count="+'a',
          success: function(data){
            $('#all').html(Math.ceil(data/12));
          }
    });
  }

  function pre(){
    if (getCookie('start')=='0') {
      return false;
    }
    setCookie('start',parseInt(getCookie('start'))-12);
    ajax_countent();
    $('#now').html(parseInt(getCookie('yema'))-1);
    setCookie('yema',''+(parseInt(getCookie('yema'))-1));
  }
  function next(){
    if ($("#all").html()==$("#now").html()) {
      return false;
    }
    setCookie('start',parseInt(getCookie('start'))+12);
    ajax_countent();
    $('#now').html(parseInt(getCookie('yema'))+1);
    setCookie('yema',''+(parseInt(getCookie('yema'))+1));
  }
  $('#pre').click(function(event) {
    /* Act on the event */
    pre();
  });
  $('#next').click(function(event) {
    /* Act on the event */
    next();
  });
	//cookie方法
	function setCookie(name,value){
		var Days = 30;
		var exp = new Date();
		exp.setTime(exp.getTime() + Days*24*60*60*30);
		document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
	}
	function getCookie(name){
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
		if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
		else
		return null;
	}
  function checkOnload(){
    setTimeout(function(){
      document.getElementsByTagName('body')[0].setAttribute('class','');
    },1500);
  }
  //ajax更新内容
  function ajax_countent(){
  	$.ajax({
  				type: "GET",
  				url: "getimgs.php",
  				data: "start="+getCookie('start'),
  				success: function(data){
  					obj = JSON.parse(data);
  					var arr = obj.result;
  					$("#main").html('');
  					for(var i = 0 ; i < arr.length ; i++){
  						var link = arr[i]['link'];
  						var name = arr[i]['file_name'];
  						document.getElementById('main').innerHTML+=`<article class="thumb">
  						<a href=${link} class="image"><img src=${link} alt="" /></a>
  						<h2>${name}</h2></article>`;
  					}
  					//解决ajax获取新的内容后js失效的bug
  					onload1();
  					onload2();
  					onload3();
  					onload4();
            checkOnload();
  				}
  	});
  }
  ajax_countent();
  getPages();
}


</script>
