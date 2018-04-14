<?php
namespace guild\data\wow\instance;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $instanceID	    unique id of the instance
 * @property-read	string		    $name		    name of the instance
 * @property-read	string		    $title		    title of the instance
 * @property-read	integer     	$mapID	        map id of the instance
 * @property-read	integer		    $isRaid	        is 1 if the instance is a raid
 * @property-read	integer	        $difficulty		raid difficulty
 * @property-read	integer		    $isActive	    is 1 if the instance is active
 */
class Instance extends DatabaseObject {
	/**
	 * @inheritDoc
	 */
	public static function getInstanceIdByZoneAndDifficulty($zone, $difficulty) {
		$sql = "SELECT	instance.instanceID
				FROM		guild".WCF_N."_instance instance
				WHERE		instance.difficulty = ?
					AND		instance.name LIKE ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$difficulty, '%'.$zone]);
		$row = $statement->fetchArray();
		if (!$row) $row = [];
		
		return new Instance(null, $row);
	}

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public static function getDatabaseTableName() {
        return 'guild' . WCF_N . '_wow_instance';
    }

    /**
     * Returns the name of the database table alias.
     *
     * @return	string
     */
    public static function getDatabaseTableAlias() {
        return 'wow_' . parent::getDatabaseTableAlias();
    }
}