<?php
namespace guild\acp\form;
use guild\data\wow\instance\InstanceAction;
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
class WowInstanceAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'wowInstanceAdd';

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
    public $instanceID = 0;

    /**
     * @inheritDoc
     */
    public $name = '';

    /**
     * @inheritDoc
     */
    public $title = '';

    /**
     * @inheritDoc
     */
    public $mapID = 0;

    /**
     * @inheritDoc
     */
    public $difficulty = 0;

    /**
     * @inheritDoc
     */
    public $isRaid = true;

    /**
     * @inheritDoc
     */
    public $isActive = true;

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
        if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
        if (isset($_POST['mapID'])) $this->mapID = intval($_POST['mapID']);
        if (isset($_POST['difficulty'])) $this->difficulty = intval($_POST['difficulty']);
        if (isset($_POST['isRaid'])) $this->isRaid = ($_POST['isRaid'] == 1) ? true : false;
        if (isset($_POST['isActive'])) $this->isActive = ($_POST['isActive'] == 1) ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->name)) {
            throw new UserInputException('name', 'invalid');
        }

        if (!in_array($this->difficulty, [0, 15, 30])) {
            throw new UserInputException('difficulty', 'invalid');
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new InstanceAction([], 'create', ['data' => [
            'name' => $this->name,
            'title' => $this->title,
            'mapID' => $this->mapID,
            'difficulty' => $this->difficulty,
            'isRaid' => $this->isRaid ? 1 : 0,
            'isActive' => $this->isActive ? 1 : 0
        ]]);
        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];

        // reset values
        $this->name = $this->title = '';
        $this->mapID = $this->difficulty = 0;
        $this->isRaid = $this->isActive = true;

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'add',
            'instanceID' => 0,
            'name' => $this->name,
            'title' => $this->title,
            'mapID' => $this->mapID,
            'difficulty' => $this->difficulty,
            'isRaid' => $this->isRaid,
            'isActive' => $this->isActive
        ]);
    }
}