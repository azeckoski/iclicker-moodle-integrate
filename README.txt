i>clicker Moodle integrate
==========================
This is the Moodle i>clicker integrate plugin which integrates Moodle with iClicker (http://www.iclicker.com/dnn/)
The plugin (block) allows students to register their clickers with the Moodle installation.
It provides an administrative interface for managing the registrations for Moodle admins.
Instructors are provided with a reporting view which allows them to view a listing of the students in their courses which have and have not registered clickers.
The plugin also provides integration with the i>clicker and i>grader desktop applications and allows direct grade import and export from the Moodle course gradebook.

Moodle Compatibility
--------------------
This plugin will work with Moodle 1.9.x. It is developed as a Moodle plugin/block.
NOTE: This is the final release for this plugin. If you need new features like SSO support then you will need to upgrade to Moodle 2.

Download Binary
---------------
The plugin will be made available via the moodle plugins listing::

    http://moodle.org/mod/data/view.php?id=6009

It can also be downloaded from the project site::

    http://code.google.com/p/iclicker-moodle-integrate/

Source
------
The source code for this plugin is located at::

    - trunk (unstable): http://iclicker-moodle-integrate.googlecode.com/svn/trunk/moodle19/
    - tags (stable): http://iclicker-moodle-integrate.googlecode.com/svn/tags/moodle19/

Install
-------
To install this plugin just extract the contents into your server dir MOODLE_HOME/blocks (so you have MOODLE_HOME/blocks/iclicker).

Once the plugin is installed, you can place the block into your instance.
This is the recommended way to setup the block::

    1. Login to your Moodle instance as an admin
    2. Click on Site Administration > Notifications
    3. Confirm the installation of the iclicker block (continue confirmation until complete)

See the Moodle docs for help installing plugins/blocks::

    http://docs.moodle.org/en/Installing_contributed_modules_or_plugins

Unit Tests
----------
If you are interested you can run the unit tests for the plugin to verify that it is compatible with your installation.
If all the tests pass then you can be confident that the plugin will work correctly.
NOTE: You need to have at least 1 user (other than the admin) in your moodle instance to run the tests successfully.
Go to the following URL in your moodle instance when logged in as an admin::

    /admin/report/unittest/index.php?path=blocks%2Ficlicker

Configuration
-------------
The configuration of the block is handled in the typical Moodle way. You must login as an administrator and then go to::

    Site Administration > Modules > Blocks > Manage blocks > i>clicker > Settings

Usage
-----
Once the installation is complete the i>clicker block should appear in the block lists and can be added anywhere
that a standard block can. It will determine permissions automatically so you can place it anywhere in your Moodle
installation that you see fit. The instructions below cover the recommended setup method but you are welcome
to place the block anywhere you like.

Adding the plugin/block to My Moodle for all users::

    # Login to your Moodle instance as an admin
    # Click on Site Administration > Modules > Blocks > Sticky blocks
    # Select My Moodle from the pulldown
        - NOTE: You should have My Moodle enabled under Site Administration > Appearance > My Moodle > mymoodleredirect
    # Select i>clicker from the Blocks pulldown

Adding the plugin/block to a specific user home::

    # Login to your Moodle instance
    # Click your site name in the upper left to go back to the site root
    # Click on the Turn editing on button in the upper right
    # Select i>clicker from the Blocks pulldown
    # Click on the Turn editing off button in the upper right

Configuring the system settings for the plugin/block::

    # Login to your Moodle instance as an admin
    # Click on Site Administration > Modules > Blocks > Manage blocks
    # Click on Settings to the right of the i>clicker listing
    # Adjust the block system settings according to your needs
    # Block setup is complete

REST data feeds
---------------
The REST data feeds for the block are documented and located at::

    /blocks/iclicker/rest.php

Release Process
---------------
Create a new tag of the code to release, then create a new binary and place it on the site::

    svn export http://iclicker-moodle-integrate.googlecode.com/svn/tags/TAG iclicker
    zip -r iclicker-VERSION.zip iclicker

Help
----
Send questions or comments to:
http://www.iclicker.com/contact/

This document is in `reST (reStructuredText) <http://docutils.sourceforge.net/rst.html>`_ format
and can be converted to html using the `online converter <http://www.tele3.cz/jbar/rest/rest.html>`_
or the `rst2a converter api <http://rst2a.com/api/>`_ or a command line tool (rst2html.py README README.html)

-Aaron Zeckoski (azeckoski @ vt.edu)
