<?php
/**
 * Contains the event listener to reset the who was online cache.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 * @package   de.codequake.wcf.whoWasOnline
 */

namespace wcf\system\event\listener;

use wcf\system\cache\builder\WhoWasOnlineCacheBuilder;

/**
 * Force resetting the who was online cache under the following scenarios:
 * - logout of an user
 * - options were changed
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
