<?php
namespace guild\data\wow\statistic;
use wcf\data\DatabaseObjectList;
use wcf\util\DateUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class StatisticList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
    public $className = Statistic::class;
    
    public function getStats(array $members) {
        $week = DateUtil::getDateTimeByTimestamp(strtotime("-56 hour"));

    	$this->sqlSelects = "wow_statistic_type.value as typeValue, wow_statistic_type.value2 as typeValue2, wow_statistic_type.type";
    	$this->sqlJoins = "LEFT JOIN guild".WCF_N."_wow_statistic_type wow_statistic_type ON (wow_statistic_type.typeID = wow_statistic.typeID)";
		parent::getConditionBuilder()->add('wow_statistic.memberID IN (?)', [$members]);
        parent::getConditionBuilder()->add('wow_statistic.week IN (?)', [[$week->format("YW"), ($week->format("YW")-1)]]);
		
		parent::getConditionBuilder()->add('(wow_statistic_type.value IN (?) OR wow_statistic_type.value2 IN (?))', [
			['race', 'thumbnail', 'iLevel', 'level', 'lastModified', 'artefactweaponLevel'],
			['artefactPower', 'artefactKnowledge', 'worldQuest']
		]);
		parent::readObjects();

		$stats = [];
		if (!empty($this->objectIDs)) {
			foreach ($this->getObjects() as $stat) {

                if ($week->format("YW") == $stat->week) {
                    $weekKey = 'this';
                } else {
                    $weekKey = 'last';
                }

				if ($stat->type == 'charakter') {
                    $stats[$stat->memberID][$weekKey][$stat->typeValue] = $stat->value;
				} else if ($stat->type == 'achievements') {
                    $stats[$stat->memberID][$weekKey][$stat->typeValue2] = $stat->value;
				}
			}
		}

		return (sizeof($stats) > 1) ? $stats : reset($stats);
    }

	/**
	 * @inheritDoc
	 */
	public function getByMemberID($memberID) {
		if (empty($memberID)) {
			return [];
		}
		
		parent::getConditionBuilder()->add('memberID = ?', [$memberID]);
		parent::readObjects();
	}

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public function getDatabaseTableName() {
        return 'guild' . WCF_N . '_wow_statistic';
    }

    /**
     * Returns the name of the database table alias.
     *
     * @return	string
     */
    public function getDatabaseTableAlias() {
        return 'wow_' . parent::getDatabaseTableAlias();
    }
}