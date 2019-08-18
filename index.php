<?php
error_reporting(0);
include 'config.php';
include './function/function.php';
include_once './function/DB.php';
if (!file_exists("install.lock")) {
  echo '<a href="./install.html">前往安装</a>';
  die();
}
islogin();

?>
<html lang="ch">
<!-- 术与道
    @SadFile v2.0
    @http://www.shuyudao.top
 -->
 <head> 
  <meta charset="utf-8" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <meta name="renderer" content="webkit" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" /> 
  <meta http-equiv="Cache-Control" content="no-siteapp" /> 
  <meta name="theme-color" content="#3F51B5" /> 
  <title>首页 - <?php echo $data[0]['site_name']; ?></title> 
  <link rel="stylesheet" href="http://cdnjs.loli.net/ajax/libs/mdui/0.4.3/css/mdui.min.css">
  <link rel="stylesheet" type="text/css" href="./static/css/main.css">
  <link href="https://cdn.bootcss.com/viewerjs/1.3.6/viewer.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.css">
  <link rel="stylesheet" type="text/css" href="static/css/prism.css">
  <link rel="stylesheet" type="text/css" href="static/css/wx-audio.css">

  <script type="text/javascript" src="http://at.alicdn.com/t/font_1334636_vc764p8yiqm.js"></script>
 </head> 
 <body class="mdui-drawer-body-left mdui-loaded"> 
  <div class="mdui-appbar-with-toolbar mdui-theme-accent-pink mdui-theme-primary-indigo">
   <div class="mdui-appbar mdui-appbar-fixed" style="box-shadow: none;">
    <div class="mdui-toolbar mdui-color-white mdui-color-theme" style="padding-left: 10px;">
     <button mdui-drawer="{target: '.mc-drawer', swipe: true}" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"><i class="mdui-icon material-icons">menu</i></button>
     <a class="mdui-typo-headline" href="/"><?php echo $data[0]['site_name']; ?></a>
     
     <div class="mdui-toolbar-spacer"></div>
     <button mdui-menu="{target: '#user-attr',covered: false}" class="mdui-btn mdui-btn-icon" style="margin-right: 20px;"><i class="mdui-icon material-icons">account_circle</i></button>

     <!-- 账号管理 -->

      <ul class="mdui-menu" id="user-attr">
        <li class="mdui-menu-item">
          <a href="/function/log/log.php" target="_blank" class="mdui-ripple"><i class="mdui-icon material-icons">playlist_play</i> 登录日志</a>
        </li>
        <li class="mdui-menu-item">
          
          <a href="/function/api.php?logout" class="mdui-ripple"><i class="mdui-icon material-icons">exit_to_app</i> 退出登录</a>
        </li>
      </ul>
    </div>
   </div>
   <div id="side" class="mc-drawer mdui-drawer">
    <div id="file_cate" class="mdui-list">
     <a class="mdui-list-item mdui-ripple mdui-text-color-theme mdui-list-item-active"><i class="mdui-list-item-icon mdui-icon material-icons">business_center</i>
      <div class="mdui-list-item-content">
       全部文件
      </div>
    </a>
     <a type="video" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">video_library</i>
      <div class="mdui-list-item-content">
       视频
      </div></a>
      <a type="music" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">library_music</i>
      <div class="mdui-list-item-content">
       音频
      </div></a>
     <a type="img" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">photo_library</i>
      <div class="mdui-list-item-content">
       图片
      </div></a>
     <a type="doc" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">library_books</i>
      <div class="mdui-list-item-content">
       文档
      </div></a>
    </div>

    <div class="mdui-list">
      <a id="share_list" type="share" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">share</i>
          <div class="mdui-list-item-content">
           我的分享
          </div>
      </a>
      <a type="false" class="mdui-list-item"><i class="mdui-list-item-icon mdui-icon material-icons">cloud_upload</i>
        <select id="upload_list_fun" style="margin-left: 30px" class="mdui-select" mdui-select>
          <option value="3" disabled>上传策略 </option>
          <div class="mdui-divider"></div>
          <?php
            $sql = "select id,name,base.policy_id from policy left join base on policy.id = base.policy_id WHERE status is null OR status = 1";
            $arr = $DB->query($sql);
            for($i = 0 ;$i < count($arr) ; $i++){
              if ($arr[$i]['policy_id']!=null) {
                echo "<option selected value='".$arr[$i]['id']."'>".$arr[$i]['name']."</option>";
              }else{
                echo "<option value='".$arr[$i]['id']."'>".$arr[$i]['name']."</option>";
              }
              
            }
          ?>
        </select>
      </a>
      <a type="setting" id="setting" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
          <div class="mdui-list-item-content">
           程序设置
          </div>
      </a>
      <?php if ($data[0]['type']=="remote") {
        echo '<a href="'.$data[0]['domain']."/Aria".'" target="_blank" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">get_app</i>
          <div class="mdui-list-item-content">
           离线下载
          </div>
        </a>';
      }?>
      
    </div>
    <div style="padding-left: 34px;position: fixed;bottom: 0px;left: 0px;">
      <p><a href="https://github.com/shuyudao/sadfile" target="_blank" style="text-decoration: none;color: #7d7d7d">SadFile 2.0</a> <a href="http://www.shuyudao.top/" target="_blank" style="text-decoration: none;color: #7d7d7d"> 术与道</a></p>
    </div>
   </div>
   <div id="page-index">
      
      <div id="topbar">
        <div style="float: left;">
          <button style="position: relative;overflow: inherit;margin-right: 10px;" class="mdui-btn mdui-btn mdui-ripple mdui-color-theme mdui-btn-dense" mdui-dialog="{target: '#upload_queue'}"><i class="mdui-icon material-icons">file_upload</i>上传<span class="tag_num">1</span></button>
          <button id="create_new_folder" class="mdui-btn mdui-btn mdui-ripple mdui-color-theme mdui-btn-dense"><i class="mdui-icon material-icons">create_new_folder</i> 新建文件夹</button>
          <input id="search_content" type="text" name="search" placeholder="输入搜索内容"><span><i id="search_file" style="color:#8f8f8f;cursor: pointer;" class="mdui-icon material-icons">search</i></span>
        </div>
        <div id="bread" class="line-limit-length">
          <span id="0">全部文件</span>
        </div>
        <div id="select-tool" style="float: left;margin-left: 30px;">
          <button id="cut-file" class="mdui-btn "><i class="mdui-icon material-icons" style="font-size: 24px;">content_cut</i>剪切</button>
          <button id="del-file-arr" class="mdui-btn "><i class="mdui-icon material-icons" style="font-size: 24px;">delete</i>删除</button>
          <button id="share-file-arr" class="mdui-btn "><i class="mdui-icon material-icons" style="font-size: 24px;">share</i>分享</button>
          <button id="paste-file" class="mdui-btn "><i class="mdui-icon material-icons" style="font-size: 24px;">description</i>粘贴</button>
          <button id="select-cancel" class="mdui-btn "><i class="mdui-icon material-icons" style="font-size: 24px;">cancel</i>取消</button>
        </div>

      </div>

      <div id="file-list" partent_dir_key="0">

        <ul class="mdui-list">
          
        </ul>
      </div>

   </div>
   
