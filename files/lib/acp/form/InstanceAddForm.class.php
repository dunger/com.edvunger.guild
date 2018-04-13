<?php
namespace guild\acp\form;
use guild\data\game\Game;
use guild\data\instance\Instance;
use guild\data\instance\InstanceAction;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'instanceAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.list';

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
    public $encounters = 0;

    /**
     * @inheritDoc
     */
    public $isActive = true;

    /**
     * @inheritDoc
     */
    public $gameID = 0;

    /**
     * @inheritDoc
     */
    public $game = null;

    /**
     * @inheritDoc
     */
    public $action = 'add';

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if ($this->action == 'edit') {
            return;
        }

        if (isset($_REQUEST['id'])) $this->gameID = intval($_REQUEST['id']);
        $this->game = new Game($this->gameID);

        if (!$this->game->gameID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_REQUEST['id'])) $this->gameID = intval($_REQUEST['id']);
        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
        if (isset($_POST['encounters'])) $this->encounters = intval($_POST['encounters']);
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
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new InstanceAction([], 'create', ['data' => [
            'gameID' => $this->gameID,
            'name' => $this->name,
            'encounters' => $this->encounters,
            'isActive' => $this->isActive ? 1 : 0
        ]]);
        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];

        // reset values
        $this->name = '';
        $this->encounters = 0;
        $this->isActive = true;

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
            'instanceID' => 0,
            'gameID' => $this->gameID,
            'name' => $this->name,
            'encounters' => $this->encounters,
            'isActive' => $this->isActive
        ]);
    }
}