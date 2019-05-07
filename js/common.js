//屏蔽鼠标右键
var jsArgument = document.getElementsByTagName("script")[document.getElementsByTagName("script").length-1].src;	//获取传递的参数
rightButton = jsArgument.substr(jsArgument.indexOf("rightButton=") + "rightButton=".length, 1);
if (rightButton == "1"){
	document.oncontextmenu = function(e){return false;}
	document.onselectstart = function(e){return false;}
	if (navigator.userAgent.indexOf("Firefox") > 0){
		document.writeln("<style>body {-moz-user-select: none;}</style>");
	}
}

// 手机站自动跳转
mobilejump = jsArgument.substr(jsArgument.indexOf("mobilejump=") + "mobilejump=".length, 1);
if (mobilejump == "1"){
	var url = window.location.href;
	if(url.slice(url.lastIndexOf("/")+1)=="index.php"){
		url = url.slice(0,url.lastIndexOf("/")+1);
	}
	var isAndroid = navigator.appVersion.toLowerCase().indexOf("android") >= 0,
	isIphone = navigator.appVersion.toLowerCase().indexOf("iphone") >= 0;
	if (isAndroid || isIphone){
	    window.location.href = url +"m";
	};
}

// 设为首页
function setHome(obj, vrl){
	try{
		obj.style.behavior='url(#default#homepage)';
		obj.setHomePage(vrl);
	}catch(e){
		if(window.netscape){
			try{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}catch (e){
				alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将[signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
				return;
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage', vrl);
		}else{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+vrl+"】设置为首页。");
        }
	}
}


// 加入收藏
function addFavorite(sURL, sTitle){
	try{
		window.external.addFavorite(sURL, sTitle);
	}catch (e){
		try{
			window.sidebar.addPanel(sTitle, sURL, "");
		}catch (e){
			alert("您使用的浏览器不支持此功能，请使用Ctrl+D进行添加");
		}
	}
}

// 保存到桌面
function toDesktop(sUrl,sName){
	try {
	    var WshShell = new ActiveXObject("WScript.Shell");
	    var oUrlLink = WshShell.CreateShortcut(WshShell.SpecialFolders("Desktop") + "\\" + sName + ".url");
	    oUrlLink.TargetPath = sUrl;
	    oUrlLink.Save();
	}catch(e){
	    alert("当前IE安全级别不允许操作！");
	}
}

// 浏览器版本
$(function(){
    var DEFAULT_VERSION = "8.0";
    var ua = navigator.userAgent.toLowerCase();
    var isIE = ua.indexOf("msie")>-1;
    var safariVersion;
    if(isIE){
        safariVersion =  ua.match(/msie ([\d.]+)/)[1];
        if(safariVersion <= DEFAULT_VERSION ){
            $("body").append("<div id='ie' style='position:fixed;top:0;left:0;z-index:999999;width:100%;padding:8px 0;background:#eee;color:#000;line-height:24px;text-align:center;font-size:16px;opacity:0.7;filter:alpha(opacity=70);'>您当前使用的IE浏览器版本过低，为了您的浏览体验，建议您升级到<a style='color:#f00;font-weight:bold;' href='https://www.microsoft.com/zh-cn/download/internet-explorer.aspx' title='点击下载最新IE浏览器' target='_blank'> IE9+ </a>或者使用<a style='color:#f00;font-weight:bold;' href='http://www.firefox.com.cn/' title='点击下载最新firefox浏览器' target='_blank'> firefox </a>,<a style='color:#f00;font-weight:bold;' href='http://www.chromeliulanqi.com/' title='点击下载最新chrome浏览器' target='_blank'> chrome </a>最新版本！<i onclick='$(this).parent().hide();' style='display:inline-bolck;*display:inline;zoom:1;width:15px;height:15px;background:#000;color:#fff;font-size:12px;line-height:15px;font-family:arial;vertical-align:middle;cursor:pointer;opacity:1;filter:alpha(opacity=100);' title='点击关闭'>X</i></div>");
        }
    }
})

// 回到顶部
$(document).ready(function(){
	$(window).scroll(function(){
		var scrollH = $(window).scrollTop();
 		if(scrollH>300){
 			$(".back-to").fadeIn();
 		}else{
 			$(".back-to").fadeOut();
 		}
	})
	$(".back-to").on('click',function(){
		$('html,body').animate({'scrollTop':0},500);
	});
})

// 在线QQ
$(function(){
	var m_woo_panel = $('#m-woo-panel');
	$('#j-close').on('click',function(){
		m_woo_panel.hide();
	});
	$('#j-console').on("click",function(){
		if($(this).hasClass("m-console-active")){
			$(this).removeClass("m-console-active");
			m_woo_panel.animate({right:0});
		}else{
			$(this).addClass("m-console-active");
			m_woo_panel.animate({right:-164});
		};
	});
});


// QQ滚动特效
$(document).ready(function(){
	$(".online_qq").floatadv();
});

jQuery.fn.floatadv = function(loaded) {
	var obj = this;
	body_height = parseInt($(window).height());
	block_height = parseInt(obj.height());

	top_position = parseInt((body_height/2) - (block_height/2) + $(window).scrollTop());

	if (body_height<block_height) { top_position = 0 + $(window).scrollTop(); };

	if(!loaded) {
		obj.css({'position': 'absolute'});
		obj.css({ 'top': top_position });
		$(window).bind('resize', function() {
			obj.floatadv(!loaded);
		});
		$(window).bind('scroll', function() {
			obj.floatadv(!loaded);
		});
	} else {
		obj.stop();
		obj.css({'position': 'absolute'});
		obj.animate({ 'top': top_position }, 400, 'linear');
	}
}


//设置字体大小
function setFontSize(size)
{
	var obj = document.getElementById("contentSize");
	if (obj)
	{
		obj.style.fontSize = size + "px";
	}
}