{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-hw_teacher_assignment.tpl,v 1.1 2004-03-01 02:15:37 ggeller Exp $ *}
{* George G. Geller *}
{* February 29, 2004 *}
{* Adapted from tiki-read_article.php *}

<!-- templates/tiki-hw_teacher_assignment.tpl start -->

<div class="articletitle">
  <span class="titlea">{$title}</span><br />
  <span style="color: rgb(0, 0, 255);">
    {tr}Due Date:{/tr} {$dueDate|tiki_long_datetime}
  </span>
</div>

<div class="articleheading">
  <table  cellpadding="0" cellspacing="0">
    <tr>
      <td  valign="top">
        <div class="articleheadingtext">{$parsed_heading}</div>
      </td>
    </tr>
  </table>
</div>

<div class="articletrailer">
  <table class="wikitopline">
    <tr>
      <td>
        <a href="tiki-hw_teacher_assignments.php" class="trailer">{tr}Assignments List{/tr}</a>
      </td>
      <td>
        {* <a href="tiki-hw_page.php?assignmentId={$assignmentId}" class="trailer">{tr}My Work{/tr}</a> *}
	Grading Queue (empty) {* GGG FIXME need link to papers to be graded *}
      </td>
      <td style="text-align:right;">
        {* TODO IN VERSION2
          <a class="trailer" href="tiki-hw_teacher_assignment_print.php?assignmentId={$listpages[ix].articleId}"><img src='img/icons/ico_print.gif' border='0' alt='{tr}Print{/tr}' title='{tr}Print{/tr}' /></a>
        TODO IN VERSION 2 *}
      </td>
    </tr>
  </table>
</div>

<div class="articlebody">
  {$parsed_body}
</div>

<!-- templates/tiki-hw_teacher_assignment.tpl end -->
