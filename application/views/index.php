<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
<title>Abot 可以自动成长的聊天机器人，基于CI，mysql开发。</title>
<link type="text/css" href="<?php echo base_url();?>static/css/style.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.js"></script>
<script type="text/javascript">
function submit(){
    var words = $('textarea').val();
    var author = $('.nickname').val();
    //判断字数
	if(words.length < 1){
        alert('不能发送空字符！');
        $('textarea').focus();
        return false;
    } else if (words > 50) {
        alert('不能大于50个字符！');
        $('textarea').focus();
        return false;    	
    }
    
    var time = new Date();
    var lastsecond = $('.lastsecond').val(); //判断秒来限制发送速度
    
    if(parseInt(Math.round(time.getTime()/1000) - parseInt(lastsecond)) < 2){
    	alert('不要发送太快哦，歇一会吧。');
    	return false; 
    }
    //把内容转移到聊天框
    var wordsline = '<div class="wordsline isay"><p class="time">'+  ('0' + time.getHours()).slice(-2) + ":" +('0' + time.getMinutes()).slice(-2) +'</p><div class="thewords"><p>'+ words +'</p><span class="triangle"></span></div></div>';
    $('textarea').val('');
	$('.center').append(wordsline);
	$('textarea').focus();
	scroll();
	$('.lastsecond').val(Math.round(time.getTime()/1000)); //更新下秒数

    //去查询
    setTimeout(function(){query(words, author);}, 500);
}

//异步查询
function query (words, author) {
	$.ajax({
		type : "GET",
  		url : "<?php echo site_url('index/submit');?>",
  		data : "words=" + words + "&author=" + author,
  		success : function(data){
    		data = eval('('+data+')');
      		addhtml(data.answer, data.author);
        }
    }) 	
}

//插入数据
function addhtml (words, author) {
	var time = new Date();
	var now = ('0' + time.getHours()).slice(-2) + ":" +('0' + time.getMinutes()).slice(-2);
	if (author) {
		author = ' (<span>@'+ author +'</span> 教我的)';
	}
 	$('.center').append('<div class="wordsline abotsay"><p class="time">'+ now +'</p><div class="thewords"><p>'+ words + author +'</p><span class="triangle"></span></div></div>');
    scroll();	
}

function scroll(){ //滚动条
	var theScroll = document.getElementById('center');
	$('.center').animate({scrollTop: theScroll.scrollHeight + 'px'});	
}

function EnterPress(e){ //传入 event
    var e = e || window.event; 
    if(e.keyCode == 13&&e.ctrlKey){ 
        submit();
    } 
} 

$(function(){
	$('.wordsline .thewords span.learn').live('click', 
		function(){
			$('textarea').val('问：'+ $(this).parents('.abotsay').prev('.isay').children('.thewords').children('p').html() +' 答：');
		}
	)
	$('.wordsline .thewords span.translate').live('click', 
		function(){
			$('textarea').val('翻译：');
		}
	)	
})
</script>
</head>

<body>
<div id="container">
    <div id="content">
        <div class="box">
        	<img src="<?php echo base_url();?>static/images/avatar.gif" class="avatar" />
        	<div class="top">
				<span>小A</span>
				<input type="text" value="" title="设置一个昵称吧" placeholder="点击设置昵称" class="nickname" />
			</div>
			<div class="center" id="center">
				<div class="wordsline abotsay"><p class="time">刚刚</p><div class="thewords"><p>Hello，我叫小A，试着随便跟我聊点什么吧，多多聊天才能帮我快速成长。</p><span class="triangle"></span></div></div>
			</div>
			<div class="bottom">
			<input type="hidden" class="lastsecond" value="0" />
				<div class="bottombar"></div>
				<textarea onkeypress="EnterPress()" onkeydown="EnterPress()"></textarea>
				<div class="copyright"><div class="enter"><em class="enterbtn" title="Enter+Ctrl键快捷发送" onclick="submit();">发 送</em>Enter+Ctrl<span class="num"></span></div><a href="http://abot.linauror.com" target="_blank">Power By Abot</a></div>
			</div>
        </div>
        <div class="wpslider">
	        <ul id="slider">
	        	<li>2012.12.22：增加了设置昵称，并输出昵称的功能</li>
	        	<li>2012.12.22：增加了翻译功能</li>
				<li>2012.12.20：增加了自动学习的功能</li>
			</ul>
		</div>
    </div>
</div>
<script type="text/javascript">
function H$(i) {return document.getElementById(i)}
function H$$(c, p) {return p.getElementsByTagName(c)}
var slider = function () {
	function init (o) {
		this.id = o.id;
		this.at = o.auto ? o.auto : 3;
		this.o = 0;
		this.pos();
	}
	init.prototype = {
		pos : function () {
			clearInterval(this.__b);
			this.o = 0;
			var el = H$(this.id), li = H$$('li', el), l = li.length;
			var _t = li[l-1].offsetHeight;
			var cl = li[l-1].cloneNode(true);
			cl.style.opacity = 0; cl.style.filter = 'alpha(opacity=0)';
			el.insertBefore(cl, el.firstChild);
			el.style.top = -_t + 'px';
			this.anim();
		},
		anim : function () {
			var _this = this;
			this.__a = setInterval(function(){_this.animH()}, 20);
		},
		animH : function () {
			var _t = parseInt(H$(this.id).style.top), _this = this;
			if (_t >= -1) {
				clearInterval(this.__a);
				H$(this.id).style.top = 0;
				var list = H$$('li',H$(this.id));
				H$(this.id).removeChild(list[list.length-1]);
				this.__c = setInterval(function(){_this.animO()}, 20);
				//this.auto();
			}else {
				var __t = Math.abs(_t) - Math.ceil(Math.abs(_t)*.07);
				H$(this.id).style.top = -__t + 'px';
			}
		},
		animO : function () {
			this.o += 2;
			if (this.o == 100) {
				clearInterval(this.__c);
				H$$('li',H$(this.id))[0].style.opacity = 1;
				H$$('li',H$(this.id))[0].style.filter = 'alpha(opacity=100)';
				this.auto();
			}else {
				H$$('li',H$(this.id))[0].style.opacity = this.o/100;
				H$$('li',H$(this.id))[0].style.filter = 'alpha(opacity='+this.o+')';
			}
		},
		auto : function () {
			var _this = this;
			this.__b = setInterval(function(){_this.pos()}, this.at*1000);
		}
	}
	return init;
}();
new slider({id:'slider'})
</script>
</body>
</html>
