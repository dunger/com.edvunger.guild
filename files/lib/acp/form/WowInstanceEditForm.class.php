<?php
namespace guild\acp\form;
use guild\data\guild\Guild;
use guild\data\wow\instance\Instance;
use guild\data\wow\instance\InstanceAction;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowInstanceEditForm extends WowInstanceAddForm {
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
        $this->title = $this->instance->title;
        $this->mapID = $this->instance->mapID;
        $this->difficulty = $this->instance->difficulty;
        $this->isRaid = $this->instance->isRaid;
        $this->isActive = $this->instance->isActive;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update instance
        $this->objectAction = new InstanceAction([$this->instance], 'update', ['data' => [
            'name' => $this->name,
            'title' => $this->title,
            'mapID' => $this->mapID,
            'difficulty' => $this->difficulty,
            'isRaid' => $this->isRaid ? 1 : 0,
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
            'action' => 'edit',
            'instanceID' => $this->instanceID,
            'name' => $this->instance->name,
            'title' => $this->instance->title,
            'mapID' => $this->instance->mapID,
            'difficulty' => $this->instance->difficulty,
            'isRaid' => $this->instance->isRaid,
            'isActive' => $this->instance->isActive
        ]);
    }
}
