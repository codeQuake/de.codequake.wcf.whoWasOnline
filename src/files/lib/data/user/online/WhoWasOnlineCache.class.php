<?php
/**
 * Contains the who was online cache decorator.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 * @package   de.codequake.wcf.whoWasOnline
 */

namespace wcf\data\user\online;

use InvalidArgumentException;
use wcf\system\cache\builder\WhoWasOnlineCacheBuilder;
use wcf\system\SingletonFactory;
use wcf\system\user\UserProfileHandler;
use wcf\system\WCF;

/**
 * Decorates the who was online cache with methods to access the cached user ids while respecting their privacy
 * settings.
 */
class WhoWasOnlineCache extends SingletonFactory
{
    /**
     * ids of the users that were online.
     * array structure: userID => 'canViewOnlineStatus' setting
     *
     * @var int[]
     */
    protected $userIDs = array();

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->userIDs = WhoWasOnlineCacheBuilder::getInstance()->getData();

        // Remove user itself. That prevents contradicting listings caused by an outdated cache.
        if (WHO_WAS_ONLINE_EXCLUDE_ACTIVE && WCF::getUser()->userID) {
            unset($this->userIDs[WCF::getUser()->userID]);
        }
    }

    /**
     * Returns the ids of all users of whose the active user can see the online status.
     *
     * @return int[]
     */
    public function getAccessibleUserIDs()
    {
        if (!WCF::getSession()->getPermission('user.profile.canViewUsersOnlineList')) {
            // user must have the permission to view the user online list
            return array();
        }

        $userIDs = array();

        foreach ($this->userIDs as $userID => $canViewOnlineStatus) {
            if ($canViewOnlineStatus === 0 ||
                $userID === WCF::getUser()->userID ||
                WCF::getSession()->getPermission('admin.user.canViewInvisible')
            ) {
                $userIDs[] = $userID;
            } elseif (WCF::getUser()->userID) {
                if ($canViewOnlineStatus === 1) {
                    $userIDs[] = $userID;
                } elseif (UserProfileHandler::getInstance()->isFollowing($userID)) {
                    $userIDs[] = $userID;
                }
            }
        }

        return $userIDs;
    }

    /**
     * Returns the users who where online. This function respects privacy settings of the users.
     *
     * @param string $displayMode 'avatars' or 'usernames'
     * @return array<\wcf\data\user\online\UserWasOnline>
     * @throws \InvalidArgumentException if the given display mode is invalid.
     */
    public function getAccessibleUsers($displayMode = 'avatars')
    {
        // validate given display mode
        if ($displayMode !== 'avatars' && $displayMode !== 'usernames') {
            throw new InvalidArgumentException(
                'Invalid display mode "' . $displayMode . '" given. Expected either "avatars" or "usernames".'
            );
        }

        $accessibleUserIDs = $this->getAccessibleUserIDs();

        if (count($accessibleUserIDs) !== 0) {
            $whoWasOnlineList = new WhoWasOnlineList($displayMode);
            $whoWasOnlineList->setObjectIDs($this->getAccessibleUserIDs());
            $whoWasOnlineList->readObjects();

            return $whoWasOnlineList->getObjects();
        }

        return array();
    }

    /**
     * Returns the ids of all users that were online. Notice that this function does not consider permissions to view
     * the online status.
     *
     * @return int[]
     */
    public function getUserIDs()
    {
        return array_keys($this->userIDs);
    }
}
