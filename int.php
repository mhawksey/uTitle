<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>uTitle - YouTube/Twitter timeline commenting tool</title>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			type : 'GET',
			url : 'i.php',
			dataType : 'html',
			data: {
				v : '<?php echo $_GET['v'];?>',
				start : '<?php echo $_GET['start'];?>'
			},
			success : function(data){
				$('#loader').hide(500);
				$('body').html(data);
				$("#myform").validate({
			submitHandler: function(form) {
				// do other stuff for a valid form
				$("#tweet").val($("#tw_preview").html());
				$.post('process.php', $("#myform").serialize(), function(data) {
					$('#results').html(data);
					if ($('#out').html() == "200"){
						$('#tw_preview').html("");
						$('#txt').val("");
						$(this).html("Mark");
						$(this).attr('class', 'but_mk');
						$(this).removeAttr('mk_time');
					}
				});
			}
		});
	$("#but_mk").toggle(
		function () {
			$(this).html("Mark");
			$(this).attr('class', 'but_mk_dis');
			$(this).attr('mk_time', $('#time').html());},
		function () { 
			$(this).html("Auto-Mark");
			$(this).attr('class', 'but_mk');}
			);
	$("#but_com").toggle(
		function () {
			$(this).html("Free");
			$(this).attr('class', 'but_mk_dis');},
		function () { 
			$(this).html("Timed");
			$(this).attr('class', 'but_mk');}
			);
		// character count http://www.9lessons.info/2010/04/live-character-count-meter-with-jquery.html
		$("#txt").keyup(function(){
			var v = "#<?php echo $_GET['v']; ?>";
			var countTxt = "";
			var timerLen = 5;
			
			var maxChar = 140 - v.length - timerLen;
			var box=$(this).val();
			var main = box.length *100;
			var value= (main / maxChar);
			var count= maxChar - box.length;
			if ($("#but_com").attr("class")=="but_mk"){
				if (box.length <= 0){
					$("#but_mk").attr('mk_time', $("#time").html());
				}
				
				if ($("#but_mk").attr("mk_time")){
						countTxt = $("#but_mk").attr("mk_time");
				}
			}
			$('#tw_preview').html(box +" "+ v +" "+ countTxt);
			
			if (count <= 0 || count === maxChar){
				$('#bar').css('background-color', '#cc0000' );
				$('#count').css('color', '#cc0000' );
				$('#update_status').attr('disabled', 'disabled');
				$('#update_status').attr('class', 'but_tw_dis');
			} else {
				$('#bar').css('background-color', '#5fbbde' );
				$('#count').css('color', '#666666' );
				$('#update_status').removeAttr("disabled");
				$('#update_status').attr('class', 'but_tw');
			}
			if(box.length <= maxChar){
				$('#bar').animate({"width": value+'%',}, 1);
			} 
			$('#count').html(count);
			return false;
		});
				/*
				$('#message').removeClass().addClass((data.error === true) ? 'error' : 'success')
					.text(data.msg).show(500);
				if (data.error === true)
					$('#demoForm').show(500);
				*/
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#loader').hide(500);
				$('#message').removeClass().addClass('error')
					.text('Oops! There was an error.').show(500);
				//$('#demoForm').show(500);
			}
		});
		return false;
	});
	 
function playerReady(obj)
      {
        player = gid(obj.id);
        addListeners();
      };


      function addListeners()
      {
        playlist = player.getPlaylist();

        if(playlist.length > 0)
        {
          player.addModelListener('TIME',    'timeMonitor');
          player.addModelListener('LOADED',  'loadedMonitor');
        }
        else
        {
          setTimeout("addListeners();", 100);
        }
      };


      function timeMonitor(obj)
      {
        //currentDuration = obj.duration;
        currentTime     = obj.position;
		$("#txt").keyup();
        gid('time').innerHTML = getTimeCode(currentTime);
      };

      function gid(name)
      {
        return document.getElementById(name);
      };
	  //String formatter from: http://forums.devshed.com/javascript-development-115/convert-seconds-to-minutes-seconds-386816.html
	  String.prototype.pad = function(l, s){
	  return (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length) + 1).join(s)).substr(0, s.length) + this + s.substr(0, l - s.length) : this; };
 
	function getTimeCode(elapsed){
		var pureSeconds=elapsed % 60;
		var seconds=""+(elapsed % 60).toFixed().pad(2, "0");
		var pureMinutes=Math.floor((elapsed-pureSeconds) / 60) % 60;
		var minutes=(Math.floor((elapsed-pureSeconds) / 60) % 60).toFixed().pad(2, "0");
		pureSeconds+=60*pureMinutes;
		var timecode=minutes+":"+seconds;
		//var timecode="00:"+(Math.floor(elapsed / 60)).toFixed().pad(2, "0") + ":" + (elapsed % 60).toFixed().pad(2, "0")+".0";
		return timecode;
	}
</script>
<link href="style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body>
<div id="message" style="display: none;"> </div>
<div id="loader"> <img src="images/ajax-loader.gif" width="32" height="32" alt="loader" /><br />
Please wait
</div>
<div id="page"></div>
  <script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
  <script type="text/javascript"> 
try {
var pageTracker = _gat._getTracker("UA-2382132-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>