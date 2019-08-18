  // $('pre').addClass("line-numbers").css("white-space", "pre-wrap");

  /*加载初始化文件列表*/
  function getFileList(type,t_page){

    var partent_dir_key = $("#file-list").attr("partent_dir_key");

    if (t_page==0||type==null) {
      $("#file-list>.mdui-list").html(`<div id="loading" class="mdui-spinner mdui-valign" style="margin: 100px auto;"><div class="mdui-spinner-layer "><div class="mdui-spinner-circle-clipper mdui-spinner-left"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-gap-patch"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-circle-clipper mdui-spinner-right"><div class="mdui-spinner-circle"></div></div></div></div>`);
    }else{
       $("#file-list>.mdui-list").append(`<div id="loading" class="mdui-spinner mdui-valign" style="margin: 15px auto;"><div class="mdui-spinner-layer "><div class="mdui-spinner-circle-clipper mdui-spinner-left"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-gap-patch"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-circle-clipper mdui-spinner-right"><div class="mdui-spinner-circle"></div></div></div></div>`);
    }

    
    $.ajax({
      url:"./function/api.php",
      data:{'get_list':partent_dir_key,"type":type,"page":t_page},
      success:function(data){
        var data = JSON.parse(data);
        $("#loading").remove();
        for(var i = 0 ; i < data['data'][0].length ; i++){
          // dir_key 在此处作为file_id
          $("#file-list>.mdui-list").append(`<li class="file-item mdui-list-item" file-id-folder="`+data['data'][0][i]['id']+`" file-id="`+data['data'][0][i]['dir_key']+`" folder="folder">
              <i class="mdui-list-item-avatar mdui-icon mdui-color-yellow-600 mdui-text-color-white material-icons">folder</i>
              <div class="mdui-list-item-content line-limit-length">
                <div class="mdui-list-item-title line-limit-length">`+data['data'][0][i]['dir_name']+`</div>
                <div class="mdui-list-item-text"><span class="date">`+data['data'][0][i]['dir_time']+`</span></div>
              </div>
              
            </li>`);

        }
        for(var i = 0 ; i < data['data'][1].length ; i++){

          var icon = checkFile(data['data'][1][i]['file_name']);

          $("#file-list>.mdui-list").append(`<li class="file-item mdui-list-item" qiniu_name="`+data['data'][1][i]['qiniu_name']+`" file-id="`+data['data'][1][i]['id']+`">
              `+icon+`
              <div class="mdui-list-item-content line-limit-length">
                <div class="mdui-list-item-title line-limit-length">`+data['data'][1][i]['file_name']+`</div>
                <div class="mdui-list-item-text"><span class="date">`+data['data'][1][i]['file_time']+`</span><span class="size">`+data['data'][1][i]['file_size']+`</span></div>
              </div>
              
            </li>`);

        }

        if ((data['data'][1].length<1&&data['data'][0].length<1)||(type!=null&&data['data'][1].length<1)) {
          if (t_page==0||type==null) {
            $("#file-list>.mdui-list").html(`<h1 style="font-size: 21px;line-height:60px;text-align: center;color: #acacac;font-weight: 300;margin: 0.67em 0px;">＞﹏＜ 没有文件</h1>`);
          }
        }

        if (type!=null) {
          if (data['data'][1].length>=12) {
            $("#file-list>.mdui-list").append(`<button id="page_share" class="mdui-btn mdui-btn-dense mdui-text-color-grey-400 mdui-center mdui-text-color-grey mdui-ripple" style="margin-top:15px;margin-bottom:15px">加载更多</button>`);
            $("#page_share").click(function(){
              t_page++;
              $(this).text("loading...");
              getFileList(type,t_page);
              $(this).remove();
            })
          }
        }

      }
    })
  }
  getFileList();

  /*变量----------------------------------*/

  var file_id = null;
  var bool_viewer = false;
  var bread_name = "";
  var dp;
  var wxAudio;
  /*禁用右键*/
  $(document).ready(function(){
   $(document).bind("contextmenu",function(e){
     return false;
   });
  });

  /*文件右键打开自定义目录列表*/
  $(document).off("mousedown",".file-item").on("mousedown",".file-item", function(e){  

        var e = e || window.event;
        var x,y;
        $("#cover_o").css('height',$("#page-index").height()+84+"px");
        x=e.clientX;
        y=e.clientY;
        if (y>(getClientHeight()-280)) {
          y = getClientHeight()-280;
        }

        if(e.button == "2"){
          controllerMenu(x,y,$(this));
        }
        return false;
  })


      /*点击空白处隐藏列表*/
      $("#cover_o").click(function(){
        hideMenu();
        $("#video").hide();
        dp.destroy();
      })
      /*隐藏列表*/
      function hideMenu(){
        $("#file-item-menu").css({'visibility':'hidden','opacity':"0",'transform':'scale(0)'});
        $("#cover_o").css("display","none");
        $(".file-item").css("background-color","");
        $("#download_file").show();
      }

      //长按文件打开操作目录列表(移动端)
        var timeOutEvent;
        $(document).on("touchstart",".file-item", function(e) { 
            var touch = e.originalEvent.targetTouches[0];
            var x,y;
            x = touch.pageX;
            y = touch.pageY - $(document).scrollTop();;
            var that = $(this);
            // 长按事件触发  
            timeOutEvent = setTimeout(function() { 
                timeOutEvent = 0;
                /*文件长按后回调*/
                $("#cover_o").css('height',$("#page-index").height()+84+"px");

                if (y>(getClientHeight()-280)) {
                  y = getClientHeight()-280;
                }
                controllerMenu(x,y,that);

            }, 700);  
            //长按500毫秒   
            // e.preventDefault();    
        });
        $(document).on("touchend",".file-item", function() {  
            clearTimeout(timeOutEvent);  
            timeOutEvent = 0;
        });

  /*文件多选*/
  var key = 0;//按键状态

  var file_id_arr = [];//选择的文件id数组
  var dir_id_arr = [];//选择的文件夹id数组

  $(window).keydown(function(e){
      if(e.ctrlKey){
          key=1;
      }
  }).keyup(function(){
          key=0;
  });
      //按住ctrl点击
      $(document).on("click",".file-item",function(){
        if(key==1){
          if ($(this).attr("checked")!="checked") {
            var file_id_temp = $(this).attr("file-id"); //获取选择的文件id
            if($(this).attr("folder")=="folder"){
              dir_id_arr[dir_id_arr.length] = file_id_temp;
            }else{
              file_id_arr[file_id_arr.length] = file_id_temp;
            }

            $("#select-tool").css("display","block");
            
            $(this).css("background-color","#dfdfdf");
            $(this).attr("checked","checked");
          }else{
            //再次点击取消选中  
            if($(this).attr("folder")=="folder"){
              dir_id_arr = delArrItem(dir_id_arr,$(this).attr("file-id"));
            }else{
              file_id_arr = delArrItem(file_id_arr,$(this).attr("file-id")); //删除数组中的文件id
            }
            

            $(this).css("background-color","");
            $(this).removeAttr("checked");

            if($(".file-item[checked]").length<1) {
              reSelectFileBtn();
              file_id_arr=[];
              dir_id_arr=[];
              $("#select-tool").css("display","none");
            }
          }
        }
      });

      //取消多选
      $("#select-cancel").click(function(){
        file_id_arr = []; //清空文件id数组
        dir_id_arr=[];
        clearElementAttr();
        $("#select-tool").css("display","none");

        reSelectFileBtn();

      })

  /*剪切文件*/
  $("#cut-file").click(function(){
      clearElementAttr();
      alert("已剪切");
      $("#paste-file").show();
      $("#del-file-arr").hide();
      $("#share-file-arr").hide();
      $(this).hide();
  })

  /*删除多文件*/
  $("#del-file-arr").click(function(){
    alert("删除成功");

    $(".file-item[checked]").animate({
      left:"500px",
      opacity:0
    },300,function(){
      $(this).remove();
    });

    clearElementAttr();
    reSelectFileBtn();
    $("#select-tool").css("display","none");

    $.ajax({
      url:"./function/delete.php",
      data:{'file':file_id_arr,"dir":dir_id_arr},
      success:function(data){
        if (JSON.parse(data)['data']!=1) {
          alert("删除失败异常","error")
        }
      }
    })

    file_id_arr = [];
    dir_id_arr=[];

  })

  /*粘贴文件*/
  $("#paste-file").click(function(){
    var partent_dir_key = $("#file-list").attr("partent_dir_key");
    $.ajax({
      url:"./function/api.php",
      data:{'file_id_arr':file_id_arr,'dir_id_arr':dir_id_arr,'partent_dir_key':partent_dir_key},
      success:function(data){
        getFileList();
      }
    })
    
    //结束
    file_id_arr = [];
    dir_id_arr=[];
    reSelectFileBtn();
    $("#select-tool").css("display","none");
  })


  /*分享多文件*/
  $("#share-file-arr").click(function(){
    $.ajax({
      url:"./function/postshare.php",
      method:"post",
      data:{'file_id':file_id_arr},
      success:function(data){
        var data = JSON.parse(data);
        var contents = data['data'].split("^^");
        mdui.dialog({
          title: '分享成功',
          content: '链接：'+contents[0]+" 提取码："+contents[1],
          buttons: [
            {
              text: '复制链接',
              onClick:function(){
                copyText();
              }
            }
          ]
        });
        alert("分享成功");
      }
    })

    //结束
    file_id_arr = [];
    dir_id_arr=[];
    reSelectFileBtn();
    clearElementAttr();
    $("#select-tool").css("display","none");
  })


  /*新建文件夹*/
  $("#create_new_folder").click(function(){

    mdui.prompt('新建文件夹',
      function (value) {
        var partent_dir_key = $("#file-list").attr("partent_dir_key");
        $.ajax({
          url:"./function/api.php?mkdir_name="+value+"&dir_key="+partent_dir_key,
          success:function(data){
            var data = JSON.parse(data);
            if (data['data']) {
              alert("创建成功");
              var html = `<li class="file-item mdui-list-item" file-id="`+data['data']+`" folder="folder">
                <i class="mdui-list-item-avatar mdui-icon mdui-color-yellow-600 mdui-text-color-white material-icons">folder</i>
                <div class="mdui-list-item-content">
                  <div class="mdui-list-item-title">`+value+`</div>
                  <div class="mdui-list-item-text"><span class="date">2017-12-15 20:22</span></div>
                </div>
              </li>`;
              
              if ($("#file-list li").length<1) {
                $("#file-list>.mdui-list").html(html);
              }else if($("#file-list li[folder]:last").length<1){
                $("#file-list li:first").before(html);
              }else{
                $("#file-list li[folder]:last").after(html);
              }
            }
          }
        })
    
      },
      function(){},
      {
        confirmText:"创建",
        cancelText:"取消"
      }
    );
    
  })



