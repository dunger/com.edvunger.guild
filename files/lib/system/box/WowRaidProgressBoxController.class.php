<?php
namespace guild\system\box;
use guild\data\guild\GuildList;
use guild\data\wow\instance\InstanceList;
use wcf\system\box\AbstractDatabaseObjectListBoxController;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowRaidProgressBoxController extends AbstractDatabaseObjectListBoxController {
    /**
    /**
     * @inheritDoc
     */
    protected $conditionDefinition = 'com.edvunger.guild.box.wow.raid.progress.condition';

    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['sidebarLeft', 'sidebarRight'];

    /**
     * @inheritDoc
     */
    protected $guildIDs = [];

    /**
     * @inheritDoc
     */
    protected $progress = [];

    /**
     * @inheritDoc
     */
    public function __construct() {
        if (!empty($this->validSortFields) && MODULE_LIKE) {
            $this->validSortFields[] = 'cumulativeLikes';
        }

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function getObjectList() {
        foreach ($this->box->getConditions() as $condition) {
            if ($condition->getObjectType()->objectType == 'com.edvunger.guild.wow.raid.progress.guild') {
                $this->guildIDs = $condition->conditionData['guildIDs'];
            }
        }

        if (!empty($this->guildIDs)) {
            $instance = new InstanceList();
            $this->progress = $instance->getProgress($this->guildIDs);
        }

        return new GuildList();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplate() {
        return WCF::getTPL()->fetch('boxWowRaidProgress', 'guild', [
            'raidProgress' => $this->progress
        ], true);
    }
}
