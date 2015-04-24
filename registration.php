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
$cntlr->processRegistration();
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
    strip_tags($site->fullname).':'.iclicker_service::msg('app.iclicker').':'.iclicker_service::msg('reg.title'),
    iclicker_service::msg('app.iclicker').' '.iclicker_service::msg('reg.title'),
	build_navigation(iclicker_service::msg('reg.title')), // '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'">'.$COURSE->shortname.'</a> ->'.get_string('formtitle', 'block_iclicker'),
	'',
	"<meta name=\"description\" content=\"".s(strip_tags($site->summary))."\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"".iclicker_service::block_url('css/iclicker.css')."\" />",
    false
);

// show messages if there are any to show
require ('user_messages.php');
?>

<div class="main_content">
    <div style="float:left; width: 45%;">
        <!-- column one -->
        <!-- clicker registration listing -->
        <table width="100%" border="1" cellspacing="0" cellpadding="0"
            summary="<?php echo iclicker_service::msg('reg.registration.table.summary') ?>">
            <thead>
                <tr class="registration_row header_row">
                    <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                        <?php echo iclicker_service::msg('reg.remote.id.header') ?>
                    </th>
                    <th width="30%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                        <?php echo iclicker_service::msg('reg.registered.date.header') ?>
                    </th>
                    <th width="40%" scope="col" height="25" valign="middle" bgcolor="#e8e8e8" class="style5">
                        <?php echo iclicker_service::msg('admin.controls.header') ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($regs as $reg) { ?>
                <tr class="registration_row data_row style1 <?php echo ($reg->activated ? '' : 'disabled') ?>">
                    <td class="clicker_id" align="center"><?php echo $reg->clicker_id ?></td>
                    <td class="date" align="center"><?php echo iclicker_service::df($reg->timecreated) ?></td>
                    <td class="controls" align="center">
                        <form method="post">
                            <input type="hidden" name="registrationId" value="<?php echo $reg->id ?>" />
                            <?php if ($reg->activated) { ?>
                            <input type="button" class="small" value="<?php echo iclicker_service::msg('app.activate') ?>" disabled="disabled" />
                            <input type="submit" class="small" value="<?php echo iclicker_service::msg('app.disable') ?>" alt="<?php echo iclicker_service::msg('reg.disable.submit.alt') ?>" />
                            <input type="hidden" name="activate" value="false" />
                            <?php } else { ?>
                            <input type="submit" class="small" value="<?php echo iclicker_service::msg('app.activate') ?>" alt="<?php echo iclicker_service::msg('reg.reactivate.submit.alt') ?>" />
                            <input type="button" class="small" value="<?php echo iclicker_service::msg('app.disable') ?>" disabled="disabled" />
                            <input type="hidden" name="activate" value="true" />
                            <?php } ?>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- registration form area -->
        <div class="registration_entry_holder" style="background-color: #A2CDED; height: 35px; margin-top: 1em;">
            <div class="registration_entry style5">
                <?php echo iclicker_service::msg('reg.register.clickers') ?>:
                &nbsp;
                <form method="post" id="registerForm">
                    <input type="hidden" name="register" value="true" />
                    <input name="clickerId" type="text" size="10" maxlength="8" value="<?php echo $clicker_id_val ?>" />
                    <input type="submit" class="registerButton" value="<?php echo iclicker_service::msg('app.register') ?>"
                        alt="<?php echo iclicker_service::msg('reg.register.submit.alt') ?>" />
                </form>
            </div>
        </div>
        <?php if ($below_messages) { ?>
        <!-- registration below messages area -->
        <div class="registration_below_messages_holder style5" style="margin-top: 1em;">
            <div class="registration_below_messages">
                <?php foreach ($below_messages as $message) { ?>
                <p class="registration_below_message"><?php echo $message ?></p>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <!-- column two -->
    <!-- instructions and image -->
    <div style="margin-left: 45%; width: 40%;">
        <div style="padding-left:10px;">
            <div class="instructions style2" style="margin-bottom: 1em;">
                <?php
                    if ($new_reg) {
                        echo iclicker_service::msg('reg.registered.instructions');
                    } else {
                        echo iclicker_service::msg('reg.registration.instructions');
                    }
                ?>
            </div>
            <img src="<?php echo iclicker_service::block_url('img/Iclicker.jpg') ?>" border="0" alt="<?php echo iclicker_service::msg('reg.iclicker.image.alt') ?>" />
        </div>
    </div>
</div>

<div class="iclicker_version">Version <?php echo iclicker_service::VERSION ?> (<?php echo iclicker_service::BLOCK_VERSION ?>)</div>

<?php print_footer(); ?>
