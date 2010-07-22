// JavaScript Document
// code adapted from http://www.akchauhan.com/javascript-list-search-using-jquery/
function ToSeconds(InStr) {
  var Match = /(\d+):(\d+):(\d+)/i.exec(InStr);
  return Number(Match[1]) * 3600 + Number(Match[2]) * 60 + Number(Match[3]);
}
$(document).ready(function () {
	$(' .jump-list p').each(function(){
		if ($(this).attr("begin")){
			var linkText = "'SEEK', "+ ToSeconds($(this).attr("begin"));
			$(this).html('<div class="tmcode">'+$(this).attr("begin")+'</div><a href="javascript:void(0);" onclick="ply.sendEvent('+linkText+')">'+$(this).html()+'</a>'); 
		}
	});
	$('#embedCode textarea').hide();
        $('label').click(function() {
			var currentId = $(this).attr('for'); 
			if ( $('#' +currentId).is(":visible") ){
				$('#' +currentId).hide(400);
			} else {
				$('#embedCode textarea').hide(400);
				$('#' +currentId).show(400);
			}
			return false;
	});
	$('#loader').hide();
	$('#search').keyup(function(event) {
		var search_text = $('#search').val();
		var rg = new RegExp(search_text,'i');
		$(' .jump-list p').each(function(){
 			if($.trim($(this).html()).search(rg) == -1) {
				//$(this).parent().css('display', 'none');
 				$(this).css('display', 'none');
				$(this).next().css('display', 'none');
				$(this).next().next().css('display', 'none');
			}	
			else {
				//$(this).parent().css('display', '');
				$(this).css('display', '');
				$(this).next().css('display', '');
				$(this).next().next().css('display', '');
			}
		});
	});
});

$('#search_clear').click(function() {
	$('#search').val('');	
	
	$('.jump-list p').each(function(){
		//$(this).parent().css('display', '');
		$(this).css('display', '');
		$(this).next().css('display', '');
		$(this).next().next().css('display', '');
	});
});