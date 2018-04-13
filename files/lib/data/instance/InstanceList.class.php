<?php
namespace guild\data\instance;
use guild\system\guild\GuildHandler;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceList extends DatabaseObjectList {
    /**
     * @inheritDoc
     */
    public $className = Instance::class;

    /**
     * @inheritDoc
     */
    public function getByGame($gameID) {
        parent::getConditionBuilder()->add('gameID = ?', [$gameID]);
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

    /**
     * @inheritDoc
     */
    public function getProgress($guildIDs) {
        $this->sqlSelects = "instance_kills.guildID as guildID, instance_kills.kills as kills";
        $this->sqlJoins = "LEFT JOIN guild".WCF_N."_instance_kills instance_kills ON (instance_kills.instanceID = instance.instanceID)";
        parent::getConditionBuilder()->add('instance_kills.guildID IN (?)', [$guildIDs]);
        parent::readObjects();

        $data = [];
        if (!empty($this->objectIDs)) {
            foreach ($this->getObjects() as $progress) {

                if (!isset($data[$progress->guildID])) {
                    $data[$progress->guildID] = [
                        'guild' => GuildHandler::getInstance()->getGuild($progress->guildID),
                        'data'  => []
                    ];
                }

                $data[$progress->guildID]['data'][] = [
                    'title'		 => $progress->name,
                    'encounters' => $progress->encounters,
                    'kills'		 => $progress->kills,
                    'percent'	 => ($progress->encounters > 0) ? ($progress->kills * 100 / $progress->encounters) : 0,
                ];
            }
        }

        return $data;
    }
}