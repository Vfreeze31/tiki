<?php

// $Header: /cvsroot/tikiwiki/tiki/comments.php,v 1.32 2004-06-22 22:25:46 rlpowell Exp $

// Copyright (c) 2002-2004, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This file sets up the information needed to display
// the comments preferences, post-comment box and the
// list of comments. Finally it displays blog-comments.tpl
// using this information

// Setup URLS for the Comments next and prev buttons and use variables that
// cannot be aliased by normal tiki variables.
// Traverse each _REQUEST data adn put them in an array

//this script may only be included - so its better to die if called directly.

if (strpos($_SERVER["SCRIPT_NAME"],"comments.php")!=FALSE) {
    //smarty is not there - we need setup
    require_once('tiki-setup.php');
    $smarty->assign('msg',tra("This script cannot be called directly"));
    $smarty->display("error.tpl");
    die;
}


require_once ('lib/tikilib.php'); # httpScheme()

if (!isset($comments_per_page)) {
    $comments_per_page = 10;
}

if (!isset($comments_default_ordering)) {
    $comments_default_ordering = 'commentDate_desc';
}

if (!isset($_REQUEST["comment_rating"])) {
    $_REQUEST["comment_rating"] = '';
}

$comments_aux = array();
$comments_show = 'n';
$comments_t_query = '';
$comments_first = 1;

foreach ($comments_vars as $c_name) {
    $comments_avar["name"] = $c_name;

    if (isset($_REQUEST[$c_name])) {
	$comments_avar["value"] = $_REQUEST[$c_name];
	$comments_aux[] = $comments_avar;
    }

    if (isset($_REQUEST[$c_name])) {
	if ($comments_first) {
	    $comments_first = 0;

	    $comments_t_query .= "?$c_name=" . $_REQUEST["$c_name"];
	} else {
	    $comments_t_query .= "&amp;$c_name=" . $_REQUEST["$c_name"];
	}
    }
}

$smarty->assign_by_ref('comments_request_data', $comments_aux);

if (!isset($_REQUEST['comments_threshold'])) {
    $_REQUEST['comments_threshold'] = 0;
}

$smarty->assign('comments_threshold', $_REQUEST['comments_threshold']);
// This sets up comments father as the father
$comments_parsed = parse_url(httpPrefix().$_SERVER["REQUEST_URI"]);
/*
   print "<pre>";
   print_r( $comments_parsed );
   print_r( $_SERVER["REQUEST_URI"] );
   print "</pre>";
 */

if (!isset($comments_parsed["query"])) {
    $comments_parsed["query"] = '';
}

parse_str($comments_parsed["query"], $comments_query);
$comments_father = httpPrefix(). $comments_parsed["path"];
$comments_complete_father = $comments_father;

/*
   if(count($comments_query)>0) {
   $comments_first=1;
   foreach($comments_query as $com_name => $com_val) {
   if($comments_first) {
   $comments_first=false;
   $comments_complete_father.='?'.$com_name.'='.$com_val;
   } else {
   $comments_complete_father.='&amp;'.$com_name.'='.$com_val;
   }
   }
   }
 */
$comments_complete_father = $comments_father . $comments_t_query;

//print("Complete Father: $comments_complete_father<br/>");
//print("Father: $comments_father<br/>");
if (strstr($comments_complete_father, "?")) {
    $comments_complete_father .= '&amp;';
} else {
    $comments_complete_father .= '?';
}

//print("Father: $comments_father<br/>");
//print("Com: $comments_complete_father<br/>");
$smarty->assign('comments_father', $comments_father);
$smarty->assign('comments_complete_father', $comments_complete_father);

if (!isset($_REQUEST["comments_threadId"])) {
    $_REQUEST["comments_threadId"] = 0;
}
$smarty->assign("comments_threadId", $_REQUEST["comments_threadId"]);

// The same for replies to comments threads

