<?php
/**
 * Contains the who was online database object list class.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 * @package   de.codequake.wcf.whoWasOnline
 */

namespace wcf\data\user\online;

use InvalidArgumentException;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of users who where online during the set threshold.
 */
class WhoWasOnlineList extends DatabaseObjectList
{
    /**
     * @param string $displayMode
     * @throws \InvalidArgumentException if the given display mode is invalid.
     */
    public function __construct($displayMode)
    {
        $this->decoratorClassName = 'wcf\data\user\online\UserWasOnline';
        $this->sqlOrderBy = 'user_table.lastActivityTime DESC';

        switch ($displayMode) {
            case 'avatars':
                if ($this->sqlSelects !== '') {
                    $this->sqlSelects .= ', ';
                }

                $this->sqlSelects .= 'user_avatar.*';
                $this->sqlJoins .= ' LEFT JOIN wcf' . WCF_N . '_user_avatar user_avatar ON (user_avatar.avatarID =
                user_table.avatarID)';
                break;

            case 'usernames':
                if ($this->sqlSelects !== '') {
                    $this->sqlSelects .= ', ';
                }

                $this->sqlSelects .= 'user_group.userOnlineMarking';
                $this->sqlJoins .= ' LEFT JOIN wcf' . WCF_N . '_user_group user_group ON (user_group.groupID =
                user_table.userOnlineGroupID)';
                break;

            default:
                throw new InvalidArgumentException('Invalid display mode "' . $displayMode . '" given. Expected
                either "avatars" or "usernames".');
        }

        parent::__construct();
    }
}
