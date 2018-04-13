<?php
namespace guild\acp\form;
use guild\data\game\Game;
use guild\data\guild\Guild;
use guild\data\instance\Instance;
use guild\data\instance\InstanceAction;
use guild\system\game\GameHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceEditForm extends InstanceAddForm {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * warning id
     * @var	integer
     */
    public $instanceID = 0;

    /**
     * edited guild object
     * @var	Guild
     */
    public $instance;

    /**
     * @inheritDoc
     */
    public $action = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->instanceID = intval($_REQUEST['id']);
        $this->instance = new Instance($this->instanceID);

        if (!$this->instance->instanceID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->name = $this->instance->name;
        $this->encounters = $this->instance->encounters;
        $this->isActive = $this->instance->isActive;
        $this->gameID = $this->instance->gameID;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update instance
        $this->objectAction = new InstanceAction([$this->instance], 'update', ['data' => [
            'name' => $this->name,
            'encounters' => $this->encounters,
            'isActive' => $this->isActive ? 1 : 0
        ]]);

        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];
        $this->instance = new Instance($this->instanceID);

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
            'instanceID' => $this->instanceID,
            'gameID' => $this->instance->gameID,
            'name' => $this->instance->name,
            'encounters' => $this->instance->encounters,
            'isActive' => $this->instance->isActive
        ]);
    }
}