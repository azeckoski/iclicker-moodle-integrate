<?php
/**
 * Copyright (c) 2012 i>clicker (R) <http://www.iclicker.com/dnn/>
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

// i18n file
$string['app.iclicker']         = 'i>clicker';
$string['app.title']            = 'i>clicker Moodle integrate';

// form controls
$string['app.register']     = 'Register';
$string['app.activate']     = 'Activate';
$string['app.disable']      = 'Disable';
$string['app.remove']       = 'Remove';

// for the main registration page
$string['reg.title'] = 'Remote Registration';
$string['reg.remote.id.header'] = 'i>clicker Remote ID';
$string['reg.registered.date.header'] = 'Registered';
$string['reg.register.clickers'] = 'Register additional clickers';
$string['reg.registration.table.summary'] = 'Lists the registered clickers for the current user; clickerId, date registered, and controls';
$string['reg.iclicker.image.alt'] = 'I-clicker Sample Remote showing the back of the remote with the location of the ID';
$string['reg.register.submit.alt'] = 'Register the clickerId';
$string['reg.disable.submit.alt'] = 'Disable this registration';
$string['reg.reactivate.submit.alt'] = 'Reactivate this registration';
$string['reg.registration.instructions'] = 'To locate your clicker ID, see the back of your remote and enter the series of numbers (and perhaps letters) on the white sticker on the bottom of your clicker.';
$string['reg.registered.instructions'] = 'You have successfully registered your i>clicker remote ID with the system. If you lose or need to tie a second clicker to your student/user ID, you can do so here by adding another clicker ID to your registration. As with your other registration, to locate your clicker ID, see the back of your remote and enter the series of numbers (and perhaps letters) on the white sticker on the bottom of your clicker.';
$string['reg.registered.success'] = 'Registered a Clicker ID ($a)';
$string['reg.registered.below.success'] = 'Congratulations; you\'ve successfully registered your i>clicker! All of your voting data (previously recorded and future votes) will now be tied to your ID.';
$string['reg.registered.clickerId.empty'] = 'The clicker ID cannot be empty, please fill in the box and try again';
$string['reg.registered.clickerId.duplicate'] = 'Clicker ID ($a) is already registered';
$string['reg.registered.below.duplicate'] = 'You have already successfully registered this clicker ($a) and it is tied to your user ID.';
$string['reg.registered.clickerId.duplicate.notowned'] = 'Your clicker ($a) has already been registered, but to another student. This could be a result of two possibilities: 1) You are sharing a clicker remote with another student in the same course. You may share your i>clicker remote with another student on campus as long as s/he is not in the same course. You cannot share i>clicker remotes with students in the same course/section. 2) You simply mis-entered your remote ID. Please try again. If you receive this message a second time, contact support@iclicker.com for additional help.';
$string['reg.registered.clickerId.invalid'] = 'The clicker ID ($a) is invalid, please correct the entry and try again';
$string['reg.activate.success.true'] = 'Reactivated clicker ($a) registration';
$string['reg.activate.success.false'] = 'Disabled clicker ($a) registration';
$string['reg.activate.registrationId.empty'] = 'The registrationId cannot be empty, internal error in the form';

// for the instructor page
$string['inst.title'] = 'Instructor Report';
$string['inst.all.courses'] = 'All Courses';
$string['inst.no.courses'] = 'No courses';
$string['inst.courses.header'] = 'Courses Listing';
$string['inst.courses.table.summary'] = 'Lists the courses taught by this instructor; title, link to students listing';
$string['inst.course'] = 'Course';
$string['inst.students'] = 'Students';
$string['inst.students.table.summary'] = 'Lists the students in the selected course; name, email, registration status';
$string['inst.student.name.header'] = 'Name';
$string['inst.student.email.header'] = 'Email';
$string['inst.student.status.header'] = 'Status';
$string['inst.student.registered.true'] = 'Registered';
$string['inst.student.registered.false'] = 'Not registered';
$string['inst.course.view.students'] = 'View Students';

// for the admin page
$string['admin.title'] = 'Admin Control';
$string['admin.process.header'] = 'Running process status:';
$string['admin.process.add'] = 'Add i>clicker to all workspaces';
$string['admin.process.remove'] = 'Remove i>clicker from all workspaces';
$string['admin.process.sync'] = 'Sync with Webservices';
$string['admin.process.type.add'] = 'Adding to workspaces';
$string['admin.process.type.remove'] = 'Removing from workspaces';
$string['admin.process.type.sync'] = 'Syncing with Webservices';
$string['admin.process.message.add'] = 'Adding i>clicker tool to all workspaces';
$string['admin.process.message.remove'] = 'Removing i>clicker tool from all workspaces';
$string['admin.process.message.sync'] = 'Syncing all clicker registrations with Webservices';
$string['admin.process.message.locked'] = 'Cannot start long running process ($a) because there is already one running on another server';
$string['admin.process.message.inprogress'] = 'Cannot start long running process ($a) because there is already one running';
$string['admin.paging'] = 'Paging:';
$string['admin.no.regs'] = 'No registrations';
$string['admin.regs.table.summary'] = 'Lists the registered clickers for all users for the admin; user name, clickerId, date registered, and controls';
$string['admin.remove.submit.alt'] = 'Remove this registration permanently';
$string['admin.username.header'] = 'User name';
$string['admin.controls.header'] = 'Controls';
$string['admin.activate.success.true'] = 'Reactivated clicker ($a->cid) registration for $a->user';
$string['admin.activate.success.false'] = 'Disabled clicker ($a->cid) registration for $a->user';
$string['admin.delete.success'] = 'Deleted clicker ($a->cid) registration ($a->rid) for $a->user';
$string['admin.config.header'] = 'i>clicker plugin configuration';
$string['admin.config.usewebservices'] = 'Use National Webservices';
$string['admin.config.domainurl'] = 'Domain URL';
$string['admin.config.workspacepagetitle'] = 'Workspace Tool Title';
$string['admin.config.syncenabled'] = 'National sync enabled';
$string['admin.config.synchour'] = 'Sync runs at hour';
$string['admin.errors.header'] = 'Most Recent Failures';


// Config
$string['config_general'] = 'General';
$string['config_notify_emails'] = 'The email addresses to send notifications to on failures, DEFAULT: none (disabled)';
$string['config_disable_alternateid'] = 'Whether to disable the use of alternate clicker IDs, DEFAULT: false (enable alternate clicker IDs)';
$string['config_webservices'] = 'WebServices';
$string['config_use_national_ws'] = 'Whether to use the webservices to store clicker registrations, DEFAULT: false';
$string['config_domain_url'] = 'The i>clicker domain URL, leave blank for DEFAULT: the Moodle server URL (e.g. http://your.server.edu)';
$string['config_webservices_url'] = 'The webservices URL, leave blank for DEFAULT: the URL for the national i>clicker webservices server';
$string['config_webservices_username'] = 'The webservices username, leave blank for the DEFAULT national webservices username';
$string['config_webservices_password'] = 'The webservices password, leave blank for the DEFAULT national webservices password';
$string['config_disable_sync'] = 'Disable the national webservices automatic synchronization, DEFAULT: false (sync enabled)';

// Main block
$string['leaveblanktohide'] = 'leave blank to hide';
$string['invalidcourse'] 	= 'Invalid CourseID: ';
$string['addpage'] 			= 'Add a Page';
$string['confirmdelete'] 	= 'Confirm Delete';
$string['deletepage'] 		= 'Do you really want to delete \'$a\'';
$string['inserterror']      = 'Insert failed';
$string['updateerror']      = 'Update failed';

?>
