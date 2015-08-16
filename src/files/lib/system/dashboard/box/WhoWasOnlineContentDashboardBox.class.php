<?php

/**
 * Contains the content dashboard box class of a list of users who were online.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */

namespace wcf\system\dashboard\box;

use wcf\data\dashboard\box\DashboardBox;
use wcf\data\user\online\WhoWasOnlineCache;
use wcf\page\IPage;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Content dashboard box of a list of users who were online.
 */
class WhoWasOnlineContentDashboardBox extends AbstractContentDashboardBox
{
    /**
     * List of user profiles.
     *
     * @var array<\wcf\data\user\UserProfile>
     */
    public $users = array();

    /**
     * {@inheritdoc}
     *
     * @param \wcf\data\dashboard\box\DashboardBox $box
     * @param \wcf\page\IPage                      $page
     *
     * @throws \wcf\system\exception\SystemException
     */
    public function init(DashboardBox $box, IPage $page)
    {
        parent::init($box, $page);

        // fetch users
        try {
            $this->users = WhoWasOnlineCache::getInstance()->getAccessibleUsers(WHO_WAS_ONLINE_CONTENT_DISPLAY, WHO_WAS_ONLINE_SORT_FIELD, WHO_WAS_ONLINE_SORT_ORDER);
        } catch (\InvalidArgumentException $e) {
            throw new SystemException(sprintf('Invalid value "%s" for option WHO_WAS_ONLINE_CONTENT_DISPLAY.', WHO_WAS_ONLINE_CONTENT_DISPLAY));
        }

        $this->fetched();
    }

    /**
     * Returns the formatted output for the who was online content box.
     *
     * @return string
     *
     * @throws \wcf\system\exception\SystemException
     */
    protected function render()
    {
        if (count($this->users) === 0) {
            return '';
        }

        WCF::getTPL()->assign(
            array(
                'whoWasOnline' => $this->users,
                'whoWasOnlineListCount' => count(WhoWasOnlineCache::getInstance()->getUserIDs()),
            )
        );

        return WCF::getTPL()->fetch('dashboardContentBoxWhoWasOnline');
    }
}
