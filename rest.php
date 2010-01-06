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
/* $Id: rest.php 9 2009-11-28 17:10:13Z azeckoski $ */

// this includes lib/setup.php and the standard set:
//setup.php : setup which creates the globals
//'/textlib.class.php');   // Functions to handle multibyte strings
//'/weblib.php');          // Functions for producing HTML
//'/dmllib.php');          // Functions to handle DB data (DML) - inserting, updating, and retrieving data from the database
//'/datalib.php');         // Legacy lib with a big-mix of functions. - user, course, etc. data lookup functions
//'/accesslib.php');       // Access control functions - context, roles, and permission related functions
//'/deprecatedlib.php');   // Deprecated functions included for backward compatibility
//'/moodlelib.php');       // general-purpose (login, getparams, getconfig, cache, data/time)
//'/eventslib.php');       // Events functions
//'/grouplib.php');        // Groups functions

//ddlib.php : modifying, creating, or deleting database schema
//blocklib.php : functions to use blocks in a typical course page
//formslib.php : classes for creating forms in Moodle, based on PEAR QuickForms

require_once ('../../config.php');
global $CFG,$USER,$COURSE;
require_once ('iclicker_service.php');
require_once ('controller.php');


// INTERNAL METHODS
/**
 * This will check for a user and return the user_id if one can be found
 * @param string $msg the error message
 * @return the user_id
 * @throws SecurityException if no user can be found
 */
function get_and_check_current_user($msg) {
    $user_id = iclicker_service::get_current_user_id();
    if (! isset($user_id)) {
        throw new SecurityException("Only logged in users can $msg");
    }
    if (! iclicker_service::is_admin($user_id) && ! iclicker_service::is_instructor($user_id)) {
        throw new SecurityException("Only instructors can " + $msg);
    }
    return $user_id;
}

/**
 * Attempt to authenticate the current request based on request params
 * @param object $cntlr the controller instance
 * @throws SecurityException if authentication is impossible given the request values
 */
function handle_authn($cntlr) {
    // extract the authn params
    $auth_username = optional_param(iclicker_controller::LOGIN, NULL, PARAM_RAW);
    $auth_password = optional_param(iclicker_controller::PASSWORD, NULL, PARAM_RAW);
    $session_id = optional_param(iclicker_controller::SESSION_ID, NULL, PARAM_RAW);
    if ($auth_username) {
        iclicker_service::authenticate_user($auth_username, $auth_password); // throws exception if fails
    } else if ($session_id) {
        $valid = FALSE; // @todo validate the session key
        if (! $valid) {
            throw new SecurityException("Invalid "+iclicker_controller::SESSION_ID+" provided, session may have expired, send new login credentials");
        }
    }
    $current_user_id = iclicker_service::get_current_user_id();
    if (isset($current_user_id)) {
        $cntlr->setHeader(iclicker_controller::SESSION_ID, sesskey());
        $cntlr->setHeader('_userId', $current_user_id);
    }
}


// REST HANDLING

//require_login();
//echo "me=".me().", qualified=".qualified_me();
//echo "user: id=".$USER->id.", auth=".$USER->auth.", username=".$USER->username.", lastlogin=".$USER->lastlogin."\n";
//echo "course: id=".$COURSE->id.", title=".$COURSE->fullname."\n";
//echo "CFG: wwwroot=".$CFG->wwwroot.", httpswwwroot=".$CFG->httpswwwroot.", dirroot=".$CFG->dirroot.", libdir=".$CFG->libdir."\n";

// activate the controller
$cntlr = new iclicker_controller(true); // with body

// init the vars to success
$valid = true;
$status = 200; // ok
$output = '';

