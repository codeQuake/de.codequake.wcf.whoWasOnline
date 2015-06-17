<?php
/**
 * Contains the event listener to reset the who was online cache upon logout of an user.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 * @package   de.codequake.wcf.whoWasOnline
 */

namespace wcf\system\event\listener;

use wcf\system\cache\builder\WhoWasOnlineCacheBuilder;

/**
 * Force resetting the who was online cache when a user logs out.
 */
class ForceResetWhoWasOnlineListener implements IParameterizedEventListener
{
    /**
     * {@inheritdoc}
     *
     * @param object $eventObj
     * @param string $className
     * @param string $eventName
     * @param array  $parameters
     * @throws \wcf\system\exception\SystemException
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        WhoWasOnlineCacheBuilder::getInstance()->reset();
    }
}
