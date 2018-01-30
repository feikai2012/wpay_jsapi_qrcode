<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";

$tools = new JsApiPay();

$openId = $tools->GetOpenid();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<!-- 控制浏览器缓存 -->
<meta http-equiv="Cache-Control" content="no-store" />
<!-- 优先使用 IE 最新版本和 Chrome -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>输入支付金额</title>

<style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	
	html,
	body {
		height: 100%;
		overflow: hidden;
	}
	
	.clearfix:after {
		content: "\200B";
		display: block;
		height: 0;
		clear: both;
	}
	
	.clearfix {
		*zoom: 1;
	}
	
	
	/*IE/7/6*/
	
	.shuru div::-webkit-scrollbar {
		width: 0;
		height: 0;
		-webkit-transition: 1s;
	}
	
	.shuru div::-webkit-scrollbar-thumb {
		background-color: #a7afb4;
		background-clip: padding-box;
		min-height: 28px;
	}
	
	.shuru div::-webkit-scrollbar-thumb:hover {
		background-color: #525252;
		background-clip: padding-box;
		min-height: 28px;
	}
	
	.shuru div::-webkit-scrollbar-track-piece {
		background-color: #ccd0d2;
	}
	
	.wrap {
		position: relative;
		margin: auto;
		max-width: 640px;
		min-width: 320px;
		width: 100%;
		height: 100%;
		background: #F0EFF5;
		overflow: hidden;
	}
	
	.layer-content {
		position: absolute;
		left: 50%;
		bottom: -200px;
		width: 100%;
		max-width: 640px;
		height: auto;
		z-index: 12;
		-webkit-transform: translateX(-50%);
		transform: translateX(-50%);
	}
	
	/* 输入表单 */
	
	.edit_cash {
		display: block;
		margin-top: 15px;
		padding: 15px;
		margin: 0 auto;
		width: 90%;
		border: 1px solid #CFCFCF;
		border-radius: 10px;
		background-color: #fff;
	}
	
	.edit_cash p {
		font-size: 14px;
		color: #8D8D8F;
	}
	
	.shuru {
		position: relative;
		margin-bottom: 10px;
	}
	
	.shuru div {
		border: none;
		width: 100%;
		height: 50px;
		font-size: 25px;
		line-height: 50px;
		border-bottom: 1px solid #CFCFCF;
		text-indent: 30px;
		outline: none;
		white-space: pre;
		overflow-x: scroll;
	}
	
	.shuru span {
		position: absolute;
		top: 5px;
		font-size: 25px;
	}
	
	.submit {
		display: block;
		margin: 20px auto 0;
		width: 90%;
		height: 40px;
		font-size: 16px;
		color: #fff;
		background: #80D983;
		border: 1px solid #47D14C;
		border-radius: 3px;
	}
	
	
	/* 键盘 */
	
	.form_edit {
		width: 100%;
		background: #D1D4DD;
	}
	
	.form_edit> div {
		margin-bottom: 2px;
		margin-right: 0.5%;
		float: left;
		width: 33%;
		height: 45px;
		text-align: center;
		color: #333;
		line-height: 45px;
		font-size: 18px;
		font-weight: 600;
		background-color: #fff;
		border-radius: 5px;
	}
	
	.form_edit> div:nth-child(3n) {
		margin-right: 0;
	}
	
	.form_edit> div:last-child {
		background-color: #DEE1E9;
	}
</style>
</head>
<body>

<div class="wrap">
	<form action="jsapi.php" class="edit_cash" method="post">
		<p>消费总额</p>
		<div class="shuru">
			<span>&yen;</span>
			<div id="div"></div>
		</div>
		<p>可询问工作人员应缴费用总额</p>
		<input type="hidden" name="paynum" id="paynum" value=""/>
		<input type="hidden" name="openId" value="<?php echo $openId?>"/>
		<input type="submit" value="支付" class="submit" />
	</form>
	
</div>
<div class="layer"></div>
<div class="form">
	<form action="" method="post">
		<input type="text" placeholder="姓名" />
		<input type="text" placeholder="联系电话" />
		<input type="submit" value="提交" class="infor-sub" />
	</form>
</div>
<div class="layer-content">
	<div class="form_edit clearfix">
		<div class="num">1</div>
		<div class="num">2</div>
		<div class="num">3</div>
		<div class="num">4</div>
		<div class="num">5</div>
		<div class="num">6</div>
		<div class="num">7</div>
		<div class="num">8</div>
		<div class="num">9</div>
		<div class="num">.</div>
		<div class="num">0</div>
		<div id="remove">删除</div>
	</div>
</div>

<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script>
	$(function(){
		
		//填写信息
		$('.infor-sub').click(function(e){
			$('.layer').hide();
			$('.form').hide();
			e.preventDefault();		//阻止表单提交
		})
		// 监听#div内容变化，改变支付按钮的颜色
		$('#div').bind('DOMNodeInserted', function(){
			if($("#div").text()!="" || $("#div").text()>'0'){
				$('.submit').removeClass('active');
				$('.submit').attr('disabled', false);
			}else{
				$('.submit').addClass('active');
				$('.submit').attr('disabled', true);
			}
		})
		
		$('#div').trigger('DOMNodeInserted');
		
		$('.shuru').click(function(e){
			$('.layer-content').animate({
				bottom: 0
			}, 200)
			e.stopPropagation();
		})
		$('.wrap').click(function(){
			$('.layer-content').animate({
				bottom: '-200px'
			}, 200)
		})
		
		$('.form_edit .num').click(function(){
			var oDiv = document.getElementById("div");
			oDiv.innerHTML += this.innerHTML;
		})
		$('#remove').click(function(){
			var oDiv = document.getElementById("div");
			var oDivHtml = oDiv.innerHTML;
			oDiv.innerHTML = oDivHtml.substring(0,oDivHtml.length-1);
		})

		
		$('.num').click(function(){
			$("#paynum").val($("#div").html());
		});
	})
	
</script>

</body>
</html>