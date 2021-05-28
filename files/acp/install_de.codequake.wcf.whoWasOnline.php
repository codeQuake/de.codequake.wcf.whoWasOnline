<?php

/**
 * Installation script for the who was online list.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */
use wcf\system\dashboard\DashboardHandler;

DashboardHandler::setDefaultValues('com.woltlab.wcf.user.DashboardPage', array(
    'de.codequake.wcf.whoWasOnline' => 5,
));
