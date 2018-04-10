<?php
namespace guild\data\guild;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	public $className = Guild::class;
	
	/**
	 * @inheritDoc
	 */
	public function getActive() {
		parent::getConditionBuilder()->add('isActive = 1', []);
		parent::readObjects();
	}

    /**
     * @inheritDoc
     */
    public function getActiveByGameID($gameID) {
        parent::getConditionBuilder()->add('gameID = ?', [$gameID]);
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::readObjects();
    }
}