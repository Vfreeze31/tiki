<?php 

class reportsLib extends TikiLib{
	function reportsLib($db) {
		$this->TikiLib($db);
	}
	
	//Sends the Email
	public function sendEmail($user_data, $report_preferences, $report_cache) {
		global $prefs, $smarty;

		include_once('lib/webmail/tikimaillib.php');
		$mail = new TikiMail();
		
		$smarty->assign('report_user', ucfirst($user_data['login']));
		$smarty->assign('report_interval', ucfirst($report_preferences['interval']));
		$smarty->assign('report_date', date("l d.m.Y"));
		$smarty->assign('report_last_report_date', date("l d.m.Y", strtotime($report_preferences['last_report'])));
		$smarty->assign('report_total_changes', count($report_cache));
		if ($prefs['feature_contribution'] == 'y' && !empty($contributions)) {
			global $contributionlib; include_once('lib/contribution/contributionlib.php');
			$smarty->assign('mail_contributions', $contributionlib->print_contributions($contributions));
		}

		$smarty->assign('report_body', $this->makeHtmlEmailBody($report_cache, $report_preferences));

		$mail->setUser($user_data['login']);
		if(is_array($report_cache)) {
			if (count($report_cache)==1) {
				$changes = "1 ".tra("change");
			} else {
				$changes = count($report_cache)." ".tra("changes");
			}
		} else {
			$changes = tra("No changes");
		}
		
		$subject = tra(ucfirst($report_preferences['interval'])." report from")." ".date("d.m.Y", time())." (".$changes.")";
		$mail->setSubject($subject);
		$mail_data = $smarty->fetchLang('de', "mail/report.tpl");
		$mail->setText($mail_data);
		
		echo "Going to ".$user_data['email']."<br>";
		echo "Subject: ".$subject."<br>";
		echo "Message template:<br>".$mail_data;
				
		$mail->buildMessage();
		$mail->send(array($user_data['email']));

		$email_test_headers .= 'From: noreply@tikiwiki.org' . "\n";	// needs a valid sender
		$email_test_headers .= 'Reply-to: '. $email_test_to . "\n";
		$email_test_headers .= "Content-type: text/html; charset=utf-8\n";
		$email_test_headers .= 'X-Mailer: Tiki/'.$TWV->version.' - PHP/' . phpversion() . "\n";
			
		$sentmail = mail($user_data['email'], $subject, $mail_data, $email_test_headers);
		
		return true;
	}
	
	//Makes time short
	private function makeTime($time) {
		if (date("d.m.Y", $time)==date("d.m.Y", time()-86400)) {
			return tra("Yesterday")." ".date("H:i", $time);
		} elseif(date("d.m.Y", $time)==date("d.m.Y", time())) {
			return tra("Today")." ".date("H:i", $time);
		} else {
			return date("d.m.", $time)." ".date("H:i", $time);
		}
	}
	
	private function makeChangeArray($report_cache) {
		foreach ($report_cache as $change) {
			$indexIdentifier = $change['event'].$change['data']['action'].$change['data']['galleryId'].$change['data']['pageName'].$change['data']['categoryId'];

			$change_array[$indexIdentifier][] = $change;
		}
		return $change_array;
	}
	
