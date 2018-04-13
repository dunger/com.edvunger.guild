<?php
namespace guild\data\instance\kills;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class KillsList extends DatabaseObjectList {
    /**
     * @inheritDoc
     */
    public $className = Kills::class;

    /**
     * Returns the name of the database table.
     *
     * @return	string
     */
    public function getDatabaseTableName() {
        return 'guild' . WCF_N . '_instance_kills';
    }
}