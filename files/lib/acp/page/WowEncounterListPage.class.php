<?php
namespace guild\acp\page;
use guild\data\wow\encounter\EncounterList;
use guild\system\cache\runtime\WowInstanceRuntimeCache;
use wcf\page\SortablePage;
use wcf\system\event\EventHandler;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowEncounterListPage extends SortablePage {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = EncounterList::class;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'encounterID';

    /**
     * @inheritDoc
     */
    public $defaultSortOrder = 'ASC';

    /**
     * @inheritDoc
     */
    public $itemsPerPage = 50;

    /**
     * @inheritDoc
     */
    public $validSortFields = ['encounterID', 'instanceID', 'name'];

    /**
     * @inheritDoc
     */
    protected function readObjects() {
        parent::readObjects();

        if (!empty($this->objectList->objectIDs)) {
            $instanceIDs = [];
            foreach ($this->objectList->objects as $object) {
                if ($object->instanceID && !in_array($object->instanceID, $instanceIDs)) {
                    $instanceIDs[] = $object->instanceID;
                }
            }

            if (!empty($instanceIDs)) {
                WowInstanceRuntimeCache::getInstance()->getObjects($instanceIDs);
            }
        }
    }
}