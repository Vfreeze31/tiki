{* $Header: /cvsroot/tikiwiki/tiki/templates/map/tiki-map_download.tpl,v 1.8 2004-06-23 22:34:29 mose Exp $ *}

<h3>{tr}Download Layer{/tr}</h3>
<b>{$userwatch}</b>, {tr}you have requested to download the layer:{/tr}<b>{$layer}</b> {tr}from
the mapfile:{/tr}<b>{$mapfile}</b><br /><br />
{tr}Here are the files to download, do not forget to rename them:{/tr}<br />
<table class="normal" >
{section name=j loop=$files}
{if $smarty.section.j.index % 2}
<tr>
<td class="odd">
<a class="link" href="files/{$dfiles[j]}">{$files[j]}</a>
</td>
</tr>
{else}
<tr>
<td class="even">
<a class="link" href="files/{$dfiles[j]}">{$files[j]}</a>
</td>
</tr>
{/if}
{/section}
</table>
