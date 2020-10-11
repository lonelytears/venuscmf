<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\category\index.html";i:1602247994;s:65:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\common\base.html";i:1602247994;}*/ ?>
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
		<li class="active"><a href="<?php echo url('category/index'); ?>" class="index-url">分类列表</a></li>
		<li><a href="<?php echo url('category/add'); ?>">新增分类</a></li>
	</ul>
	
	<table class="table table-bordered table-hover top10">
		<tr class="table-header">
			<th>ID</th>
			<th>分类名称</th>
			<th>层级关系</th>
			<th style="text-align: center;">状态</th>
			<th style="text-align: center;">操作</th>
		</tr>
		<?php if(is_array($categorylists) || $categorylists instanceof \think\Collection || $categorylists instanceof \think\Paginator): $i = 0; $__LIST__ = $categorylists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vocate): $mod = ($i % 2 );++$i;?>
			<tr>
				<td><?php echo $vocate['id']; ?></td>
				<td><?php echo get_tree_icons($vocate['path']); ?><?php echo $vocate['name']; ?></td>
				<td><?php echo $vocate['path']; ?></td>
				<td style="text-align: center;">
					<?php if($vocate['status'] == '1'): ?>
						<a class="btn btn-success btn-xs">显示</a>
					<?php else: ?>
						<a class="btn btn-default btn-xs">隐藏</a>
					<?php endif; ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo url('category/edit', array('id' => $vocate['id'])); ?>" class="btn btn-warning btn-xs">编辑</a>
					<a class="btn btn-danger btn-xs left10 btn-deletes" data-url="<?php echo url('category/deletes'); ?>" data-id="<?php echo $vocate['id']; ?>">删除</a>
				</td>
			</tr>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<tr class="table-header">
			<th>ID</th>
			<th>分类名称</th>
			<th>层级关系</th>
			<th style="text-align: center;">状态</th>
			<th style="text-align: center;">操作</th>
		</tr>
	</table>

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

</body>
</html>