if (!isset($_REQUEST["comments_reply_threadId"])) {
    $_REQUEST["comments_reply_threadId"] = 0;
}
$smarty->assign("comments_reply_threadId", $_REQUEST["comments_reply_threadId"]);


include_once ("lib/commentslib.php");
$commentslib = new Comments($dbTiki);

// Include the library for comments (if not included)
if (!isset($comments_prefix_var)) {
    $comments_prefix_var = '';
}

if (!isset($_REQUEST[$comments_object_var])) {
    die ("The comments_object_var variable cannot be found as a REQUEST variable");
}

$comments_objectId = $comments_prefix_var . $_REQUEST["$comments_object_var"];

// Process a post form here 
if ($tiki_p_post_comments == 'y') {
    if (isset($_REQUEST["comments_postComment"])) {
	$comments_show = 'y';

	if ((!empty($_REQUEST["comments_title"])) && (!empty($_REQUEST["comments_data"]))) {
	    if (!isset($_REQUEST["comments_parentId"])) {
		$_REQUEST["comments_parentId"] = 0;
	    }

	    //Replace things between square brackets by links
	    $_REQUEST["comments_data"] = strip_tags($_REQUEST["comments_data"]);

	    if ($_REQUEST["comments_threadId"] == 0) {
		if (isset($_REQUEST["comments_reply_threadId"]) &&
			$_REQUEST["comments_reply_threadId"] )
		{
		    $reply_info = $commentslib->get_comment($_REQUEST["comments_reply_threadId"]);
		    $in_reply_to = $reply_info["message_id"];
		} else {
		    $in_reply_to = '';
		}
		$message_id = '';

		$object = explode(':', $comments_objectId );
		if( $object[0] == 'forum' )
		{
		    $parent_id = $_REQUEST["comments_grandParentId"];
		} else {
		    $parent_id = $_REQUEST["comments_parentId"];
		}

		$commentslib->post_new_comment($comments_objectId, $parent_id,
			$user,
			$_REQUEST["comments_title"],
			$_REQUEST["comments_data"],
			$message_id, $in_reply_to );
	    } else {
		if ($tiki_p_edit_comments == 'y') {
		    $commentslib->update_comment($_REQUEST["comments_threadId"], $_REQUEST["comments_title"],
			    $_REQUEST["comment_rating"], $_REQUEST["comments_data"]);
		}
	    }

	    $object = explode(':', $comments_objectId );

	    if( $object[0] == 'forum' )
	    {
		// Deal with mail notifications.
		include_once('lib/notifications/notificationemaillib.php');
		sendForumEmailNotification('forum_post_thread',
			$_REQUEST['comments_parentId'], $forum_info,
			$_REQUEST["comments_title"], $_REQUEST["comments_data"], $user,
			$thread_info['title'], $message_id, $in_reply_to);

		$commentslib->register_forum_post($_REQUEST["forumId"], $_REQUEST["comments_parentId"]);
	    }
	    if (($feature_user_watches == 'y') && ($wiki_watch_comments == 'y') && (isset($_REQUEST["page"]))) {
		include_once ('lib/webmail/tikimaillib.php');
		$nots = $commentslib->get_event_watches('wiki_page_changed', $_REQUEST["page"]);
		$isBuilt = false;
		foreach ($nots as $not) {
		    if ($wiki_watch_editor != 'y' && $not['user'] == $user)
			break;
		    if (!$isBuilt) {
			$isBuilt = true;
			$smarty->assign('mail_page', $_REQUEST["page"]);
			$smarty->assign('mail_date', date("U"));
			$smarty->assign('mail_user', $user);
			$smarty->assign('mail_title', $_REQUEST["comments_title"]);
			$smarty->assign('mail_comment', $_REQUEST["comments_data"]);
			$smarty->assign('mail_hash', $not['hash']);
			$foo = parse_url($_SERVER["REQUEST_URI"]);
			$machine = httpPrefix(). dirname( $foo["path"] );
			$smarty->assign('mail_machine', $machine);
			$parts = explode('/', $foo['path']);

			if (count($parts) > 1)
			    unset ($parts[count($parts) - 1]);

			$smarty->assign('mail_machine_raw', httpPrefix(). implode('/', $parts));
			$mail = new TikiMail();
		    }
		    global $language;// TODO: optimise by grouping user by language
		    $languageEmail = $tikilib->get_user_preference($not['user'], "language", $language);
		    $mail->setUser($not['user']);
		    $mail_data = $smarty->fetchLang($languageEmail, 'mail/user_watch_wiki_page_changed_subject.tpl');
		    $mail->setSubject(sprintf($mail_data, $_REQUEST["page"]));
		    $mail_data = $smarty->fetchLang($languageEmail, 'mail/user_watch_wiki_page_comment.tpl');
		    $mail->setText($mail_data);
		    $mail->buildMessage();
		    $mail->send(array($not['email']));
		}
	    }

	} else {
	    $smarty->assign('msg', tra("Missing title or body when trying to post a comment"));

	    $smarty->display("error.tpl");
	    die;
	}
    }
}

