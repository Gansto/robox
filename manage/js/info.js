//一、二、三级栏目联动相关
function baseIdChange(str)
{
	var i;

	var baseObj		= document.getElementById(str + "_base_id");
	var secondObj	= document.getElementById(str + "_second_id");
	var thirdObj	= document.getElementById(str + "_third_id");

	
	var base_id		= baseObj.options[baseObj.selectedIndex].value;

	secondObj.options.length = 0;
	secondObj.options[0] = new Option("请选择二级栏目", "0");
	thirdObj.options.length = 0;
	thirdObj.options[0] = new Option("请选择三级栏目", "0");
	thirdObj.style.display = "none";

	for (i = 0; i < secondClass.length; i++)
	{
		if (secondClass[i][3] == base_id)
		{
			secondObj.options[secondObj.options.length] = new Option(secondClass[i][1], secondClass[i][0]);
		}
	}
}

function secondIdChange(str)
{
	var i, third_state;
	var secondObj	= document.getElementById(str + "_second_id");
	var thirdObj	= document.getElementById(str + "_third_id");
	var second_id	= secondObj.options[secondObj.selectedIndex].value;

	thirdObj.options.length = 0;
	thirdObj.options[0] = new Option("请选择三级栏目", "0");
	thirdObj.style.display = "none";

	if (second_id > 0)
	{
		for (i = 0; i < secondClass.length; i++)
		{
			if (secondClass[i][0] == second_id)
			{
				third_state = secondClass[i][2];
				break;
			}
		}

		if (third_state == 1)
		{
			thirdObj.style.display = "inline";

			for (i = 0; i < thirdClass.length; i++)
			{
				if (thirdClass[i][2] == second_id)
				{
					thirdObj.options[thirdObj.options.length] = new Option(thirdClass[i][1], thirdClass[i][0]);
				}
			}
		}
	}
}

function setClass(base_id, second_id, third_id, str)
{
	var i, third_state;

	var baseObj		= document.getElementById(str + "_base_id");
	var secondObj	= document.getElementById(str + "_second_id");
	var thirdObj	= document.getElementById(str + "_third_id");

	if (base_id > 0)
	{
		for (i = 0; i < baseObj.options.length; i++)
		{
			if (baseObj.options[i].value == base_id)
			{
				baseObj.options[i].selected = true;
				baseIdChange(str);
				break;
			}
		}
	}

	if (base_id > 0 && second_id > 0)
	{
		for (i = 0; i < secondObj.options.length; i++)
		{
			if (secondObj.options[i].value == second_id)
			{
				secondObj.options[i].selected = true;
				secondIdChange(str);
				break;
			}
		}
	}

	if (base_id > 0 && second_id > 0 && third_id > 0)
	{
		for (i = 0; i < thirdObj.options.length; i++)
		{
			if (thirdObj.options[i].value == third_id)
			{
				thirdObj.options[i].selected = true;
				break;
			}
		}
	}
}

function checkClass(str)
{
	var baseObj		= document.getElementById(str + "_base_id");
	var secondObj	= document.getElementById(str + "_second_id");
	var thirdObj	= document.getElementById(str + "_third_id");

	if (baseObj.options[baseObj.selectedIndex].value == "0")
	{
		alert("请选择一级栏目！");
		baseObj.focus();
		return false;
	}
	if (secondObj.options[secondObj.selectedIndex].value == "0")
	{
		alert("请选择二级栏目！");
		secondObj.focus();
		return false;
	}
	if (thirdObj.options.length > 1 && thirdObj.options[thirdObj.selectedIndex].value == "0")
	{
		alert("请选择三级栏目！");
		thirdObj.focus();
		return false;
	}
	return true;
}

function checkDateBeginToEnd(form)
{
	if (form.search_begin_date.value != "" && !isDate(form.search_begin_date.value))
	{
		form.search_begin_date.focus();
		alert("开始时间格式不正确");
		return false;
	}
	
	if (form.search_end_date.value != "" && !isDate(form.search_end_date.value))
	{
		form.search_end_date.focus();
		alert("截止时间格式不正确");
		return false;
	}

	return true;
}

//检查参数是否是日期
function isDate(str)
{
	//解决某些服务器控制面板可能设置不同日期格式的问题。
	str = str.replace(/\//g, "-");
	str = str.replace(/\./g, "-");

	var date = str.split("-");
	if (date.length != 3)
	{
		return false;
	}

	var parseDate = new Date(date[0], date[1] - 1, date[2]);
	if (!parseDate || isNaN(parseDate))
	{
		return false;
	}

	if (parseDate.getFullYear() != date[0] || parseDate.getMonth() + 1 != date[1] || parseDate.getDate() != date[2])
	{
		return false;
	}

	return true;
}

//批量替换类型
function defaultPath(oldpath)
{
	$('input[name="replace_type"]').click(
		function(){
			if($(this).val() == "1")
			{
				$('input[name="new_keyword"]').attr("value", "/upload/");
				$('input[name="old_keyword"]').attr("value", oldpath);
				$(".common").hide();
			}
			else
			{
				$('input[name="new_keyword"]').attr("value", "");
				$('input[name="old_keyword"]').attr("value", "");
				$(".common").show();
			}
		}	
	);
}