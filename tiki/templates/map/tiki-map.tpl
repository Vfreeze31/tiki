{* $Header: /cvsroot/tikiwiki/tiki/templates/map/tiki-map.tpl,v 1.23 2004-09-08 19:53:06 mose Exp $ *}

<script src="lib/map/map.js"></script>

<h1><a href="tiki-map.phtml">TikiMap</a></h1><br />
<div align="center">
   <form name="frmmap" action="tiki-map.phtml" method="get">
   <input type="hidden" name="mapfile" value="{$mapfile}" />
	  <table border="0" cellpadding="0" cellspacing="0" >
	  <tr>
		<td align="center" valign="middle">
		<table class="normal">
		<tr><td>
		  <input type="image" id="map" src="{$image_url}"
			{if $xsize != ""}width="{$xsize}"{/if} 
			{if $ysize != ""}height="{$ysize}"{/if} 
		  border="1"
		  alt="{tr}click on the map to zoom or pan, do not drag{/tr}" 
		  title="{tr}click on the map to zoom or pan, do not drag{/tr}" />
		</td></tr>
		<tr><td>
		  <img id="scale" src="{$image_scale_url}" border="0" alt="{tr}Scale{/tr}" title="{tr}Scale{/tr}" />
		</td></tr>
		<tr><td align="center">	
		{if $zoom eq -4}
		  <img name="imgzoom0" src="img/icons/zoom-4.gif" onclick="zoomin(0)" alt="-x4" title="{tr}Zoom out x4{/tr}" border="1">
		{else}
		  <img name="imgzoom0" src="img/icons/zoom-4.gif" onclick="zoomin(0)" alt="-x4" title="{tr}Zoom out x4{/tr}">
		{/if}
		{if $zoom eq -3}
		  <img name="imgzoom1" src="img/icons/zoom-3.gif" onclick="zoomin(1)" alt="-x3" title="{tr}Zoom out x3{/tr}" border="1">
		{else}  
		  <img name="imgzoom1" src="img/icons/zoom-3.gif" onclick="zoomin(1)" alt="-x3" title="{tr}Zoom out x3{/tr}">
		{/if}
		{if $zoom eq -2}
		  <img name="imgzoom2" src="img/icons/zoom-2.gif" onclick="zoomin(2)" alt="-x2" title="{tr}Zoom out x2{/tr}" border="1">
		{else}
		  <img name="imgzoom2" src="img/icons/zoom-2.gif" onclick="zoomin(2)" alt="-x2" title="{tr}Zoom out x2{/tr}">
		{/if}
		{if $zoom eq 0}
		  <img name="imgzoom3" src="img/icons/info.gif" onclick="zoomin(3)" alt="Q" title="{tr}Query{/tr}" border="1">
		{else}
		  <img name="imgzoom3" src="img/icons/info.gif" onclick="zoomin(3)" alt="Q" title="{tr}Query{/tr}">
		{/if}
		{if $zoom eq 1}
		  <img name="imgzoom4" src="img/icons/move.gif" onclick="zoomin(4)" alt="P" title="{tr}Pan{/tr}" border="1">
		{else}
		  <img name="imgzoom4" src="img/icons/move.gif" onclick="zoomin(4)" alt="P" title="{tr}Pan{/tr}">
		{/if}
		{if $zoom eq 2}
		  <img name="imgzoom5" src="img/icons/zoom+2.gif" onclick="zoomin(5)" alt="x2" title="{tr}Zoom in x2{/tr}" border="1">
		{else}
		  <img name="imgzoom5" src="img/icons/zoom+2.gif" onclick="zoomin(5)" alt="x2" title="{tr}Zoom in x2{/tr}">
		{/if}
		{if $zoom eq 3}
		  <img name="imgzoom6" src="img/icons/zoom+3.gif" onclick="zoomin(6)" alt="x3" title="{tr}Zoom in x3{/tr}" border="1">
		{else}
		  <img name="imgzoom6" src="img/icons/zoom+3.gif" onclick="zoomin(6)" alt="x3" title="{tr}Zoom in x3{/tr}">
		{/if}
		{if $zoom eq 4}
		  <img name="imgzoom7" src="img/icons/zoom+4.gif" onclick="zoomin(7)" alt="x4" title="{tr}Zoom in x4{/tr}" border="1">
		{else}
		  <img name="imgzoom7" src="img/icons/zoom+4.gif" onclick="zoomin(7)" alt="x4" title="{tr}Zoom in x4{/tr}">
		{/if}
		&nbsp;
		  <select name="zoom" size="1" onchange="cbzoomchange()">
		  {html_options values=$zoom_values selected=$zoom output=$zoom_display}
		  </select>
		  <select name="size" size="1">
		  {html_options values=$possiblesizes selected=$size output=$displaysizes}
		  </select>
		<br />
		 <input name="Redraw" value="{tr}Redraw{/tr}" type="Submit" />	
		 <br />
		 <small>{tr}select zoom/pan/query and image size{/tr}</small>
		 </td></tr>
		 <tr><td align="center">
		 
		 {if $map_view == "" }
		  {*if view is empty do not display empty list, else display view list*} 
		 {else}
		 <select name="view" size="1">
		   <option selected value="#">Select country,and Go!
		   {html_options values=$view_name name=$view output=$view_name}
		   </select>
		   <input type="submit" name="Go" value="Go" />		       
		&nbsp;{/if}
		 {if $tiki_p_map_edit eq 'y'}
     			&nbsp; 
     			<a class="link" href="tiki-map_edit.php?mapfile={$mapfile}&amp;mode=editing">
     			<img src="img/icons/config.gif" border="0"  alt="{tr}edit{/tr}" title="{tr}edit{/tr}" />
     			</a>
     		{/if}
     &nbsp;
     <a href="tiki-map.phtml?mapfile={$mapfile}" ><small>{tr}Reset Map{/tr}</small></a>
						
