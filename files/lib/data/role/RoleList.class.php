<?php
namespace guild\data\role;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
    public $className = Role::class;
    
	/**
	 * @inheritDoc
	 */
    public function getActive($gameID) {
        parent::getConditionBuilder()->add('gameID = ?', [$gameID]);
    	parent::getConditionBuilder()->add('isActive = 1', []);
    	parent::readObjects();
     }

    /**
     * @inheritDoc
     */
    public function getActiveSortByGame() {
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::readObjects();

        $roles = [];
        if (!empty($this->objectIDs)) {
            foreach ($this->getObjects() as $role) {
                $roles[$role->gameID][$role->roleID] = $role;
            }
        }

        return $roles;
    }
}