<?php
namespace guild\system\game\api;
use \guild\data\instance\InstanceList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class DefaultApi {
    /**
     * @inheritDoc
     */
    private $buttons = [
        'instance' => [
            'title' => 'guild.acp.game.instances',
            'icon' => 'fa-fort-awesome',
            'controller' => 'InstanceList',
        ]
    ];

    /**
     * @inheritDoc
     */
    private $guildButtons = [
        'instanceKills' => [
            'title' => 'guild.acp.game.instance.kills.settings',
            'icon' => 'fa-fort-awesome',
            'controller' => 'InstanceKillsList',
        ]
    ];

    /**
     * @inheritDoc
     */
    private $fields = [];

    /**
     * @inheritDoc
     */
    public function getButtons() {
        return $this->buttons;
    }

    /**
     * @inheritDoc
     */
    public function getGuildButtons() {
        return $this->guildButtons;
    }

    /**
     * @inheritDoc
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function getInstanceClass() {
        return new InstanceList();
    }
}