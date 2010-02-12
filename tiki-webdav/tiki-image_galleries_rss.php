<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once ('tiki-setup.php');
require_once ('lib/tikilib.php');
require_once ('lib/imagegals/imagegallib.php');
require_once ('lib/rss/rsslib.php');

$access->check_feature('feature_galleries');

if ($prefs['rss_image_galleries'] != 'y') {
        $errmsg=tra("rss feed disabled");
        require_once ('tiki-rss_error.php');
}

$res=$access->authorize_rss(array('tiki_p_view_image_gallery','tiki_p_admin_galleries'));
if($res) {
   if($res['header'] == 'y') {
      header('WWW-Authenticate: Basic realm="'.$tikidomain.'"');
      header('HTTP/1.0 401 Unauthorized');
   }
   $errmsg=$res['msg'];
   require_once ('tiki-rss_error.php');
}

$feed = "imggal";
$uniqueid = $feed;
$output = $rsslib->get_from_cache($uniqueid);

if ($output["data"]=="EMPTY") {
	$title = (!empty($title_rss_image_galleries)) ? $title_rss_image_galleries : tra("Tiki RSS feed for image galleries");
	$desc = (!empty($desc_rss_image_galleries)) ? $desc_rss_image_galleries : tra("Last images uploaded to the image galleries.");
	
	$id = "imageId";
	$titleId = "name";
	$descId = "description";
	$dateId = "created";
	$authorId = "user";
	$readrepl = "tiki-browse_image.php?imageId=%s";
	
        $tmp = $prefs['title_rss_'.$feed];
        if ($tmp<>'') $title = $tmp;
        $tmp = $prefs['desc_rss_'.$feed];
        if ($desc<>'') $desc = $tmp;
	
	$changes = $imagegallib->list_images(0,$prefs['max_rss_image_galleries'],$dateId.'_desc', '');
	$output = $rsslib->generate_feed($feed, $uniqueid, '', $changes, $readrepl, '', $id, $title, $titleId, $desc, $descId, $dateId, $authorId);
}
header("Content-type: ".$output["content-type"]);
print $output["data"];
