<?php
namespace guild\data\wow\instance;
use guild\system\guild\GuildHandler;
use wcf\data\DatabaseObjectList;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
    public $className = Instance::class;

    /**
     * @inheritDoc
     */
    public function getInstances() {
        parent::readObjects();
    }

	/**
	 * @inheritDoc
	 */
    public function getRaids() {
    	parent::getConditionBuilder()->add('isRaid = 1', []);
    	parent::readObjects();
     }

    /**
     * @inheritDoc
     */
    public function getProgress($guildIDs) {
        $data = [];

        $sql = "SELECT		wow_instance.title, wow_encounter_kills.guildID,
							count(DISTINCT wow_encounter.encounterID) as encounters,
							SUM(wow_encounter_kills.isKilled) as kills
        		FROM		guild".WCF_N."_wow_instance wow_instance
        		LEFT JOIN	guild".WCF_N."_wow_encounter wow_encounter ON (wow_encounter.instanceID = wow_instance.instanceID)
        		LEFT JOIN	guild".WCF_N."_wow_encounter_kills wow_encounter_kills ON (wow_encounter_kills.encounterID = wow_encounter.encounterID)
        		WHERE		wow_instance.isActive = 1
        			AND		wow_instance.isRaid = 1
        			AND     wow_encounter_kills.guildID IN (?".str_repeat(',?', count($guildIDs) - 1).")
        		GROUP BY	wow_instance.title, wow_encounter_kills.guildID
        		ORDER BY	wow_instance.instanceID ASC";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($guildIDs);
        while ($row = $statement->fetchArray()) {
            if (!isset($data[$row['guildID']])) {
                $data[$row['guildID']] = [
                    'guild' => GuildHandler::getInstance()->getGuild($row['guildID']),
                    'data'  => []
                ];
            }

            $data[$row['guildID']]['data'][] = [
                'title'		 => $row['title'],
                'encounters' => $row['encounters'],
                'kills'		 => $row['kills'],
                'percent'	 => ($row['encounters'] > 0) ? ($row['kills'] * 100 / $row['encounters']) : 0,
            ];
        }

        return $data;
    }

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public function getDatabaseTableName() {
        return 'guild' . WCF_N . '_wow_instance';
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