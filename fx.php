<?php
include 'config.php';
include "./function/function.php";
include_once './function/DB.php';
$key = $_GET['key'];
if (isset($_POST['pwd'])) {
	$pwd = $_POST['pwd'];
	$sql = "SELECT * FROM share left join files ON share.file_id = files.id WHERE share_key = '$key' AND share_pwd = '$pwd'";
	$arr = $DB->query($sql);
}
?>
<html lang="ch">
 <head> 
  <meta charset="utf-8" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <meta name="renderer" content="webkit" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" /> 
  <meta http-equiv="Cache-Control" content="no-siteapp" /> 
  <meta name="theme-color" content="#3F51B5" /> 
  <title>提取文件 - <?php echo $data[0]['site_name']; ?></title> 
  <link rel="stylesheet" href="http://cdnjs.loli.net/ajax/libs/mdui/0.4.3/css/mdui.min.css">
  <link href="https://cdn.bootcss.com/viewerjs/1.3.6/viewer.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.css">
  <link rel="stylesheet" type="text/css" href="static/css/prism.css">
  <link rel="stylesheet" type="text/css" href="static/css/wx-audio.css">
  <script type="text/javascript" src="http://at.alicdn.com/t/font_1334636_vc764p8yiqm.js"></script>
  <style type="text/css">
  	#main{
  		background-color: #fff;
  		width:500px;
      margin:0 auto;
  		height: auto;
  		box-sizing: border-box;
  		padding: 20px;
      margin-bottom: 20px;
  	}
  	#download{
  		margin-top: 20px;
  	}
  	@media screen and (max-width: 500px){
  		#main{
  			width: 100%;
        padding: 6px;
  		}
      .mdui-container{
        width: 98%;
      }
  	}

    .line-limit-length {

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: 14px;

    }
  </style>
  </head>
  <body>
  	<div class="mdui-container">
      <?php 

        if ($data[0]['isshare']!=0) {
        
      ?>
    	<h2 align="center" style="font-weight: 100;margin-top: 50px;">文件列表</h2>
    	<div id="main" class="mdui-centerb mdui-shadow-1">
    		<?php
    			if (!isset($_POST['pwd'])||count($arr)<1) {
    			
    		?>
	    		<h3 align="center">提取文件</h3>
	    		<form action="" method="post">
		    		<div class="mdui-textfield" style="margin-top: -20px">
					  <input class="mdui-textfield-input" name="pwd" placeholder="提取码" type="password"/>
					</div>
					<br>
					<button id="ti" class="mdui-btn mdui-color-indigo mdui-ripple mdui-center">提取</button>
				</form>
			<?php
				} else {
          for($i = 0 ;$i < count($arr); $i++) {
            $t = $arr[$i];
			?>

				<li class="mdui-list-item mdui-ripple">
				    <i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-blue mdui-text-color-white">assignment</i>
				    <div class="mdui-list-item-content line-limit-length">
				      <div class="mdui-list-item-title line-limit-length"><?php echo $t['file_name']; ?></div>
				      <div class="mdui-list-item-text"><?php echo $t['share_time']; ?>&nbsp;&nbsp;<?php echo $t['file_size']; ?></div>
				    </div>
				    <a target="_blank" href="./function/download.php?key=<?php echo $t['share_key']."&pwd=".$t['share_pwd']."&file_id=".$t['file_id']; ?>"><button class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">cloud_download</i></button></a>
				 </li>
				 
			<?php
        }
       } ?>
    	</div>
      <?php
        } else {
     ?>
        <h2 align="center" style="line-height: 120px;">已取消文件分享</h2>
      <?php }?>
  	</div>
  </body>
</html>
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	$("#ti").click(function(){
		var url = window.location.href;
		$("form").attr("action",url);
		$("form").submit();
	})

</script>