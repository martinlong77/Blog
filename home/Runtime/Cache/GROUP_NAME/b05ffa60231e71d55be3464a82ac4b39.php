<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="__PUBLIC__/images/common/main/favicon.ico" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/css/style.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/btn.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/backtotop.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/jquery-ui/swanky-purse/jquery-ui-1.10.3.custom.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery-ui/jquery-ui-1.10.3.custom.min.js"></script>

<title>专辑列表_<?php echo ($info["nickname"]); ?>的照片_JellyNotes</title>
<?php if(!empty($_GET['aid'])): ?><link type="text/css" href="__PUBLIC__/js/wookmark/css/style.css" rel="stylesheet" />
<script type="text/javascript" src="__PUBLIC__/js/wookmark/jquery.imagesloaded.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/wookmark/jquery.wookmark.min.js"></script><?php endif; ?>

</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_resize">
      <div class="logo">
        <h1><a href="<?php echo U('index/index?hot=1');?>">果冻<span>写</span></a> <small><span>写生活</span>享生活</small></h1>
      </div>
      <div class="clr"></div>
      <div class="menu_nav">
        <ul>
          <?php if(($_GET['hot']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/index?hot=1');?>">热门博客</a></li>		  
		 <?php if(($navi["index"]) == "1"): if(($navi["user"]) == $_SESSION['id']): if(($_GET["follow"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/followBlog?follow=1');?>">关注</a></li><?php endif; ?>
		  <?php if(($_GET["blog"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/proBlog?blog=1&id='.$navi['user']);?>">博客</a></li>
		  
          <?php if(($_GET["pic"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Pic/proPic?pic=1&id='.$navi['user']);?>">照片</a></li>
          <?php if(($_GET["about"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Profile/index?about=1&id='.$navi['user']);?>">关于我</a></li>
		  
		<?php if(($navi["user"]) == $_SESSION['id']): if(($_GET["new"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo ($navi["new"]); ?>"><?php echo ($navi["name"]); ?></a></li><?php endif; ?>
		<?php else: ?>		
		<li><a href="<?php echo U('Index/login?login=1');?>">登陆</a></li>
		  <li><a href="<?php echo U('Index/register?join=1');?>">加入果冻</a></li><?php endif; ?> 
        </ul>
      </div>
      <div class="searchform">
        <form id="formsearch" method="get" action="<?php echo U('Pub/search');?>">
          <span><input name="search" class="editbox_search" type="text" value="搜博客" onFocus="clearText(this)" onBlur="clearText(this)" /></span>
          <input type="submit" class="button orange bigrounded" value="搜一下" />
        </form>
      </div>
      <div class="clr"></div>
    </div>
			<script type="text/javascript">
		//点击输入框提示消失
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
		</script>
  </div>

  <div class="content">
    <div class="content_resize">
	 <div class="content_resize2">
      <div class="mainbar">
        <div class="article">
          <div class="clr"></div>		  
		  <!-- 图片列表 -->		  
		  <?php if(empty($_GET['aid'])): ?><h2>专辑列表</h2>
		  		<ul class="pt_list_nb">
		  <?php if(is_array($albumList)): $i = 0; $__LIST__ = $albumList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><li><div class="pt_border"><p><a href="<?php echo U('Pic/proPic?pic=1&id='.$_GET['id'].'&aid='.$a['album_id']);?>" target="_blank"><?php if(empty($a["album_cover"])): ?><img src="__PUBLIC__/images/common/main/no_cover.gif"/><?php else: ?><img src="<?php echo ($a["album_cover"]); ?>" title="<?php echo ($a["album_name"]); ?>" alt="<?php echo ($a["album_name"]); ?>" /><?php endif; ?></a></p></div>
			<span class="pic_con"><p><a href="<?php echo U('Pic/proPic?pic=1&id='.$_GET['id'].'&aid='.$a['album_id']);?>" target="_blank" style="font-size:16px;color:#3366CC"><?php echo ($a["album_name"]); ?></a>&nbsp; <?php if(($_GET['id']) == $_SESSION['id']): if(!in_array(($a["album_id"]), explode(',',"1,2"))): ?><a href="javascript:;" name="<?php echo ($a["album_name"]); ?>" aid="<?php echo ($a["album_id"]); ?>" url="<?php echo U('Pic/proPic?id='.$_GET['id'].'&aid='.$a['album_id']);?>" onclick="edit_album(this)">[编辑]</a><?php endif; endif; ?></p></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
		  </ul>
		  <div class="clr"></div>
		  <p class="pages"><small>&nbsp;&nbsp;&nbsp;</small><?php echo ($page); ?></p>
		  <script type="text/javascript">
//显示修改框
function edit_album(info){
 var name = $('#album_name').attr('name');
 var aid = $('#album_name').attr('aid');
 var url = $('#album_name').attr('url');
 if (name){
  var ori_ele = '<p class="pt_list_title"><a href="'+ url +'" target="_blank" style="font-size:16px;color:#3366CC">'+ name +'</a>&nbsp; <a href="javascript:;" name="'+ name +'" aid="'+ aid +'" url="'+ url +'" onclick="edit_album(this)">[编辑]</a></p>';
  $('#modi_album').replaceWith(ori_ele);
 }
  
  var name = $(info).attr('name');
  var aid = $(info).attr('aid');
  var url = $(info).attr('url');
  var content = '<div id="modi_album"><p><span id="edit_result" class="result" style="display:none"></span></p><p><input type="text" id="album_name" name="'+ name +'" aid="'+ aid +'" url="'+ url +'" value="'+ name +'" class="small"/><p/><input type="button" value="保存" class="button white" onclick="submit_name()" /> &nbsp;&nbsp<input type="button" value="取消" class="button black" onclick="edit_close()"></div>';
   $(info).parent().replaceWith(content);
} 

//关闭编辑框
function edit_close(info){
 var name = $('#album_name').attr('name');
 var aid = $('#album_name').attr('aid'); 
 var url = $('#album_name').attr('url');
 var ori_ele = '<p class="pt_list_title"><a href="'+ url +'" target="_blank" style="font-size:16px;color:#3366CC">'+ name +'</a>&nbsp; <a href="javascript:;" name="'+ name +'" aid="'+ aid +'" url="'+ url +'" onclick="edit_album(this)">[编辑]</a></p>';
 $('#modi_album').replaceWith(ori_ele);
}
     
//提交修改
function submit_name(){
 var name = $('#album_name').val();
 var aid = $('#album_name').attr('aid'); 
 var url = $('#album_name').attr('url');
  $.post('<?php echo ($operaUrl["edit"]); ?>',
  		 {cg_id:aid,name:name},
         function(data){
		 if (data.status == 1) {
		  new_ele = '<p class="pt_list_title"><a href="'+ url +'" target="_blank" style="font-size:16px;color:#3366CC">'+ name +'</a>&nbsp; <a href="javascript:;" name="'+ name +'" aid="'+ aid +'" url="'+ url +'" onclick="edit_album(this)">[编辑]</a></p>';		 
		  $('#modi_album').replaceWith(new_ele);
		 } else {
		  $('#edit_result').html(data.info).show();
		  setTimeout(function(){ $('#edit_result').hide();},2000);
		 }
  });
 } 
</script>
		  <?php else: ?>
		  <h2 id="album_title"><?php echo ($albumInfo["name"]); ?></h2>
		  <?php if(($_GET['id']) == $_SESSION['id']): if(!in_array(($_GET['aid']), explode(',',"1,2"))): ?><div id="modi_album" style="display:none"><p><input type="text" value="<?php echo ($albumInfo["name"]); ?>" id="album_name" class="small"/> <span id="edit_result" class="result" style="display:none"></span><p/><input type="hidden" id="aid" value="<?php echo ($_GET["aid"]); ?>" /><input type="button" onclick="edit_album()" value="保存" class="button white"/> &nbsp;&nbsp <input type="button" value="取消" class="button black" onclick="close_edit()"></div><?php endif; endif; ?>	
		  <p><span id="album_info">创建时间(<?php echo (date('Y-m-d H:i',$albumInfo["time"])); ?>)&nbsp;|&nbsp; 图片 (<?php echo ($albumInfo["count"]); ?>)<?php if(($_GET['id']) == $_SESSION['id']): if(!in_array(($_GET['aid']), explode(',',"1,2"))): ?>&nbsp;|&nbsp; <a href="javascript:;" onclick="open_edit()">编辑</a><?php endif; endif; ?></span>	
<?php if(($_GET['id']) == $_SESSION['id']): if(!in_array(($_GET['aid']), explode(',',"1,2"))): ?><script type="text/javascript">
 //显示设置专辑名称表单
function open_edit(){
 $('#album_title').hide();
 $('#album_info').hide();
 $('#modi_album').show();
}
 
//隐藏表单
function close_edit(){
  $('#album_title').show();
  $('#album_info').show();
  $('#modi_album').hide();
} 
 
 //提交修改
function edit_album(){
  var name = $('#album_name').val();
  var aid = $('#aid').val();
  $.post('<?php echo ($operaUrl["edit"]); ?>',
         {cg_id:aid,name:name},
         function(data){
		 if (data.status == 1) {
  		  $('#album_title').html(name).show();
  		  $('#album_info').show();
   		  $('#modi_album').hide();
  		 } else {
		  $('#edit_result').html(data.info).show();
		  setTimeout(function(){ $('#edit_result').hide();},2000);
		 }
  });
}
</script><?php endif; endif; ?>	  		  		  
      	  <div id="main" role="main">
		<ul id="tiles">
		   <?php if(is_array($imageList)): $i = 0; $__LIST__ = $imageList;if( count($__LIST__)==0 ) : echo "这个专辑还没有图片" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?><li id="id_<?php echo ($i["image_id"]); ?>"><a href="<?php echo U('pic/image?pic=1&id='.$i['image_id']);?>" target="_blank" class="img_stream" aid="<?php echo ($_GET["aid"]); ?>" id="<?php echo ($i["image_id"]); ?>" uid="<?php echo ($_GET["id"]); ?>" ><img src="<?php echo ($i["image_path"]); ?>" title="<?php echo ($i["image_title"]); ?>" alt="<?php echo ($i["image_title"]); ?>"/><span class="info"><?php echo ($i["image_title"]); ?></span></a>
		   <?php if(($_GET['id']) == $_SESSION['id']): ?><br /><a href="javascript:;" aid="<?php echo ($_GET["aid"]); ?>" id="<?php echo ($i["image_id"]); ?>" path="<?php echo ($i["image_path"]); ?>" onclick="set_cover(this)">设为封面</a> &nbsp;|&nbsp; <a href="javascript:;" onclick="move_cg(this)" cg_id="<?php echo ($i["image_album"]); ?>" pf_id="<?php echo ($i["image_id"]); ?>">转移专辑</a> &nbsp;|&nbsp; <?php if(($_GET['aid']) == "2"): ?><a href="javascript:;" onclick="set_avatar(this)" path="<?php echo ($i["image_path"]); ?>">设为头像</a> &nbsp;|&nbsp;<?php endif; ?><a href="javascript:;" id="<?php echo ($i["image_id"]); ?>" aid="<?php echo ($_GET["aid"]); ?>" path="<?php echo ($i["image_path"]); ?>" onclick="del_img(this)" name="<?php echo ($i["image_title"]); ?>">删除图片</a><?php endif; ?></li><?php endforeach; endif; else: echo "这个专辑还没有图片" ;endif; ?>
		  </ul>	
		  </div>	
		  <script type="text/javascript">
$(function($){
//瀑布流设置
 $('#tiles').imagesLoaded(function() {
  var handler = null;
  // Prepare layout options.
  var options = {
   itemWidth: 210, // Optional, the width of a grid item
   autoResize: true, // This will auto-update the layout when the browser window is resized.
   container: $('#main'), // Optional, used for some extra CSS styling
   offset: 5, // Optional, the distance between grid items
   flexibleWidth: 310 // Optional, the maximum width of a grid item
   };

  function applyLayout() {
    $('#tiles').imagesLoaded(function() {
    // Destroy the old handler
    if (handler.wookmarkInstance) {
      handler.wookmarkInstance.clear();
    }

    // Create a new layout handler.
    handler = $('#tiles li');
    handler.wookmark(options);
    });
  }   

  /*
   * When scrolled all the way to the bottom, add more tiles.
   */  
  var loading = $("#loading").data("on", false);//通过给loading这个div增加属性on，来判断执行一次ajax请求   
  function onScroll(event) {
    if(loading.data("on")) return;  
      // Check if we're within 100 pixels of the bottom edge of the broser window.
      var winHeight = window.innerHeight ? window.innerHeight : $(window).height(); // iphone fix
      var closeToBottom = ($(window).scrollTop() + winHeight > $(document).height() - 100);

    if (closeToBottom) {
      // Get the first then items from the grid, clone them, and add them to the bottom of the grid.
      //在这里将on设为true来阻止继续的ajax请求 
      loading.data("on", true).fadeIn();                
      var id = $('#tiles li:last').children('.img_stream').attr('id');
      var aid = <?php echo ($_GET["aid"]); ?>;
      var uid = <?php echo ($_GET["id"]); ?>;
      $.get('<?php echo U('Pic/moreImg');?>',
          {id:id,aid:aid,uid:uid},
          function(data){
           var img_list = '';  //变量必须声明和初始化,切记！
           imgList = data.data;  //图片数据
           total = data.info;  //图片总数            
           if($.isArray(imgList)){                               
            for(i=0;i<total;i++){     
             img_list += '<li><a href="'+ imgList[i]['url'] +'" target="_blank" class="img_stream" aid="'+ aid +'" id="'+ imgList[i]['id'] +'" uid="'+ uid +'" ><img src="'+ imgList[i]['path'] +'" title="'+ imgList[i]['title'] +'" alt="'+ imgList[i]['title'] +'"/><span class="info">'+ imgList[i]['title'] +'</span></a><?php if(($_GET["id"]) == $_SESSION["id"]): ?><br /><a href="javascript:;" aid="'+ aid +'" id="'+ imgList[i]['id'] +'" path="'+ imgList[i]['path'] +'" onclick="set_cover(this)">设为封面</a> &nbsp;|&nbsp; <a href="javascript:;" onclick="move_cg(this)" cg_id="'+ aid +'" pf_id="'+ imgList[i]['id'] +'">转移专辑</a> &nbsp;|&nbsp; <?php if(($_GET['aid']) == "2"): ?><a href="javascript:;" onclick="set_avatar(this)" path="'+ imgList[i]['path'] +'">设为头像</a> &nbsp;|&nbsp;<?php endif; ?><a href="javascript:;" id="'+ imgList[i]['id'] +'" jump="1" path="'+ imgList[i]['path'] +'" onclick="del_img(this)">删除</a><?php endif; ?></li>';
            }        

              $('#tiles').append(img_list);
             
              applyLayout();  //申请执行一次配置，之前就是少了次配置
                       
              loading.data("on", false);   //一次请求完成，将on设为false，可以进行下一次的请求         
          }  
        loading.fadeOut();                                        
      });              
    }    
  }

   
   $(window).bind('scroll', onScroll);  // Capture scroll event.   
   handler = $('#tiles li');  // Get a reference to your grid items.   
   handler.wookmark(options); // Call the layout function.
 });
});
</script>
		  <?php if(($_GET['id']) == $_SESSION['id']): ?><div id="delete_confirm" title="删除确认">
删除后不可恢复,确定要删除 <span id="del_name"></span> 吗？
<input type="hidden" id="pic_id" />
<input type="hidden" id="pic_aid" />
<input type="hidden" id="pic_path" />
<input type="hidden" id="del_jump" value="<?php echo ($navi["jump"]); ?>" />
</div>  
<script type="text/javascript">
$(function($){   
  //删除确认
 $('#delete_confirm').dialog({  
  autoOpen: false,
  resizable: false,
  modal: true,
  draggable: false,
  buttons: {
        '确定':function(){
         var id = $('#pic_id').val();
         var path = $('#pic_path').val();  
         var aid = $('#pic_aid').val();
         var jump = $('#del_jump').val();  
         $.post('<?php echo U('pic/edit?set=del');?>',
          {id:id,path:path,aid:aid},
          function(data){ 
          if (data.status == 1) {
           if (jump != 1) {
            location.href = data.data;
           } else {
            $('#id_'+id).hide(500);
            applyLayout();         
            //setTimeout('location.reload()',1000);
           }
          } else {
           alert(data.info);
          }
         }); 
        $(this).dialog('close');
      },
        '取消':function(){ $(this).dialog('close')}
  }
 });
});

//设置封面
function set_cover(param){
 var path = $(param).attr('path');
 var aid = $(param).attr('aid');
 $.post('<?php echo U('pic/edit?set=cover');?>',
  {path:path,aid:aid},
  function(data){
   $('#move_result').text(data.info);
   $('#move_result').dialog('open');
   $('.sb_album:first').children().children().children('img').attr('src',path);
 });									
}
 
//用作头像
function set_avatar(param){
 var path = $(param).attr('path');
 $.post('<?php echo U('Pic/edit?set=avatar');?>',
   {path:path},
   function(data){
	$('#avatar').attr('src',path);
    $('#move_result').text(data.info);
	$('#move_result').dialog('open');
 });
}
		
//删除图片		
function del_img(param){ 
  var id = $(param).attr('id');
  var path = $(param).attr('path');  
  var aid = $(param).attr('aid');
  var name= $(param).attr('name');

  $('#pic_id').val(id);
  $('#pic_aid').val(aid);  
  $('#pic_path').val(path);  
  $('#del_name').text(name);
  $('#delete_confirm').dialog('open'); 						
}
</script><?php endif; ?>		 
		 <div class="clr"></div>
		 <div id="loading" class="loading-wrap">
    	  <span class="loading">加载中,请稍候...</span>
 		   </div><?php endif; ?>		    		 				  		
		  </div> 	
      </div>
      <div class="sidebar">
	  <div class="gadget">
<h2 class="star"><?php echo ($info["nickname"]); ?></h2>
          <div class="clr"></div>
          <ul class="sb_menu">		  
		    <?php if(!empty($info["unread_apply"])): ?><li><a href="<?php echo U('Profile/reminder?list=friend');?>"><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_apply"]); ?></span> 条未读好友申请</span></a></li><?php endif; ?>
		  <?php if(!empty($info["unread_message"])): ?><li><a href="<?php echo U('Profile/reminder?list=message');?>"><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_message"]); ?></span> 条未读留言</span></a></li><?php endif; ?>
		  <?php if(!empty($info["unread_comment"])): ?><li><?php if(empty($comment)): ?><a href="<?php echo U('Profile/reminder?list=pic_comment');?>"><?php else: ?><a href="<?php echo U('Profile/reminder?list=comment');?>"><?php endif; ?><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_comment"]); ?></span> 条未读评论</span></a></li><?php endif; ?>
		  <li><?php if(($navi["user"]) == $_SESSION['id']): ?><a href="<?php echo U('Profile/editProfile?about=1&set=avatar');?>"><img id="avatar" src="<?php echo ($info["avatar"]); ?>" /></a><?php else: ?><img id="avatar" src="<?php echo ($info["avatar"]); ?>" /><?php endif; ?></li>
		  <li><span class="info">关注博客:<a href="<?php echo U('Profile/followlist?follow=1&id='.$navi['user']);?>" target="_blank"><?php echo ($info["following"]); ?></a></span></li>	
		  <?php if(($navi["user"]) == $_SESSION['id']): ?><li><span class="info">我的收藏:<a href="<?php echo U('Blog/collectBlog?blog=1');?>" target="_blank"><?php echo ($info["collect"]); ?></a></span></li><?php endif; ?>
		  <li><span class="info">博客访问:<?php echo ($info["visits"]); ?></span></li>
		  <li><span class="info">关注人气:<a href="<?php echo U('Profile/fanslist?about=1&id='.$navi['user']);?>" target="_blank"><?php echo ($info["followers"]); ?></a></span></li>
		  <?php if(!empty($_SESSION['isLoginIn'])): if(($navi["user"]) == $_SESSION['id']): ?><li><a href="<?php echo U('Profile/reminder?list=friend');?>"><span class="apply">消息中心(<?php if(empty($unread_news)): ?><span><?php else: ?><span class="retitle"><?php endif; echo ($info["unread_news"]); ?></span>)</span></a></li>
		  <li><button class="button gray large" onClick="logout()">登出</button></li>		
		  <?php else: ?>
		  <li><?php if(($info["isFollow"]) == "0"): ?><a href="javascript:;" id="follow" fid="<?php echo ($navi["user"]); ?>"><span class="rela_info">关注Ta</span></a>
			<a href="javascript:;" id="unfollow" fid="<?php echo ($navi["user"]); ?>"><span id="new_fol" style="display:none"><span id="followed" class="rela_info">√已关注</span><span id="unf_btn" class="prompt" style="display:none">取消关注</span></span></a>
		      <?php else: ?>
		      <a href="javascript:;" id="unfollow" fid="<?php echo ($navi["user"]); ?>"><span id="new_fol"><span id="followed" class="rela_info">√已关注</span><span id="unf_btn" class="prompt" style="display:none">取消关注</span></span></a>
			  <a href="javascript:;" id="follow" fid="<?php echo ($navi["user"]); ?>" style="display:none"><span class="rela_info">关注Ta</span></a><?php endif; ?>
		  </li>
		  <li><?php switch($info["isFriend"]): case "0": ?><a href="javascript:;" id="add_fri"><span class="rela_info">加好友</span></a>
		  <span id="wait_app" style="display:none"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span><?php break;?>
		  <?php case "-1": ?><span id="wait_app"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span>
		  <a href="javascript:;" id="add_fri" style="display:none"><span class="rela_info">加好友</span></a><?php break;?>
		  <?php default: ?>
		  <span id="now_fri">√<span class="info">朋友</span> &nbsp;<a href="javascript:;" id="break" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">解除关系</span></a></span>
		  <a href="javascript:;" id="add_fri" style="display:none"><span class="rela_info">加好友</span></a>
		  <span id="wait_app" style="display:none"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span><?php endswitch;?>
		  <div id="apply_area" style="display:none;">
		  <input type="hidden" id="fri_id" value="<?php echo ($navi["user"]); ?>" />
		  <p><span class="prompt">打个招呼吧</span>(15字以内):
		  <input type="text" id="app_mes" class="small" value="Hi！能交个朋友吗？"/><br/>
		  <input type="button" value="发送" class="button white" id="send_apply"/>
		  <input type="button" value="取消" id="cancel" class="button gray" /></p>
		  </div>
		  </li>
		  <script type="text/javascript">
$(function($){
 //加关注
 $('#follow').click(function(){
 var fid = $('#follow').attr('fid');
  $.post('<?php echo U('Profile/rela?set=follow');?>',
     {fid:fid},
     function(data){
      $('#follow').hide();
      $('#new_fol').show();
  });
 });
 
 //鼠标移动到关注上面显示效果
 $('#new_fol').mouseenter(function(){
  $('#followed').hide();
  $('#unf_btn').show();
 });
 
 $('#new_fol').mouseleave(function(){
  $('#followed').show();
  $('#unf_btn').hide();
 });
 
 //取消关注
 $('#unfollow').click(function(){
  var fid = $('#unfollow').attr('fid');
  $.post('<?php echo U('Profile/rela?set=unFollow');?>',
     {fid:fid},
     function(data){
      $('#new_fol').hide();
      $('#follow').show();
  });
 });
 
  //显示加好友表单
 $('#add_fri').click(function(){
  $('#apply_area').show(500);
  $('#add_fri').hide();
 });
 
  //关闭加好友表单
 $('#cancel').click(function(){
  $('#add_fri').show(500);
  $('#apply_area').hide();
 });
 
 //提交添加好友申请
 $('#send_apply').click(function(){
  var fid = $('#fri_id').val();
  var app_mes = $('#app_mes').val();
  $.post('<?php echo U('profile/rela?set=apply');?>',
        {friend:fid,message:app_mes},
		 function(data){
		  $('#apply_area').hide();
		  $('#wait_app').show();
  });
 });
 
 //取消申请
 $('#un_app').click(function(){
  var fid = $('#un_app').attr('fid');
  $.post('<?php echo U('Profile/rela?set=unApply');?>',
        {fid:fid},
  		function(data){
	     $('#wait_app').hide();
		   $('#add_fri').show();	
  });
 });
 
 //解除关系
 $('#break').click(function(){
  if(confirm('确定和TA解除朋友关系吗？')){
   var fid = $('#break').attr('fid');
   $.post('<?php echo U('Profile/rela?set=reFriend');?>',
    {fid:fid},
	function(){
     $('#now_fri').hide();
	   $('#add_fri').show();
   });
  }
 }); 
});
</script><?php endif; endif; ?> 
          </ul>
		  </div>
	  	 
	  <div class="gadget">          
          <div class="clr"></div>
		  <?php if(!empty($_GET['aid'])): ?><h3>图片专辑</h3>
<?php if(is_array($album)): $i = 0; $__LIST__ = $album;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><div class="sb_album"><p><a href="<?php echo U('Pic/proPic?pic=1&id='.$_GET['id'].'&aid='.$a['album_id']);?>" target="_blank"><?php if(empty($a["album_cover"])): ?><img src="__PUBLIC__/images/common/main/no_cover.gif"/><?php else: ?><img src="<?php echo ($a["album_cover"]); ?>" title="<?php echo ($a["album_name"]); ?>" alt="<?php echo ($a["album_name"]); ?>" /><?php endif; ?></a><a href="<?php echo U('Pic/proPic?pic=1&id='.$_GET['id'].'&aid='.$a['album_id']);?>" target="_blank" ><?php echo ($a["album_name"]); ?></a></p></div>	
			<div class="clr"></div><?php endforeach; endif; else: echo "" ;endif; ?>			
			<a href="<?php echo U('Pic/proPic?pic=1&id='.$_GET['id']);?>" style="float:right"><h3>全部专辑</h3></a>			  		
		  <div class="clr"></div><?php endif; ?>
		<p class="spec"></p>
		<?php if(($_GET['id']) == $_SESSION['id']): ?><button class="button orange large" id="edit_cg">查看列表</button>
<script type="text/javascript">
$(function($){
 $('#edit_dialog').dialog({
	autoOpen: false,
	resizable: false,
	modal: true
 });
		
 //打开对话框		
 $('#edit_cg').click(function() {
  $('#edit_dialog').dialog('open');
 });
 
 //转移分组
 $('#select_cg').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
			  '确认':function(){
			   var pf_id = $('#pf_id').val();
			   var cg_id = $('#cg_id').val();
			   $.post('<?php echo ($operaUrl["move"]); ?>',
        		{pf_id:pf_id,cg_id:cg_id},
      			function(data){
	 	   			if (data.status == 1){
	 	   		<?php if(!empty($_GET['aid'])): ?>$('#id_'+pf_id).hide(800);//图片转移效果<?php endif; ?>
	 	   		<?php if(!empty($_GET['cid'])): ?>$('#id_'+pf_id).hide(800);//文章转移效果<?php endif; ?>
	 	   		<?php if(!empty($_GET['gid'])): ?>$('#id_'+pf_id).hide(800);//好友或关注转移效果<?php endif; ?>	 	   			 					
					$('#pf_id_'+pf_id).attr('cg_id',cg_id);	//其他转移赋值
					$('#move_result').text(data.info);
		    		$('#move_result').dialog('open');			
		   		}	
  			  });
			  $(this).dialog('close');
			},
			  '取消':function(){ $(this).dialog('close')}
	}
 });
 
 //转移结果
 $('#move_result').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	open: function(event,ui){
		$('#select_cg').dialog('close');
		setTimeout(function(){ $('#move_result').dialog('close')},1000);
	}
 });
});

 //转移分组
 function move_cg(param) {
  var pf_id = $(param).attr('pf_id');
  var cg_id = $(param).attr('cg_id');
  if(cg_id == 0){	//判断是否没有分组
    var option = '<option value="0">请选择分组</option>';
	var first_option = $('#cg_id').find('option:first').val();	//获取第一个选项值
	if (first_option != 0){	//第一个选项不是0分组则添加
	 $('#cg_id').prepend(option);
	}
	$('#cg_id').val(0);
  } else {
  	$('#cg_id').val(cg_id);
  }
    
  $('#pf_id').val(pf_id);  
  $('#select_cg').dialog('open');
 }

		  </script>
		  <div id="edit_dialog" title="所有列表">
		  <div id="list">
		  <?php if(is_array($cate_group)): $i = 0; $__LIST__ = $cate_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cg): $mod = ($i % 2 );++$i;?><p><span class="desc"><?php echo ($cg["name"]); ?></span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="<?php echo ($cg["name"]); ?>" cg_id="<?php echo ($cg["cg_id"]); ?>">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="<?php echo ($cg["cg_id"]); ?>">删除</a></p><?php endforeach; endif; else: echo "" ;endif; ?>
		  </div>		
		<span id="error_result" class="result"></span>		       	   
		  <p><button class="button orange large" id="add_cg">添加</button></p>
		  
		  <div id="new_cg" style="display:none;">
		  <p><span class="category">分组名:</span><input type="text" id="new_name" class="small"/></p>
		  <input type="button" onclick="create_cg()" class="button white" value="保存"/>
		  <input type="button" id="cancel_add" class="button black" value="取消" />
		  </div>
		  
<div id="delete_cg" title="请选择">
</div>
<script type="text/javascript">
  //显示新增分类表
 $('#add_cg').click(function(){
  $('#new_cg').show();
  $('#add_cg').hide();
 });
 
 //隐藏新增表
 $('#cancel_add').click(function(){
  $('#new_cg').hide();
  $('#add_cg').show();
 }); 

function create_cg(){
   //新增分组
   var name = $('#new_name').val();   
   $.post('<?php echo ($operaUrl["add"]); ?>',
        {name:name},
      	function(data){
	 	 if (data.status == 1){
		   cg = data.data;
		   var new_cg = '<p><span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg.id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="' + cg.id + '">删除</a></p>';		   		   
		  $('#list').append(new_cg);
		  
		  <?php if(($_GET["blog"]) == "1"): ?>//博客页面添加选择分组列表
		    var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/blog-proBlog-blog-1-id-<?php echo ($_GET["id"]); ?>-cid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#category_list').append(new_cg);<?php endif; ?>

		  <?php if(($_GET["follow"]) == "1"): ?>if ($('#fg_list')) {	//判断是查看关注博客页面还是查看关注页面
			//关注博客页面到选择分组列表
		    var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/Profile-followList-follow-1-id-1-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#fg_list').append(new_cg);
		   } else {
		   	var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/blog-followBlog-follow-1-id-1-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#fgb_list').append(new_cg);
		   }<?php endif; ?>

		  <?php if(($_GET["about"]) == "1"): ?>var new_cg = '<li id="cg_id_'+cg.id+'"><a href="/index.php/blog-id=<?php echo ($_GET["id"]); ?>-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#friend_list').append(new_cg);<?php endif; ?>
		   
		  if($('#cid')){	//编辑和添加博客页面的添加分组列表
		    var new_c = '<option value="'+cg.id+'" >'+ name +'</option>';
		   	$('#cid').append(new_c);
		  }
		  
		  $('#new_name').val('');	//清空输入框
		  $('#new_cg').hide();
		  $('#add_cg').show();
		 } else {
		  $('#error_result').html(data.info).show();
		   setTimeout(function(){ $('#error_result').hide()},2000);
		 } 	    
   });
}

//删除分组
function del_cg(param){
  if(confirm('确定删除这个分组？')){
   var cg_id = $(param).attr('cg_id');
   $.post('<?php echo ($operaUrl["del"]); ?>',
    {cg_id:cg_id},
    function(data){
     if(data.status == 1){
      $(param).parent().hide(300);
	  if ($('#cg_id_'+cg_id)){
	   $('#cg_id_'+cg_id).hide();
	  }
	  
	  if ($('#cid')){	//移除添加的分组
	   $('#cid').val(cg_id);
	   $('#cid option:selected').remove();
	   $('#cid').val(1); 
	  }
	  
     } else {
	  alert(data.info);
	 }
   });
  }
}

//显示编辑输入框
function modi_name(info){
  var cg_id = $('#cg_id').val(); 
  if (cg_id) {
  var name = $('#save_name').attr('alt');
  //还原正在编辑的表单
  var ori_ele = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="'+ cg_id +'">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
  $('#editing').replaceWith(ori_ele);
 }
 
 //注意内容多也不要换行！
 var name = $(info).attr('name');
 var cg_id = $(info).attr('cg_id');
 var edit_form = '<div id="editing"><input type="text" id="save_name" alt="'+ name +'" value="'+ name +'" class="small"/><input type="hidden" id="cg_id" value="'+ cg_id +'" /><br/><input type="button" class="button white" value="保存" onclick="edit_name(this)" /> &nbsp;<input type="button" onclick="cancel_modi(this)" class="button black" value="取消" /></div>';
 $(info).parent().html(edit_form);
}

//修改分组名
function edit_name(info){
  var name = $('#save_name').val();
  var cg_id = $('#cg_id').val();
  $.post('<?php echo ($operaUrl["edit"]); ?>',
        {name:name,cg_id:cg_id},
      	function(data){
		 if (data.status == 1) {
		  var get_back = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg_id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
		  $(info).parent().replaceWith(get_back);
		 } else {
		  $('#error_result').html(data.info).show();
		  setTimeout(function(){ $('#error_result').hide();},2000);
		 }
  });
}

//点击取消修改按钮
function cancel_modi(info){
 var cg_id = $('#cg_id').val();
 var name = $('#save_name').attr('alt');
 var content = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg_id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
 $(info).parent().replaceWith(content);
}

/*
window.onbeforeunload = on_handle;
function on_handle(){
 return '您将关闭本页面';
}*/ 
</script>

		  </div>
		  <div id="select_cg" title="请选择">
		  		  <h3>转移到:
		  <select id="cg_id">
		  <?php if(empty($cateList)): if(empty($selectAlbum)): if(is_array($cate_group)): $i = 0; $__LIST__ = $cate_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cg): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cg["cg_id"]); ?>"><?php echo ($cg["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		  <?php else: ?>
		  <?php if(is_array($selectAlbum)): $i = 0; $__LIST__ = $selectAlbum;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sa): $mod = ($i % 2 );++$i;?><option value="<?php echo ($sa["aid"]); ?>"><?php echo ($sa["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		  <?php else: ?>
		  <?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ca["category_id"]); ?>"><?php echo ($ca["category_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		  </select></h3>	  	
		  <input type="hidden" id="pf_id" value="" />
		  </div>
		  <div id="move_result" title="操作结果">	
		  </div>	 			
		<p class="spec"></p>
		<a href="<?php echo U('Pic/poNew?new=1');?>" class="button blue large">上传图片</a><?php endif; ?>
		</div>
      </div>
      <div class="clr"></div>
    </div></div>
  </div>
<div class="footer">
    <div class="footer_resize">
      <p class="lf"><?php if(empty($_SESSION['isLoginIn'])): ?><button class="button orange bigrounded" id="open_login_dialog">登陆</button><?php else: ?><button class="button gray" onclick="logout()">登出</button><?php endif; ?> 
        Copyright &copy; 2013 <a href="<?php echo U('index/index?hot=1');?>">www.Jellong.com</a>, All Rights Reserved</p>
      <ul class="fmenu">
	  <?php if(($_GET['hot']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/index?hot=1');?>">热门博客</a></li>
	  <?php if(($_SESSION['isLoginIn']) == "1"): if(($_GET['follow']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/followBlog?follow=1');?>">我的关注</a></li>
          <?php if(($_GET['blog']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/proBlog?blog=1&id='.$_SESSION['id']);?>">我的博客</a></li>	  
          <?php if(($_GET['pic']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Pic/proPic?pic=1&id='.$_SESSION['id']);?>">我的照片</a></li>
          <?php if(($_GET['about']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Profile/index?about=1&id='.$_SESSION['id']);?>">关于我</a></li>
		  <?php if(($_GET['new']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/newblog?new=1');?>">写博客</a></li>
		  <div id="login_out" title="登出成功！"></div>	
<script type="text/javascript">
$(function($){
 //登出成功显示
 $('#login_out').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	open: function(event,ui){	
		var wait = document.getElementById('wait_time');
		var interval = setInterval(function(){			
			var time = --wait.innerHTML;
			if(time <= 0) {
			 location.href = $('#href').attr('href');
			 clearInterval(interval);
			}
		},1000);						
	}
 });
});

//登出
function logout(){
 var jump = '<?php echo ($navi["log_jump"]); ?>';
 $.post('<?php echo U('Index/loginOut');?>',
        function(data){
		 if(data.status == 1){
		  if (jump == 1) {
		  	var prompt = '<a href="'+data.data+'" id="href">页面将在 <b id="wait_time">1</b> 秒后跳转，如不想等待请点击此处。</a>';
		  } else {
		  	var prompt = '<a href="javascript:location.reload();" id="href">页面将在 <b id="wait_time">1</b> 秒后跳转，如不想等待请点击此处。</a>';
		  }
		  $('#login_out').on('dialogclose', function( event, ui ) {location.href = $('#href').attr('href')}); 	
		  $('#login_out').html(prompt);	 
		  $('#login_out').dialog('open');
		 } else {
		  alert(data.info);
		 }
 });
}
</script>	
		  <?php else: ?>
		  <li><a href="<?php echo U('Index/login?login=1');?>">登陆</a></li>
		  <li><a href="<?php echo U('Index/register?join=1');?>">加入果冻</a></li>
		  <div id="login_dialog" title="用户登陆">
		  <p><span class="login_title">账号：</span><input type="text" class="small" id="login_name"/></p>
<p><span class="login_title">密码：</span><input type="password" class="small" id="login_pword"/></p>
<input type="button" value="登陆果冻" class="button orange bigrounded" id="dialog_login" />
			<a href="<?php echo U('index/findpw?find=1');?>" class="btn btn-inverse" style="margin-left:10px;">忘记密码?</a><br/>
		    <a href="<?php echo U('Index/register?join=1');?>" style="float:right">还没有账号?立刻加入果冻！</a></p>
		<span id="login_dialog_error" class="result"></span>
		<div id="login_success" title="登陆成功！"></div>
		<script type="text/javascript">
$(function($){	//登录框方法
 $('#login_dialog').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false
 });
		
 //打开对话框		
 $('#open_login_dialog').click(function() {
  $('#login_dialog').dialog('open');
 });

 //登陆成功显示
 $('#login_success').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	close: function(event,ui) {location.href = $('#href').attr('href')},
	open: function(event,ui){				
		//var wait = document.getElementById('wait_time');
		var interval = setInterval(function(){
			var wait = $('#wait_time').text();			
			var time = --wait;
			$('#wait_time').text(time);
			//var time = --wait.innerHTML;	//就是这个地方dialog和jq有关的地方老是不能刷新
			if(time <= 0) {
			<?php if(($_GET["join"]) == "1"): ?>location.href = $('#href').attr('href'); //注册页登陆跳转
			<?php else: ?>	
			 location.reload();		//不是注册页登陆直接刷新<?php endif; ?>
			 clearInterval(interval);
			}
		},1000);						
	}
 });
 
 $('#dialog_login').click(function(){	//登陆
  $.post('<?php echo U('Index/loginin');?>',
        {uname:$('#login_name').val(),pword:$('#login_pword').val()},
      	function(data){
	 	 if (data.status == 1){
	 	 <?php if(($_GET["join"]) == "1"): ?>var prompt = '<a href="'+data.data+'" id="href">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a>'; //不是注册页登陆则刷新	 	 
	 	 <?php else: ?>
	 	  var prompt = '<a href="javascript:location.reload();" id="href">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a>';//注册页登陆则跳转<?php endif; ?>		
	 	  $('#login_success').html(prompt);
	 	  $('#login_dialog').dialog('close');
		  $('#login_success').dialog('open');		  
	 	 } else {
	 	  $('#login_dialog_error').html(data.info).show();
		  setTimeout(function(){ $('#login_dialog_error').hide()},2000);	
	     }
   });
 });
}); 
</script>
		  </div><?php endif; ?>
      </ul>
      <div class="clr"></div>
    </div>
  </div>
  <p id="back-to-top"><a href="javascript:;"><span></span>回顶部</a></p>
<script type="text/javascript">
$(function(){
 $(window).scroll(function(){	//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
    if ($(window).scrollTop()>100){
      $('#back-to-top').fadeIn(200);
    } else {
      $('#back-to-top').fadeOut(200);
    }
  });            

  $('#back-to-top').click(function(){	//当点击跳转链接后，回到页面顶部位置
   $('body,html').animate({scrollTop:0},1000);
   return false;
  });
});
</script>
</div>
</body>
</html>