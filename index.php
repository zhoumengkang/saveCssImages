<!DOCTYPE HTML>
<html>
	<head>
		<title>下载css中的图片</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<style type="text/css">
			#wamp{margin:0 auto;width:970px;padding: 10px;}
			#wamp div{margin-bottom: 10px;}
			span{color: #999;}
			.input{width: 500px;}
			button{cursor: pointer;background: #5ab2ce;color: #FFF;}
			button:hover{background: #7BC3D6;;}
			.input,button{padding: 5px;border: 2px solid #dedede;-moz-border-radius: 15px;-webkit-border-radius: 15px;border-radius:15px;}
		</style>
	</head>
	<body>
		<div id="wamp">
			<div>
				<tt><b>使用说明：</b></tt><br/>
				<tt>输入要下载的css文件的地址<span>(例如：<a href="http://su.bdimg.com/static/superpage/css/index_min_809c83fd.css" target="_blank">http://su.bdimg.com/static/superpage/css/index_min_809c83fd.css</a>)</span></tt><br/>
				<tt>下载的文件保存在本程序的web目录下</tt><br/>
			</div>
			<div>
				<form action="" method="post" name="myform">
					<input type="text" name="weburl" value="" class="input" />
					<button>开始下载</button> 
				</form>
			</div>
			<div><?php include_once ('do.php');?></div>
		</div>
	</body>
</html>
