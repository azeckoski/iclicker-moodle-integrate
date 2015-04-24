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
$cntlr->processAdmin();
extract($cntlr->results);

// begin rendering
$site = get_site();
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
    strip_tags($site->fullname).':'.iclicker_service::msg('app.iclicker').':'.iclicker_service::msg('admin.title'),
    iclicker_service::msg('app.iclicker').' '.iclicker_service::msg('admin.title'),
    build_navigation(iclicker_service::msg('admin.title')), // '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'">'.$COURSE->shortname.'</a> ->'.get_string('formtitle', 'block_iclicker'),
    '',
    "<meta name=\"description\" content=\"".s(strip_tags($site->summary))."\">\n".
        "<link rel=\"stylesheet\" type=\"text/css\" href=\"".iclicker_service::block_url('css/iclicker.css')."\" />\n".
        "<script src=\"".iclicker_service::block_url('js/jquery.js')."\" language=\"JavaScript\" type=\"text/javascript\"></script>\n".
        "<script language=\"JavaScript\" type=\"text/javascript\"><!-- ensure no conflicts with other libs -->jQuery.noConflict();</script>\n".
        "<script src=\"".iclicker_service::block_url('js/iclicker.js')."\" language=\"JavaScript\" type=\"text/javascript\"></script>",
    false
);

// show messages if there are any to show
require ('user_messages.php');
?>

<div class="admin_controls">
    <?php if ($runner_exists) { ?>
    <div class="process_status">
        <?php echo iclicker_service::msg('admin.process.header') ?>
        <span class="runner_type"><?php echo iclicker_service::msg('admin.process.type.'.$runner_type) ?></span> :
        <span id="runnerStatus" class="runner_status"><?php echo $runner_percent ?>%</span>
    </div>
    <?php } ?>
    <?php if ($useNationalWebservices) { ?>
    <div class="workspace_form">
        <form method="post" style="display:inline;">
            <input type="hidden" name="runner" value="runner" />
            <input type="submit" class="runner_button" name="syncAll" value="<?php echo iclicker_service::msg('admin.process.sync') ?>" />
        </form>
    </div>
    <?php } ?>
    <?php if ($runner_exists) { ?>
    <script type="text/javascript">Iclicker.initStatusChecker("#runnerStatus", "<?php echo $status_url ?>");</script>
    <?php } ?>
</div>

<div class="main_content">
    <!-- pager control -->
    <div class="paging_bar">
        <?php echo iclicker_service::msg('admin.paging') ?>
        <?php if ($total_count > 0) {
            echo $pagerHTML;
        } else {
            echo '<i>'.iclicker_service::msg('admin.no.regs').'</i>';
        } ?>
    </div>

    <!-- clicker registration listing -->
    <table width="90%" border="1" cellspacing="0" cellpadding="0"
        summary="<?php echo iclicker_service::msg('admin.regs.table.summary') ?>">
        <thead>
            <tr class="registration_row header_row">
                <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <?php echo iclicker_service::msg('admin.username.header') ?>
                </th>
                <th width="20%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <a href="<?php echo $adminPath.'&sort=clicker_id&page='.$page ?>"><?php echo iclicker_service::msg('reg.remote.id.header') ?></a>
                </th>
                <th width="20%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                    <a href="<?php echo $adminPath.'&sort=timecreated&page='.$page ?>"><?php echo iclicker_service::msg('reg.registered.date.header') ?></a>
                </th>
                <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5" nowrap="nowrap">
                    <a href="<?php echo $adminPath.'&sort=activated&page='.$page ?>"><?php echo iclicker_service::msg('admin.controls.header') ?></a>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($registrations as $registration) { ?>
            <tr class="registration_row data_row style1 <?php echo $registration->activated ? '' : 'disabled' ?>">
                <td class="user_name" align="center"><?php echo $registration->user_display_name ?></td>
                <td class="clicker_id" align="center"><?php echo $registration->clicker_id ?></td>
                <td class="date" align="center"><?php echo iclicker_service::df($registration->timecreated) ?></td>
                <td class="controls" align="center">
                    <form method="post">
                        <input type="hidden" name="page" value="<?php echo $page ?>" />
                        <input type="hidden" name="sort" value="<?php echo $sort ?>" />
                        <input type="hidden" name="registrationId" value="<?php echo $registration->id ?>" />
                        <?php if ($registration->activated) { ?>
                        <input type="button" class="small" value="<?php echo iclicker_service::msg('app.activate') ?>" disabled="disabled" />
                        <input type="submit" class="small" value="<?php echo iclicker_service::msg('app.disable') ?>" alt="<?php echo iclicker_service::msg('reg.disable.submit.alt') ?>" />
                        <input type="hidden" name="activate" value="0" />
                        <?php } else { ?>
                        <input type="submit" class="small" value="<?php echo iclicker_service::msg('app.activate') ?>" alt="<?php echo iclicker_service::msg('reg.reactivate.submit.alt') ?>" />
                        <input type="button" class="small" value="<?php echo iclicker_service::msg('app.disable') ?>" disabled="disabled" />
                        <input type="hidden" name="activate" value="1" />
                        <?php } ?>
                    </form>
                    <form method="post">
                        <input type="hidden" name="page" value="<?php echo $page ?>" />
                        <input type="hidden" name="sort" value="<?php echo $sort ?>" />
                        <input type="hidden" name="registrationId" value="<?php echo $registration->id ?>" />
                        <input type="hidden" name="remove" value="0" />
                        <input type="submit" class="small" value="<?php echo iclicker_service::msg('app.remove') ?>" alt="<?php echo iclicker_service::msg('admin.remove.submit.alt') ?>" />
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php if (count($recent_failures) > 0) { ?>
<div class="admin_errors">
    <fieldset class="visibleFS">
        <legend class="admin_errors_header">
            <?php echo iclicker_service::msg('admin.errors.header') ?>
        </legend>
        <ul class="tight admin_errors_list">
            <?php foreach($recent_failures as $message) { ?>
            <li class="admin_errors_list_item"><?php echo $message ?></li>
            <?php } ?>
        </ul>
    </fieldset>
</div>
<?php } ?>

<div class="admin_config">
    <fieldset class="visibleFS">
        <legend class="admin_config_header">
            <?php echo iclicker_service::msg('admin.config.header') ?>
        </legend>
        <ul class="tight admin_config_list">
            <li class="admin_config_list_item"><?php echo iclicker_service::msg('admin.config.usewebservices') ?>: <?php echo $useNationalWebservices ? 'true':'false' ?></li>
            <li class="admin_config_list_item"><?php echo iclicker_service::msg('admin.config.domainurl') ?>: <?php echo $domainURL ?></li>
            <?php if ($useNationalWebservices) { ?>
            <li class="admin_config_list_item"><?php echo iclicker_service::msg('admin.config.syncenabled') ?>: <?php echo !$disableSyncWithNational ? 'true':'false' ?></li>
            <?php } ?>
        </ul>
    </fieldset>
</div>

<div class="iclicker_version">Version <?php echo iclicker_service::VERSION ?> (<?php echo iclicker_service::BLOCK_VERSION ?>)</div>

<?php print_footer(); ?>
