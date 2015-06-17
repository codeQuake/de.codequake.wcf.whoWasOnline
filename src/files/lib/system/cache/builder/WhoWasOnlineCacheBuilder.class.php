<?php
/**
 * Contains the who was online cache builder.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 * @package   de.codequake.wcf.whoWasOnline
 */

namespace wcf\system\cache\builder;

use wcf\data\user\User;
use wcf\system\WCF;

/**
 * Caches the user ids of all users who were online during the set threshold along with there privacy setting to see
 * their online status.
 */
class WhoWasOnlineCacheBuilder extends AbstractCacheBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->maxLifetime = 300;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $parameters
     * @return array
     * @throws \wcf\system\database\DatabaseException
     */
    protected function rebuild(array $parameters)
    {
        $data = array();

        $optionID = User::getUserOptionID('canViewOnlineStatus');
        switch (WHO_WAS_ONLINE_PERIOD) {
            case 'today':
                $threshold = mktime(0, 0, 0);
                break;

            case '24h':
                $threshold = TIME_NOW - 86400;
                break;
        }

        $sql = '
            SELECT user_table.userID, option_value.userOption' . $optionID . ' AS canViewOnlineStatus
            FROM wcf' . WCF_N . '_user user_table
            LEFT JOIN wcf' . WCF_N . '_session session ON (session.userID = user_table.userID)
            LEFT JOIN wcf' . WCF_N . '_user_option_value option_value ON (option_value.userID = user_table.userID)
            WHERE user_table.lastActivityTime > ?
                AND (session.sessionID IS NULL OR session.lastActivityTime < ?)
            ORDER BY user_table.' . WHO_WAS_ONLINE_SORT_FIELD . ' ' . WHO_WAS_ONLINE_SORT_ORDER;
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($threshold, TIME_NOW - USER_ONLINE_TIMEOUT));

        while ($row = $statement->fetchArray()) {
            $data[$row['userID']] = $row['canViewOnlineStatus'];
        }

        return $data;
    }
}
