<?php
namespace guild\acp\form;
use guild\data\wow\encounter\EncounterAction;
use guild\data\wow\instance\Instance;
use guild\data\wow\instance\InstanceList;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowEncounterAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'wowEncounterAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.wow';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $encounterID = 0;

    /**
     * @inheritDoc
     */
    public $instanceID = 0;

    /**
     * @inheritDoc
     */
    public $name = '';

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['encounterID'])) $this->encounterID = intval($_POST['encounterID']);
        if (isset($_POST['instanceID'])) $this->instanceID = intval($_POST['instanceID']);
        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->encounterID)) {
            throw new UserInputException('encounterID', 'invalid');
        }

        if (empty($this->name)) {
            throw new UserInputException('name', 'invalid');
        }

        $instance = new Instance($this->instanceID);
        if (!$instance->instanceID) {
            throw new UserInputException('instanceID', 'invalid');
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new EncounterAction([], 'create', ['data' => [
            'encounterID' => $this->encounterID,
            'instanceID' => $this->instanceID,
            'name' => $this->name,
        ]]);
        /** @var Encounter $encounter */
        $this->objectAction->executeAction()['returnValues'];

        // reset values
        $this->name = '';
        $this->instanceID = 0;

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        $instanceList = new InstanceList();
        $instanceList->getInstances();

        WCF::getTPL()->assign([
            'action' => 'add',
            'name' => $this->name,
            'encounterID' => $this->encounterID,
            'instanceID' => $this->instanceID,
            'instanceList' => $instanceList
        ]);
    }
}