	public function makeHtmlEmailBody($report_cache, $report_preferences) {
		$change_array = $this->makeChangeArray($report_cache);
		
		$somethingHasHappened = false;

		//URL Prefix
		global $tikilib;
		$foo = parse_url($_SERVER["REQUEST_URI"]);
		$machine = $tikilib->httpPrefix() . dirname( $foo["path"] );

		$morechanges = 0;
		foreach ($change_array as $somethingHasHappened=>$array) {
			
			foreach ($array as $key=>$change) {
				
				if ($report_preferences['view']=="short" AND $key>0) {

					$morechanges++;
								
				} elseif ($report_preferences['view']=="detailed" OR $key==0) {

				if ($morechanges>0) {
					$body .= "&nbsp;&nbsp;&nbsp;".tra("and")." ".$morechanges." ".tra("more changes of the same type...")."<br>";
					$morechanges = 0;
				}

				if($key>0)
					$body .= "&nbsp; ";
				else
					$body .= "<b>";

				$body .= $this->makeTime(strtotime($change['time'])).": ";
				if ($change['event']=='image_gallery_changed' && empty($change['data']['action'])) {
					$body .= $change['data']['user']." ".tra("changed the picture gallery")." <a href=\"$machine/tiki-browse_gallery.php?galleryId=".$change['data']['galleryId']."&offset=0&sort_mode=created_desc\">".$change['data']['galleryName']."</a>.";
				} elseif ($change['event']=='image_gallery_changed' && $change['data']['action']=="upload image") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("uploaded the picture")." <a href=\"$machine/tiki-browse_image.php?imageId=".$change['data']['imageId']."\">".$change['data']['imageName']."</a> ".tra("onto")." <a href=\"$machine/tiki-browse_gallery.php?galleryId=".$change['data']['galleryId']."&offset=0&sort_mode=created_desc\">".$change['data']['galleryName']."</a>.";
				} elseif ($change['event']=='image_gallery_changed' && $change['data']['action']=="remove image") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("removed the picture")." <a href=\"$machine/tiki-browse_image.php?imageId=".$change['data']['imageId']."\">".$change['data']['imageName']."</a> ".tra("from")." <a href=\"$machine/tiki-browse_gallery.php?galleryId=".$change['data']['galleryId']."&offset=0&sort_mode=created_desc\">".$change['data']['galleryName']."</a>.";
				} elseif ($change['event']=="wiki_page_changed") {
					$body .= "<u>".$change['data']['editUser']."</u> ".tra("edited the wikipage")." <a href=\"$machine/tiki-index.php?page=".$change['data']['pageName']."\">".$change['data']['pageName']."</a> (<a href=\"$machine/tiki-pagehistory.php?page=".$change['data']['pageName']."&diff_style=sidediff&compare=Compare&newver=".($change['data']['oldVer']+1)."&oldver=".$change['data']['oldVer']."\">".tra("this history")."</a>, <a href=\"$machine/tiki-pagehistory.php?page=".$change['data']['pageName']."&diff_style=sidediff&compare=Compare&newver=0&oldver=".$change['data']['oldVer']."\">".tra("all history")."</a>)";

				} elseif ($change['event']=="file_gallery_changed" && empty($change['data']['action'])) {
					$body .= "<u>".$change['data']['user']."</u> ".tra("edited the file gallery")." <a href=\"$machine/tiki-list_file_gallery.php?galleryId=".$change['data']['galleryId']."\">".$change['data']['galleryName']."</a>";
				} elseif ($change['event']=="file_gallery_changed" && $change['data']['action']=="upload file") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("uploaded the file")." <a href=\"$machine/tiki-download_file.php?fileId=".$change['data']['fileId']."\">".$change['data']['fileName']."</a> ".tra("onto")." <a href=\"$machine/tiki-list_file_gallery.php?galleryId=".$change['data']['galleryId']."\">".$change['data']['galleryName']."</a>.";
				} elseif ($change['event']=="file_gallery_changed" && $change['data']['action']=="remove file") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("removed the file")." <a href=\"$machine/tiki-download_file.php?fileId=".$change['data']['fileId']."\">".$change['data']['fileName']."</a> ".tra("from")." <a href=\"$machine/tiki-list_file_gallery.php?galleryId=".$change['data']['galleryId']."\">".$change['data']['galleryName']."</a>.";					

				} elseif ($change['event']=="category_changed") {
					if ($change['data']['action']=="object entered category") {
						$body .= "<u>".$change['data']['user']."</u> ".tra("added the ".$change['data']['objectType'])." <a href=\"$machine/".$change['data']['objectUrl']."\">".$change['data']['objectName']."</a> ".tra("to the category")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['categoryId']."&deep=off\">".$change['data']['categoryName']."</a>.";
					} elseif ($change['data']['action']=="object leaved category") {
						$body .= "<u>".$change['data']['user']."</u> ".tra("removed the ".$change['data']['objectType'])." <a href=\"$machine/".$change['data']['objectUrl']."\">".$change['data']['objectName']."</a> ".tra("from the category")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['categoryId']."&deep=off\">".$change['data']['categoryName']."</a>.";
					} elseif ($change['data']['action']=="category created") {
						$body .= "<u>".$change['data']['user']."</u> ".tra("created the subcategory")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['categoryId']."&deep=off\">".$change['data']['categoryName']."</a> ".tra("in")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['parentId']."&deep=off\">".$change['data']['parentName']."</a>.";
					} elseif ($change['data']['action']=="category removed") {
						$body .= "<u>".$change['data']['user']."</u> ".tra("removed the subcategory")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['categoryId']."&deep=off\">".$change['data']['categoryName']."</a> ".tra("from")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['parentId']."&deep=off\">".$change['data']['parentName']."</a>.";
					} elseif ($change['data']['action']=="category updated") {
						$body .= "<u>".$change['data']['user']."</u> ".tra("edited the category")." <a href=\"$machine/tiki-browse_categories.php?parentId=".$change['data']['categoryId']."&deep=off\">".$change['data']['categoryName']."</a>";
					}
				} elseif ($change['event']=="article_deleted") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("removed the article")." <a href=\"$machine/tiki-read_article.php?articleId=".$change['data']['articleId']."\">".$change['data']['articleTitle']."</a>.";
				} elseif ($change['event']=="article_submitted") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("created the article")." <a href=\"$machine/tiki-read_article.php?articleId=".$change['data']['articleId']."\">".$change['data']['articleTitle']."</a>.";
				} elseif ($change['event']=="article_edited") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("edited the article")." <a href=\"$machine/tiki-read_article.php?articleId=".$change['data']['articleId']."\">".$change['data']['articleTitle']."</a>.";

				} elseif ($change['event']=="blog_post") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("replied to the blog")." <a href=\"$machine/tiki-view_blog.php?blogId=".$change['data']['blogId']."\">".$change['data']['blogTitle']."</a> <a href=\"$machine/tiki-view_blog_post.php?postId=\"".$change['data']['postId']."></a>.";

				} elseif ($change['event']=="forum_post_topic") {
					$body .= "<u>".$change['data']['user']."</u> ".tra("created the topic")." <a href=\"$machine/tiki-view_forum_thread.php?comments_parentId=".$change['data']['topicId']."&forumId=".$change['data']['forumId']."\">".$change['data']['threadName']."</a> ".tra("at forum")." <a href=\"$machine/tiki-view_forum.php?forumId=".$change['data']['forumId']."\">".$change['data']['forumName']."</a>.";
				} elseif ($change['event']=="forum_post_thread") {
					global $dbTiki;
					include_once ("lib/commentslib.php");
					$commentslib = new Comments($dbTiki);
					$parent_topic = $commentslib->get_comment($change['data']['topicId']);
					
					$body .= "<u>".$change['data']['user']."</u> <a href=\"$machine/tiki-view_forum_thread.php?forumId=".$change['data']['forumId']."&comments_parentId=".$change['data']['topicId']."#threadId".$change['data']['threadId']."\">".tra("replied")."</a> ".tra("to the topic")." <a href=\"$machine/tiki-view_forum_thread.php?comments_parentId=".$change['data']['topicId']."&forumId=".$change['data']['forumId']."\">".$parent_topic['title']."</a>.";
				}
				if ($key==0)
					$body .= "</b>";
					
				$body .= "<br>";
			}
			}
		}
		
		if(!$somethingHasHappened) {
			return tra("Nothing has happened.");
		} else {
			return $body;
		}
	}
}

global $dbTiki;
$reportslib = new reportsLib($dbTiki);
?>