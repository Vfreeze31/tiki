<?php
// Initialization
require_once('tiki-setup.php');

if($feature_forums != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if($feature_forum_rankings != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if($tiki_p_forum_read != 'y') {
  $smarty->assign('msg',tra("Permission denied you cannot view this section"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

$allrankings = Array(
  Array( 'name'=> tra('Last forum topics'), 'value'=> 'forums_ranking_last_topics'),
  Array( 'name'=> tra('Most read topics'), 'value'=>'forums_ranking_most_read_topics'),
  Array( 'name'=> tra('Top topics'), 'value'=>'forums_ranking_top_topics'),
  Array( 'name'=> tra('Forum posts'), 'value'=>'forums_ranking_most_commented_forum'),
  Array( 'name'=> tra('Most visited forums'), 'value'=>'forums_ranking_most_visited_forums')
);
$smarty->assign('allrankings',$allrankings);

if(!isset($_REQUEST["which"])) {
  $which = 'forums_ranking_last_topics';
} else {
  $which = $_REQUEST["which"];
}
$smarty->assign('which',$which);


// Get the page from the request var or default it to HomePage
if(!isset($_REQUEST["limit"])) {
  $limit = 10;
} else {
  $limit = $_REQUEST["limit"];
}

$smarty->assign_by_ref('limit',$limit);

// Rankings:
// Top Pages
// Last pages
// Top Authors
$rankings=Array();

$rk = $tikilib->$which($limit);
$rank["data"] = $rk["data"];
$rank["title"] = $rk["title"];
$rank["y"]=$rk["y"];
$rankings[] = $rank;



$smarty->assign_by_ref('rankings',$rankings);
$smarty->assign('rpage','tiki-forum_rankings.php');
// Display the template
$smarty->assign('mid','tiki-ranking.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>
