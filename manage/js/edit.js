
$(function(){
	EditFocus();
	BindTabTable();
});


function DeleteConfirm() {
	return confirm("确定删除此记录吗？删除后不能恢复。");
}

function DeleteConfirmCustom(tips) {
	if (tips == "")
	{
		tips = "确定删除此记录吗？"
	}
	return confirm(tips);
}

function EditFocus() {
	if($('input[name="title"]').length){
		$('input[name="title"]').focus();
	} else if($('input[name="name"]').length){
		$('input[name="name"]').focus();
	} else if($('input[name="oldpass"]').length){
		$('input[name="oldpass"]').focus();
	}
}

function BindTabTable() {
	$('.tabTable').find("a").bind('click', function() {
		var num = $('.tabTable').find("a").index($(this));
		$('.editTable').hide();
		$('.editTable').eq(num).show();

		$('.tabTable').find("a").removeClass("current");
		$(this).addClass("current");
	});
}

function BindPopupDatepicker () {
	$(':input.popupDatepicker').datepick( {dateFormat: 'yyyy-m-d'} );
}

//根据指定的表单名切换标签
function Tab(field)
{
	var num = $('.editTable').index($('.editTable').has($(':input[name=' + field + ']')));
	$('.tabTable').find("a").eq(num).click();
}

//数字验证函数
function ValidateNumeric(numeric)
{
	return /^\d+$/.exec(numeric);
}

//正整数验证函数
function ValidatePositiveNumeric(numeric)
{
	return /^[1-9]\d*$/.exec(numeric);
}

//checkbox 必选
function CheckRadioNotEmpty(field, tips, isTab)
{
	if (tips == "") tips = "请选择记录状态。"

	var fieldObj = $('input:radio[name=' + field + ']:checked');

	if (fieldObj.val() == undefined)
	{
		alert(tips);
		if (isTab) Tab(field);
		fieldObj.focus();
		return false;
	}
	return true;
}

//支持切换标签功能的表单项验证函数, 验证必填
function CheckInputNotEmpty(field, tips, isTab)
{
	if (tips == "") tips = "请输入必填项。"

	var fieldObj = $(':input[name=' + field + ']');

	if (fieldObj.val() == "")
	{
		alert(tips);
		if (isTab) Tab(field);
		fieldObj.focus();
		return false;
	}
	return true;
}

//支持切换标签功能的表单项验证函数，验证数字类型
function CheckInputIsNumeric(field, isPositiveNumeric, tips, isTab)
{
	if (tips == "") tips = "请输入正确的数字。"

	var fieldObj = $(':input[name=' + field + ']');

	if (isPositiveNumeric ? !ValidatePositiveNumeric(fieldObj.val()) : !ValidateNumeric(fieldObj.val()))
	{
		alert(tips);
		if (isTab) Tab(field);
		fieldObj.focus();
		return false;
	}
	return true;
}
