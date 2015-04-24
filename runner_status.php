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
 * checks the status of the current sync process for completion
 */
 
require_once ('../../config.php');
global $CFG,$USER,$COURSE;
require_once ('iclicker_service.php');

header("HTTP/1.0 200 OK");
header("Content-Type: application/json");

$runner_time_key = get_config(iclicker_service::BLOCK_RUNNER_KEY);
$runner_exists = ((isset($runner_time_key) && $runner_time_key > 0) ? true : false);
$runner_percent = $runner_exists ? 50 : 100;
$runner_complete = $runner_exists ? 'false' : 'true';
$runner_error = "";

?>{"type": "sync", "percent": <?php echo $runner_percent ?>, "complete": <?php echo $runner_complete ?>, "error": "<?php echo $runner_error ?>"}