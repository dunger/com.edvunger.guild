<?php
namespace guild\acp\form;
use guild\data\guild\Guild;
use guild\data\instance\Instance;
use guild\data\instance\InstanceList;
use guild\data\instance\kills\Kills;
use guild\data\instance\kills\KillsAction;
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
class InstanceKillsAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'instanceKillsAdd';

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
    public $gameID = 0;

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
    public $instanceKillsID = 0;

    /**
     * @inheritDoc
     */
    public $instanceID = 0;

    /**
     * @inheritDoc
     */
    public $kills = 0;

    /**
     * @inheritDoc
     */
    public $action = 'add';

    /**
     * @inheritDoc
     */
    public $instanceList = null;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if ($this->action == 'edit') {
            return;
        }

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new IllegalLinkException();
        }

        $this->gameID = $this->guild->gameID;
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        if (isset($_POST['instanceID'])) $this->instanceID = intval($_POST['instanceID']);
        if (isset($_POST['kills'])) $this->kills = intval($_POST['kills']);
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->instanceList = new InstanceList();
        $this->instanceList->getActiveByGame($this->guild->gameID);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        $instance = new Instance($this->instanceID);
        if (!$instance->instanceID) {
            throw new UserInputException('instanceID', 'invalid');
        }

        if ($this->action == 'add') {
            if (Kills::getKillsByGuild($this->instanceID, $this->guildID) !== false) {
                throw new UserInputException('instanceID', 'invalid');
            }
        }

        if ($this->kills > $instance->encounters) {
            throw new UserInputException('kills', 'invalid');
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new KillsAction([], 'create', ['data' => [
            'guildID' => $this->guildID,
            'instanceID' => $this->instanceID,
            'kills' => $this->kills
        ]]);
        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];

        // reset values
        $this->kills = $this->instanceID = 0;

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
            'guildID' => $this->guild->guildID,
            'instanceID' => $this->instanceID,
            'kills' => $this->kills,
            'instanceList' => $this->instanceList
        ]);
    }
}