// check to see if this is one of the paths we understand
if (! $cntlr->path) {
    $valid = false;
    $output = "Unknown path ($cntlr->path) specified"; 
    $status = 404; // not found
}
if ($valid 
        && "POST" != $cntlr->method 
        && "GET" != $cntlr->method) {
    $valid = false;
    $output = "Only POST and GET methods are supported";
    $status = 405; // method not allowed
}
if ($valid) {
    // check against the ones we know and process
    $parts = split('/', $cntlr->path);
    $pathSeg0 = count($parts) > 0 ? $parts[0] : NULL;
    $pathSeg1 = count($parts) > 1 ? $parts[1] : NULL;
    try {
        // handle the request authn if needed
        handle_authn($cntlr);
        if ("GET" == $cntlr->method) {
            if ("courses" == $pathSeg0) {
                // handle retrieving the list of courses for an instructor
                $user_id = get_and_check_current_user("access instructor courses listings");
                $output = iclicker_service::encode_courses($user_id);

            } else if ("students" == $pathSeg0) {
                // handle retrieval of the list of students
                $course_id = $pathSeg1;
                if ($course_id == null) {
                    throw new InvalidArgumentException(
                            "valid course_id must be included in the URL /students/{course_id}");
                }
                get_and_check_current_user("access student enrollment listings");
                $output = iclicker_service::encode_enrollments($course_id);

            } else {
                // UNKNOWN
                $valid = false;
                $output = "Unknown path ($cntlr->path) specified"; 
                $status = 404; //NOT_FOUND
            }
        } else {
            // POST
            if ("gradebook" == $pathSeg0) {
                // handle retrieval of the list of students
                $course_id = $pathSeg1;
                if ($course_id == null) {
                    throw new InvalidArgumentException(
                            "valid course_id must be included in the URL /gradebook/{course_id}");
                }
                get_and_check_current_user("upload grades into the gradebook");
                $xml = $cntlr->body;
                try {
                    $gradebook = iclicker_service::decode_gradebook($xml);
                    // process gradebook data
                    $results = iclicker_service::save_gradebook($gradebook);
                    // generate the output
                    $output = iclicker_service::encode_gradebook_results($results);
                    if (! $output) {
                        // special RETURN, non-XML, no failures in save
                        $cntlr->setStatus(200);
                        $cntlr->setContentType("text/plain");
                        $output = "True";
                        $cntlr->sendResponse($output);
                        return; // SHORT CIRCUIT
                    } else {
                        // failures occurred during save
                        $status = 200; //OK;
                    }
                } catch (InvalidArgumentException $e) {
                    // invalid XML
                    $valid = false;
                    $output = "Invalid gradebook XML in request, unable to process:/n $xml";
                    $status = 400; //BAD_REQUEST;
                }

            } else if ("authenticate" == $pathSeg0) {
                get_and_check_current_user("authenticate via iclicker");
                // special return, non-XML
                $cntlr->setStatus(204); //No content
                $cntlr->sendResponse();
                return; // SHORT CIRCUIT

            } else if ("register" == $pathSeg0) {
                get_and_check_current_user("upload registrations data");
                $xml = $cntlr->body;
                $cr = iclicker_service::decode_registration($xml);
                $owner_id = $cr->owner_id;
                $message = '';
                $reg_status = false;
                try {
                    iclicker_service::create_clicker_registration($cr->clicker_id, $owner_id);
                    // valid registration
                    $message = iclicker_service::msg('reg.registered.below.success', $cr->clicker_id);
                    $reg_status = true;
                } catch (ClickerIdInvalidException $e) {
                    // invalid clicker id
                    $message = iclicker_service::msg('reg.registered.clickerId.invalid', $cr->clicker_id);
                } catch (InvalidArgumentException $e) {
                    // invalid user id
                    $message = "Student not found in the CMS";
                } catch (ClickerRegisteredException $e) {
                    // already registered
                    $key = '';
                    if ($e->owner_id == $e->registered_owner_id) {
                        // already registered to this user
                        $key = 'reg.registered.below.duplicate';
                    } else {
                        // already registered to another user
                        $key = 'reg.registered.clickerId.duplicate.notowned';
                    }
                    $message = iclicker_service::msg($key, $cr->clicker_id);
                }
                $registrations = iclicker_service::get_registrations_by_user($owner_id, true);
                $output = iclicker_service::encode_registration_result($registrations, $reg_status, $message);
                if ($reg_status) {
                    $status = 200; //OK;
                } else {
                    $status = 400; //BAD_REQUEST;
                }

            } else {
                // UNKNOWN
                $valid = false;
                $output = "Unknown path ($path) specified"; 
                $status = 404; //NOT_FOUND;
            }
        }
    } catch (SecurityException $e) {
        $valid = false;
        $current_user_id = currentUser();
        if ($current_user_id == null) {
            $output = "User must be logged in to perform this action: " . $e;
            $status = 403; //UNAUTHORIZED;
        } else {
            $output = "User ($current_user_id) is not allowed to perform this action: " . $e;
            $status = 401; //FORBIDDEN;
        }
    } catch (InvalidArgumentException $e) {
        $valid = false;
        $output = "Invalid request: " . $e;
        //log.warn("i>clicker: " + $output, $e);
        $status = 400; //BAD_REQUEST;
    } catch (Exception $e) {
        $valid = false;
        $output = "Failure occurred: " . $e;
        //log.warn("i>clicker: " + $output, $e);
        $status = 500; //INTERNAL_SERVER_ERROR;
    }
}
if ($valid) {
    // send the response
    $cntlr->setStatus(200);
    $cntlr->setContentType('application/xml');
    $output = iclicker_controller::XML_HEADER . $output;
    $cntlr->sendResponse($output);
} else {
    // error with info about how to do it right
    $cntlr->setStatus($status);
    $cntlr->setContentType('text/plain');
    // add helpful info to the output
    $msg = "ERROR $status: Invalid request (".$cntlr->method." /".$cntlr->path.")" .
        "\n\n=INFO========================================================================================\n".
        $output.
        "\n\n-HELP----------------------------------------------------------------------------------------\n".
        "Valid request paths include the following (without the block prefix: ".iclicker_service::block_url('rest.php')."):\n".
        "POST /authenticate             - authenticate by sending credentials (".iclicker_controller::LOGIN.",".iclicker_controller::PASSWORD.") \n".
        "                                 return status 204 (valid login) \n".
        "POST /register                 - Add a new clicker registration, return 200 for success or 400 with \n".
        "                                 registration response (XML) for failure \n".
        "GET  /courses                  - returns the list of courses for the current user (XML) \n".
        "GET  /students/{course_id}     - returns the list of student enrollments for the given course (XML) \n".
        "                                 or 403 if user is not an instructor in the specified course \n".
        "POST /gradebook/{course_id}    - send the gradebook data into the system, returns errors on failure (XML) \n".
        "                                 or 'True' if no errors, 400 if the xml is missing or course_id is invalid, \n".
        "                                 403 if user is not an instructor in the specified course \n".
        "\n".
        "Authenticate by sending credentials (".iclicker_controller::LOGIN.",".iclicker_controller::PASSWORD.") or by sending a valid session id (".iclicker_controller::SESSION_ID.") in the request parameters \n".
        "The response headers will include the sessionId when credentials are valid \n".
        "Invalid credentials or sessionId will result in a 401 (invalid credentials) or 403 (not authorized) status \n".
        "NOTE: all endpoints return 403 if user is not an instructor \n";
    $cntlr->sendResponse($msg);
}

?>