<?php
namespace guild\acp\form;
use guild\data\guild\Guild;
use guild\data\instance\Instance;
use guild\data\instance\kills\Kills;
use guild\data\instance\kills\KillsAction;
use guild\system\guild\GuildHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceKillsEditForm extends InstanceKillsAddForm {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.guild.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGuild'];

    /**
     * warning id
     * @var	integer
     */
    public $killsID = 0;

    /**
     * edited guild object
     * @var	Guild
     */
    public $instanceKills;

    /**
     * @inheritDoc
     */
    public $action = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->killsID = intval($_REQUEST['id']);
        $this->instanceKills = new Kills($this->killsID);

        if (!$this->instanceKills->killsID) {
            throw new IllegalLinkException();
        }

        $this->instanceID = $this->instanceKills->instanceID;
        $this->guild = GuildHandler::getInstance()->getGuild($this->instanceKills->guildID);
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->kills = $this->instanceKills->kills;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update instance
        $this->objectAction = new KillsAction([$this->instanceKills], 'update', ['data' => [
            'kills' => $this->kills
        ]]);

        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];
        $this->instanceKills = new Kills($this->killsID);

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => $this->action,
            'instanceKillsID' => $this->killsID,
            'instanceID' => $this->instanceKills->instanceID,
            'kills' => $this->instanceKills->kills
        ]);
    }
}