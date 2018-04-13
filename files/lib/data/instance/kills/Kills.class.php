<?php
namespace guild\data\instance\kills;
use guild\system\cache\runtime\InstanceRuntimeCache;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class Kills extends DatabaseObject {
    /**
     * @inheritDoc
     */
    public $instance = null;

    /**
     * @inheritDoc
     */
    public static function getKillsByGuild($instanceID, $guildID) {
        $sql = "SELECT	kills.*
                FROM    guild".WCF_N."_instance_kills kills
                WHERE   kills.instanceID = ?
                AND     kills.guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$instanceID, $guildID]);
        $row = $statement->fetchArray();
        return ($row) ? new Kills(null, $row) : false;
    }

    /**
     * @inheritDoc
     */
    public function getInstance() {
        if ($this->instance === null && $this->instanceID) {
            $this->instance = InstanceRuntimeCache::getInstance()->getObject($this->instanceID);
        }

        return $this->instance;
    }

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public static function getDatabaseTableName() {
        return 'guild' . WCF_N . '_instance_kills';
    }
}