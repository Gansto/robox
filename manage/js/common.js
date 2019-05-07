$(function(){
	BindTooltip();
	BindPic();
});
//============================================================================================

//问号提示框
function BindTooltip() {
	$(".tooltip").hover(
		function(){
			var span = $(this).find("span");
			if (span.length < 1)
			{
				$(this).append("<span>" + GetTooltipDescription($(this).attr("tip")) + "</span>");
				span = $(this).find("span");
			}
			span.show();
		},
		function(){ $(this).find("span").hide(); }
	);

	$(".tooltip").mousemove(function(e) {
		var span = $(this).find("span");
		var width = span.width() + 85;
		var height = span.height() + 20;

		var mousex, mousey

		if ($(this).attr("position") == "top")
		{
			mousex = e.pageX + 10;
			mousey = e.pageY - 60;
		}
		else
		{
			mousex = e.pageX + 10;
			mousey = e.pageY + 5;
		}

		if (mousex + width > GetPageWidth())
			mousex -= width;
		if (mousey + height > GetPageHeight())
			mousey -= height;

		span.css({ top: mousey, left: mousex });
	});
}

//图片预览
function BindPic()
{
	$('table.listTable a.picView').hover(
		function(){
			if ($(this).attr("picUrl") != "")
			{
				var span = $(this).find("span");
				if (span.length < 1)
				{
					$(this).append("<span><img src='" + $(this).attr("picUrl") + "' width='100' /></span>");
					span = $(this).find("span");
				}
				span.show();
			}
		},
		function(){ $(this).find("span").hide(); }
	);

	$('table.listTable a.picView').mousemove(function(e) {
		var span = $(this).find("span");
		var width = span.width() + 40;
		var height = span.height() + 20;

		var mousex, mousey

		mousex = e.pageX + 10;
		mousey = e.pageY + 5;

		if (mousex + width > GetPageWidth())
			mousex -= width;
		if (mousey + height > GetPageHeight())
			mousey -= height;

		span.css({ top: mousey, left: mousex });
	});
}

