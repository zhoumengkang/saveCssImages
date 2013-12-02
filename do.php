<?php
set_time_limit(0);
echo '<pre>';
if($_POST["weburl"]!=Null){
	//1.下载css
	$url=$_POST["weburl"];
	if(!file_exists("web")){
		mkdir("web",0777,true);
	}
	$filename="web/css.txt";
	downCss($url,$filename);
	//2.获取css中图片保存的相对路径
   	$baseUrl=getBaseUrl($url);
	//3.获取css中的图片地址
	$img=getImgUrl($filename,$baseUrl);
	//5.下载图片保存到原网页中“相同的路径”
	saveImg($img);
	unset($_POST);
}
/**********************************
*优化下载函数
*$url 			原文件地址
***********************************/
function download($url){
	$opts = array(
		'http'=>array(
	  		'method' => "GET",
	  		'timeout' => 3, //超时30秒
	  		'user_agent'=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
	 	)
	);
	$context = stream_context_create($opts);
	return $data = file_get_contents($url, false, $context);
}
/**********************************
*下载css文件
*$url 			原文件地址
*$filename 		文件名
***********************************/
function downCss($url,$filename){
	$data=download($url);
	$res=file_put_contents($filename,$data);
	if($res){
		echo $url.'</a><font color="green">下载成功</font><br/>';
	}else{
		echo $url.'</a><font color="red">下载失败</font><br/>';
		exit();
	}
}
/**********************************
*获取baseUrl函数
*$url			post获得的css地址
*$baseUrl 		用于后面与css中的调用的图片地址拼接
***********************************/
function getBaseUrl($url){
	$baseUrl[0]=dirname($url);//当前css所在路径
	$baseUrl[1]=parse_url($url,PHP_URL_SCHEME).'://'.parse_url($url,PHP_URL_HOST);//网站域名
	return $baseUrl;
}
/**********************************
*获取css文件中的图片地址
*$url 			原文件地址
*$baseUrl 		与css中url()中的图片地址拼接的相对路径（数组）
***********************************/
function getImgUrl($filename,$baseUrl){
	$file=fopen($filename,'r');
	while(!feof($file)){
		$content.=fread($file,1024);
	}
	$arr=explode('url(',$content);
	array_shift($arr);
	foreach($arr as $k => &$v){
		$a=explode(')',$v);
		$v=trim(trim($a[0],'"'),"'");//去掉单双引号
		if(preg_match('/\?/',$v)){//去掉标记
			$change=explode('?',$v);
			$v=$change[0];
		}
		if(preg_match('/^http/',$v)){
			//完整路径
			continue;
		}else{
			if(preg_match('/^\//',$v)){
				$v=$baseUrl[1].$v;//绝对路径
			}else{
				$v=$baseUrl[0].'/'.$v;//c相对路径
			}
		}
	}
	echo '<br/>所有的图片地址如下：<br/>';
	$img=array_unique($arr);
	unset($v);
	$n = 1;
	echo '<table>';
	foreach ($img as $value) {
		echo '<tr><td>['.$n.']</td><td>'.$value.'</td></tr>';
		$n += 1;
	}
	echo '</table><br/>';
	return $img;
}
/**********************************
*下载图片
*$img 			文件存放路径和图片的个数加1组成的数组
***********************************/
function saveImg($img){
	//把协议头（http://）换成web文件夹
	$filename=preg_replace('/http:\/\//','web/',$img);	//图片保存路径
	foreach($img as $i=>$v){
		$a = dirname ($filename[$i]); //类似于 web/su.bdimg.com/static/superpage/css/../img 直接mkdir会报错
		if (!file_exists($a)) {
			if (preg_match('/\.\./',$a)) {
				if(zmkMakeDir($a)){
					echo './'.$a.'<font color="green">路径创建成功</font><br/>';
				}else{
					echo './'.$a.'<font color="red">路径创建失败</font><br/>';
				}	
			}else{
				if(mkdir($a,0777,true)){
					echo './'.$a.'<font color="green">路径创建成功</font><br/>';
				}else{
					echo './'.$a.'<font color="red">路径创建失败</font><br/>';
				}
			}
		}
		$c=file_put_contents($filename[$i],download($v));
		if($c){
			echo './'.$filename[$i].'<font color="green">下载成功</font><br/>';
		}else{
			echo './'.$filename[$i].'<font color="red">下载失败</font><br/>';
		}
	}
}
/**********************************
*路径处理函数
*$path 			eg:"web/su.bdimg.com/static/superpage/css/../../images";
***********************************/
function zmkMakeDir($pathOld){
	$path = explode('/', $pathOld);
	$newPath = ".";
	while ($str = array_shift($path)){
		$newPath.='/'.$str;
		if (!file_exists($newPath)) {
			mkdir($newPath,0777,true);
		}
	}
	return file_exists($pathOld);
}