<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="author" content="http://sc.chinaz.com/jiaoben/" />
<title>jQuery精确到毫秒的倒计时代码 - 站长素材</title>

<script type="text/javascript" src="jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var times = 60 * 25 - 50; // 60秒
		countTime = setInterval(function() {
			times = --times < 0 ? 0 : times;

			var min = Math.floor(times / 60).toString();

			if(min.length <= 1) {
				min = "0" + min;
			}

			var ms = Math.floor(times % 60).toString();

			if(ms.length <= 1) {
				ms = "0" + ms;
			}

			if(times == 0) {
				alert("任务结束");
				clearInterval(countTime);
			}
			// 获取分钟、毫秒数
			$(".c").html(min);
			$(".a").html(ms);
		}, 1000);
	});
</script>
<style>
	.warp{
		width: 100%;
		height: 100px;
		line-height: 100px;
		text-align: center;
		font-size: 40px;
		font-family: "微软雅黑";
	}
	.warp strong{
		width: 100px;
		display: inline-block;
		text-align: center;
		font-family: georgia;
		color: #C9302C;
	}
</style>
</head>

<body>
<div class="warp">
	<strong class="c"></strong>分 <strong class="a"></strong>秒
</div>

<div style="text-align:center;margin:50px 0; font:normal 14px/24px 'MicroSoft YaHei';">
<p>适用浏览器：360、FireFox、Chrome、Safari、Opera、傲游、搜狗、世界之窗. 不支持IE8及以下浏览器。</p>
<p>来源：<a href="http://sc.chinaz.com/" target="_blank">站长素材</a></p>
</div>
</body>
</html>