//tooltip提示文本
function GetTooltipDescription(tip) {
	switch (tip)
	{
		case "sortnum_asc"				: return "显示顺序，按从小到大顺序排列。"
		case "sortnum_desc"				: return "显示顺序，按从大到小顺序排列。"

		case "is_viewed"				: return "信息是否已经查看。"
		case "is_visible"				: return "信息是否在网站前台显示。"

		case "contact_dept_email"		: return "网站浏览者对部门的留言会自动发送一封邮件到指定的部门邮箱。"
		case "contact_dept_showForm"	: return "用户查看部门信息时，是否显示留言表单。"

		case "job_email"				: return "网站浏览者对职位的应聘留言会自动发送一封邮件到指定的联系邮箱。"
		case "job_showForm"				: return "用户查看职位信息时，是否显示留言表单。"

		case "message_sortnum"			: return "显示顺序，按置顶、显示、不显示的顺序排列，显示状态相同时按序号从大到小顺序排列。"
		case "message_state"			: return "该条留言是否在网站前台显示以及置顶。"

		case "adver_kind"				: return "广告内容采用哪种效果在前台网页展现。单击类型名称可查看广告效果示例。"
		case "adver_size"				: return "广告图片或广告动画显示的尺寸大小，如100*200，即宽100像素，高200像素。"
		case "adver_visible"			: return "广告是否在网站前台显示。只有上传了广告文件选择显示后才可以看到效果。"
		case "adver_autoclose"			: return "单击广告后，广告效果是否关闭。"
		case "adver_onlydefault"		: return "广告仅在网站首页显示。"
		case "adver_showonce"			: return "在网站被关闭之前，如刷新、后退等操作时，广告不会重复显示。"

		case "banner_title"				: return "标志一个动画效果，在选择栏目的banner动画时会以此处的动画标题显示备选。"
		case "banner_kind"				: return "动画效果采用哪种特效类型，默认备选的有三种，也可以自行上传。单击类型名称可查看动画效果示例。"
		case "banner_size"				: return "动画效果的尺寸大小，如100*200，即宽100像素，高200像素。"
		case "banner_bg"				: return "动画效果的背景颜色，单击色块选择颜色，或直接输入如 #EEEEEE 的颜色代码。"
		case "banner_pic"				: return "如选择直接上传动画，请在此处上传动画文件；如选择系统提供的默认动画，请在此处上传动画中包含的各张图片。"

		case "config_banner"			: return "如此处不选择动画，则使用“网站基本设置”中的默认Banner。如想选择新动画效果，请先在“栏目动画管理”中增加新动画文件。"

		case "admin_state"				: return "状态为正常时，管理员可以正常登录、管理后台，禁用时无法使用该账号登录后台。"
		case "admin_info"				: return "当分配了某个栏目权限时（如某一级栏目），该账号拥有该栏目所有子类的权限（拥有该一级栏目下所有二级、三级栏目权限），不拥有其他同级栏目及其他栏目子类的权限。"
		case "admin_function"			: return "系统管理员无需分配，自动具有所有高级权限，其他等级管理员均需分配才拥有相应的高级权限。"

		case "info_state"				: return "该信息是否显示及置顶。"
		case "info_publishdate"			: return "该信息的发布时间。"
		case "info_title_style"			: return "该信息标题的样式，需要突出显示时设置，一般情况下不需要设置，防止显示样式混乱。"
		case "info_target"				: return "该信息是用新窗口打开还是在原窗口打开。"
		case "info_member_grade"		: return "网站浏览者对该信息的访问权限，默认对所有人开放。"
		case "info_views"				: return "该信息在前台被浏览者访问的总次数。"
		case "info_product_no"			: return "仅针对在线订购使用，显示在购物车中，不需要请留空。"
		case "info_price"				: return "显示在图片列表标题下方，不需要可留空。"
		case "info_url"					: return "该信息的链接地址，不填写则使用默认链接。"
		case "info_admin_name"			: return "该信息的发布人。"
		case "info_third_class"			: return "该信息所属三级分类。"
		case "info_preview"				: return "在前台预览该文章最终效果。"

		case "keywords"					: return "meta关键字，不显示，仅用于搜索引擎优化。"
		case "description"				: return "meta描述，不显示，仅用于搜索引擎优化。"

		case "class_third_state"		: return "该栏目是否允许三级分类。"
		case "class_is_product"			: return "该栏目发布的信息是否显示在线订购按钮。"
		case "class_show_next"			: return "该栏目信息是否显示上一页，下一页的链接。"
		case "class_info_state"			: return "该栏目在前台的展现形式。"
		case "class_attribute"			: return "该栏目在前台展现形式的具体属性值。"
		case "class_member_grade"		: return "网站浏览者对该栏目的访问权限，默认对所有人开放。"

		case "default_from"				: return "该模块的信息来源，可以来源于栏目，或来源于自定义内容的编辑器中。"
		case "default_place"			: return "该模块在首页的显示位置。"
		case "default_show_header"		: return "该模块是否显示头部，即名称/标题部分。"
		case "default_info_state"		: return "该模块在首页的展现形式。"
		case "default_attribute"		: return "该模块在首页展现形式的具体属性值。"
		case "default_marquee"			: return "该模块的滚动参数设置，默认不滚动。建议首页模块的滚动效果不超过两处，否则可能影响首页运行速度。"
		case "default_more_url"			: return "该模块'更多'按钮的链接地址，只有显示头部的情况下启用，未指定则显示默认地址。"
		case "default_class_name"		: return "默认样式为siteL,此外可以使用siteM样式，后者的区别是新闻列表可以隔行变色，图片列表的间距较小。"

		case "operate"					: return "修改、删除等常用操作按钮。"
		default							: return tip;
	}
}

