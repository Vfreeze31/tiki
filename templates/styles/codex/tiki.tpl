{* Index we display a wiki page here *}
{include file="header.tpl"}
<div id="tiki-main">
{if $feature_top_bar eq 'y'}
<div id="tiki-top">
{include file="tiki-top_bar.tpl"}
</div>
{/if}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td id="page11"><img src="styles/codex/page_0.gif" width="34" height="31" alt="" border="0" /></td>
		{if $feature_left_column eq 'y'}
		<td id="page12"><img src="styles/codex/page_0.gif" width="158" height="31" alt="" border="0" /></td>
		{/if}
		<td id="page13"><img src="styles/codex/page_0.gif" width="7" height="31" alt="" border="0" /></td>
		<td id="page14" width="100%" {if $feature_right_column eq 'y'}colspan="2"{/if}><img src="styles/codex/page_0.gif" width="50" height="31" alt="" border="0" /></td>
		<td id="page15"><img src="styles/codex/page_0.gif" width="38" height="31" alt="" border="0" /></td>
	</tr>
	<tr>
		<td id="page21"><img src="styles/codex/page_0.gif" width="34" height="1" alt="" border="0" /></td>
		{if $feature_left_column eq 'y'}
		<td id="page22">
			{section name=homeix loop=$left_modules}
			{$left_modules[homeix].data}
			{/section}
		</td>
		{/if}
		<td id="page23"><img src="styles/codex/page_0.gif" width="7" height="1" alt="" border="0" /></td>
		<td id="page24" width="100%">
			<div id="tiki-center">
			{include file=$mid}
			{if $show_page_bar eq 'y'}
			{include file="tiki-page_bar.tpl"}
			{/if}
			</div>
		</td>			
		{if $feature_right_column eq 'y'}
		<td id="rightcolumn">
			{section name=homeix loop=$right_modules}
			{$right_modules[homeix].data}
			{/section}
		</td>
		{/if}
		<td id="page25"><img src="styles/codex/page_0.gif" width="38" height="1" alt="" border="0" /></td>
	</tr>
	<tr>
		<td id="page31"><img src="styles/codex/page_0.gif" width="34" height="41" alt="" border="0" /></td>
		{if $feature_left_column eq 'y'}
		<td align="center" id="page32"><img src="styles/codex/page_3_2a.png" width="158" height="41" alt="" border="0" /></td>
		{/if}
		<td id="page33"><img src="styles/codex/page_0.gif" width="7" height="41" alt="" border="0" /></td>
		<td id="page34" width="100%" {if $feature_right_column eq 'y'}colspan="2"{/if}><img src="styles/codex/page_0.gif" width="50" height="41" alt="" border="0" /></td>
		<td id="page35"><img src="styles/codex/page_0.gif" width="38" height="41" alt="" border="0" /></td>
	</tr>
</table>
{if $feature_bot_bar eq 'y'}
<div id="tiki-bot">
{include file="tiki-bot_bar.tpl"}
</div>
{/if}
</div>
{include file="footer.tpl"}
