var rows;
var chklItems;
var chklAllSelect;
var scriptArgs = document.getElementById("list").getAttribute('data');

$(function(){
	rows				= $("table.listTable").find("tr").not(".thirdTr,.secondTr");
	//rows				= $("table.listTable").children().children();
	rows				= rows.slice(1, rows.length - 1);
	chklItems			= $("table.listTable").find(":checkbox[name='ids[]']");
	chklAllSelect		= $("table.listTable").find(":checkbox[name='chklAllSelect']");

	BindListTable();
	BindSelect();
	BindPoptip();
});


//============================================================================================
function BindListTable() {
	for (var i = 0; i < rows.length; i += 2) {
		rows[i].className = "Alternating";
	}
	if(scriptArgs == 'true')
	{
		rows.click(function(e) {
			$(this).toggleClass("Choose");
			if (e.target.tagName.toLowerCase() != "input")
			{
				var checkbox = $(this).find(":checkbox");
				checkbox.attr("checked", !checkbox.attr("checked"));
			}
			chklAllSelect.attr("checked", IsChklItemsAllSelected());
		});
	}

	rows.hover(
		function(){ $(this).addClass("Hover") },
		function(){ $(this).removeClass("Hover") }
	);
}

function BindSelect() {
	chklAllSelect.click(function(){
		SetChklItems($(this).attr("checked"));
	});

	$("#lnkAllSelect").click(function(){
		SetChklItems(!IsChklItemsAllSelected());
	});

	$("#lnkInverseSelect").click(function(){
		$.each(chklItems, function(){
			var checked = $(this).attr("checked");
			$(this).parent().parent().toggleClass("Choose", !checked);
			$(this).attr("checked", !checked);
		});

		chklAllSelect.attr("checked", IsChklItemsAllSelected());
	});
}

function SetChklItems(bool) {
	chklAllSelect.attr("checked", bool);
	chklItems.attr("checked", bool);
	rows.toggleClass("Choose", bool);
}

function IsChklItemsAllSelected() {
	return chklItems.length == GetSelectedCount();
}

function GetSelectedCount() {
	return $(":checkbox[name='ids[]']:checked").length;
}


//============================================================================================
function DeleteSomeConfirm() {
	//if (chklItems.length < 1)
//		return;

	if (GetSelectedCount() < 1) {
		alert("没有选择需要删除的记录，请先选择。");
		return;
	}

	return confirm("确定删除选中的记录吗？删除后不能恢复。");
}

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

function CheckSomeConfirm(tips) {
	if (tips == "")
		tips = "确定操作选中的记录吗？"

	//if (chklItems.length < 1)
//		return;

	if (GetSelectedCount() < 1) {
		alert("没有选择需要操作的记录，请先选择。");
		return;
	}

	return confirm(tips);
}


//============================================================================================
function BindPoptip() {
	$("a.listArrow").click(function(){
		var popupBody = $(this).parent().next(".popupBody");
		if (popupBody.is(":visible"))
		{
			$("dd.popupBody").hide();
		}
		else
		{
			$("dd.popupBody").hide();
			popupBody.show();
		}
	});

	$("p.closed").mousedown (
		function(){ $(this).parent().hide();
	});
}