<?php
namespace guild\data\wow\encounter;
use guild\system\cache\runtime\WowInstanceRuntimeCache;
use wcf\data\DatabaseObject;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class Encounter extends DatabaseObject {
    public $instance = null;
	public $killCounter=0;

	public function getInstance() {
        if ($this->instance === null && $this->instanceID) {
            $this->instance = WowInstanceRuntimeCache::getInstance()->getObject($this->instanceID);
        }

        return $this->instance;
    }

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public static function getDatabaseTableName() {
        return 'guild' . WCF_N . '_wow_encounter';
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