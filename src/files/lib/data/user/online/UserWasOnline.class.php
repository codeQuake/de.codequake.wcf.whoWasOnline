<?php

/**
 * Contains the database object to represent a user who was online.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */

namespace wcf\data\user\online;

use wcf\data\user\UserProfile;
use wcf\util\StringUtil;

/**
 * Represents a user who was online during the set threshold.
 *
 * @property string $username
 * @property string $userOnlineMarking
 */
class UserWasOnline extends UserProfile
{
    /**
     * Returns the formatted username.
     *
     * @return string
     */
    public function getFormattedUsername()
    {
        $username = StringUtil::encodeHTML($this->username);

        if ($this->userOnlineMarking && $this->userOnlineMarking !== '%s') {
            $username = sprintf($this->userOnlineMarking, $username);
        }

        return $username;
    }
}
