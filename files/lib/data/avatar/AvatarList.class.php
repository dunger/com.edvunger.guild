<?php
namespace guild\data\avatar;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class AvatarList extends DatabaseObjectList {
    /**
     * @inheritDoc
     */
    public $className = Avatar::class;

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
    public function getActiveByGame($gameID) {
        parent::getConditionBuilder()->add('isActive = 1', []);
        parent::getConditionBuilder()->add('gameID = ?', [$gameID]);
        parent::readObjects();
    }

    public function searchByAutoAssignment($id) {
        if (empty($this->objectIDs)) {
            return null;
        }

        $key = array_search($id, array_column($this->getObjects(), 'autoAssignment', 'avatarID'));
        return ($key !== false) ? $key : null;
    }
}