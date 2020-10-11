<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\article\index.html";i:1602247994;s:65:"D:\PHPCode\venuscmf_tp5\public/../app/admin\view\common\base.html";i:1602247994;}*/ ?>
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
			
	<div style="height: 32px;">
		<ul class="nav nav-tabs pull-left">
			<li class="active"><a href="<?php echo url('article/index'); ?>" class="index-url">文章列表</a></li>
			<li><a href="<?php echo url('article/add'); ?>">新增文章</a></li>
		</ul>
		<form name="searchForm" class="form-inline pull-left" action="<?php echo url('article/index'); ?>" method="get" style="margin-top: -2px; margin-left: 20px;">
			<div class="form-group">
				<label class="sr-only">这里不显示</label>
				<div class="input-group">
					<div class="input-group-addon">标题</div>
					<input type="text" name="title" value="<?php echo $title; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="sr-only">这里不显示</label>
				<div class="input-group">
					<div class="input-group-addon">发布时间</div>
					<input type="text" name="startime" value="<?php echo $startime; ?>" id="datetime_start" class="form-control" style="width: 120px;" readonly>
					<div class="input-group-addon">~</div>
					<input type="text" name="endtime" value="<?php echo $endtime; ?>" id="datetime_end" class="form-control" style="width: 120px;" readonly>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">搜索</button>
			<a href="<?php echo url('article/index'); ?>" class="btn btn-default">清空</a>
		</form>
	</div>
	
	<table class="table table-bordered table-hover top10" style="margin-bottom: 10px; font-size: 12px;">
		<tr class="table-header" style="background-color: #EFEFEF;">
			<th>ID</th>
			<th style="width: 40%;">标题</th>
			<th>所属分类</th>
			<th>作者 / 来源</th>
			<th>浏览</th>
			<th>发布时间</th>
			<th style="text-align: center;">状态</th>
			<th style="text-align: center;">操作</th>
		</tr>
		<?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voart): $mod = ($i % 2 );++$i;?>
			<tr>
				<td><?php echo $voart['id']; ?></td>
				<td>
					<img class="artlists-faceimg show-faceimg" src="<?php echo $voart['faceimg']; ?>">
					<span style="white-space: pre-wrap; word-wrap: break-word;"><?php echo $voart['title']; ?></span>
				</td>
				<td><?php echo $voart['cname']; ?></td>
				<td>
					作者：<?php echo $voart['author']; ?> <br>
					来源：<?php echo $voart['source']; ?>
				</td>
				<td><?php echo $voart['click']; ?></td>
				<td><?php echo date('Y-m-d',$voart['time_report']); ?></td>
				<td style="text-align: center;">
					<?php if($voart['status'] == '1'): ?>
						<a class="btn btn-success btn-xs btn-editstatus" data-url="<?php echo url('article/editstatus'); ?>" data-id="<?php echo $voart['id']; ?>">显示</a>
					<?php else: ?>
						<a class="btn btn-default btn-xs btn-editstatus" data-url="<?php echo url('article/editstatus'); ?>" data-id="<?php echo $voart['id']; ?>">隐藏</a>
					<?php endif; ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo url('article/edit', array('id' => $voart['id'])); ?>" class="btn btn-warning btn-xs">编辑</a>
					<a class="btn btn-danger btn-xs left10 btn-deletes" data-url="<?php echo url('article/deletes'); ?>" data-id="<?php echo $voart['id']; ?>">删除</a>
				</td>
			</tr>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<tr class="table-header" style="background-color: #EFEFEF;">
			<th>ID</th>
			<th style="width: 40%;">标题</th>
			<th>所属分类</th>
			<th>作者 / 来源</th>
			<th>浏览</th>
			<th>发布时间</th>
			<th style="text-align: center;">状态</th>
			<th style="text-align: center;">操作</th>
		</tr>
	</table>
	<div class="page-info">
		<div id="page_info"></div>
		<input type="hidden" id="allpages" value="<?php echo $allpages; ?>" />
		<input type="hidden" id="nowpage" value="<?php echo $nowpage; ?>" />
	</div>

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

<script src="/static/js/laypage/laypage.js"></script>
<script>
	// 清空 cookie 上一页 url
	$.cookie('edit_back_referrer', '', {path: '/'});
	
	// 实例化时间控件 laydate
	laydate.render({
		elem: '#datetime_start', //指定元素
		format: 'yyyy-MM-dd',
	});
	laydate.render({
		elem: '#datetime_end', //指定元素
		format: 'yyyy-MM-dd',
	});
	
	// ajax 分页
	laypage({
		cont: 'page_info',
		pages: $('body #allpages').val(),
		curr: function(){
				var page = $('body #nowpage').val();
				return page ? page : 1;
			}(),
		skip: true, // 分页跳转
		jump: function(e, first){
			if(!first){
				layer.load(0, {shade: [0.1, '#FFF']}); // 加载层
				var formData = $('form[name="searchForm"]').serialize();
				var url = '/admin/article/index?page=' + e.curr + '&' + formData;
				
				window.location.href = url;
				return false;
			}
		}
	});
</script>

</body>
</html>