if ($tiki_p_vote_comments == 'y') {
    // Process a vote here
    if (isset($_REQUEST["comments_vote"]) && isset($_REQUEST["comments_threadId"])) {
	$comments_show = 'y';

	if (!$tikilib->user_has_voted($user, 'comment' . $_REQUEST["comments_threadId"])) {
	    $commentslib->vote_comment($_REQUEST["comments_threadId"], $user, $_REQUEST["comments_vote"]);

	    $tikilib->register_user_vote($user, 'comment' . $_REQUEST["comments_threadId"]);
	}

	$_REQUEST["comments_threadId"] = 0;
	$smarty->assign('comments_threadId', 0);
    }
}

if ($_REQUEST["comments_threadId"] > 0) {
    $comment_info = $commentslib->get_comment($_REQUEST["comments_threadId"]);

    $smarty->assign('comment_title', $comment_info["title"]);
    $smarty->assign('comment_rating', $comment_info["comment_rating"]);	
    $smarty->assign('comment_data', $comment_info["data"]);
} elseif ($_REQUEST["comments_reply_threadId"] > 0) {
    // Replies to comments.
    $comment_info = $commentslib->get_comment($_REQUEST["comments_reply_threadId"]);
    // Add the replied-to text, with >.
    $smarty->assign('comment_data', preg_replace( '/\n/', '> ', '> ' . $comment_info["data"] ) );

    $smarty->assign('comment_title', tra('Re:').' '.$comment_info["title"]);
    $smarty->assign('comments_reply_threadId', $_REQUEST["comments_reply_threadId"]);
} else {
    $smarty->assign('comment_title', '');
    $smarty->assign('comment_rating', '');	
    $smarty->assign('comment_data', '');
}

if ($tiki_p_remove_comments == 'y') {
    if (isset($_REQUEST["comments_remove"]) && isset($_REQUEST["comments_threadId"])) {
	$area = 'delcomment';
	if (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"])) {
	    key_check($area);
	    $comments_show = 'y';
	    $commentslib->remove_comment($_REQUEST["comments_threadId"]);
	} else {
	    key_get($area);
	}
    }
}

$smarty->assign('comment_preview', 'n');

if (isset($_REQUEST["comments_previewComment"])) {
    $smarty->assign('comments_preview_title', $_REQUEST["comments_title"]);

    $smarty->assign('comments_preview_data', $commentslib->parse_comment_data(strip_tags($_REQUEST["comments_data"])));
    $smarty->assign('comment_title', $_REQUEST["comments_title"]);
    $smarty->assign('comment_rating', $_REQUEST["comment_rating"]);		
    $smarty->assign('comment_data', $_REQUEST["comments_data"]);
    $smarty->assign('comment_preview', 'y');
}

