<?php
namespace guild\data\wow\encounter;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class EncounterList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
    public $className = Encounter::class;
    
	/**
	 * @inheritDoc
	 */
    public function getEncounterByInstanceID($instanceID) {
		if (!(int)$instanceID) {
			return [];
		}
		
		parent::getConditionBuilder()->add('instanceID = ?', [(int)$instanceID]);
		parent::readObjects();
    }
}