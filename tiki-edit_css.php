<?php 
// $Id: tiki-edit_css.php,v 1.3 2003-08-01 10:30:45 redflo Exp $
include_once("tiki-setup.php");
include_once("lib/csslib.php");


//
// Load CSS2 styled file (@import aware)
//
// TODO: Will M$ windowz eat '/' as path delimiter?
//
function load_css2_file($filename, $styledir)
{
    $data = '';
    $lines = file($filename);
    //
    foreach ($lines as $line)
    {
	if (preg_match_all("/@import( |\t)+('|\")(.*)(|\")( |\t)*;/U", $line, $importfiles, PREG_SET_ORDER))
	{
	    foreach ($importfiles as $file)
	    {
		$import = $styledir.'/'.$file[3];
		$data .= load_css2_file($import, substr($import, 0, strrpos($import, "/")));
		$line = str_replace($file[0], "", $line);
	    }
	}
	// TODO: Does it matter what $line may contain smth before '@import'? :)
	$data .= $line;
    }
    return $data;    
}

// remove soon..
#$feature_edit_css = 'y';
#$tiki_p_create_css = 'y';
if(!isset($feature_editcss)) $feature_editcss = 'n';
if(!isset($tiki_p_create_css)) $tiki_p_create_css = 'n';

if($feature_editcss != 'y') {
  $smarty->assign('msg',tra("Feature disabled"));
	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if($tiki_p_create_css != 'y') {
  $smarty->assign('msg',tra("You dont have permission to use this feature"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}
if (!isset($_REQUEST["editstyle"])) $_REQUEST["editstyle"] = '';
if (!isset($_REQUEST["sub"])) $_REQUEST["sub"] = '';
if (!isset($_REQUEST["try"])) $_REQUEST["try"] = '';

$editstyle = $_REQUEST["editstyle"];
$styledir = "styles";

if (isset($_REQUEST["edit"]) and $_REQUEST["edit"]) {
	$action = 'edit';
//	$data = implode("",file("$styledir/$editstyle.css"));
	$data = load_css2_file("$styledir/$editstyle.css", $styledir);
} elseif (isset($_REQUEST["save"]) and $_REQUEST["save"]) {
	$action = 'display';
	$data = '';
	$file = str_replace("-$user","",$editstyle);
	$fp = fopen("$styledir/{$file}-$user.css","w");
	$editstyle = "{$file}-$user";
	if(!$fp) {
		$smarty->assign('msg',tra("You dont have permission to write the style sheet"));
    $smarty->display("styles/$style_base/error.tpl");
    die;
  }
  fwrite($fp,$_REQUEST["data"]);
  fclose($fp);
} else {
  $action = 'display';
	$data = '';
}	
$smarty->assign('action',$action);
$smarty->assign('data',$data);

$cssdata = $csslib->browse_css("$styledir/$editstyle.css");
if ((!$cssdata["error"]) and is_array($cssdata["content"])) {
	$parsedcss = $csslib->parse_css($cssdata["content"]);
} else {
	$parsedcss = $cssdata["error"];
}
$smarty->assign('css',$parsedcss);
$smarty->assign('editstyle',$editstyle);

if ($_REQUEST["try"]) {
	$style = "$editstyle.css";
	$smarty->assign('style',$style);
}

$list = $csslib->list_css($styledir);
$smarty->assign('list',$list);

$smarty->assign('mid','tiki-edit_css.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>