function GetPageHeight(){
	if($.browser.msie) {
		return document.compatMode == "CSS1Compat"? document.documentElement.clientHeight : document.body.clientHeight;
	} else {
		return self.innerHeight;
	}
}

function GetPageWidth(){
	if($.browser.msie) {
		return document.compatMode == "CSS1Compat"? document.documentElement.clientWidth : document.body.clientWidth;
	} else {
		return self.innerWidth;
	}
}

//上传工具窗
function pic(id, tableName, idName, picName, widthName, heightName)
{
	var str = "upload_pic.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;

	var p = window.open(str, "pic", "width=550, height=400, scrollbars=1, left=100, top=100");

}

function pic2(id, tableName, idName, picName, widthName, heightName)
{
	var str = "upload_pic2.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;

	var p = window.open(str, "pic2", "width=550, height=400, scrollbars=1, left=100, top=100");

}

function annex(id, tableName, idName, picName)
{
	var str = "upload_annex.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName;
	var p = window.open(str, "annex", "width=550, height=300, scrollbars=1, left=100, top=100");
}

function adver(id, tableName, idName, picName)
{
	var str = "upload_adver.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName;
	var p = window.open(str, "adver", "width=550, height=400, scrollbars=1, left=100, top=100");
}

function banner(id, tableName, idName, picName, widthName, heightName)
{
	var str = "upload_banner.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;

	var p = window.open(str, "banner", "width=550, height=400, scrollbars=1, left=100, top=100");

}

function banner1(id, tableName, idName, picName, widthName, heightName)
{
	var str = "upload_banner1.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;

	var p = window.open(str, "banner1", "width=550, height=400, scrollbars=1, left=100, top=100");

}

function style(picName)
{
	var str = "upload_style.php?picName=" + picName;
	var p = window.open(str, "style", "width=550, height=400, scrollbars=1, left=100, top=100");
}

function uploadInfoPic2(id, small_image_width, small_image_height)
{
	var str = "upload_info_pic2.php?id=" + id + "&small_image_width=" + small_image_width + "&small_image_height=" + small_image_height;
	var p = window.open(str, "info_pic2", "width=550, height=400, scrollbars=1, left=100, top=100");
}


// 检查操作
function operationCheck(obj)
{
	var hasChecked = false;

	if (!obj)
	{
		return false;
	}

	if (obj.length)
	{
		for (i = 0; i < obj.length; i++)
		{
			if (obj[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
	}
	else
	{
		hasChecked = obj.checked;
	}

	if (!hasChecked)
	{
		alert("请先选择准备操作的记录");
		return false;
	}

	if (document.listForm.operation.value == "")
	{
		alert("请选择操作选项");
		document.listForm.operation.focus();
		return false;
	}

	if (confirm("即将操作所有选择的记录, 且该操作不能恢复! 是否继续 ?"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function optionCheck()
{
	if (document.listForm.operation.value == "move" || document.listForm.operation.value == "copy")
	{
		document.getElementById("end_select").style.display = "block";
	}
	else
	{
		document.getElementById("end_select").style.display = "none";
	}
}

//检查是否选择了条目，并提示是否设置选中的条目的状态
function stateCheck(obj)
{
	var hasChecked = false;

	if (!obj)
	{
		document.getElementById("state").options[0].selected = true;
		return false;
	}

	if (obj.length)
	{
		for (i = 0; i < obj.length; i++)
		{
			if (obj[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
	}
	else
	{
		hasChecked = obj.checked;
	}

	if (!hasChecked)
	{
		alert('请先选择准备设置状态的记录');
		document.getElementById("state").options[0].selected = true;
		return false;
	}
	else
	{
		if (document.getElementById("state").options[document.getElementById("state").selectedIndex].value == "-1")
		{
			alert('请选择状态');
			return false;
		}


		return true;
	}
}