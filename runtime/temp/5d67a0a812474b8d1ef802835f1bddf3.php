<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\article\add.html";i:1602247994;s:65:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\common\base.html";i:1602247994;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/static/admin/css/style.css?time=<?php echo $systime; ?>">
	
	
	<title><?php echo $nowmenuname; ?></title>
	
	<script>
		var root_url = "<?php echo url('index/index'); ?>";
	</script>
</head>
<body>
<div class="container-fluid topnav">
	<a href="<?php echo url('index/index'); ?>" class="brand pull-left">
		<img src="/static/admin/images/logo.png">
	</a>
	<ul class="topmenu pull-left">
		<li><a class="btn btn-info btn-launch" title="伸缩导航"><i class="fa fa-bars"></i></a></li><li><a class="btn btn-warning btn-recache" data-url="<?php echo url('setting/recache'); ?>" title="刷新缓存"><i class="fa fa-trash"></i></a></li>
		<li><a class="btn btn-success" title="网站首页"><i class="fa fa-home"></i></a></li>
	</ul>
	<ul class="userinfo pull-right">
		<li>
			<a>
				<i class="fa fa-calendar"></i> <span id="calendar"><?php echo date("Y 年 m 月 d 日", $systime); ?></span>
			</a>
		</li>
		<li><a><i class="fa fa-user"></i> <?php echo $username; ?></a></li>
		<li class="truebtn">
			<a class="truebtn btn-repasswd" data-url="<?php echo url('myinfo/repasswd'); ?>" data-title="修改密码" data-width="600" data-height="280">
				<i class="fa fa-key"></i> 修改密码
			</a>
		</li>
		<li class="truebtn">
			<a class="truebtn btn-logout" data-url="<?php echo url('publics/logout'); ?>">
				<i class="fa fa-sign-out"></i> 注销
			</a>
		</li>
	</ul>
</div>

<div class="container-fluid main">
	<div id="sidebar" class="sidebar">
		<?php echo $sidebar; ?>
	</div>
	<div class="mainbox">
		<div class="maintop">
			<div class="nowseat">
				<span>当前位置：</span>
				<span>
					<?php if($nowmenuname == 'index'){ ?>
						后台首页
					<?php }else{ ?>
						<?php echo (isset($nowmenuname) && ($nowmenuname !== '')?$nowmenuname:"后台首页"); } ?>
				</span>
			</div>
			<div class="btninfo">
				<a class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i> 刷 新</a>
			</div>
		</div>
		<div class="maincont">
			
	<ul class="nav nav-tabs">
		<li><a href="<?php echo url('article/index'); ?>" class="index-url">文章列表</a></li>
		<li class="active"><a href="<?php echo url('article/add'); ?>">新增文章</a></li>
	</ul>
	
	<form name="dataForm" enctype="multipart/form-data" class="form-horizontal top15" action="<?php echo url('article/addsave'); ?>">
		<table class="table table-bordered must-middle">
			<tr>
				<th width="10%">发布时间</th>
				<td width="45%">
					<input type="text" id="datetime_picker" name="time_report" value="<?php echo date('Y-m-d',$systime); ?>" class="form-control" readonly="readonly">
				</td>
				<th width="10%" rowspan="3">封面图</th>
				<td width="35%" rowspan="3" style="text-align: center;">
					<img src="/static/admin/images/default.png" style="width: 120px; height: 75px;">
					<span class="help-block" style="color: #999;">封面图请在编辑中上传</span>
				</td>
			</tr>
			<tr>
				<th><span class="must-red">*</span> 所属分类</th>
				<td>
					<?php echo $categorylists; ?>
				</td>
			</tr>
			<tr>
				<th><span class="must-red">*</span> 标题</th>
				<td>
					<input type="text" name="title" class="form-control" maxlength="50">
				</td>
			</tr>
			<tr>
				<th>信息来源</th>
				<td>
					<input type="text" name="source" value="xiaocaihu.com" class="form-control" maxlength="50">
				</td>
				<th>作者</th>
				<td>
					<input type="text" name="author" value="xiaocaihu.com" class="form-control">
				</td>
			</tr>
			<tr>
				<th>简述</th>
				<td colspan="3">
					<textarea name="resume" class="form-control" maxlength="200" style="height: 55px; resize: none;"></textarea>
				</td>
			</tr>
			<tr>
				<th>SEO 标题</th>
				<td>
					<textarea name="seo_title" class="form-control" maxlength="80" style="height: 55px; resize: none;"></textarea>
				</td>
				<th>SEO 关键字</th>
				<td>
					<textarea name="seo_keys" class="form-control" maxlength="100" style="height: 55px; resize: none;"></textarea>
				</td>
			</tr>
			<tr>
				<th>SEO 描述</th>
				<td colspan="3">
					<textarea name="seo_desc" class="form-control" maxlength="200" style="height: 55px; resize: none;"></textarea>
				</td>
			</tr>
			<tr>
				<th><span class="must-red">*</span> 内容</th>
				<td colspan="3">
					<script id="ueditorbox" name="content" type="text/plain" style="width: 100%; height: 450px;">请输入文章内容</script>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;">
					<a class="btn btn-primary btn-save">保 存</a>
					<a class="btn btn-default btn-cancel left10">返 回</a>
				</td>
			</tr>
		</table>
	</form>

		</div>
	</div>
</div>

<div id="pop_html" style="display: none;"></div>

<script src="/static/js/jquery.min.js"></script>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/admin/js/jquery.cookie.js"></script>
<script src="/static/js/layer/layer.js"></script>
<script src="/static/js/laydate/laydate.js"></script>
<script src="/static/admin/js/common.js?time=<?php echo $systime; ?>"></script>

<script>
	// 自定义 ueditor 上传地址，默认 article，这里要在下面的 js 前面
	var ueditor_upload_url = "<?php echo url('ueditor/doupload', array('savepath' => 'article')); ?>";
</script>
<script src="/static/admin/js/ueditor/ueditor.config.js"></script>
<script src="/static/admin/js/ueditor/ueditor.all.min.js"></script>
<script>
$(function(){
	// 实例化时间控件 laydate
	laydate.render({
		elem: '#datetime_picker', //指定元素
		format: 'yyyy-MM-dd',
	});
	
	// 实例化 ueditor
	var ue = UE.getEditor('ueditorbox', {
		toolbars: [
			['fullscreen','preview','source','undo','redo','bold','italic','underline','strikethrough','fontborder','subscript','superscript','horizontal','removeformat','pasteplain','blockquote','emotion','spechars','link','unlink','cleardoc'],
			['insertcode','fontfamily','fontsize','paragraph','forecolor','backcolor','background','insertorderedlist','insertunorderedlist','simpleupload','insertimage','map'],
			['justifyleft','justifyright','justifycenter','justifyjustify','imagenone','imageleft','imagecenter','imageright','insertparagraphbeforetable','inserttable','deletetable','edittable','edittd','insertrow','insertcol','deleterow','deletecol','splittorows','splittocols','splittocells','mergecells','mergeright','mergedown','deletecaption','inserttitle'],
		],
		autoHeightEnabled: false,
		autoFloatEnabled: false,
		autoClearinitialContent: true // 获得焦点后是否清空编辑器内容
	});
});
</script>

</body>
</html>