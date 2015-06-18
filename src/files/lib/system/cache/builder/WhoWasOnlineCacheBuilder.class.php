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
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\SystemException;
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

            default:
                throw new SystemException(
                    'Invalid value "' . WHO_WAS_ONLINE_CONTENT_DISPLAY . '" for option WHO_WAS_ONLINE_CONTENT_DISPLAY.'
                );
        }

        $conditionBuilder = new PreparedStatementConditionBuilder();
        $conditionBuilder->add('user_table.lastActivityTime > ?', array($threshold));

        $additionalJoin = '';
        if (WHO_WAS_ONLINE_EXCLUDE_ACTIVE) {
            $additionalJoin = 'LEFT JOIN wcf' . WCF_N . '_session session ON (session.userID = user_table.userID)';
            $conditionBuilder->add(
                '(session.sessionID IS NULL OR session.lastActivityTime < ?)',
                array(TIME_NOW - USER_ONLINE_TIMEOUT)
            );
        }

        $sql = '
            SELECT user_table.userID, option_value.userOption' . $optionID . ' AS canViewOnlineStatus
            FROM wcf' . WCF_N . '_user user_table
            LEFT JOIN wcf' . WCF_N . '_user_option_value option_value ON (option_value.userID = user_table.userID)
            ' . $additionalJoin . '
            ' . $conditionBuilder . '
            ORDER BY user_table.' . WHO_WAS_ONLINE_SORT_FIELD . ' ' . WHO_WAS_ONLINE_SORT_ORDER;

        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($conditionBuilder->getParameters());

        while ($row = $statement->fetchArray()) {
            $data[$row['userID']] = $row['canViewOnlineStatus'];
        }

        return $data;
    }
}