</div>
   <!-- 扩展 -->


   <ul id="file-item-menu" class="mdui-menu">
    <li id="open_file" class="mdui-menu-item">
     <a href="javascript:;" class="mdui-ripple"><i class="mdui-icon material-icons">open_in_new</i> 打开</a>
    </li>
    <li class="mdui-divider"></li>
    <li id="download_file" class="mdui-menu-item">
      <a href="javascript:;" class="mdui-ripple"><i class="mdui-icon material-icons">file_download</i> 下载</a>
    </li>
    <li id="share_file" class="mdui-menu-item">
      <a href="javascript:;" class="mdui-ripple"><i class="mdui-icon material-icons">share</i> 分享</a>
    </li>
    
    <li id="rename_file" class="mdui-menu-item">
      <a href="javascript:;" class="mdui-ripple"><i class="mdui-icon material-icons">border_color</i> 重命名</a>
    </li>

    <li id="delete_file" class="mdui-menu-item">
      <a href="javascript:;" class="mdui-ripple"><i class="mdui-icon material-icons">delete</i> 删除</a>
    </li>

  </ul>

<!-- 透明遮罩 -->
  <div id="cover_o" style="width: 100%;min-height: 100%;position: absolute;z-index: 9999;top:0px;display: none;left: 0px;"></div>

