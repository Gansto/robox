
$(function(){
	NextPage();
	ViewTargetCheckbox();
});

function NextPage() {
	$('input:.lnkNext').click(function(){
		var value = $(':radio:checked').val();
		if(value == "info"){
			window.location = "base_class_edit.asp";
		} else if(value == "function"){
			window.location = "config_menu_link.asp?menuType=function";
		} else if(value == "link"){
			window.location = "config_menu_link.asp?menuType=link";
		}
	})
}

function ViewTargetCheckbox() {
	$('input[name = "state"]').click(function(){
		$('#target').toggle(this.checked);
	})
}