/*文件上传队列操作*/
  
  //显示上传队列中的数量
  check_upload_queue_num();
  var b = true;
  $(window).keydown(function(e){
      b = false;
  })
  $(window).keyup(function(e){
      b = true;
  })
//进入目录
$(document).off("click",".file-item[folder]").on("click",".file-item[folder]",function(){
  if (b) {
    $("#file-list").attr("partent_dir_key",$(this).attr("file-id"));
    $("#bread").append(`<span>/</span><span id=`+$(this).attr("file-id")+`>`+$(this).find(".mdui-list-item-title").text()+`</span>`)
    getFileList();
  }else{
    console.log("keydown:"+b);
  }
})

//面包屑导航的文件加载
$(document).off("click","#bread span").on("click","#bread span",function(){
  if ($(this).attr("id")==undefined) {
    return false;
  }
  $("#file-list").attr("partent_dir_key",$(this).attr("id"));

  $(this).nextAll().remove();
  getFileList();
  
})

//关闭文档预览
$("#close_txt").click(function(){
  $("#view_txt").css("display","none");
  $("#view_txt code").html("加载中...");
})

//关闭音频预览
$(document).off("click","#close_music").on("click","#close_music",function(){
  $("#music").css("display","none");
  $("#d-audio-content").remove();
})


