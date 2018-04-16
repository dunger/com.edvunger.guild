<?php
namespace guild\system\box;
use guild\data\guild\GuildList;
use guild\data\wow\instance\InstanceList;
use guild\system\game\GameHandler;
use guild\system\guild\GuildHandler;
use wcf\system\box\AbstractDatabaseObjectListBoxController;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceProgressBoxController extends AbstractDatabaseObjectListBoxController {
    /**
     * @inheritDoc
     */
    protected $conditionDefinition = 'com.edvunger.guild.box.instance.progress.condition';

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
            if ($condition->getObjectType()->objectType == 'com.edvunger.guild.instance.progress') {
                $this->guildIDs = $condition->conditionData['guildIDs'];
            }
        }

        if (!empty($this->guildIDs)) {
            $guilds = GuildHandler::getInstance()->getGuilds($this->guildIDs);

            $games = [];
            foreach ($guilds as $guild) {
                if (!$guild->isActive) {
                    continue;
                }

                $games[$guild->gameID][] = $guild->guildID;
            }

            if (sizeof($games)) {
                foreach ($games as $gameID => $guildIDs) {
                    $apiInstanceClass = GameHandler::getInstance()->getGame($gameID)->getApiClass()->getInstanceClass();
                    $this->progress = array_merge($this->progress, $apiInstanceClass->getProgress($guildIDs));
                }
            }
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
