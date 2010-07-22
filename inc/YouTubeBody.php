<div class="container">
  <div class="search-pane">
    <div class="search-container">
      <div class="jump-list"> %XMLSUBTITLE% </div>
      <div class="search-box">
        <div class="text-field-box"> Search
          <input name="" type="text"  id="search" autocomplete="off" class="search-box-bg" />
          <a href="javascript:void(0);" id="search_clear" >X</a> </div>
      </div>
    </div>
  </div>
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
    <div id="info">
    <h2>Information</h2>
    <p>The 'Tweet preview' indicates the message that will appear in your timeline when you hit the 'Tweet' button (or Shift+Alt+t). </p>
    <p>The current video position will be tweeted unless you use the 'Mark' button (or Shift+Alt+m) to link your tweet to a specific point in the timeline.</p>
    <p>By using this service you give permission for us to display your tweet. This site adheres to Twitter's tweet removal policy through integration with the Twitter Search and the Twapper Keeper archive service.</p>
  </div>
</div>
<script src="http://www.rsc-ne-scotland.org.uk/mashe/utitle/player/swfobject.js" type="text/javascript"></script>
<script type="text/javascript">
     var so = new SWFObject('http://www.rsc-ne-scotland.org.uk/mashe/utitle/player/player.swf','ply','470','320','9','#ffffff');  
		 so.addParam('allowfullscreen','true');   
		 so.addVariable('autostart','true');
		 so.addParam('allowscriptaccess','always');   
		 so.addParam('wmode','opaque');   
		 so.addVariable('file','http://www.youtube.com/watch?v=%YOUTUBEURL%');
		 so.addVariable('start','%START%');
		 so.addVariable('plugins', 'viral-2,captions-1');   
		 so.addVariable('viral.onpause', 'false');
		 so.addVariable('viral.functions', 'link,embed');
		 so.addVariable('captions.file', 'http://www.rsc-ne-scotland.org.uk/mashe/utitle/xml/%YOUTUBEURL%.xml');
		 so.write('mediaspace1');
    
	 </script>
<script src="scripts/filter.js" type="text/javascript"></script>
