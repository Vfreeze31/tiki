{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use this page to configure the feeds generated by Tiki. To syndicate feeds from other sites, use the <strong>Admin</strong> &gt; <strong>External Feeds</strong> menu option, or{/tr} <a class="rbox-link" href="tiki-admin_rssmodules.php" title="{tr}External feeds{/tr}">{tr}Click Here{/tr}</a>.{/remarksbox}


<form action="tiki-admin.php?page=rss" method="post">
<input type="hidden" name="rss" />
<div class="heading input_submit_container" style="text-align: right">
	<input type="submit" value="{tr}Change preferences{/tr}" />
</div>
<fieldset><legend>{tr}{$crumbs[$crumb]->title}{/tr}{if $prefs.feature_help eq 'y'}{help crumb=$crumbs[$crumb]}{/if}</legend>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_articles" name="rss_articles" onclick="flip('articlesrss');"{if $prefs.rss_articles eq 'y'} checked="checked"{/if}{if $prefs.feature_articles ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_articles">{tr}Articles{/tr}{if $prefs.rss_articles eq 'y'}<a href="tiki-articles_rss.php" title="{tr}Feed for articles{/tr}."> {icon _id=feed}</a>{/if} {if $prefs.feature_articles ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}</label>
	
<div id="articlesrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_articles eq 'y') and ($prefs.feature_articles eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_articles" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_articles|escape}' />
	{preference name="desc_rss_articles" label="{tr}Description{/tr}"}
	<br />{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_articles" size="5" value="{$prefs.max_rss_articles|escape}" />
<br />{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_articles" {if $prefs.showAuthor_rss_articles eq 'y'} checked="checked"{/if}/>
<br />{tr}Homepage URL:{/tr}<br /><input type="text" name="index_rss_articles" size="20" style="width: 95%" value="{$prefs.index_rss_articles|escape}" />
</div>
</div>
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_blogs" name="rss_blogs" onclick="flip('blogsrss');"{if $prefs.rss_blogs eq 'y'} checked="checked"{/if}{if $prefs.feature_blogs ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_blogs">{tr}Blogs{/tr}</label>{if $prefs.rss_blogs eq 'y'}<a href="tiki-blogs_rss.php" title="{tr}Feed for blogs{/tr}.">{icon _id=feed}</a>{/if} {if $prefs.feature_blogs ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	
<div id="blogsrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_blogs eq 'y') and ($prefs.feature_blogs eq 'y')}block{else}none{/if};">
	<div class="adminoptionbox">
		{tr}Title:{/tr} <input type="text" name="title_rss_blogs" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_blogs|escape}' /><br />
		{tr}Description:{/tr} <textarea name="desc_rss_blogs" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_blogs|escape}</textarea><br />
		{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_blogs" size="5" value="{$prefs.max_rss_blogs|escape}" /><br />
		{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_blogs" {if $prefs.showAuthor_rss_blogs eq 'y'} checked="checked"{/if}/><br /><br />
		{tr}Homepage URL:{/tr}<br />
		<input type="text" name="index_rss_blogs" size="20" style="width: 95%" value="{$prefs.index_rss_blogs|escape}" />
	</div>
</div>	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"> <input type="checkbox" id="rss_blog" name="rss_blog" onclick="flip('blogrss');"{if $prefs.rss_blog eq 'y'} checked="checked"{/if}{if $prefs.feature_blogs ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_blog">{tr}Individual blogs{/tr}</label>
<div id="blogrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_blog eq 'y') and ($prefs.feature_blogs eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_blog" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_blog|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_blog" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_blog|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_blog" size="5" value="{$prefs.max_rss_blog|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_blog" {if $prefs.showAuthor_rss_blog eq 'y'} checked="checked"{/if} /><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_blog" size="20" style="width: 95%" value="{$prefs.index_rss_blog|escape}" />
</div>
</div>	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_image_galleries" name="rss_image_galleries" onclick="flip('image_galleriesrss');"{if $prefs.rss_image_galleries eq 'y'} checked="checked"{/if}{if $prefs.feature_galleries ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_image_galleries">{tr}Image galleries{/tr}</label>{if $prefs.rss_image_galleries eq 'y'}<a href="tiki-image_galleries_rss.php" title="{tr}Feed for image galleries{/tr}.">{icon _id=feed}</a>{/if}
{if $prefs.feature_galleries ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
<div class="adminoptionboxchild" id="image_galleriesrss" style="display:{if ($prefs.rss_image_galleries eq 'y') and ($prefs.feature_galleries eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_image_galleries" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_image_galleries|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_image_galleries" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_image_galleries|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_image_galleries" size="5" value="{$prefs.max_rss_image_galleries|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_image_galleries" {if $prefs.showAuthor_rss_image_galleries eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_image_galleries" size="20" style="width: 95%" value="{$prefs.index_rss_image_galleries|escape}" />
</div>
	</div>
</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_image_gallery" name="rss_image_gallery" onclick="flip('image_galleryrss');"{if $prefs.rss_image_gallery eq 'y'} checked="checked"{/if}{if $prefs.feature_galleries ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_image_gallery">{tr}Individual image galleries{/tr}</label> {if $prefs.feature_galleries ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
<div id="image_galleryrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_image_gallery eq 'y') and ($prefs.feature_galleries eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_image_gallery" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_image_gallery|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_image_gallery" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_image_gallery|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_image_gallery" size="5" value="{$prefs.max_rss_image_gallery|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_image_gallery" {if $prefs.showAuthor_rss_image_gallery eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br /><input type="text" name="index_rss_image_gallery" size="20" style="width: 95%" value="{$prefs.index_rss_image_gallery|escape}" />
</div>
</div>	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_file_galleries" name="rss_file_galleries" onclick="flip('file_galleriesrss');"{if $prefs.rss_file_galleries eq 'y'} checked="checked"{/if}{if $prefs.feature_file_galleries ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_file_galleries">{tr}File Galleries{/tr}</label>{if $prefs.rss_file_galleries eq 'y'}<a href="tiki-file_galleries_rss.php" title="{tr}Feed for file galleries{/tr}.">{icon _id=feed}</a>{/if}
{if $prefs.feature_file_galleries ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	<div class="adminoptionboxchild" id="file_galleriesrss" style="display:{if ($prefs.rss_file_galleries eq 'y') and ($prefs.feature_file_galleries eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_file_galleries" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_file_galleries|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_file_galleries" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_file_galleries|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_file_galleries" size="5" value="{$prefs.max_rss_file_galleries|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_file_galleries" {if $prefs.showAuthor_rss_file_galleries eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_file_galleries" size="20" style="width: 95%" value="{$prefs.index_rss_file_galleries|escape}" /></div>
	</div>
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_file_gallery" name="rss_file_gallery" onclick="flip('file_galleryrss');"{if $prefs.rss_file_gallery eq 'y'} checked="checked"{/if}{if $prefs.feature_file_galleries ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_file_gallery">{tr}Individual file galleries{/tr}</label>
<div id="file_galleryrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_file_gallery eq 'y') and ($prefs.feature_file_galleries eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_file_gallery" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_file_gallery|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_file_gallery" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_file_gallery|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_file_gallery" size="5" value="{$prefs.max_rss_file_gallery|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_file_gallery" {if $prefs.showAuthor_rss_file_gallery eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_file_gallery" size="20" style="width: 95%" value="{$prefs.index_rss_file_gallery|escape}" />
</div>	
</div>	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_wiki" name="rss_wiki" onclick="flip('wikirss');"{if $prefs.rss_wiki eq 'y'} checked="checked"{/if}{if $prefs.feature_wiki ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_wiki">{tr}Wiki{/tr}</label>{if $prefs.rss_wiki eq 'y'}<a href="tiki-wiki_rss.php" title="{tr}Feed for wiki{/tr}.">{icon _id=feed}</a>{/if} {if $prefs.feature_wiki ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
<div id="wikirss" class="adminoptionboxchild" style="display:{if ($prefs.rss_wiki eq 'y') and ($prefs.feature_wiki eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
 	{tr}Title:{/tr} <input type="text" name="title_rss_wiki" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_wiki|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_wiki" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_wiki|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_wiki" size="5" value="{$prefs.max_rss_wiki|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_wiki" {if $prefs.showAuthor_rss_wiki eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br /><input type="text" name="index_rss_wiki" size="20" style="width: 95%" value="{$prefs.index_rss_wiki|escape}" />
</div>
            </div>	
	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_forums" name="rss_forums" onclick="flip('forumsrss');"{if $prefs.rss_forums eq 'y'} checked="checked"{/if}{if $prefs.feature_forums ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_forums">{tr}Forums{/tr}</label>{if $prefs.rss_forums eq 'y'} <a href="tiki-forums_rss.php" title="{tr}Feed for forums{/tr}.">{icon _id=feed}</a>{/if} {if $prefs.feature_forums ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	<div id="forumsrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_forums eq 'y') and ($prefs.feature_forums eq 'y')}block{else}none{/if};">
	<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_forums" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_forums|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_forums" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_forums|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_forums" size="5" value="{$prefs.max_rss_forums|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_forums" {if $prefs.showAuthor_rss_forums eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_forums" size="20" style="width: 95%" value="{$prefs.index_rss_forums|escape}" /></div>
            </div>
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_forum" name="rss_forum" onclick="flip('forumrss');"{if $prefs.rss_forum eq 'y'} checked="checked"{/if}{if $prefs.feature_forums ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_forum">{tr}Individual forums{/tr}</label>
<div id="forumrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_forum eq 'y') and ($prefs.feature_forums eq 'y')}block{else}none{/if};">
<div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_forum" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_forum|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_forum" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_forum|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_forum" size="5" value="{$prefs.max_rss_forum|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_forum" {if $prefs.showAuthor_rss_forum eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_forum" size="20" style="width: 95%" value="{$prefs.index_rss_forum|escape}" /></div>
            </div>	
	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_trackers" name="rss_trackers" onclick="flip('trackersrss');"{if $prefs.rss_trackers eq 'y'} checked="checked"{/if}{if $prefs.feature_trackers ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_trackers">{tr}Trackers{/tr}</label> {if $prefs.feature_trackers ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
			<div class="adminoptionboxchild" id="trackersrss" style="display:{if ($prefs.rss_trackers eq 'y') and ($prefs.feature_trackers eq 'y')}block{else}none{/if};">
    <div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_trackers" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_trackers|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_trackers" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_trackers|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_trackers" size="5" value="{$prefs.max_rss_trackers|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_trackers" {if $prefs.showAuthor_rss_trackers eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_trackers" size="20" style="width: 95%" value="{$prefs.index_rss_trackers|escape}" /></div>
            </div>	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_tracker" name="rss_tracker" onclick="flip('trackerrss');"{if $prefs.rss_tracker eq 'y'} checked="checked"{/if}{if $prefs.feature_trackers ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_tracker">{tr}Individual trackers items{/tr} </label>
	<div id="trackerrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_tracker eq 'y') and ($prefs.feature_trackers eq 'y')}block{else}none{/if};">
            <div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_tracker" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_tracker|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_tracker" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_tracker|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_tracker" size="5" value="{$prefs.max_rss_tracker|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_tracker" {if $prefs.showAuthor_rss_tracker eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_tracker" size="20" style="width: 95%" value="{$prefs.index_rss_tracker|escape}" /></div>
            </div>
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input id="rss_calendar" type="checkbox" name="rss_calendar" onclick="flip('calendarrss');"{if $prefs.rss_calendar eq 'y'} checked="checked"{/if}{if $prefs.feature_calendar ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_calendar">{tr}Calendar events{/tr}</label>{if $prefs.rss_calendar eq 'y'} <a href="tiki-calendars_rss.php">{icon _id=feed}</a>{/if} {if $prefs.feature_calendar ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	<div id="calendarrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_calendar eq 'y') and ($prefs.feature_calendar eq 'y')}block{else}none{/if};">
            <div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_calendar" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_calendar|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_calendar" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_calendar|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_calendar" size="5" value="{$prefs.max_rss_calendar|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_calendar" {if $prefs.showAuthor_rss_calendar eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_calendar" size="20" style="width: 95%" value="{$prefs.index_rss_calendar|escape}" /></div>
            </div>
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_directories" name="rss_directories" onclick="flip('directoriesrss');"{if $prefs.rss_directories eq 'y'} checked="checked"{/if}{if $prefs.feature_directory ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_directories">{tr}Directories{/tr}</label>{if $prefs.rss_directories eq 'y'} <a href="tiki-directory_rss.php" title="{tr}Feed for directory{/tr}.">{icon _id=feed}</a>{/if}  {if $prefs.feature_directory ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	<div id="directoriesrss" class="adminoptionboxchild" style="display:{if ($prefs.rss_directories eq 'y') and ($prefs.feature_directory eq 'y')}block{else}none{/if};">
            <div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_directories" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_calendar|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_directories" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_directories|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_directories" size="5" value="{$prefs.max_rss_directories|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_directories" {if $prefs.showAuthor_rss_directories eq 'y'} checked="checked"{/if}/><br />
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_directories" size="20" style="width: 95%" value="{$prefs.index_rss_directories|escape}" /></div>
            </div>
	
	</div>
</div>

<div class="adminoptionbox">
	<div class="adminoption"><input type="checkbox" id="rss_mapfiles" name="rss_mapfiles" onclick="flip('mapfilesrss');"{if $prefs.rss_mapfiles eq 'y'} checked="checked"{/if}{if $prefs.feature_maps ne 'y'} disabled="disabled"{/if} /></div>
	<div class="adminoptionlabel"><label for="rss_mapfiles">{tr}Map files{/tr}</label>{if $prefs.rss_mapfiles eq 'y'} <a href="tiki-map_rss.php" title="{tr}Feed for maps{/tr}.">{icon _id=feed}</a>{/if}  {if $prefs.feature_maps ne 'y'}&nbsp;({tr}Feature is disabled.{/tr} <a href="tiki-admin.php?page=features" title="{tr}Features{/tr}">{tr}Enable now{/tr}</a>.){/if}
	<div class="adminoptionboxchild" id="mapfilesrss" style="display:{if ($prefs.rss_mapfiles eq 'y') and ($prefs.feature_maps eq 'y')}block{else}none{/if};">
            <div class="adminoptionbox">
	{tr}Title:{/tr} <input type="text" name="title_rss_mapfiles" size="20" maxlength="255" style="width:95%" value='{$prefs.title_rss_mapfiles|escape}' /><br />
	{tr}Description:{/tr} <textarea name="desc_rss_mapfiles" style="width:95%" cols="20" rows="2">{$prefs.desc_rss_mapfiles|escape}</textarea><br />
	{tr}Maximum number of items to display:{/tr}<input type="text" name="max_rss_mapfiles" size="5" value="{$prefs.max_rss_mapfiles|escape}" /><br />
	{tr}Show Author:{/tr} <input type="checkbox" name="showAuthor_rss_mapfiles" {if $prefs.showAuthor_rss_mapfiles eq 'y'} checked="checked"{/if}/><br />	
	{tr}Homepage URL:{/tr}<br />
	<input type="text" name="index_rss_mapfiles" size="20" style="width: 95%" value="{$prefs.index_rss_mapfiles|escape}" /></div>
            </div>
	</div>
</div>
</fieldset>

<fieldset><legend>{tr}Defaults{/tr}</legend>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rssfeed_default_version">{tr}Default RDF version:{/tr}</label> <select name="rssfeed_default_version" id="rssfeed_default_version">
              <option value="9" {if $prefs.rssfeed_default_version eq "9"}selected="selected"{/if}> RSS 0.91 </option>
              <option value="1" {if $prefs.rssfeed_default_version eq "1"}selected="selected"{/if}> RSS 1.0 </option>
              <option value="2" {if $prefs.rssfeed_default_version eq "2"}selected="selected"{/if}> RSS 2.0 </option>
              <option value="3" {if $prefs.rssfeed_default_version eq "3"}selected="selected"{/if}> PIE0.1 </option>
              <option value="4" {if $prefs.rssfeed_default_version eq "4"}selected="selected"{/if}> MBOX </option>
              <option value="5" {if $prefs.rssfeed_default_version eq "5"}selected="selected"{/if}> ATOM 0.3 </option>
              <option value="6" {if $prefs.rssfeed_default_version eq "6"}selected="selected"{/if}> OPML </option>
              <option value="7" {if $prefs.rssfeed_default_version eq "7"}selected="selected"{/if}> HTML </option>
              <option value="8" {if $prefs.rssfeed_default_version eq "8"}selected="selected"{/if}> JS </option>
            </select>
			<br /><em>{tr}Specification{/tr} 
              <a href="http://www.w3.org/TR/rdf-schema/" target="tikihelp" class="tikihelp" title="{tr}Specification:{/tr} RDF 1.0">RDF 1.0</a>,
              <a href="http://blogs.law.harvard.edu/tech/rss" target="tikihelp" class="tikihelp" title="{tr}Specification:{/tr} RDF 2.0">RDF 2.0</a>,
              <a href="http://bitworking.org/rfc/draft-gregorio-07.html" target="tikihelp" class="tikihelp" title="{tr}Specification:{/tr} Atom 0.3">Atom 0.3</a>.</em>
			</div>
</div>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rssfeed_language">{tr}Language{/tr}</label>: <input type="text" name="rssfeed_language" id="rssfeed_language" size="10" value="{$prefs.rssfeed_language|escape}" />{if $prefs.feature_help eq 'y'}<a href="http://blogs.law.harvard.edu/tech/rss#optionalChannelElements" target="tikihelp" class="tikihelp" title="{tr}Documentation:{/tr} RDF">{icon _id=help}</a>{/if}</div>
</div>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rssfeed_editor">{tr}Editor:{/tr}</label> <input type="text" name="rssfeed_editor" id="rssfeed_editor" size="50" value="{$prefs.rssfeed_editor|escape}" />{if $prefs.feature_help eq 'y'}<a href="http://blogs.law.harvard.edu/tech/rss#optionalChannelElements" target="tikihelp" class="tikihelp" title="{tr}Documentation:{/tr} RDF">{icon _id=help}</a>{/if}</div>
</div>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rssfeed_webmaster">{tr}Webmaster:{/tr}</label> <input type="text" name="rssfeed_webmaster" id="rssfeed_webmaster" size="50" value="{$prefs.rssfeed_webmaster|escape}" />{if $prefs.feature_help eq 'y'}<a href="http://blogs.law.harvard.edu/tech/rss#optionalChannelElements" target="tikihelp" class="tikihelp" title="{tr}Documentation:{/tr} RDF">{icon _id=help}</a>{/if}</div>
</div>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rssfeed_img">{tr}Image Path:{/tr}</label> <input type="text" name="rssfeed_img" id="rssfeed_img" size="50" value="{$prefs.rssfeed_img|escape}" /></div>
</div>
<div class="adminoptionbox">
	<div class="adminoptionlabel"><label for="rss_cache_time">{tr}Caching time:{/tr}</label> <input type="text" name="rss_cache_time" id="rss_cache_time" size="4" value="{$prefs.rss_cache_time}" /> {tr}seconds{/tr}<br /><em>{tr}Use <strong>0</strong> for no caching{/tr}.</em></div>
	{remarksbox type="Note" title="{tr}Note{/tr}"}{tr}Enabling caching will override existing permissions - all content will be public. Enable this option <strong>only</strong> if your syndicated content should be public.{/tr}{/remarksbox}	
</div>
	{preference name='rss_basic_auth'}
</fieldset>


<div class="heading input_submit_container" style="text-align: center">
	<input type="submit" value="{tr}Change preferences{/tr}" />
</div>
</form>