<!-- 文件上传队列 -->

  <div class="mdui-dialog" id="upload_queue">
    <div style="color: #fff;background-color: #3f51b5;height: 50px;width: 100%;padding-top: 1px;position: relative;">
      <h4 style="padding-left: 20px;line-height: 12px;" >文件上传</h4>
      <button class="mdui-btn mdui-btn-icon" style="position: absolute;float: right;top: 8px;right: 20px;">
        <input id="select_file_btn" style="position: relative;z-index: 999;opacity: 0;width: 100%" type="file" name="file">
        <i class="mdui-icon material-icons">add</i>
      </button>
    </div>

    <ul class="mdui-list" style="height: 250px;overflow-y: overlay;margin-top: -8px;">
      <!--  -->
    </ul>
  </div>

<!-- 图片预览 -->
<ul id="viewer">
    <li><img src="" data-original=""></li>
</ul>
<!-- 文件下载(在当前页面) -->
<iframe id="download-file" src="" style="display: none"></iframe>
<!-- input -->
<input type="text" id="input" name="">

<!-- 视频预览 -->
<div id="video" class="mdui-shadow-5">
  <div id="dplayer"></div>
</div>

<!-- 文档预览 -->
<div id="view_txt">
<button id="close_txt" class="mdui-btn mdui-btn-icon" style="position: fixed;color: #fff;right: 20px;top: 10px;z-index: 99999;"><i class="mdui-icon material-icons">close</i></button>
<pre class="line-numbers"><code class="language-php">加载中...</code></pre>
</div>

<!-- 音频预览 -->
<div id="music" title="点击收缩">
  
</div>
<!-- 策略 -->
<div class="mdui-dialog" id="policy_manger">

  <div class="mdui-dialog-title">添加上传策略</div>
  <div class="mdui-dialog-content">

    <div class="mdui-textfield">
      <input class="mdui-textfield-input" id="policy_name" type="text" placeholder="策略名称"/>
    </div>
    <div class="mdui-textfield">
      <input class="mdui-textfield-input" id="policy_ak" type="text" placeholder="ak"/>
    </div>
    <div class="mdui-textfield">
      <input class="mdui-textfield-input" id="policy_sk" type="text" placeholder="sk"/>
    </div>
    <div class="mdui-textfield">
      <input class="mdui-textfield-input" id="policy_bucket" type="text" placeholder="bucket"/>
    </div>
    <div class="mdui-textfield">
      <input class="mdui-textfield-input" id="policy_url" type="text" placeholder="url地址"/>
    </div>
    <span id="hide_cae">策略类型：
    <select class="mdui-select" id="policy_type" mdui-select>
      <option value="remote" selected>远程/本地</option>
      <option value="qiniu">七牛</option>
    </select></span>
    <div style="margin-top: 30px;">
      <button id="save_policy" class="mdui-btn mdui-color-indigo mdui-ripple" style="float: right;">添加策略</button>
    </div>
  </div>

</div>

</body>
</html>
<script src="http://cdnjs.loli.net/ajax/libs/mdui/0.4.3/js/mdui.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript" src="./static/js/checkfile.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/plupload/2.1.9/moxie.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/plupload/2.1.9/plupload.full.min.js"></script>
<script type="text/javascript" src="./static/js/qiniu.js"></script>

<script type="text/javascript" src="./static/js/common.js"></script>
<script src="https://cdn.bootcss.com/viewerjs/1.3.6/viewer.min.js"></script>
<script src="https://cdn.bootcss.com/flv.js/1.5.0/flv.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.js"></script>
<script type="text/javascript" src="./static/js/wx-audio.js"></script>
<script type="text/javascript" src="./static/js/main.js"></script>