// Check for settings
if (!isset($_REQUEST["comments_maxComments"])) {
    $_REQUEST["comments_maxComments"] = $comments_per_page;
} else {
    $comments_show = 'y';
}

if (!isset($_REQUEST["comments_style"])) {
    // TODO: Make this an option.
    $_REQUEST["comments_style"] = 'commentStyle_threaded';
} else {
    $comments_show = 'y';
}

if (!isset($_REQUEST["comments_sort_mode"])) {
    $_REQUEST["comments_sort_mode"] = $comments_default_ordering;
} else {
    $comments_show = 'y';
}

if (!isset($_REQUEST["comments_commentFind"])) {
    $_REQUEST["comments_commentFind"] = '';
} else {
    $comments_show = 'y';
}

$smarty->assign('comments_maxComments', $_REQUEST["comments_maxComments"]);
$smarty->assign('comments_sort_mode', $_REQUEST["comments_sort_mode"]);
$smarty->assign('comments_style', $_REQUEST["comments_style"]);
$smarty->assign('comments_commentFind', $_REQUEST["comments_commentFind"]);
$smarty->assign('comments_show', $comments_show);

//print("Show: $comments_show<br/>");
// Offset setting for the list of comments
if (!isset($_REQUEST["comments_offset"])) {
    $comments_offset = 0;
} else {
    $comments_offset = $_REQUEST["comments_offset"];
}

$smarty->assign('comments_offset', $comments_offset);

// Now check if we are displaying top-level comments or a specific comment
if (!isset($_REQUEST["comments_parentId"])) {
    $_REQUEST["comments_parentId"] = 0;
}

$smarty->assign('comments_parentId', $_REQUEST["comments_parentId"]);

if( isset( $_REQUEST["comments_grandParentId"] ) )
{
    $smarty->assign('comments_grandParentId', $_REQUEST["comments_grandParentId"]);
}

$comments_coms = $commentslib->get_comments($comments_objectId, $_REQUEST["comments_parentId"],
	$comments_offset, $_REQUEST["comments_maxComments"], $_REQUEST["comments_sort_mode"], $_REQUEST["comments_commentFind"],
	$_REQUEST['comments_threshold'], $_REQUEST["comments_style"]);
$comments_cant = $commentslib->count_comments($comments_objectId);
$smarty->assign('comments_below', $comments_coms["below"]);
$smarty->assign('comments_cant', $comments_cant);

//print "<pre>";
//print_r($comments_coms);
//print "</pre>";
// Offset management
$comments_maxRecords = $_REQUEST["comments_maxComments"];
if( $comments_maxRecords != 0 )
{
    $comments_cant_pages = ceil($comments_coms["cant"] / $comments_maxRecords);
    $smarty->assign('comments_actual_page', 1 + ($comments_offset / $comments_maxRecords));
} else {
    $comments_cant_pages = 1;
    $smarty->assign('comments_actual_page', 1 );
}
$smarty->assign('comments_cant_pages', $comments_cant_pages);

if ($comments_coms["cant"] > ($comments_offset + $comments_maxRecords)) {
    $smarty->assign('comments_next_offset', $comments_offset + $comments_maxRecords);
} else {
    $smarty->assign('comments_next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($comments_offset > 0) {
    $smarty->assign('comments_prev_offset', $comments_offset - $comments_maxRecords);
} else {
    $smarty->assign('comments_prev_offset', -1);
}

$smarty->assign('comments_coms', $comments_coms["data"] );

// Grab the parent comment to show.  -rlpowell
if (isset($_REQUEST["comments_parentId"]) &&
	$_REQUEST["comments_parentId"] > 0 && 
	($tiki_p_post_comments == 'y') &&
	(isset($_REQUEST['comments_previewComment']) ||
	 isset($_REQUEST['post_reply']))) {
    $parent_com = $commentslib->get_comment($_REQUEST["comments_parentId"]);
    $smarty->assign_by_ref('parent_com', $parent_com);
}

?>
