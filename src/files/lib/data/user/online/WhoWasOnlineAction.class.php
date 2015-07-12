<?php

/**
 * Contains the database object action class for users who were online.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */

namespace wcf\data\user\online;

use wcf\data\IGroupedUserListAction;
use wcf\data\user\UserAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\user\GroupedUserList;
use wcf\system\WCF;

/**
 * Contains actions related to the who was online package.
 */
class WhoWasOnlineAction extends UserAction implements IGroupedUserListAction
{
    /**
     * Validates the permissions to get a grouped list of users who were online.
     *
     * @throws \wcf\system\exception\PermissionDeniedException
     */
    public function validateGetGroupedUserList()
    {
        if (!WCF::getSession()->getPermission('user.profile.canViewUsersOnlineList')) {
            throw new PermissionDeniedException();
        }

        $this->readInteger('pageNo');
    }

    /**
     * Returns a formatted and grouped list of users who were online during the set threshold.
     *
     * @return array
     */
    public function getGroupedUserList()
    {
        $userIDs = WhoWasOnlineCache::getInstance()->getAccessibleUserIDs();
        $pageCount = ceil(count($userIDs) / 20);

        // create grouped list
        $group = new GroupedUserList();
        $group->addUserIDs(array_splice($userIDs, ($this->parameters['pageNo'] - 1) * 20, 20));

        // load user profiles
        GroupedUserList::loadUsers();

        WCF::getTPL()->assign(array(
            'groupedUsers' => array($group),
        ));

        return array(
            'pageCount' => $pageCount,
            'template' => WCF::getTPL()->fetch('groupedUserList'),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function __init($baseClass, $indexName)
    {
        parent::__init($baseClass, $indexName);

        $this->allowGuestAccess = array('getGroupedUserList');
    }
}
