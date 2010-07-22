<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Twitter Subtitle for YouTube - search and timecode jump</title>
<style type="text/css">
<!--
body {
font-family: Arial, Helvetica, sans-serif;
font-size:90%;
margin:0px;
}
.container {
	width: 470px;
}
.jump-list {
	overflow: scroll;
	height: 200px;
}
.jump-list .tmcode{
display: block;
float:left;
height:100%;
padding:5px;
}
.jump-list p {
	border-bottom: 1px dotted #333333;
	margin:0px;
	padding:0px;
	font-size: 80%;
	background-color:#EBEBEB;
}
.jump-list a {
    display: block;
	color:#000000;
    padding: 5px;
    text-decoration: none;
	padding-left:60px;
}
.jump-list a, .comm {
    display: block;
	color:#000000;
    padding: 5px;
    text-decoration: none;
	padding-left:60px;
}
.jump-list a:hover {
	background-color:#999999;
}
.search-box-bg {
width:380px;
margin:5px;
}
#embedCode{
width:465px;
float:left;
}
#embedCode textarea{
width:465px;
}
a:hover {background:#ffffff; text-decoration:none;} /*BG color is a must for IE6*/
a.tooltip span {display:none; padding:2px 3px; margin-left:10px; margin-top:15px;width:130px;}
a.tooltip:hover span{display:inline; position:absolute; background:#ffffff; border:1px solid #cccccc; color:#6c6c6c;}

-->
</style>
<script type="text/javascript">
if (top.location != self.location) { document.write('<style>#embedCode{display:none}</style>'); } 
</script>
</head>
<body>
<div class="container">
  <div id="mediaspace1"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
  <div id="embedCode" style="text-align:center; ">
    <form method="post" action="" name="embedForm" >
      Comments powered by <img src="%UTITLE_URL%images/twitter.png" alt="twitter" width="79" height="18" align="absMiddle" /> | Embed:
      <label for="embedPly"><a href="javascript:void(0);" class="tooltip"><span>Embed video with Twitter subtitles</span><img src="%UTITLE_URL%images/embed_ply.gif" width="22" height="16" border="0" align="absmiddle"></a></label>
      <label for="embedSrch"><a href="javascript:void(0);" class="tooltip"><span>Embed video with Twitter subtitles and timeline navigation</span><img src="%UTITLE_URL%images/embed_srch.gif" width="22" height="16" border="0" align="absmiddle"></a></label>
      <label for="embedAll"><a href="javascript:void(0);" class="tooltip"><span>Embed video, navigation and comment entry</span><img src="%UTITLE_URL%images/embed_all.gif" width="22" height="16" border="0" align="absmiddle"></a></label><br/>
      <textarea id="embedPly"  rows="4" wrap="VIRTUAL" onClick="select();">&lt;embed src='%UTITLE_URL%player/player.swf' height='320' width='470' allowscriptaccess='always' allowfullscreen='true' flashvars='&amp;autostart=false&amp;captions.back=false&amp;captions.file=%UTITLE_URL_ENCODE%xml%2F%YOUTUBEURL%.xml&amp;captions.fontsize=14&amp;captions.height=296&amp;captions.state=true&amp;captions.visible=true&amp;captions.width=470&amp;captions.x=0&amp;captions.y=0&amp;file=http%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D%YOUTUBEURL%&amp;plugins=viral-2%2Ccaptions-1&amp;viral.functions=link%2Cembed&amp;viral.onpause=false'/&gt;</textarea>
      <textarea id="embedSrch" rows="4" wrap="VIRTUAL" onClick="select();">&lt;iframe src=&quot;%UTITLE_URL%u.php?v=%YOUTUBEURL%&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; allowtransparency=&quot;true&quot; style=&quot;border:none; overflow:hidden; width:470px; height:570px&quot;&gt;&lt;/iframe&gt;</textarea>
      <textarea id="embedAll"  rows="4" wrap="VIRTUAL" onClick="select();">&lt;iframe src=&quot;%UTITLE_URL%int.php?v=%YOUTUBEURL%&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; allowtransparency=&quot;true&quot; style=&quot;border:none; overflow:hidden; width:996px; height:586px&quot;&gt;&lt;/iframe&gt;</textarea>
    </form>
  </div>
  <div class="search-container">
    <div class="search-box">
      <div class="text-field-box"> Search<input name="" type="text"  id="search" autocomplete="off" class="search-box-bg" />
        <a href="javascript:void(0);" id="search_clear" >X</a> </div>
    </div>
  </div>
  <div class="jump-list">
	%XMLSUBTITLE%
  </div>
</div>
</div>
<script src="%UTITLE_URL%player/swfobject.js" type="text/javascript"></script>
<script type="text/javascript">
     var so = new SWFObject('%UTITLE_URL%player/player.swf','ply','470','320','9','#ffffff');  
		 so.addParam('allowfullscreen','true');   
		 so.addVariable('autostart','false');
		 so.addParam('allowscriptaccess','always');   
		 so.addParam('wmode','opaque');   
		 so.addVariable('file','http://www.youtube.com/watch?v=%YOUTUBEURL%');
		 so.addVariable('plugins', 'viral-2,captions-1');   
		 so.addVariable('viral.onpause', 'false');
		 so.addVariable('viral.functions', 'link,embed');
		 so.addVariable('captions.file', '%UTITLE_URL%xml/%YOUTUBEURL%.xml');
		 so.write('mediaspace1');
	 </script>
<script src="%UTITLE_URL%scripts/jquery.js" type="text/javascript"></script>
<script src="%UTITLE_URL%scripts/filter.js" type="text/javascript"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2382132-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>