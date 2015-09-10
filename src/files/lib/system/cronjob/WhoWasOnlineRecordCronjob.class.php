<?php

/**
 * Contains the cronjob to calculate the user was online record at midnight.
 *
 * @author    Florian Frantzen <ray176@me.com>
 * @copyright 2015 codequake.de
 * @license   LGPL
 */

namespace wcf\system\cronjob;

use wcf\data\cronjob\Cronjob;
use wcf\data\option\OptionAction;
use wcf\system\WCF;

/**
 * Re-calculates the user was online record at midnight.
 */
class WhoWasOnlineRecordCronjob implements ICronjob
{
    /**
     * {@inheritdoc}
     */
    public function execute(Cronjob $cronjob)
    {
        if (!WHO_WAS_ONLINE_ENABLE_RECORD) {
            return;
        }

        $sql = 'SELECT COUNT(*) FROM wcf'.WCF_N.'_user user_table WHERE lastActivityTime >= ? AND lastActivityTime < ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array(strtotime('yesterday'), strtotime('today')));
        $count = $statement->fetchSingleColumn();

        if ($count > WHO_WAS_ONLINE_RECORD) {
            $optionAction = new OptionAction(array(), 'import', array(
                'data' => array(
                    'who_was_online_record' => $count,
                    'who_was_online_record_time' => strtotime('yesterday'),
                ),
            ));
            $optionAction->executeAction();
        }
    }
}
