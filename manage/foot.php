<div class="page-footer clearfix">
	<div class="page-footer-inner">服务电话：400-882-0608 电子邮件：kf@ibw.cn</div>
	<div class="scroll-to-top"><i class="icon-arrow-up"></i></div>
</div>
<script src="themes/plugins/jquery.min.js"></script>
<script src="themes/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="themes/plugins/uniform/jquery.uniform.min.js"></script>
<script src="themes/plugins/datatable.js"></script>
<script src="themes/plugins/datatables/datatables.min.js"></script>
<script src="themes/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script>
$(function(){
	var $uniformed = $("body").find(".checkbox");
	$uniformed.uniform();
})
</script>
<script src="themes/js/app.min.js" type="text/javascript"></script>
<script src="themes/js/layout.min.js" type="text/javascript"></script>
<script>
var TableDatatablesManaged = function() {
	var e = function() {
		var e = $(".listTable");
		e.dataTable({
			 paging: false,
			  searching: false,
	    		ordering:  false,
	    	scrollX:false
		});
		e.find(".group-checkable").change(function() {
			var e = jQuery(this).attr("data-set"),
			a = jQuery(this).is(":checked");
			jQuery(e).each(function() {
				a ? ($(this).prop("checked", !0), $(this).parents("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).parents("tr").removeClass("active"))
			}),
			jQuery.uniform.update(e)
		}),
		e.on("change", "tbody tr .checkbox",
		function() {
			$(this).parents("tr").toggleClass("active")
		})
	};
	return {
		init: function() {
			jQuery().dataTable && (e())
		}
	}
} ();
TableDatatablesManaged.init()
</script>

<?
	$db->close();
?>