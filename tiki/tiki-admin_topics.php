<?php

require_once('tiki-setup.php');

if($feature_articles != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}


// PERMISSIONS: NEEDS p_admin

  if($tiki_p_admin_cms != 'y') {
    $smarty->assign('msg',tra("You dont have permission to use this feature"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
  }


if(isset($_REQUEST["addtopic"])) {
  if(isset($_FILES['userfile1'])&&is_uploaded_file($_FILES['userfile1']['tmp_name'])) {
    $fp = fopen($_FILES['userfile1']['tmp_name'],"rb");
    $data = fread($fp,filesize($_FILES['userfile1']['tmp_name']));
    fclose($fp);
    $imgtype = $_FILES['userfile1']['type'];
    $imgsize = $_FILES['userfile1']['size'];
    $imgname = $_FILES['userfile1']['name'];
  } else {
    $smarty->assign('msg',tra("No image uploaded"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
  }
  // Store the image
  $tikilib->add_topic($_REQUEST["name"],$imgname,$imgtype,$imgsize,$data);
}

if(isset($_REQUEST["remove"])) {
  $tikilib->remove_topic($_REQUEST["remove"]);
}

if(isset($_REQUEST["activate"])) {
  $tikilib->activate_topic($_REQUEST["activate"]);
}

if(isset($_REQUEST["deactivate"])) {
  $tikilib->deactivate_topic($_REQUEST["deactivate"]);
}


$topics = $tikilib->list_topics();
for($i=0;$i<count($topics);$i++) {
  if($userlib->object_has_one_permission($topics[$i]["topicId"],'topic')) {
    $topics[$i]["individual"]='y';
    
    if($userlib->object_has_permission($user,$topics[$i]["topicId"],'topic','tiki_p_topic_read')) {
      $topics[$i]["individual_tiki_p_topic_read"]='y';
    } else {
      $topics[$i]["individual_tiki_p_topic_read"]='n';
    }
    if($tiki_p_admin=='y' || $userlib->object_has_permission($user,$topics[$i]["topicId"],'topic','tiki_p_admin_cms')) {
      $topics[$i]["individual_tiki_p_topic_read"]='y';
    } 
    
  } else {
    $topics[$i]["individual"]='n';
  }
}


$smarty->assign('topics',$topics);

$smarty->assign('mid','tiki-admin_topics.tpl');
$smarty->display("styles/$style_base/tiki.tpl");


?>