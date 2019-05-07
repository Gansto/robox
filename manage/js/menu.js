$(function(){
	$("h2").click(function() {
        $(this).toggleClass("current").next("dl").slideToggle("fast");
		$(this).siblings("h3").next("dl").slideUp("fast");
        $(this).siblings("h3").removeClass("current");
		$("h3").next("dl").find("dd").slideUp("fast");
    });

	$("h3").click(function() {
		$(this).toggleClass("current").next("dl").slideToggle("fast").siblings("dl").slideUp("fast");
		$(this).next("dl").find("dd").slideUp("fast");
    });

    $("dt").click(function() {
		if($(this).next("dd").length > 0)
		{
			$(this).toggleClass("current").siblings("dd").slideToggle("fast");
			$(this).siblings("dt").removeClass("current");
		}
    });

	$("#logout").click(function() {
		if (confirm('确定要退出吗？')) return true; else return false;
	});

	if (menuBaseId > 0)
		$("#base" + menuBaseId).trigger("click");
	if (menuSecondId > 0)
		$("#second" + menuSecondId).trigger("click");
});