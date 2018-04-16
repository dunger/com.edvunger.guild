<?php
namespace guild\data\member;
use guild\data\wow\statistic\StatisticList;
use guild\util\RaidUtil;
use wcf\data\DatabaseObjectList;
use wcf\system\cache\runtime\UserProfileRuntimeCache;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberList extends DatabaseObjectList {
    /**
     * @inheritDoc
     */
    public $className = Member::class;

    /**
     * @inheritDoc
     */
    public function getMemberByIDs(array $memberIDs, $guildID, array $userIDs=[], $missing=false) {
        $members = [];

        if (empty($memberIDs) && $missing === false) {
            return [];
        }

        if ($missing === false) {
            parent::getConditionBuilder()->add('memberID IN (?)', [$memberIDs]);
        } else {
            if (!empty($memberIDs)) {
                parent::getConditionBuilder()->add('memberID NOT IN (?)', [$memberIDs]);
            }
            if (!empty($memberIDs)) {
                parent::getConditionBuilder()->add('userID NOT IN (?)', [$userIDs]);
            }
            parent::getConditionBuilder()->add('isMain = 1', []);
        }

        parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        parent::getConditionBuilder()->add('isActive = 1', []);

        parent::readObjects();

        $userIDs = [];
        if (!empty($this->objectIDs)) {
            foreach ($this->getObjects() as $member) {
                $members[$member->userID] = $member;
                if ($missing === true) {
                    $userIDs[] = $member->userID;
                }
            }
        }

        if (!empty($this->objectIDs) && $missing === true) {
            $users = UserProfileRuntimeCache::getInstance()->getObjects($userIDs);

            foreach ($this->getObjects() as $member) {
                if ($member->userID) {
                    $members[$member->userID]->profile = $users[$member->userID];
                }
            }
        }

        return $members;
    }

    /**
     * @inheritDoc
     */
    public function getActiveByUserID($userID, $guildID = null) {
        $this->sqlOrderBy = 'isMain DESC, name DESC';
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::getConditionBuilder()->add('userID = ?', [$userID]);
        if ($guildID) {
            parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        }
        parent::readObjects();
    }

    /**
     * @inheritDoc
     */
    public function getMemberByUserIDs(array $userIDs, $guildID, $missing=false) {
        $members = [];

        if (empty($userIDs) && $missing === false) {
            return [];
        }

        if ($missing === false) {
            parent::getConditionBuilder()->add('userID IN (?)', [$userIDs]);
        } else {
            if (empty($userIDs)) {
                parent::getConditionBuilder()->add('(userID IS NULL)', []);
            } else {
                parent::getConditionBuilder()->add('(userID NOT IN (?) OR userID IS NULL)', [$userIDs]);
            }
        }

        parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        parent::getConditionBuilder()->add('isActive = 1', []);

        parent::readObjects();

        if (!empty($this->objectIDs)) {
            foreach ($this->getObjects() as $member) {
                if ($missing === false) {
                    $members[$member->userID] = $member;
                } else {
                    $members[$member->memberID] = $member;
                }

            }
        }

        return $members;
    }

    /**
     * @inheritDoc
     */
    public function getActiveWithStatsByGuildID($guildID) {
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        parent::readObjects();

        if (empty($this->objectIDs)) {
            return null;
        }

        $statsList = new StatisticList();
        $stats = $statsList->getStats(array_keys($this->getObjects()));

        foreach ($this->getObjects() as $member) {
            $member->nameNormalize = RaidUtil::normalizeUtf8String($member->name);

            if(isset($stats[$member->memberID])) {
                $member->stats = $stats[$member->memberID];
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getInactive($guildID=null) {
        if ($guildID !== null) {
            parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        }
        parent::getConditionBuilder()->add('isActive = 0', []);
        parent::getConditionBuilder()->add('isApiActive = 0', []);
        parent::readObjects();
    }

    /**
     * @inheritDoc
     */
    public function getByGuildID($guildID, $mainCharOnly=false) {
        if ($mainCharOnly) {
            parent::getConditionBuilder()->add('isMain = ?', [$mainCharOnly]);
        }
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::getConditionBuilder()->add('guildID = ?', [$guildID]);
        parent::readObjects();
    }
}