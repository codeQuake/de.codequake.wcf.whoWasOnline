<?php

/**
 * Contains the who was online database object list class.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */

namespace wcf\data\user\online;

use wcf\data\DatabaseObjectList;
use wcf\data\user\UserProfileList;

/**
 * Represents a list of users who where online during the set threshold.
 */
class WhoWasOnlineList extends UserProfileList
{
    /**
     * @param string $displayMode
     *
     * @throws \InvalidArgumentException if the given display mode is invalid.
     */
    public function __construct($displayMode)
    {
        $this->sqlOrderBy = 'user_table.lastActivityTime DESC';

        switch ($displayMode) {
            case 'usernames':
                // nothing to do
                break;

            case 'avatars':
                if ($this->sqlSelects !== '') {
                    $this->sqlSelects .= ', ';
                }

                $this->sqlSelects .= 'user_avatar.*';
                $this->sqlJoins .= ' LEFT JOIN wcf'.WCF_N.'_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)';
                break;

            default:
                throw new \InvalidArgumentException(sprintf('Invalid display mode "%s" given. Expected either "avatars" or "usernames".', $displayMode));
        }

        DatabaseObjectList::__construct();
    }
}
