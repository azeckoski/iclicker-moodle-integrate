<?php
/**
 * Copyright (c) 2009 i>clicker (R) <http://www.iclicker.com/dnn/>
 *
 * This file is part of i>clicker Moodle integrate.
 *
 * i>clicker Moodle integrate is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * i>clicker Moodle integrate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with i>clicker Moodle integrate.  If not, see <http://www.gnu.org/licenses/>.
 */
/* $Id$ */

/**
 * Handles rendering the form for creating new pages and the submission of the form as well
 * NOTE: table is named iclicker
 */

require_once ('../../config.php');
global $CFG, $USER, $COURSE;
require_once ('iclicker_service.php');
require_once ('controller.php');

require_login();

// activate the controller
$cntlr = new iclicker_controller();
$cntlr->processInstructor();
//echo '<pre>';
//var_export($cntlr->results);
//echo '</pre>';
extract($cntlr->results);

// begin rendering
$site = get_site();
$navigation = iclicker_service::msg('inst.title');
if ($show_students && $course) {
    $navigation = array(
        array('name' => iclicker_service::msg('inst.title'), 'link' => $instPath),
        array('name' => $course->fullname)
    );
}
/*
param: string  $title Appears at the top of the window
param: string  $heading Appears at the top of the page
param: array   $navigation Array of $navlinks arrays (keys: name, link, type) for use as breadcrumbs links
param: string  $focus Indicates form element to get cursor focus on load eg  inputform.password
param: string  $meta Meta tags to be added to the header
param: boolean $cache Should this page be cacheable?
param: string  $button HTML code for a button (usually for module editing)
param: string  $menu HTML code for a popup menu
param: boolean $usexml use XML for this page
param: string  $bodytags This text will be included verbatim in the <body> tag (useful for onload() etc)
param: bool    $return If true, return the visible elements of the header instead of echoing them.
 */
print_header(
    strip_tags($site->fullname).':'.iclicker_service::msg('app.iclicker').':'.iclicker_service::msg('inst.title'),
    iclicker_service::msg('app.iclicker').' '.iclicker_service::msg('inst.title'),
    build_navigation($navigation), // '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'">'.$COURSE->shortname.'</a> ->'.get_string('formtitle', 'block_iclicker'),
    '',
    "<meta name=\"description\" content=\"".s(strip_tags($site->summary))."\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"".iclicker_service::block_url('css/iclicker.css')."\" />",
    false
);

// show messages if there are any to show
require ('user_messages.php');
?>

<div class="main_content">
<?php if (count($courses) == 0) { ?>
    <span class="no_items"><?php echo iclicker_service::msg('inst.no.courses') ?></span>
<?php } else if ($show_students) { ?>
    <div class="title"><?php echo iclicker_service::msg('inst.course') ?>: <?php echo $course->fullname ?></div>
    <div class="description"><?php echo $course->summary ?></div>
    <!-- clicker registration listing -->
    <div><?php echo iclicker_service::msg('inst.students') ?> (<?php echo $students_count ?>):</div>
    <table width="80%" border="1" cellspacing="0" cellpadding="0" class="students_list"
        summary="<?php echo iclicker_service::msg('inst.students.table.summary') ?>">
        <thead>
            <tr class="students_header header_row">
                <th width="40%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <?php echo iclicker_service::msg('inst.student.name.header') ?>
                </th>
                <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <?php echo iclicker_service::msg('inst.student.email.header') ?>
                </th>
                <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <?php echo iclicker_service::msg('inst.student.status.header') ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($students as $student) { ?>
            <tr class="<?php echo ($student->clicker_registered ? 'registered' : 'unregistered') ?> students_row data_row style1">
                <td align="center" class="user_name"><?php echo $student->name ?></td>
                <td align="center" class="user_email"><?php echo $student->email ?></td>
                <td align="center" class="clicker_status"><?php echo iclicker_service::msg('inst.student.registered.'.($student->clicker_registered ? 'true':'false')) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <div class="title"><?php echo iclicker_service::msg('inst.courses.header') ?> (<?php echo $courses_count ?>):</div>
    <!-- course listing -->
    <table width="90%" border="1" cellspacing="0" cellpadding="0"
        summary="<?php echo iclicker_service::msg('inst.courses.table.summary') ?>">
        <thead>
            <tr class="courses_header header_row">
                <th width="70%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <?php echo iclicker_service::msg('inst.course') ?>
                </th>
                <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($courses as $course) { ?>
            <tr class="courses_row data_row style1">
                <td align="center"><?php echo $course->fullname ?></td>
                <td align="center"><a href="<?php echo $instPath.'?courseId='.$course->id ?>"><?php echo iclicker_service::msg('inst.course.view.students') ?></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
</div>

<div class="iclicker_version">Version <?php echo iclicker_service::VERSION ?> (<?php echo iclicker_service::BLOCK_VERSION ?>)</div>

<?php print_footer(); ?>
