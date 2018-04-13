<?php
namespace guild\acp\page;
use guild\data\guild\Guild;
use guild\data\instance\kills\KillsList;
use guild\system\cache\runtime\InstanceRuntimeCache;
use wcf\page\SortablePage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceKillsListPage extends SortablePage {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.guild.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGuild'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = KillsList::class;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'killsID';

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
    public $validSortFields = ['killsID', 'instanceID', 'guildID'];

    /**
     * @inheritDoc
     */
    public $guildID = 0;

    /**
     * @inheritDoc
     */
    public $guild = null;

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
                InstanceRuntimeCache::getInstance()->getObjects($instanceIDs);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'guildID' => $this->guildID
        ]);
    }
}