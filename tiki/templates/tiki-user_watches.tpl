<a class="pagetitle" href="tiki-user_watches.php">{tr}User Watches{/tr}</a>

<!-- help links -->
{if $feature_help eq 'y'}
<a href="http://tikiwiki.org/tiki-index.php?page=UserWatches" target="tikihelp" class="tikihelp" title="{tr}Tikiwiki.org help{/tr}: {tr}User Preferences{/tr}">
<img border='0' src='img/icons/help.gif' alt='{tr}help{/tr}' /></a>
{/if}
<!--  link to tpl -->
{if $feature_view_tpl eq 'y'}
<a href="tiki-edit_templates.php?template=templates/tiki-user_watches.tpl" target="tikihelp" class="tikihelp" title="{tr}View tpl{/tr}: {tr}UserPreferences tpl{/tr}">
<img border='0' src='img/icons/info.gif' alt='{tr}edit template{/tr}' /></a>
{/if}
<!--  tiki toolbar -->
{include file=tiki-mytiki_bar.tpl}
<br /><br />
<!--  begin watches -->
<h3>{tr}Watches{/tr}</h3>


<form action="tiki-user_watches.php" method="post" id='formi'>
{tr}Event{/tr}:<select name="event" onchange="javascript:document.getElementById('formi').submit();">
<option value"" {if $smarty.request.event eq ''}selected="selected"{/if}>{tr}All{/tr}</option>
{section name=ix loop=$events}
<option value="{$events[ix]|escape}" {if $events[ix] eq $smarty.request.event}selected="selected"{/if} />{$events[ix]}</option>
{/section}
</select>
</form>

<form action="tiki-user_watches.php" method="post">
<table class="normal">
<tr>
<td style="text-align:center;"  class="heading"><input type="submit" name="delete" value="{tr}x{/tr} " /></td>
<td class="heading">{tr}event{/tr}</td>
<td class="heading">{tr}object{/tr}</td>
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$watches}
<tr>
<td style="text-align:center;"class="{cycle advance=false}">
<input type="checkbox" name="watch[{$watches[ix].hash}]" />
</td>
<td class="{cycle advance=false}">{tr}{$watches[ix].event}{/tr}</td>
<td class="{cycle}"><a class="link" href="{$watches[ix].url}">{$watches[ix].type}:{$watches[ix].title}</a></td>
</tr>
{/section}
</table>
</form>