<br /> 
		   <small>{tr}Click on the map or click redraw{/tr}</small>
		  <input type="hidden" name="minx" value="{$minx}" />
		  <input type="hidden" name="miny" value="{$miny}" />
		  <input type="hidden" name="maxx" value="{$maxx}" />
		  <input type="hidden" name="maxy" value="{$maxy}" />
		  <input type="hidden" name="oldsize" value="{$size}" />
		  <a href="tiki-index.php?page={$map_help}"><small>{tr}Help{/tr}</small></a>&nbsp;
		  <a href="tiki-index.php?page={$map_comments}"><small>{tr}Comments{/tr}</small></a>
		</td></tr>
		</table>
		</td>
		<td valign="top">
		  <table class="normal">
		  <tr><td class="heading" align="center"><b>Overview</b></td></tr>
		  <tr><td align="center" valign="middle" bgcolor="FFFFFF">
		    <img id="ref" src="{$image_ref_url}" border="1" alt="{tr}Overview{/tr}" title="{tr}Overview{/tr}" />
		  </td ></tr>	   
		    <tr><td class="heading" align="center"><b>Legend</b></td></tr>
		    <tr><td align="center" bgcolor="FFFFFF"><img id="leg" src="{$image_leg_url}" border="0" alt="{tr}Legend{/tr}" title="{tr}Legend{/tr}" /></td></tr>
		  </td></tr>
		  <tr><td>
		  <div class="separator">
    {if $feature_menusfolderstyle eq 'y'}
    <a class="separator" href="javascript:icntoggle('layermenu');"><img src="img/icons/fo.gif" border="0" name="layermenuicn" alt=""/>&nbsp;</a>
    {else}<a class="separator" href="javascript:toggle('layermenu');">[-]</a>{/if} 
    {tr}Layer Manager{/tr}
    {if $feature_menusfolderstyle ne 'y'}<a class="separator" href="javascript:toggle('layermenu');">[+]</a>{/if}
    </div>
		  <div id='layermenu' style="{$mnu_layermenu}">
		  <table class="normal">
		  <tr><td class="heading">
		  <b>Layer</b></td>
		  <td class="heading"><b>{tr}On{/tr}</b></td>
		  <td class="heading"><img src="img/icons/edit.gif" border="0" alt="{tr}Label{/tr}" title="{tr}Label{/tr}" /></td>
		  <td class="heading"><img src="img/icons/question.gif" border="0" alt="{tr}Query{/tr}" title="{tr}Query{/tr}" /></td>
		  <td class="heading"><img src="img/icons/ico_save.gif" border="0" alt="{tr}Download{/tr}" title="{tr}Download{/tr}" /></td></tr>
		  {section name=j loop=$my_layers}
		  <tr>
		  {if $smarty.section.j.index % 2}
		  <td class="odd">
		  {else}
		  <td class="even">
		  {/if}
		  {tr}{$layer_wiki[j]}{/tr}
		  </td>	
		  {if $smarty.section.j.index % 2}
		  <td class="odd">
		  {else}
		  <td class="even">
		  {/if}
		   <input type="checkbox" name="{$my_layers[j]->name}" value="1" {$my_layers_checked[j]} /> 
		  </td>
		  {if $smarty.section.j.index % 2}
		  <td class="odd">
		  {else}
		  <td class="even">
		  {/if}
		  {if $layer_label[j] eq "On"}
		  <input type="checkbox" name="{$my_layers[j]->name}_label" value="1" {$my_layers_label_checked[j]} />
		  {else}
		  &nbsp;
		  {/if}
		  </td>
		  {if $smarty.section.j.index % 2}
		  <td class="odd">
		  {else}
		  <td class="even">
		  {/if}
		  {if $layer_query[j] eq "On"}
     <img src="img/icons/question.gif" border="0" alt="{tr}Query{/tr}" title="{tr}Query{/tr}" />		  
		  {else}
		  &nbsp;
		  {/if}
		  </td>
		  {if $smarty.section.j.index % 2}
		  <td class="odd">
		  {else}
		  <td class="even">
		  {/if}
		  {if $layer_download[j] eq "T"}
		  <small>
		  <a href="tiki-map_download.phtml?mapfile={$mapfile}&amp;layer={$my_layers[j]->name}">
		  <img src="img/icons/ico_save.gif" border="0" alt="{tr}Download{/tr}" title="{tr}Download{/tr}" />
		  </a>
		  </small>
		  {/if}
		  </td></tr>
		  {/section}
		  </table>
		  </div>
		  </td>
	   </tr>
	   </table>
   </td></tr>
	  </table>		  
	  </form>
	  {$map_querymsg}
</div>