//动态引入上传js
function refUploadJs(){
  $.ajax({
    url:"./function/api.php?info=1",
    success:function(data){
      data = JSON.parse(data);
      if (data['data'][0]['type']=='remote') {
        var oHead = document.getElementsByTagName('HEAD').item(0); 
        var oScript= document.createElement("script"); 
        oScript.type = "text/javascript"; 
        oScript.src="./static/js/remote.js"; 
        oHead.appendChild(oScript); 
      }else{
        var oHead = document.getElementsByTagName('HEAD').item(0); 
        var oScript= document.createElement("script"); 
        oScript.type = "text/javascript"; 
        oScript.src="./static/js/qiniuUp.js"; 
        oHead.appendChild(oScript); 
      }
      
    }
  })
}
refUploadJs();
//搜索
$("#search_file").click(function(){
  var keyword = $("#search_content").val();
  $.ajax({
    url:"./function/api.php",
    data:{"search_word":keyword},
    success:function(data){
      var data = JSON.parse(data);
      $("#file-list>.mdui-list").html("");
      for(var i = 0 ; i < data['data'].length ; i++){

          var icon = checkFile(data['data'][i]['file_name']);

          $("#file-list>.mdui-list").append(`<li class="file-item mdui-list-item" qiniu_name="`+data['data'][i]['qiniu_name']+`" file-id="`+data['data'][i]['id']+`">
              `+icon+`
              <div class="mdui-list-item-content line-limit-length">
                <div class="mdui-list-item-title line-limit-length">`+data['data'][i]['file_name']+`</div>
                <div class="mdui-list-item-text"><span class="date">`+data['data'][i]['file_time']+`</span><span class="size">`+data['data'][i]['file_size']+`</span></div>
              </div>
              
            </li>`);

        }
    }
  })
})
  //切换文件类型列表
  $("#side .mdui-list-item").click(function(){

    var type = $(this).attr('type');
    if (type=="false") {
      return false;
    }


    $("#side .mdui-list-item").removeClass("mdui-list-item-active");
    $("#side .mdui-list-item").removeClass("mdui-text-color-theme");
    $(this).addClass("mdui-list-item-active");
    $(this).addClass("mdui-text-color-theme");

    if (type=="share"||type=="setting") {
      return false;
    }

    if (type!=null) {
      $("#topbar").hide();
      $("#file-list").css("padding-top","0px")
      $("#page-index").css("padding-top","0px")
    }else{
      $("#topbar").show();
      $("#file-list").css("padding-top","50px")
      $("#page-index").css("padding-top","20px")
    }
    var t_page = 0;
    getFileList(type,t_page);

    reSelectFileBtn();
    file_id_arr=[];
    dir_id_arr=[];
    $("#select-tool").css("display","none");
    
  })

  //上传策略存储位置选择
  $("#upload_list_fun").change(function(){
    var id = $("#upload_list_fun").val();
    $.ajax({
      url:"./function/api.php?policy_id="+id,
      success:function(data){
        location.reload();
      }
    });
  });

  function getShareList(page){

    $("#file-list>.mdui-list").append(`<div id="loading" class="mdui-spinner mdui-valign" style="margin: 15px auto;"><div class="mdui-spinner-layer "><div class="mdui-spinner-circle-clipper mdui-spinner-left"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-gap-patch"><div class="mdui-spinner-circle"></div></div><div class="mdui-spinner-circle-clipper mdui-spinner-right"><div class="mdui-spinner-circle"></div></div></div></div>`);

    $.ajax({
      url:"./function/api.php?share_list=1&page="+page,
      success:function(data){
        $("#loading").remove();
        var data = JSON.parse(data);

        for(var i = 0 ; i < data['data'].length ; i++){
          var t = data['data'][i];
          var icon = checkFile(t['file_name']);
          $("#file-list>.mdui-list").append(`<li class="file-item mdui-list-item" qiniu_name="`+t['qiniu_name']+`" file-id="`+t['id']+`">
              `+icon+`
              <div class="mdui-list-item-content line-limit-length">
                <div class="mdui-list-item-title line-limit-length">`+t['file_name']+`</div>
                <div class="mdui-list-item-text">分享时间:<span class="date">`+t['share_time']+`</span> 下载次数:<span class="date">`+t['download_cont']+`</span> 文件大小: <span class="size">`+t['file_size']+`</span></div>
              </div>
              <div class="mdui-btn-group" share_key="`+t['share_key']+`" share_pwd="`+t['share_pwd']+`">
                <button type="button" class="mdui-btn view_share" mdui-tooltip="{content: '分享预览'}"><i class="mdui-icon material-icons">remove_red_eye</i></button>
                <button type="button" class="mdui-btn share_pwd" mdui-tooltip="{content: '提取密码'}"><i class="mdui-icon material-icons">fingerprint</i></button>
                <button type="button" class="mdui-btn share_link" mdui-tooltip="{content: '复制链接'}"><i class="mdui-icon material-icons">content_copy</i></button>
                <button type="button" class="mdui-btn del_share"><i class="mdui-icon material-icons">delete</i></button>
              </div>
            </li>`);

        }

        if (data['data'].length>=12) {
          $("#file-list>.mdui-list").append(`<button id="page_share" class="mdui-btn mdui-btn-dense mdui-text-color-grey-400 mdui-center mdui-text-color-grey mdui-ripple" style="margin-top:15px;margin-bottom:15px">加载更多</button>`);
          $("#page_share").click(function(){
            page++;
            $(this).text("loading...");
            getShareList(page);
            $(this).remove();
          })
        }

        if (data['data'].length<1&&page<1) {
          $("#file-list>.mdui-list").html(`<h1 style="font-size: 21px;line-height:60px;text-align: center;color: #acacac;font-weight: 300;margin: 0.67em 0px;">＞﹏＜ 当前没有分享</h1>`);
        }

      }
    })
  }


  //加载分享页面
  var page = 0;
  $("#share_list").click(function(){
    $("#topbar").hide();
    $("#file-list").css("padding-top","0px");
    $("#page-index").css("padding-top","0px");
    $("#file-list>.mdui-list").html("");
    
    getShareList(page);

  })

  

  //打开分享界面
  $(document).off("click",".view_share").on("click",".view_share", function(){
    var sk = $(this).parent().attr("share_key");
    window.open("./fx.php?key="+sk,"_blank");
  })
  //查看密码
  $(document).off("click",".share_pwd").on("click",".share_pwd", function(){
    var pwd = $(this).parent().attr("share_pwd");
    var sk = $(this).parent().attr("share_key");
    var that = $(this);

    mdui.prompt('文件提取密码',
      function(value){
        $.get("./function/api.php?change_share_pwd=1&newpwd="+value+"&share_key="+sk);
        that.parent().attr("share_pwd",value);
      },
      function(){},
      {
        confirmText:"修改密码",
        cancelText:"关闭",
        defaultValue:pwd,
        maxlength:4
      }
    )
  })
  //复制分享链接
  $(document).off("click",".share_link").on("click",".share_link", function(){
    var pwd = $(this).parent().attr("share_pwd");
    var sk = $(this).parent().attr("share_key");
    var input = document.getElementById("input");
    text = "链接：http://"+window.location.host+"/fx.php?key="+sk+" 密码："+pwd;

    input.value = text; // 修改文本框的内容
    input.select(); // 选中文本
    document.execCommand("copy"); // 执行浏览器复制命令
    alert("复制成功");
  })
  
  //删除分享
  $(document).off("click",".del_share").on("click",".del_share", function(){
    var sk = $(this).parent().attr("share_key");
    $.get("./function/api.php?del_share="+sk);
    $("div[share_key="+sk+"]").parent().animate({
      left:"500px",
      opacity:0
    },300,function(){
      $("div[share_key="+sk+"]").parent().remove();
    });
  })


  function settingHtml(){

    html=`
      <div id="setting_page" style="width:600px;margin:0 auto">
        <p><b>账户资料</b></p>
        <div class="mdui-shadow-2 setting-item">
          <div class="mdui-textfield">
            <i class="mdui-icon material-icons">account_circle</i>
            <label class="mdui-textfield-label">用户名</label>
            <input class="mdui-textfield-input setting_username base_set" name="user_name" placeholder="加载数据中..." type="text"/>
          </div>
          <div class="mdui-textfield">
            <i class="mdui-icon material-icons">lock</i>
            <label class="mdui-textfield-label">密码</label>
            <input class="mdui-textfield-input setting_password base_set" name="user_pwd" value="md5md5md5" type="password"/>
          </div>
        </div>
        <p><b>站点配置</b></p>
        <div class="mdui-shadow-2 setting-item">
          <div class="mdui-textfield">
            <i class="mdui-icon material-icons">link</i>
            <label class="mdui-textfield-label">站点url</label>
            <input class="mdui-textfield-input setting_siteurl base_set" name="site_url" placeholder="加载数据中..." type="text"/>
          </div>
          <div class="mdui-textfield">
            <i class="mdui-icon material-icons">dns</i>
            <label class="mdui-textfield-label">站点名称</label>
            <input class="mdui-textfield-input setting_sitename base_set" name="site_name" placeholder="加载数据中..." type="text"/>
          </div>
          <div style="margin-top:30px;width:100%;">
            <span style="padding-left:10px">关闭分享</span>
            <span style="float:right">
              <label class="mdui-switch">
              <input type="checkbox" name="isshare" value="false" class="search_share base_set"/>
              <i class="mdui-switch-icon"></i>
            </label>
            </span>
          </div>
        </div>

        <p><b>上传策略</b></p>
        <div class="mdui-shadow-2 setting-item">
          <button id="add_policy" class="mdui-btn mdui-btn-dense mdui-color-indigo mdui-ripple" mdui-dialog="{target: '#policy_manger'}"><i class="mdui-icon material-icons">add</i> 添加策略</button>

          <ul class="mdui-list" id="policy_list">
          </ul>  
        </div>

      </div>
    `

    $("#topbar").hide();
    $("#file-list").css("padding-top","0px")
    $("#page-index").css("padding-top","0px")
    $("#file-list>.mdui-list").html(html);

    $.ajax({
      url:"./function/api.php?setting",
      success:function(data){
        var data = JSON.parse(data);
        data = data['data'][0];
        $(".setting_username").val(data['user_name']);
        $(".setting_siteurl").val(data['site_url']);
        $(".setting_sitename").val(data['site_name']);
        if (data['isshare']==1) {
          $(".search_share").attr("checked","checked");
          $(".search_share").val("on");
        }
      }
    })

    $.ajax({
      url:"./function/api.php?getPolicy",
      success:function(data){
          var data = JSON.parse(data);
          data = data['data']
          for(var i = 0 ; i < data.length ; i++){
            $("#policy_list").append(`
              <li class="mdui-list-item mdui-ripple" p_id="`+data[i]['id']+`">
                <div class="mdui-list-item-content">`+data[i]['name']+`</div>
                <button class="mdui-btn mdui-btn-icon edit_policy" mdui-dialog="{target: '#policy_manger'}"><i class="mdui-icon material-icons">edit</i></button>
                <button class="mdui-btn mdui-btn-icon del_policy"><i class="mdui-icon material-icons">delete</i></button>
              </li>
            `)  
          }
      }
    })
    //添加策略
    $("#add_policy").click(function(){
      $("#policy_manger .mdui-dialog-title").text("添加上传策略");
      $("#policy_manger button").text("添加策略");
      $("#hide_cae").show();
      $("#policy_name").val("");
      $("#policy_ak").val("");
      $("#policy_sk").val("");
      $("#policy_url").val("");
      $("#policy_bucket").val("");
      $("#policy_type").val("remote");

      $(document).off("click","#save_policy").on("click","#save_policy",function(){
        var name = $("#policy_name").val();
        var ak = $("#policy_ak").val();
        var sk = $("#policy_sk").val();
        var domain = $("#policy_url").val();
        var t_last_char = domain.replace(/^(.*[n])*.*(.|n)$/g, "$2")
        if (t_last_char == "/") {
          domain = domain.slice(0,domain.length - 1);
        }
        var bucket = $("#policy_bucket").val();
        var type = $("#policy_type").val();
        $.ajax({
          url:"./function/api.php",
          method:"post",
          data:{'name':name,'ak':ak,'sk':sk,'domain':domain,'bucket':bucket,'type':type,"add_policy":1},
          success:function(data){
            var data = JSON.parse(data);
            if (data['status']==200) {
              alert("修改成功");
              settingHtml();
            }
          }
        })
      })


    })
    //编辑策略
    $(document).off("click",".edit_policy").on("click",".edit_policy",function(){
      $("#policy_manger .mdui-dialog-title").text("编辑上传策略");
      $("#policy_manger button").text("保存修改");
      $("#hide_cae").hide();
      $.ajax({
        url:"/function/api.php?getPolicyInfo="+$(this).parent().attr("p_id"),
        success:function(data){
          var data = JSON.parse(data);
          data = data['data'][0];
          $("#policy_name").val(data['name']);
          $("#policy_ak").val(data['ak']);
          $("#policy_sk").val(data['sk']);
          $("#policy_url").val(data['domain']);
          $("#policy_bucket").val(data['bucket']);
          $("#policy_type").val(data['type']);
          var id = data['id'];
          $(document).off("click","#save_policy").on("click","#save_policy",function(){
            var name = $("#policy_name").val();
            var ak = $("#policy_ak").val();
            var sk = $("#policy_sk").val();
            var domain = $("#policy_url").val();
            var t_last_char = domain.replace(/^(.*[n])*.*(.|n)$/g, "$2")
            if (t_last_char == "/") {
              domain = domain.slice(0,domain.length - 1);
            }
            var bucket = $("#policy_bucket").val();
            var type = $("#policy_type").val();
            $.ajax({
              url:"./function/api.php",
              method:"post",
              data:{'name':name,'ak':ak,'sk':sk,'domain':domain,'bucket':bucket,'id':id},
              success:function(data){
                var data = JSON.parse(data);
                if (data['status']==200) {
                  alert("修改成功")
                }
              }
            })
          })

        }
      })

    });

    //删除策略
    $(document).off("click",".del_policy").on("click",".del_policy",function(){
      var id = $(this).parent().attr("p_id");
      var that = $(this);
      $.ajax({
        url:"./function/api.php?del_id="+id,
        success:function(data){
          that.parent().remove();
        }
      })
    })
    
    //
    
    $(document).off("change",".base_set").on("change",".base_set",function(){
      $(document).off("blur",".base_set").on("blur",".base_set",function(){

        var field_name = $(this).attr("name");
        var field_value = $(this).val();

        if (field_name=="site_url") {
          var t_last_char = field_value.replace(/^(.*[n])*.*(.|n)$/g, "$2")
          if (t_last_char == "/") {
            field_value = field_value.slice(0,field_value.length - 1);
          }
        }

        $.ajax({
          url:"./function/api.php",
          method:"post",
          data:{'field_name':field_name,"field_value":field_value},
          success:function(data){
          }
        })
      })
    })

    $(".search_share").click(function(){
      if ($(this).val()=="on") {
        $(this).val("false")
      }else{
        $(this).val("on")
      }
    })
    

  }

  $("#setting").click(function(){
    settingHtml();
  });

